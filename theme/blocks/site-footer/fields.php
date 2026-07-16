<?php
/**
 * ACF fields for cular/site-footer.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_site_footer',
		'title'    => 'Site Footer',
		'fields'   => array(
			array( 'key' => 'field_cular_footer_headline', 'label' => 'Headline', 'name' => 'headline', 'type' => 'text', 'default_value' => "Your Brand's Marketing Team" ),
			array(
				'key'        => 'field_cular_footer_cols',
				'label'      => 'Link columns',
				'name'       => 'columns',
				'type'       => 'repeater',
				'layout'     => 'block',
				'button_label' => 'Add column',
				'sub_fields' => array(
					array( 'key' => 'field_cular_footer_col_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_cular_footer_col_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'text' ),
					array( 'key' => 'field_cular_footer_col_url', 'label' => 'URL', 'name' => 'url', 'type' => 'text' ),
				),
			),
			array( 'key' => 'field_cular_footer_news_title', 'label' => 'Newsletter text', 'name' => 'newsletter_text', 'type' => 'textarea', 'rows' => 2 ),
			array(
				'key'        => 'field_cular_footer_social',
				'label'      => 'Social links',
				'name'       => 'social',
				'type'       => 'repeater',
				'layout'     => 'table',
				'sub_fields' => array(
					array( 'key' => 'field_cular_footer_social_net', 'label' => 'Network', 'name' => 'network', 'type' => 'text' ),
					array( 'key' => 'field_cular_footer_social_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/site-footer' ),
			),
		),
	)
);
