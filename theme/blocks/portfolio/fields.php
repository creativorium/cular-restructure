<?php
/**
 * ACF fields for cular/portfolio.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_portfolio',
		'title'    => 'Portfolio',
		'fields'   => array(
			array( 'key' => 'field_cular_portfolio_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Our Previous Work' ),
			array( 'key' => 'field_cular_portfolio_intro', 'label' => 'Intro paragraph', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
			array(
				'key'          => 'field_cular_portfolio_items',
				'label'        => 'Featured projects',
				'name'         => 'featured_items',
				'type'         => 'relationship',
				'post_type'    => array( 'portfolio_item' ),
				'filters'      => array( 'search' ),
				'return_format' => 'id',
				'instructions' => 'Pick and order the projects to feature. Leave empty to show the most recent.',
			),
			array( 'key' => 'field_cular_portfolio_count', 'label' => 'Number of projects (when none picked)', 'name' => 'count', 'type' => 'number', 'default_value' => 5, 'min' => 1, 'max' => 12 ),
			array( 'key' => 'field_cular_portfolio_all_url', 'label' => 'View-all URL', 'name' => 'all_url', 'type' => 'text' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/portfolio' ),
			),
		),
	)
);
