<?php
/**
 * Auto-register every block under /blocks.
 *
 * Convention per block folder:
 *   blocks/<name>/block.json   (registers the block; uses ACF render template)
 *   blocks/<name>/render.php   (PHP markup)
 *   blocks/<name>/fields.php   (ACF field group via acf_add_local_field_group)
 *   blocks/<name>/<name>.scss  (styles; imported by src/main.js glob)
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	function () {
		$blocks_dir = CULAR_DIR . '/blocks';
		if ( ! is_dir( $blocks_dir ) ) {
			return;
		}

		foreach ( glob( $blocks_dir . '/*', GLOB_ONLYDIR ) as $block_path ) {
			// Register the block from its block.json (WP core handles ACF blocks
			// that declare "acf" + "renderTemplate").
			if ( file_exists( $block_path . '/block.json' ) ) {
				register_block_type( $block_path );
			}

			// Load its ACF field group, if any.
			if ( file_exists( $block_path . '/fields.php' ) && function_exists( 'acf_add_local_field_group' ) ) {
				require_once $block_path . '/fields.php';
			}
		}
	}
);

/**
 * Restrict the inserter to our blocks + a curated set of core blocks.
 * Comment this out while prototyping if you want the full core library.
 */
add_filter(
	'allowed_block_types_all',
	function ( $allowed, $context ) {
		// Let the editor for reusable/synced patterns keep everything.
		if ( ! ( $context->post ?? null ) ) {
			return $allowed;
		}

		$cular = array();
		foreach ( glob( CULAR_DIR . '/blocks/*/block.json' ) as $json ) {
			$data = json_decode( file_get_contents( $json ), true );
			if ( ! empty( $data['name'] ) ) {
				$cular[] = $data['name'];
			}
		}

		$core = array(
			'core/paragraph',
			'core/heading',
			'core/image',
			'core/list',
			'core/list-item',
			'core/buttons',
			'core/button',
			'core/group',
			'core/columns',
			'core/column',
			'core/spacer',
			'core/separator',
		);

		return array_merge( $cular, $core );
	},
	10,
	2
);
