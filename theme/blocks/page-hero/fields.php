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
			array( 'key' => 'field_cular_phero_flush', 'label' => 'Flush left', 'name' => 'flush', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'instructions' => 'Remove the hero&#39;s horizontal inset so the title sits on the container edge.' ),
			array( 'key' => 'field_cular_phero_scroll', 'label' => 'Scroll indicator', 'name' => 'show_scroll', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'instructions' => 'Show the waterfall "Scroll Down" rail, as on the homepage and About heroes.' ),
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
				'key'           => 'field_cular_phero_wide',
				'label'         => 'Wide container',
				'name'          => 'wide',
				'type'          => 'true_false',
				'instructions'  => 'Align the title with a 1440px page container instead of 1152px.',
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
