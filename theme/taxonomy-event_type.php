<?php
/**
 * PHP template-hierarchy override: Event Type term archives (/event-type/<term>/).
 *
 * Hybrid rule: a PHP template wins when it is MORE specific in the template
 * hierarchy than any block template. Here taxonomy-event_type.php beats
 * templates/archive.html. If templates/taxonomy-event_type.html existed,
 * the block template would win instead (equal specificity -> block wins).
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="wp-block-group is-layout-constrained has-global-padding">
	<header class="alignwide">
		<h1><?php single_term_title(); ?></h1>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="alignwide agent-card-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			?>
		</div>
		<div class="alignwide">
			<?php the_posts_pagination(); ?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'No events in this category yet.', 'agent-theme' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
