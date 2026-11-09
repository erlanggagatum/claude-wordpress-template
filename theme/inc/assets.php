<?php
/**
 * Front-end assets.
 *
 * Plain CSS/JS, no build step. Add files under assets/ and enqueue here.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'agent_theme_enqueue_assets' );

/**
 * Enqueue front-end CSS and JS.
 */
function agent_theme_enqueue_assets(): void {
	wp_enqueue_style(
		'agent-theme-main',
		AGENT_THEME_URI . '/assets/css/main.css',
		array(),
		agent_theme_asset_version( 'assets/css/main.css' )
	);

	wp_enqueue_script(
		'agent-theme-main',
		AGENT_THEME_URI . '/assets/js/main.js',
		array(),
		agent_theme_asset_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}

/**
 * Cache-busting version: file mtime in debug mode, theme version otherwise.
 *
 * @param string $relative_path Path relative to the theme root.
 */
function agent_theme_asset_version( string $relative_path ): string {
	$file = AGENT_THEME_DIR . '/' . $relative_path;

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $file ) ) {
		return (string) filemtime( $file );
	}

	return AGENT_THEME_VERSION;
}
