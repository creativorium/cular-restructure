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
			array( 'key' => 'field_cular_services_heading', 'label' => 'Section heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Our Services' ),
			array(
				'key'          => 'field_cular_services_items',
				'label'        => 'Service cards',
				'name'         => 'items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add service card',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_services_item_title', 'label' => 'Big title', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_cular_services_item_sub', 'label' => 'Subtitle', 'name' => 'subtitle', 'type' => 'text' ),
					array( 'key' => 'field_cular_services_item_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 4 ),
					array( 'key' => 'field_cular_services_item_label', 'label' => 'Link label', 'name' => 'link_label', 'type' => 'text' ),
					array( 'key' => 'field_cular_services_item_url', 'label' => 'Link URL', 'name' => 'url', 'type' => 'text' ),
					array(
						'key'     => 'field_cular_services_item_theme',
						'label'   => 'Card colour',
						'name'    => 'card_theme',
						'type'    => 'select',
						'choices' => array( 'warm' => 'Warm (orange → gold)', 'green' => 'Green → gold' ),
						'default_value' => 'warm',
					),
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
