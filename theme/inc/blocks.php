<?php
/**
 * Registers custom blocks: every blocks/<name>/block.json is auto-registered.
 *
 * Blocks are dynamic (render.php) with plain-JS editor scripts (no build step).
 * See docs/recipes/add-block.md.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'agent_theme_register_blocks' );

/**
 * Register each block folder.
 */
function agent_theme_register_blocks(): void {
	$block_files = glob( AGENT_THEME_DIR . '/blocks/*/block.json' );

	foreach ( $block_files ? $block_files : array() as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
}

add_filter( 'block_categories_all', 'agent_theme_block_category' );

/**
 * Put theme blocks in their own inserter category.
 *
 * @param array $categories Existing categories.
 */
function agent_theme_block_category( array $categories ): array {
	array_unshift(
		$categories,
		array(
			'slug'  => 'agent-theme',
			'title' => __( 'Agent Theme', 'agent-theme' ),
		)
	);
	return $categories;
}
