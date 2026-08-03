<?php
/**
 * ACF fields for cular/cta-panel.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_cta_panel',
		'title'    => 'CTA Panel',
		'fields'   => array(
			array( 'key' => 'field_cular_ctap_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
			array( 'key' => 'field_cular_ctap_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_cular_ctap_btn_label', 'label' => 'Button label', 'name' => 'button_label', 'type' => 'text' ),
			array( 'key' => 'field_cular_ctap_btn_url', 'label' => 'Button URL', 'name' => 'button_url', 'type' => 'url' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/cta-panel' ),
			),
		),
	)
);
