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
			array( 'key' => 'field_cular_fn_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Our Field Notes' ),
			array( 'key' => 'field_cular_fn_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 4 ),
			array( 'key' => 'field_cular_fn_count', 'label' => 'Number of posts', 'name' => 'count', 'type' => 'number', 'default_value' => 6, 'min' => 1, 'max' => 12 ),
			array( 'key' => 'field_cular_fn_all', 'label' => 'View-all URL', 'name' => 'all_url', 'type' => 'text' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/field-notes' ),
			),
		),
	)
);
