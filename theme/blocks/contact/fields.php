<?php
/**
 * ACF fields for cular/contact.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_contact',
		'title'    => 'Contact',
		'fields'   => array(
			array( 'key' => 'field_cular_contact_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 4 ),
			array( 'key' => 'field_cular_contact_email', 'label' => 'Email', 'name' => 'email', 'type' => 'text' ),
			array( 'key' => 'field_cular_contact_phone', 'label' => 'Phone', 'name' => 'phone', 'type' => 'text' ),
			array( 'key' => 'field_cular_contact_company', 'label' => 'Company name', 'name' => 'company', 'type' => 'text' ),
			array( 'key' => 'field_cular_contact_address', 'label' => 'Address', 'name' => 'address', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_cular_contact_map', 'label' => 'Directions URL', 'name' => 'map_url', 'type' => 'url' ),
			array( 'key' => 'field_cular_contact_form_head', 'label' => 'Form heading', 'name' => 'form_heading', 'type' => 'text', 'default_value' => 'Book a Call with Us' ),
			array( 'key' => 'field_cular_contact_form', 'label' => 'Intake form type', 'name' => 'form_type', 'type' => 'text', 'default_value' => 'contact' ),
			array( 'key' => 'field_cular_contact_form_only', 'label' => 'Form only', 'name' => 'form_only', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'instructions' => 'Hide the contact-details column and give the form the full width. Use on dedicated /form/ pages.' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/contact' ),
			),
		),
	)
);
