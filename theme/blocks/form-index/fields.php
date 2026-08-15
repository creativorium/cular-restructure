<?php
/**
 * ACF fields for cular/form-index.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'    => 'group_cular_form_index',
		'title'  => 'Form Index',
		'fields' => array(
			array( 'key' => 'field_cular_fidx_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_cular_fidx_parent', 'label' => 'Parent page ID', 'name' => 'parent', 'type' => 'number', 'instructions' => 'Leave blank to list the children of the current page.' ),
			array( 'key' => 'field_cular_fidx_empty', 'label' => 'Empty message', 'name' => 'empty_text', 'type' => 'text', 'default_value' => 'No forms are published yet.' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/form-index' ),
			),
		),
	)
);
