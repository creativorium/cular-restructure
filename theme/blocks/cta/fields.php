<?php
/**
 * ACF fields for cular/cta.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_cta',
		'title'    => 'CTA',
		'fields'   => array(
			array( 'key' => 'field_cular_cta_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Want to work with us?' ),
			array( 'key' => 'field_cular_cta_highlight', 'label' => 'Highlighted word (gold)', 'name' => 'highlight', 'type' => 'text', 'default_value' => 'us?', 'instructions' => 'This word/phrase at the end of the heading gets the gold gradient.' ),
			array( 'key' => 'field_cular_cta_label', 'label' => 'Link label', 'name' => 'button_label', 'type' => 'text', 'default_value' => 'Start Our Partnership Here' ),
			array( 'key' => 'field_cular_cta_url', 'label' => 'Link URL', 'name' => 'button_url', 'type' => 'text' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/cta' ),
			),
		),
	)
);
