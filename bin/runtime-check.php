<?php
/**
 * Runtime checks. Runs INSIDE WordPress via: wp eval-file bin/runtime-check.php
 * Verifies that everything declared in the theme actually registered.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * URLs worth fetching in `check.php --http`: home, search, archive + one item
 * per public CPT, recent pages/posts.
 *
 * @return string[]
 */
function agent_theme_check_urls(): array {
	$urls = array( home_url( '/' ), home_url( '/?s=test' ) );
	foreach ( get_post_types( array( 'public' => true, '_builtin' => false ) ) as $pt ) {
		$archive = get_post_type_archive_link( $pt );
		if ( $archive ) {
			$urls[] = $archive;
		}
		$one = get_posts( array( 'post_type' => $pt, 'numberposts' => 1 ) );
		if ( $one ) {
			$urls[] = get_permalink( $one[0] );
		}
	}
	foreach ( get_taxonomies( array( 'public' => true, '_builtin' => false ) ) as $tax ) {
		$terms = get_terms( array( 'taxonomy' => $tax, 'number' => 1, 'hide_empty' => true ) );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$urls[] = get_term_link( $terms[0] );
		}
	}
	foreach ( get_posts( array( 'post_type' => array( 'page', 'post' ), 'numberposts' => 10 ) ) as $p ) {
		$urls[] = get_permalink( $p );
	}
	return array_values( array_unique( $urls ) );
}

// `wp eval-file bin/runtime-check.php urls` prints the URL list and stops.
if ( in_array( 'urls', $args ?? array(), true ) ) {
	echo implode( "\n", agent_theme_check_urls() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$agent_errors = array();

if ( get_template() !== 'agent-theme' && ! function_exists( 'agent_theme_load_configs' ) ) {
	WP_CLI::error( 'Agent Theme is not active. Run: php tools/wp-cli.phar theme activate agent-theme' );
}

if ( ! wp_is_block_theme() ) {
	$agent_errors[] = 'wp_is_block_theme() is false — templates/index.html missing?';
}

foreach ( agent_theme_load_configs( 'post-types' ) as $agent_cfg ) {
	if ( ! post_type_exists( $agent_cfg['slug'] ) ) {
		$agent_errors[] = "Post type not registered: {$agent_cfg['slug']}";
	}
}
foreach ( agent_theme_load_configs( 'taxonomies' ) as $agent_cfg ) {
	if ( ! taxonomy_exists( $agent_cfg['slug'] ) ) {
		$agent_errors[] = "Taxonomy not registered: {$agent_cfg['slug']}";
	}
}
foreach ( agent_theme_meta_configs() as $agent_cfg ) {
	foreach ( $agent_cfg['post_type'] as $agent_pt ) {
		$agent_registered = get_registered_meta_keys( 'post', $agent_pt );
		foreach ( array_keys( $agent_cfg['fields'] ) as $agent_key ) {
			if ( ! isset( $agent_registered[ $agent_key ] ) ) {
				$agent_errors[] = "Meta not registered: {$agent_pt}.{$agent_key}";
			}
		}
	}
}
foreach ( glob( AGENT_THEME_DIR . '/blocks/*/block.json' ) ?: array() as $agent_file ) {
	$agent_block = json_decode( (string) file_get_contents( $agent_file ), true );
	if ( ! WP_Block_Type_Registry::get_instance()->is_registered( $agent_block['name'] ?? '' ) ) {
		$agent_errors[] = 'Block not registered: ' . ( $agent_block['name'] ?? $agent_file );
	}
}
foreach ( glob( AGENT_THEME_DIR . '/patterns/*.php' ) ?: array() as $agent_file ) {
	$agent_head = get_file_data( $agent_file, array( 'slug' => 'Slug' ) );
	if ( ! WP_Block_Patterns_Registry::get_instance()->is_registered( $agent_head['slug'] ) ) {
		$agent_errors[] = "Pattern not registered: {$agent_head['slug']}";
	}
}

// Every block template must resolve.
foreach ( get_block_templates( array(), 'wp_template' ) as $agent_tpl ) {
	if ( '' === trim( (string) $agent_tpl->content ) ) {
		$agent_errors[] = "Empty block template: {$agent_tpl->slug}";
	}
}

// Render every pattern & template part to catch PHP errors in render callbacks.
ob_start();
foreach ( glob( AGENT_THEME_DIR . '/parts/*.html' ) ?: array() as $agent_file ) {
	do_blocks( (string) file_get_contents( $agent_file ) );
}
ob_end_clean();

if ( $agent_errors ) {
	foreach ( $agent_errors as $agent_e ) {
		WP_CLI::warning( $agent_e );
	}
	WP_CLI::error( count( $agent_errors ) . ' runtime problem(s).' );
}
WP_CLI::success(
	sprintf(
		'Runtime OK: theme %s, %d CPT(s), %d block(s).',
		get_template(),
		count( agent_theme_load_configs( 'post-types' ) ),
		count( glob( AGENT_THEME_DIR . '/blocks/*/block.json' ) ?: array() )
	)
);
