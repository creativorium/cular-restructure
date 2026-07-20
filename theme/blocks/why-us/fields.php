<?php
/**
 * ACF fields for cular/why-us.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_why_us',
		'title'    => 'Why Work With Us',
		'fields'   => array(
			array(
				'key'           => 'field_cular_why_logos',
				'label'         => 'Client logos',
				'name'          => 'logos',
				'type'          => 'gallery',
				'return_format' => 'array',
				'instructions'  => 'White client logos for the scrolling strip. Leave empty to use the current set.',
			),
			array( 'key' => 'field_cular_why_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Why work with us?' ),
			array( 'key' => 'field_cular_why_body', 'label' => 'Body copy', 'name' => 'body', 'type' => 'textarea', 'rows' => 8, 'instructions' => 'Blank lines start a new paragraph.' ),
			array(
				'key'           => 'field_cular_why_badges',
				'label'         => 'Certification badges',
				'name'          => 'badges',
				'type'          => 'gallery',
				'return_format' => 'array',
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/why-us' ),
			),
		),
	)
);
