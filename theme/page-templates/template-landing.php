<?php
/**
 * Template Name: Landing Page (PHP)
 * Template Post Type: page
 *
 * Example PHP page template. Selectable in the editor's Template panel
 * alongside block templates from theme.json "customTemplates".
 *
 * Use a PHP template when you need logic a block template can't express:
 * conditional output, external API data, complex queries, form handling.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="wp-block-group is-layout-constrained has-global-padding agent-landing">
	<?php agent_theme_pattern( 'agent-theme/hero' ); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'entry-content is-layout-constrained' ); ?>>
			<?php the_content(); ?>
		</article>
		<?php
	endwhile;
	?>

	<?php
	$agent_upcoming = new WP_Query(
		array(
			'post_type'      => 'event',
			'posts_per_page' => 3,
			'no_found_rows'  => true,
		)
	);

	if ( post_type_exists( 'event' ) && $agent_upcoming->have_posts() ) :
		?>
		<section class="alignwide agent-landing__events">
			<h2><?php esc_html_e( 'Upcoming events', 'agent-theme' ); ?></h2>
			<div class="agent-card-grid">
				<?php
				while ( $agent_upcoming->have_posts() ) :
					$agent_upcoming->the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
