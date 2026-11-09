/**
 * Editor script for agent-theme/event-list.
 *
 * Plain JS using wp.* globals: no JSX, no build step. The front end is
 * rendered by render.php; the editor previews it with ServerSideRender.
 * save() returns null because the block is dynamic.
 */
( function ( wp ) {
	const { registerBlockType } = wp.blocks;
	const { createElement: el, Fragment } = wp.element;
	const { InspectorControls, useBlockProps } = wp.blockEditor;
	const { PanelBody, RangeControl, ToggleControl } = wp.components;
	const ServerSideRender = wp.serverSideRender;
	const { __ } = wp.i18n;

	registerBlockType( 'agent-theme/event-list', {
		edit( { attributes, setAttributes } ) {
			const blockProps = useBlockProps();

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Settings', 'agent-theme' ) },
						el( RangeControl, {
							label: __( 'Number of events', 'agent-theme' ),
							value: attributes.count,
							min: 1,
							max: 12,
							onChange: ( count ) => setAttributes( { count } ),
						} ),
						el( ToggleControl, {
							label: __( 'Upcoming only', 'agent-theme' ),
							checked: attributes.upcomingOnly,
							onChange: ( upcomingOnly ) => setAttributes( { upcomingOnly } ),
						} )
					)
				),
				el(
					'div',
					blockProps,
					el( ServerSideRender, {
						block: 'agent-theme/event-list',
						attributes,
					} )
				)
			);
		},
		save: () => null,
	} );
} )( window.wp );
