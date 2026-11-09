<?php
/**
 * Card partial for any post type. Used via get_template_part( 'template-parts/content', 'card' ).
 *
 * Shows event meta automatically when the post is an Event.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'agent-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="agent-card__media">
			<?php the_post_thumbnail( 'agent-card' ); ?>
		</a>
	<?php endif; ?>

	<div class="agent-card__body">
		<h3 class="agent-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<?php if ( 'event' === get_post_type() ) : ?>
			<p class="agent-card__meta">
				<?php
				echo esc_html(
					implode(
						' · ',
						array_filter(
							array(
								agent_theme_format_date( (string) agent_theme_meta( 'event_date' ) ),
								(string) agent_theme_meta( 'event_location' ),
							)
						)
					)
				);
				?>
			</p>
		<?php endif; ?>

		<div class="agent-card__excerpt"><?php the_excerpt(); ?></div>
	</div>
</article>
