<?php
/**
 * Sample content. Runs INSIDE WordPress:  php tools/wp-cli.phar eval-file bin/seed.php
 * Idempotent: skips items whose slug already exists.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create a post if no post with that slug exists. Returns the ID.
 *
 * @param array $postarr wp_insert_post() args; must include post_name.
 */
function agent_seed_post( array $postarr ): int {
	$existing = get_page_by_path( $postarr['post_name'], OBJECT, $postarr['post_type'] ?? 'post' );
	if ( $existing ) {
		WP_CLI::log( "exists: {$postarr['post_type']}/{$postarr['post_name']}" );
		return $existing->ID;
	}
	$id = wp_insert_post( $postarr + array( 'post_status' => 'publish' ), true );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( $id->get_error_message() );
		return 0;
	}
	WP_CLI::log( "created: {$postarr['post_type']}/{$postarr['post_name']} (#{$id})" );
	return $id;
}

// The Event demo may have been removed (bin/rename.php --strip-examples).
$agent_has_events = post_type_exists( 'event' );

// Pages.
$agent_home_content = "<!-- wp:pattern {\"slug\":\"agent-theme/hero\"} /-->\n\n";
if ( $agent_has_events ) {
	$agent_home_content .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Upcoming events</h2>\n<!-- /wp:heading -->\n\n<!-- wp:agent-theme/event-list {\"count\":5} /-->\n\n";
}
$agent_home_content .= '<!-- wp:pattern {"slug":"agent-theme/call-to-action"} /-->';

$home = agent_seed_post(
	array(
		'post_type'     => 'page',
		'post_name'     => 'home',
		'post_title'    => 'Home',
		'post_content'  => $agent_home_content,
		'page_template' => 'page-no-title',
	)
);
agent_seed_post(
	array(
		'post_type'     => 'page',
		'post_name'     => 'landing',
		'post_title'    => 'Landing (PHP template)',
		'post_content'  => "<!-- wp:paragraph -->\n<p>This page uses page-templates/template-landing.php.</p>\n<!-- /wp:paragraph -->",
		'page_template' => 'page-templates/template-landing.php',
	)
);
agent_seed_post(
	array(
		'post_type'    => 'page',
		'post_name'    => 'about',
		'post_title'   => 'About',
		'post_content' => "<!-- wp:paragraph -->\n<p>About this site.</p>\n<!-- /wp:paragraph -->",
	)
);
$blog = agent_seed_post(
	array(
		'post_type'  => 'page',
		'post_name'  => 'blog',
		'post_title' => 'Blog',
	)
);

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'page_for_posts', $blog );

if ( $agent_has_events ) :

// Taxonomy terms.
foreach ( array( 'Workshop', 'Meetup', 'Conference' ) as $term ) {
	if ( ! term_exists( $term, 'event_type' ) ) {
		wp_insert_term( $term, 'event_type' );
	}
}

// Events.
$events = array(
	array( 'wordpress-meetup', 'WordPress Meetup', 'Meetup', '+7 days', 'Community Hall', 'in-person' ),
	array( 'block-theme-workshop', 'Block Theme Workshop', 'Workshop', '+21 days', 'Online', 'online' ),
	array( 'agentic-dev-conf', 'Agentic Dev Conference', 'Conference', '+45 days', 'Convention Center', 'hybrid' ),
);
foreach ( $events as [ $slug, $title, $type, $offset, $location, $format ] ) {
	$id = agent_seed_post(
		array(
			'post_type'    => 'event',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_excerpt' => "Sample {$type} event.",
			'post_content' => "<!-- wp:paragraph -->\n<p>Details about {$title}.</p>\n<!-- /wp:paragraph -->",
		)
	);
	if ( $id ) {
		update_post_meta( $id, 'event_date', wp_date( 'Y-m-d', strtotime( $offset ) ) );
		update_post_meta( $id, 'event_time', '18:00' );
		update_post_meta( $id, 'event_location', $location );
		update_post_meta( $id, 'event_format', $format );
		update_post_meta( $id, 'event_url', 'https://example.com/register' );
		wp_set_object_terms( $id, $type, 'event_type' );
	}
}

endif; // $agent_has_events.

// Blog post.
agent_seed_post(
	array(
		'post_type'    => 'post',
		'post_name'    => 'hello-agents',
		'post_title'   => 'Hello, agents',
		'post_content' => "<!-- wp:paragraph -->\n<p>First post.</p>\n<!-- /wp:paragraph -->",
	)
);

flush_rewrite_rules( false );
WP_CLI::success( 'Seed complete.' );
