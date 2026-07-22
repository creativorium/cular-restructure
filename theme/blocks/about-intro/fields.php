<?php
/**
 * ACF fields for cular/about-intro.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_about_intro',
		'title'    => 'About Intro',
		'fields'   => array(
			array( 'key' => 'field_cular_about_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'About Us' ),
			array( 'key' => 'field_cular_about_body', 'label' => 'Body', 'name' => 'body', 'type' => 'textarea', 'rows' => 10, 'instructions' => 'Blank lines start a new paragraph.' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/about-intro' ),
			),
		),
	)
);
