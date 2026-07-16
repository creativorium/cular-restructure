<?php
/**
 * Register the "Cular" block category so our components group together
 * in the inserter.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'block_categories_all',
	function ( $categories ) {
		array_unshift(
			$categories,
			array(
				'slug'  => 'cular',
				'title' => 'Cular',
				'icon'  => null,
			)
		);
		return $categories;
	}
);
