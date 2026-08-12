<?php
/**
 * Stop Elementor's frontend assets loading on pages that are NOT built with
 * Elementor (i.e. our block pages). Pages still built with Elementor keep
 * everything, so the old site continues to work while we migrate.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is the current queried object an Elementor-built page?
 */
function cular_is_elementor_page() {
	if ( ! is_singular() ) {
		return false;
	}
	$id = get_queried_object_id();
	if ( ! $id ) {
		return false;
	}
	return 'builder' === get_post_meta( $id, '_elementor_edit_mode', true );
}

/**
 * Plugin/upload paths whose assets belong to the Elementor stack.
 *
 * This used to be a hardcoded list of ~20 handle names, which silently rotted:
 * Elementor 3.x splits its CSS per widget (`widget-image`, `widget-icon-list`,
 * `widget-spacer`, …) and registers a new handle for every widget type a page
 * uses, so converting the portfolio pages — which lean on image and icon-list
 * widgets — quietly reintroduced five stylesheets the list had never heard of.
 * Matching on where the file comes from instead means new widget handles, and
 * the Elementor add-ons, are covered without anyone having to notice.
 *
 * @return string[]
 */
function cular_elementor_asset_paths() {
	return array(
		'/plugins/elementor/',
		'/plugins/elementor-pro/',
		'/plugins/pro-elements/',
		'/plugins/dynamic-content-for-elementor/',
		'/plugins/extensions-for-elementor-form/',
		'/uploads/elementor/',
	);
}

/**
 * Dequeue every registered asset in $wp_assets that Elementor owns.
 *
 * @param WP_Dependencies $assets  wp_styles() or wp_scripts().
 * @param callable        $dequeue wp_dequeue_style or wp_dequeue_script.
 */
function cular_dequeue_elementor_assets( $assets, $dequeue ) {
	$paths = cular_elementor_asset_paths();

	foreach ( $assets->queue as $handle ) {
		$src = $assets->registered[ $handle ]->src ?? '';
		if ( ! $src || ! is_string( $src ) ) {
			continue;
		}
		foreach ( $paths as $path ) {
			if ( false !== strpos( $src, $path ) ) {
				$dequeue( $handle );
				break;
			}
		}
	}
}

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_admin() || cular_is_elementor_page() ) {
			return;
		}

		cular_dequeue_elementor_assets( wp_styles(), 'wp_dequeue_style' );
		cular_dequeue_elementor_assets( wp_scripts(), 'wp_dequeue_script' );

		// Shared libraries Elementor pulls in that live outside its own folder.
		foreach ( array( 'swiper', 'e-swiper', 'font-awesome', 'font-awesome-5-all', 'elementor-icons' ) as $handle ) {
			wp_dequeue_style( $handle );
			wp_dequeue_script( $handle );
		}
	},
	// Late enough that everything has registered, but Elementor's own CSS
	// manager also enqueues on wp_enqueue_scripts at the default priority.
	9999
);

/**
 * Elementor adds `elementor-default` / `elementor-kit-N` to <body> on every
 * page, and `elementor-kit-N` is what its global kit CSS hangs off. With the
 * stylesheet dequeued the classes are dead weight that only invites confusion
 * about whether a page is still on Elementor.
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( cular_is_elementor_page() ) {
			return $classes;
		}
		return array_values(
			array_filter(
				$classes,
				function ( $class ) {
					return 'elementor-default' !== $class && 0 !== strpos( $class, 'elementor-kit-' );
				}
			)
		);
	},
	20
);
