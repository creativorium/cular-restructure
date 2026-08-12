<?php
/**
 * ACF fields for cular/case-study.
 *
 * Shape mirrors what every one of the 47 old Elementor case studies actually
 * contained (see reference/elementor-extract.php): a client logo, an intro, a
 * service list, then heading-led sections of copy and media.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'    => 'group_cular_case_study',
		'title'  => 'Case Study',
		'fields' => array(
			array( 'key' => 'field_cular_cs_logo', 'label' => 'Client logo', 'name' => 'client_logo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Falls back to the linked portfolio item\'s card art.' ),
			array( 'key' => 'field_cular_cs_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0, 'toolbar' => 'basic' ),
			array( 'key' => 'field_cular_cs_services', 'label' => 'Services', 'name' => 'services', 'type' => 'textarea', 'rows' => 4, 'instructions' => 'One service per line. Falls back to the portfolio item\'s tags.' ),

			array(
				'key'          => 'field_cular_cs_sections',
				'label'        => 'Sections',
				'name'         => 'sections',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add section',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_cs_s_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_cular_cs_s_body', 'label' => 'Body', 'name' => 'body', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0, 'toolbar' => 'basic' ),
					array(
						'key'          => 'field_cular_cs_s_media',
						'label'        => 'Media',
						'name'         => 'media',
						'type'         => 'repeater',
						'layout'       => 'table',
						'button_label' => 'Add media',
						'sub_fields'   => array(
							array( 'key' => 'field_cular_cs_m_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail' ),
							array( 'key' => 'field_cular_cs_m_video', 'label' => 'Video URL', 'name' => 'video', 'type' => 'text', 'instructions' => 'Self-hosted .webm/.mp4. Takes precedence over the image, which then acts as the poster.' ),
						),
					),
				),
			),

			array( 'key' => 'field_cular_cs_rel_head', 'label' => 'Related heading', 'name' => 'related_heading', 'type' => 'text', 'default_value' => 'Check some of our other work' ),
			array( 'key' => 'field_cular_cs_rel_count', 'label' => 'Related count', 'name' => 'related_count', 'type' => 'number', 'default_value' => 4, 'min' => 0, 'max' => 8, 'instructions' => 'Set 0 to hide the related-work section.' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/case-study' ),
			),
		),
	)
);
