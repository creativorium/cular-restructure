<?php
/**
 * ACF fields for cular/page-hero.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_page_hero',
		'title'    => 'Page Hero',
		'fields'   => array(
			array( 'key' => 'field_cular_phero_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'instructions' => 'Defaults to the page title.' ),
			array( 'key' => 'field_cular_phero_lead', 'label' => 'Lead line', 'name' => 'lead', 'type' => 'text', 'instructions' => 'Optional bold line above the body copy.' ),
			array( 'key' => 'field_cular_phero_body', 'label' => 'Body', 'name' => 'body', 'type' => 'textarea', 'rows' => 6, 'instructions' => 'Blank lines start a new paragraph.' ),
			array( 'key' => 'field_cular_phero_image', 'label' => 'Cut-out image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
			array(
				'key'           => 'field_cular_phero_bare',
				'label'         => 'Transparent band',
				'name'          => 'bare',
				'type'          => 'true_false',
				'instructions'  => 'Let the surrounding group background show through instead of the green band.',
				'default_value' => 0,
				'ui'            => 1,
			),
			array(
				'key'           => 'field_cular_phero_size',
				'label'         => 'Band size',
				'name'          => 'size',
				'type'          => 'select',
				'choices'       => array( 'large' => 'Large (hub pages)', 'small' => 'Small (detail pages)' ),
				'default_value' => 'large',
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/page-hero' ),
			),
		),
	)
);
