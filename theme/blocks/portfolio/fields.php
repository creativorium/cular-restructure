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
			array( 'key' => 'field_cular_portfolio_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Our Previous Work' ),
			array( 'key' => 'field_cular_portfolio_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
			array( 'key' => 'field_cular_portfolio_count', 'label' => 'Number of projects', 'name' => 'count', 'type' => 'number', 'default_value' => 6, 'min' => 1, 'max' => 12 ),
			array( 'key' => 'field_cular_portfolio_all_url', 'label' => 'View-all URL', 'name' => 'all_url', 'type' => 'text' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/portfolio' ),
			),
		),
	)
);
