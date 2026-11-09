<?php
/**
 * Example taxonomy: Event Type (attached to the Event CPT).
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

return array(
	'slug'       => 'event_type',
	'singular'   => __( 'Event Type', 'agent-theme' ),
	'plural'     => __( 'Event Types', 'agent-theme' ),
	'post_types' => array( 'event' ),
	'args'       => array(
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'event-type' ),
	),
);
