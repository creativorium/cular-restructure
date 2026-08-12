<?php
/**
 * ACF fields for cular/portfolio-archive.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'    => 'group_cular_portfolio_archive',
		'title'  => 'Portfolio Archive',
		'fields' => array(
			array( 'key' => 'field_cular_pa_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'A showcase of some of our work, in no particular order.' ),
			array( 'key' => 'field_cular_pa_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_cular_pa_filters', 'label' => 'Show service filters', 'name' => 'show_filters', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1 ),
			array( 'key' => 'field_cular_pa_filter_label', 'label' => 'Filter label', 'name' => 'filter_label', 'type' => 'text', 'default_value' => 'Curious to see what we can do? Select a service to browse our portfolio.' ),
			array( 'key' => 'field_cular_pa_cta_text', 'label' => 'CTA text', 'name' => 'cta_text', 'type' => 'text', 'default_value' => 'Want to work with us?' ),
			array( 'key' => 'field_cular_pa_cta_label', 'label' => 'CTA button label', 'name' => 'cta_label', 'type' => 'text', 'default_value' => 'Get in Touch' ),
			array( 'key' => 'field_cular_pa_cta_url', 'label' => 'CTA button URL', 'name' => 'cta_url', 'type' => 'url', 'instructions' => 'Defaults to the Contact page.' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/portfolio-archive' ),
			),
		),
	)
);
