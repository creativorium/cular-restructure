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
			array(
				'key'          => 'field_cular_sdet_details',
				'label'        => 'What it covers',
				'name'         => 'details',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add item',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_sdet_d_q', 'label' => 'Title', 'name' => 'q', 'type' => 'text' ),
					array( 'key' => 'field_cular_sdet_d_a', 'label' => 'Detail', 'name' => 'a', 'type' => 'textarea', 'rows' => 4 ),
				),
			),
			array( 'key' => 'field_cular_sdet_port_head', 'label' => 'Portfolio heading', 'name' => 'portfolio_heading', 'type' => 'text', 'instructions' => 'e.g. "Some of our previous Graphic Design work".' ),
			array( 'key' => 'field_cular_sdet_port_tags', 'label' => 'Portfolio tags', 'name' => 'portfolio_tags', 'type' => 'text', 'instructions' => 'Comma-separated portfolio_tag names. Leave blank to hide the section.' ),
			array( 'key' => 'field_cular_sdet_port_count', 'label' => 'Portfolio count', 'name' => 'portfolio_count', 'type' => 'number', 'default_value' => 4 ),
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
					array( 'key' => 'field_cular_sdet_rel_items', 'label' => 'Sub-topics', 'name' => 'items', 'type' => 'textarea', 'rows' => 4, 'instructions' => 'One per line.' ),
				),
			),
			array( 'key' => 'field_cular_sdet_form_head', 'label' => 'Form heading', 'name' => 'form_heading', 'type' => 'text', 'default_value' => 'Book a Call with Us' ),
			array(
				'key'          => 'field_cular_sdet_form',
				'label'        => 'Intake form',
				'name'         => 'form_type',
				'type'         => 'text',
				'instructions' => 'Cular Intake Forms type, e.g. "contact", "ads", "seo". Leave blank for no form.',
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/service-detail' ),
			),
		),
	)
);
