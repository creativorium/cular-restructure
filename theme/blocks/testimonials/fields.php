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
			array(
				'key'          => 'field_cular_tst_items',
				'label'        => 'Testimonials',
				'name'         => 'items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add testimonial',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_tst_quote', 'label' => 'Quote', 'name' => 'quote', 'type' => 'textarea', 'rows' => 4 ),
					array( 'key' => 'field_cular_tst_author', 'label' => 'Author', 'name' => 'author', 'type' => 'text' ),
					array( 'key' => 'field_cular_tst_role', 'label' => 'Role / Company', 'name' => 'role', 'type' => 'text' ),
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
