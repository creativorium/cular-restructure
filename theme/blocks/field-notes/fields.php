<?php
/**
 * ACF fields for cular/field-notes.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_field_notes',
		'title'    => 'Field Notes',
		'fields'   => array(
			array( 'key' => 'field_cular_fn_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Our Field Notes' ),
			array( 'key' => 'field_cular_fn_count', 'label' => 'Number of posts', 'name' => 'count', 'type' => 'number', 'default_value' => 3, 'min' => 1, 'max' => 6 ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/field-notes' ),
			),
		),
	)
);
