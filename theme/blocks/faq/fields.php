<?php
/**
 * ACF fields for cular/faq.
 *
 * Leaving "Groups" empty falls back to the roster in data.php.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_faq',
		'title'    => 'FAQ',
		'fields'   => array(
			array( 'key' => 'field_cular_faq_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Frequently Asked Questions' ),
			array( 'key' => 'field_cular_faq_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_cular_faq_eyebrow', 'label' => 'Card eyebrow', 'name' => 'eyebrow', 'type' => 'text', 'default_value' => 'Cular' ),
			array(
				'key'          => 'field_cular_faq_groups',
				'label'        => 'Groups',
				'name'         => 'groups',
				'type'         => 'repeater',
				'instructions' => 'Leave empty to use the built-in FAQ content.',
				'layout'       => 'block',
				'button_label' => 'Add category',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_faq_group_title', 'label' => 'Category title', 'name' => 'title', 'type' => 'text' ),
					array(
						'key'          => 'field_cular_faq_subgroups',
						'label'        => 'Sub-groups',
						'name'         => 'subgroups',
						'type'         => 'repeater',
						'layout'       => 'block',
						'button_label' => 'Add sub-group',
						'sub_fields'   => array(
							array( 'key' => 'field_cular_faq_sub_title', 'label' => 'Sub-group title', 'name' => 'title', 'type' => 'text', 'instructions' => 'Leave blank for an ungrouped list.' ),
							array(
								'key'           => 'field_cular_faq_sub_align',
								'label'         => 'Align',
								'name'          => 'align',
								'type'          => 'select',
								'choices'       => array( 'left' => 'Left', 'right' => 'Right' ),
								'default_value' => 'left',
							),
							array(
								'key'          => 'field_cular_faq_items',
								'label'        => 'Questions',
								'name'         => 'items',
								'type'         => 'repeater',
								'layout'       => 'block',
								'button_label' => 'Add question',
								'sub_fields'   => array(
									array( 'key' => 'field_cular_faq_q', 'label' => 'Question', 'name' => 'q', 'type' => 'text' ),
									array( 'key' => 'field_cular_faq_a', 'label' => 'Answer', 'name' => 'a', 'type' => 'textarea', 'rows' => 4 ),
								),
							),
						),
					),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/faq' ),
			),
		),
	)
);
