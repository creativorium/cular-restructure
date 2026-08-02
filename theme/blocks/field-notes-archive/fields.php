<?php
/**
 * ACF fields for cular/field-notes-archive.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_fn_archive',
		'title'    => 'Field Notes Archive',
		'fields'   => array(
			array( 'key' => 'field_cular_fna_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Field Notes' ),
			array( 'key' => 'field_cular_fna_intro', 'label' => 'Intro (one line per paragraph)', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/field-notes-archive' ),
			),
		),
	)
);
