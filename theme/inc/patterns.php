<?php
/**
 * Block pattern categories.
 *
 * Pattern files in patterns/*.php are auto-registered by WordPress from
 * their file header (Title, Slug, Categories...). Only categories live here.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'agent_theme_register_pattern_categories' );

/**
 * Register pattern categories used by patterns/*.php.
 */
function agent_theme_register_pattern_categories(): void {
	register_block_pattern_category(
		'agent-theme',
		array( 'label' => __( 'Agent Theme', 'agent-theme' ) )
	);
	register_block_pattern_category(
		'agent-theme-sections',
		array( 'label' => __( 'Agent Theme: Sections', 'agent-theme' ) )
	);
}
