<?php
/**
 * Example custom fields for the Event CPT.
 *
 * Read in PHP:          agent_theme_meta( 'event_date' )
 * Read in block HTML:   paragraph bound to core/post-meta, see templates/single-event.html
 * Read/write via REST:  /wp-json/wp/v2/event/<id>  ->  "meta": { "event_date": "2026-12-01" }
 * Write via WP-CLI:     wp post meta update <id> event_date 2026-12-01
 *
 * @package AgentTheme
 */

defined( 'ABSPATH' ) || exit;

return array(
	'post_type' => 'event',
	'title'     => __( 'Event Details', 'agent-theme' ),
	'context'   => 'normal',
	'fields'    => array(
		'event_date'     => array(
			'label' => __( 'Date', 'agent-theme' ),
			'type'  => 'date',
		),
		'event_time'     => array(
			'label' => __( 'Start time', 'agent-theme' ),
			'type'  => 'time',
		),
		'event_location' => array(
			'label' => __( 'Location', 'agent-theme' ),
			'type'  => 'text',
		),
		'event_url'      => array(
			'label'       => __( 'Registration URL', 'agent-theme' ),
			'type'        => 'url',
			'description' => __( 'Link to tickets or sign-up page.', 'agent-theme' ),
		),
		'event_format'   => array(
			'label'   => __( 'Format', 'agent-theme' ),
			'type'    => 'select',
			'options' => array(
				'in-person' => __( 'In person', 'agent-theme' ),
				'online'    => __( 'Online', 'agent-theme' ),
				'hybrid'    => __( 'Hybrid', 'agent-theme' ),
			),
		),
		'event_capacity' => array(
			'label' => __( 'Capacity', 'agent-theme' ),
			'type'  => 'integer',
		),
		'event_featured' => array(
			'label' => __( 'Featured event', 'agent-theme' ),
			'type'  => 'checkbox',
		),
	),
);
