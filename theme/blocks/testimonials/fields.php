<?php
/**
 * ACF fields for cular/testimonials.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_testimonials',
		'title'    => 'Testimonials',
		'fields'   => array(
			array( 'key' => 'field_cular_tst_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Testimonials' ),
			array( 'key' => 'field_cular_tst_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'text' ),
			array(
				'key'          => 'field_cular_tst_videos',
				'label'        => 'Video testimonials',
				'name'         => 'videos',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add video testimonial',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_tst_v_url', 'label' => 'Video URL', 'name' => 'video_url', 'type' => 'text' ),
					array( 'key' => 'field_cular_tst_v_logo', 'label' => 'Client logo', 'name' => 'logo', 'type' => 'image', 'return_format' => 'array' ),
					array( 'key' => 'field_cular_tst_v_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text' ),
					array( 'key' => 'field_cular_tst_v_company', 'label' => 'Company', 'name' => 'company', 'type' => 'text' ),
				),
			),
			array(
				'key'          => 'field_cular_tst_items',
				'label'        => 'Written testimonials',
				'name'         => 'items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add written testimonial',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_tst_quote', 'label' => 'Quote', 'name' => 'quote', 'type' => 'textarea', 'rows' => 5 ),
					array( 'key' => 'field_cular_tst_author', 'label' => 'Author', 'name' => 'author', 'type' => 'text' ),
					array( 'key' => 'field_cular_tst_role', 'label' => 'Company / role', 'name' => 'role', 'type' => 'text' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/testimonials' ),
			),
		),
	)
);
