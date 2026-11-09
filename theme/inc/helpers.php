<?php
/**
 * Generic helpers shared by the registries.
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load every config file in a config/ subdirectory.
 *
 * Each file must `return` an array. Files starting with "_" are skipped,
 * so you can keep drafts/examples like `_example.php` around.
 *
 * @param string $dir Subdirectory of config/, e.g. "post-types".
 * @return array<string, array> Map of file basename => returned config.
 */
function agent_theme_load_configs( string $dir ): array {
	$configs = array();
	$files   = glob( AGENT_THEME_DIR . '/config/' . $dir . '/*.php' );

	if ( ! $files ) {
		return $configs;
	}

	sort( $files );

	foreach ( $files as $file ) {
		$name = basename( $file, '.php' );
		if ( str_starts_with( $name, '_' ) ) {
			continue;
		}

		$config = require $file;

		if ( ! is_array( $config ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				esc_html( sprintf( 'Config file %s must return an array.', $file ) ),
				'0.1.0'
			);
			continue;
		}

		$configs[ $name ] = $config;
	}

	return $configs;
}

/**
 * Build a full post type labels array from singular/plural names.
 *
 * @param string $singular e.g. "Event".
 * @param string $plural   e.g. "Events".
 * @return array<string, string>
 */
function agent_theme_post_type_labels( string $singular, string $plural ): array {
	$lower_plural   = strtolower( $plural );
	$lower_singular = strtolower( $singular );

	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'add_new'               => __( 'Add New', 'agent-theme' ),
		/* translators: %s: singular post type name. */
		'add_new_item'          => sprintf( __( 'Add New %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular post type name. */
		'edit_item'             => sprintf( __( 'Edit %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular post type name. */
		'new_item'              => sprintf( __( 'New %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular post type name. */
		'view_item'             => sprintf( __( 'View %s', 'agent-theme' ), $singular ),
		/* translators: %s: plural post type name. */
		'view_items'            => sprintf( __( 'View %s', 'agent-theme' ), $plural ),
		/* translators: %s: plural post type name. */
		'search_items'          => sprintf( __( 'Search %s', 'agent-theme' ), $plural ),
		/* translators: %s: lowercase plural post type name. */
		'not_found'             => sprintf( __( 'No %s found.', 'agent-theme' ), $lower_plural ),
		/* translators: %s: lowercase plural post type name. */
		'not_found_in_trash'    => sprintf( __( 'No %s found in Trash.', 'agent-theme' ), $lower_plural ),
		/* translators: %s: plural post type name. */
		'all_items'             => sprintf( __( 'All %s', 'agent-theme' ), $plural ),
		/* translators: %s: singular post type name. */
		'archives'              => sprintf( __( '%s Archives', 'agent-theme' ), $singular ),
		/* translators: %s: singular post type name. */
		'attributes'            => sprintf( __( '%s Attributes', 'agent-theme' ), $singular ),
		/* translators: %s: lowercase singular post type name. */
		'insert_into_item'      => sprintf( __( 'Insert into %s', 'agent-theme' ), $lower_singular ),
		/* translators: %s: lowercase singular post type name. */
		'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', 'agent-theme' ), $lower_singular ),
		/* translators: %s: plural post type name. */
		'filter_items_list'     => sprintf( __( 'Filter %s list', 'agent-theme' ), $lower_plural ),
		/* translators: %s: plural post type name. */
		'items_list'            => sprintf( __( '%s list', 'agent-theme' ), $plural ),
	);
}

/**
 * Build a taxonomy labels array from singular/plural names.
 *
 * @param string $singular e.g. "Event Type".
 * @param string $plural   e.g. "Event Types".
 * @return array<string, string>
 */
function agent_theme_taxonomy_labels( string $singular, string $plural ): array {
	return array(
		'name'          => $plural,
		'singular_name' => $singular,
		'menu_name'     => $plural,
		/* translators: %s: plural taxonomy name. */
		'all_items'     => sprintf( __( 'All %s', 'agent-theme' ), $plural ),
		/* translators: %s: singular taxonomy name. */
		'edit_item'     => sprintf( __( 'Edit %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'view_item'     => sprintf( __( 'View %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'update_item'   => sprintf( __( 'Update %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'add_new_item'  => sprintf( __( 'Add New %s', 'agent-theme' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'new_item_name' => sprintf( __( 'New %s Name', 'agent-theme' ), $singular ),
		/* translators: %s: plural taxonomy name. */
		'search_items'  => sprintf( __( 'Search %s', 'agent-theme' ), $plural ),
		/* translators: %s: singular taxonomy name. */
		'parent_item'   => sprintf( __( 'Parent %s', 'agent-theme' ), $singular ),
		/* translators: %s: lowercase plural taxonomy name. */
		'not_found'     => sprintf( __( 'No %s found.', 'agent-theme' ), strtolower( $plural ) ),
	);
}
