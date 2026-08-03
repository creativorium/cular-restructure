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
			array(
				'key'           => 'field_cular_about_scroll',
				'label'         => 'Show "Scroll Down" indicator',
				'name'          => 'show_scroll',
				'type'          => 'true_false',
				'default_value' => 1,
				'ui'            => 1,
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/about-intro' ),
			),
		),
	)
);
