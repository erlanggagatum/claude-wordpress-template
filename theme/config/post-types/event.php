<?php
/**
 * Example custom post type: Event.
 *
 * Reference implementation. Related files:
 *   config/taxonomies/event-type.php  - taxonomy attached to this CPT
 *   config/meta/event.php             - custom fields
 *   templates/single-event.html       - block template for one event
 *   templates/archive-event.html      - block template for /events/
 *   blocks/event-list/                - dynamic block listing events
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

return array(
	'slug'     => 'event',
	'singular' => __( 'Event', 'agent-theme' ),
	'plural'   => __( 'Events', 'agent-theme' ),
	'args'     => array(
		'menu_icon'    => 'dashicons-calendar-alt',
		'rewrite'      => array( 'slug' => 'events' ),
		'has_archive'  => 'events',
		'menu_position' => 20,
	),
);
