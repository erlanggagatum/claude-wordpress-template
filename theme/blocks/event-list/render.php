<?php
/**
 * Server render for agent-theme/event-list.
 *
 * Available variables (provided by WordPress):
 *   $attributes (array)    Block attributes from block.json.
 *   $content    (string)   Inner blocks markup (unused).
 *   $block      (WP_Block) Block instance.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

$agent_args = array(
	'post_type'      => 'event',
	'posts_per_page' => max( 1, min( 12, (int) ( $attributes['count'] ?? 3 ) ) ),
	'no_found_rows'  => true,
	'meta_key'       => 'event_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
);

if ( ! empty( $attributes['upcomingOnly'] ) ) {
	// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	$agent_args['meta_query'] = array(
		array(
			'key'     => 'event_date',
			'value'   => wp_date( 'Y-m-d' ),
			'compare' => '>=',
			'type'    => 'DATE',
		),
	);
}

$agent_events = new WP_Query( $agent_args );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'agent-event-list' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $agent_events->have_posts() ) : ?>
		<ul class="agent-event-list__items">
			<?php
			while ( $agent_events->have_posts() ) :
				$agent_events->the_post();
				$agent_date     = agent_theme_format_date( (string) agent_theme_meta( 'event_date' ) );
				$agent_location = (string) agent_theme_meta( 'event_location' );
				?>
				<li class="agent-event-list__item">
					<a class="agent-event-list__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php if ( $agent_date || $agent_location ) : ?>
						<span class="agent-event-list__meta">
							<?php echo esc_html( implode( ' · ', array_filter( array( $agent_date, $agent_location ) ) ) ); ?>
						</span>
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<p class="agent-event-list__empty"><?php esc_html_e( 'No events to show.', 'agent-theme' ); ?></p>
	<?php endif; ?>
</div>
<?php
wp_reset_postdata();
