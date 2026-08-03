<?php
/**
 * ACF fields for cular/service-detail.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_service_detail',
		'title'    => 'Service Detail',
		'fields'   => array(
			array( 'key' => 'field_cular_sdet_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Approach and work specifics' ),
			array( 'key' => 'field_cular_sdet_body', 'label' => 'Body', 'name' => 'body', 'type' => 'textarea', 'rows' => 8, 'instructions' => 'Blank lines start a new paragraph.' ),
			array( 'key' => 'field_cular_sdet_btn', 'label' => 'Button label', 'name' => 'button_label', 'type' => 'text', 'default_value' => 'Get in Touch' ),
			array( 'key' => 'field_cular_sdet_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url', 'instructions' => 'Defaults to the Contact page.' ),
			array( 'key' => 'field_cular_sdet_rel_head', 'label' => 'Related heading', 'name' => 'related_heading', 'type' => 'text' ),
			array(
				'key'          => 'field_cular_sdet_related',
				'label'        => 'Related services',
				'name'         => 'related',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add related service',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_sdet_rel_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_cular_sdet_rel_url', 'label' => 'Link', 'name' => 'url', 'type' => 'url' ),
					array( 'key' => 'field_cular_sdet_rel_img', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/service-detail' ),
			),
		),
	)
);
