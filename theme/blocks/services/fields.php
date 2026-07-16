<?php
/**
 * ACF fields for cular/services.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_services',
		'title'    => 'Services',
		'fields'   => array(
			array( 'key' => 'field_cular_services_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Our Services' ),
			array( 'key' => 'field_cular_services_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
			array(
				'key'        => 'field_cular_services_items',
				'label'      => 'Service items',
				'name'       => 'items',
				'type'       => 'repeater',
				'layout'     => 'block',
				'button_label' => 'Add service',
				'sub_fields' => array(
					array( 'key' => 'field_cular_services_item_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_cular_services_item_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
					array( 'key' => 'field_cular_services_item_url', 'label' => 'Link URL', 'name' => 'url', 'type' => 'text' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/services' ),
			),
		),
	)
);
