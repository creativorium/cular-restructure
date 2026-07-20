<?php
/**
 * Cular theme bootstrap.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

define( 'CULAR_DIR', get_template_directory() );
define( 'CULAR_URI', get_template_directory_uri() );
define( 'CULAR_VERSION', '0.1.0' );

require_once CULAR_DIR . '/inc/enqueue.php';
require_once CULAR_DIR . '/inc/block-category.php';
require_once CULAR_DIR . '/inc/blocks.php';
require_once CULAR_DIR . '/inc/site-chrome.php';
require_once CULAR_DIR . '/inc/elementor-offload.php';

/**
 * Theme supports.
 */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	}
);

/**
 * Warn (admin only) if ACF Pro isn't active — blocks need it.
 */
add_action(
	'admin_notices',
	function () {
		if ( ! function_exists( 'acf_register_block_type' ) && ! function_exists( 'acf_add_local_field_group' ) ) {
			echo '<div class="notice notice-error"><p><strong>Cular theme:</strong> Advanced Custom Fields PRO is required for the site\'s blocks. Please install and activate it.</p></div>';
		}
	}
);
