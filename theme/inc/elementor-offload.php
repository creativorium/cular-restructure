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

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_admin() || cular_is_elementor_page() ) {
			return;
		}

		$styles = array(
			'elementor-frontend',
			'elementor-post-6',
			'elementor-global',
			'elementor-icons',
			'elementor-common',
			'elementor-pro',
			'widget-styles-pro',
			'swiper',
			'e-swiper',
			'font-awesome',
			'font-awesome-5-all',
		);
		foreach ( $styles as $handle ) {
			wp_dequeue_style( $handle );
		}

		$scripts = array(
			'elementor-frontend',
			'elementor-frontend-modules',
			'elementor-common',
			'elementor-webpack-runtime',
			'elementor-pro-frontend',
			'elementor-pro-webpack-runtime',
			'pro-elements-handlers',
			'swiper',
			'e-swiper',
		);
		foreach ( $scripts as $handle ) {
			wp_dequeue_script( $handle );
		}
	},
	100
);
