<?php
/**
 * Navigation helpers.
 *
 * Menus are edited in wp-admin under Appearance > Menus and assigned to the
 * "Primary Menu" / "Social Links" locations — no template editing required.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a registered nav-menu location into a simple label/url list.
 * Only top-level items are used (the side panel is a flat list).
 *
 * @param string $location Registered menu location slug.
 * @return array<int, array{label:string,url:string,network:string}>
 */
function cular_menu_items( $location ) {
	$out       = array();
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return $out;
	}

	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( ! $items ) {
		return $out;
	}

	foreach ( $items as $item ) {
		if ( 0 !== (int) $item->menu_item_parent ) {
			continue;
		}
		$out[] = array(
			'label'   => $item->title,
			'url'     => $item->url,
			'network' => $item->title,
		);
	}

	return $out;
}
