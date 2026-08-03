<?php
/**
 * ACF fields for cular/service-list.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_service_list',
		'title'    => 'Service List',
		'fields'   => array(
			array( 'key' => 'field_cular_svc_card_title', 'label' => 'Card heading', 'name' => 'card_title', 'type' => 'text', 'instructions' => 'Optional heading inside the card.' ),
			array(
				'key'           => 'field_cular_svc_variant',
				'label'         => 'Colour',
				'name'          => 'variant',
				'type'          => 'select',
				'choices'       => array( 'green' => 'Green / gold', 'warm' => 'Warm (orange)' ),
				'default_value' => 'green',
			),
			array( 'key' => 'field_cular_svc_btn', 'label' => 'Default button label', 'name' => 'button_label', 'type' => 'text', 'default_value' => 'Discover More' ),
			array(
				'key'          => 'field_cular_svc_items',
				'label'        => 'Services',
				'name'         => 'items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add service',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_svc_item_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_cular_svc_item_text', 'label' => 'Blurb', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
					array( 'key' => 'field_cular_svc_item_url', 'label' => 'Link', 'name' => 'url', 'type' => 'url' ),
					array( 'key' => 'field_cular_svc_item_btn', 'label' => 'Button label', 'name' => 'button_label', 'type' => 'text', 'instructions' => 'Falls back to the default above.' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/service-list' ),
			),
		),
	)
);
