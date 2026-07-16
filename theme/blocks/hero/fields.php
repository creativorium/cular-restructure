<?php
/**
 * ACF fields for cular/hero.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_hero',
		'title'    => 'Hero',
		'fields'   => array(
			array(
				'key'   => 'field_cular_hero_eyebrow',
				'label' => 'Eyebrow',
				'name'  => 'eyebrow',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_cular_hero_heading',
				'label' => 'Heading',
				'name'  => 'heading',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_cular_hero_subtext',
				'label' => 'Subtext',
				'name'  => 'subtext',
				'type'  => 'textarea',
				'rows'  => 3,
			),
			array(
				'key'   => 'field_cular_hero_cta_label',
				'label' => 'CTA label',
				'name'  => 'cta_label',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_cular_hero_cta_url',
				'label' => 'CTA URL',
				'name'  => 'cta_url',
				'type'  => 'url',
			),
			array(
				'key'           => 'field_cular_hero_bg',
				'label'         => 'Background image',
				'name'          => 'background',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'cular/hero',
				),
			),
		),
	)
);
