<?php
/**
 * ACF fields for cular/site-header.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_site_header',
		'title'    => 'Site Header',
		'fields'   => array(
			array(
				'key'           => 'field_cular_header_logo',
				'label'         => 'Logo',
				'name'          => 'logo',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'thumbnail',
			),
			array(
				'key'        => 'field_cular_header_nav',
				'label'      => 'Navigation items',
				'name'       => 'nav_items',
				'type'       => 'repeater',
				'layout'     => 'table',
				'button_label' => 'Add menu item',
				'sub_fields' => array(
					array( 'key' => 'field_cular_header_nav_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
					array( 'key' => 'field_cular_header_nav_url', 'label' => 'URL', 'name' => 'url', 'type' => 'text' ),
				),
			),
			array(
				'key'        => 'field_cular_header_social',
				'label'      => 'Social links',
				'name'       => 'social',
				'type'       => 'repeater',
				'layout'     => 'table',
				'button_label' => 'Add social link',
				'sub_fields' => array(
					array( 'key' => 'field_cular_header_social_net', 'label' => 'Network', 'name' => 'network', 'type' => 'text' ),
					array( 'key' => 'field_cular_header_social_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/site-header' ),
			),
		),
	)
);
