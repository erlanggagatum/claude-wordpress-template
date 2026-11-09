<?php
/**
 * Agent Theme bootstrap.
 *
 * Keep this file tiny. All logic lives in inc/*.php, all declarative
 * definitions live in config/**. See AGENTS.md at the repo root.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

define( 'AGENT_THEME_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
define( 'AGENT_THEME_DIR', get_template_directory() );
define( 'AGENT_THEME_URI', get_template_directory_uri() );

/*
 * Load order matters: helpers first, then registries that consume config/.
 */
$agent_theme_includes = array(
	'inc/helpers.php',      // Generic helpers (config loader, label builder).
	'inc/setup.php',        // Theme supports, menus, image sizes.
	'inc/assets.php',       // Front-end and editor assets.
	'inc/post-types.php',   // Registers config/post-types/*.php.
	'inc/taxonomies.php',   // Registers config/taxonomies/*.php.
	'inc/meta.php',         // Registers config/meta/*.php + admin meta boxes.
	'inc/blocks.php',       // Registers blocks/*/block.json.
	'inc/patterns.php',     // Pattern categories (patterns/ auto-load).
	'inc/template-tags.php', // Helpers used inside PHP templates.
);

foreach ( $agent_theme_includes as $agent_theme_file ) {
	require_once AGENT_THEME_DIR . '/' . $agent_theme_file;
}
unset( $agent_theme_includes, $agent_theme_file );
