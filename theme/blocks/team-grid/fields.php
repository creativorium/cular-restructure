<?php
/**
 * ACF fields for cular/team-grid.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_team_grid',
		'title'    => 'Team Grid',
		'fields'   => array(
			array( 'key' => 'field_cular_tg_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'default_value' => 'Meet the Cular Creative Team!' ),
			array(
				'key'          => 'field_cular_tg_members',
				'label'        => 'Members',
				'name'         => 'members',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Add member',
				'sub_fields'   => array(
					array( 'key' => 'field_cular_tg_photo', 'label' => 'Photo', 'name' => 'photo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ),
					array( 'key' => 'field_cular_tg_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text' ),
					array( 'key' => 'field_cular_tg_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text' ),
				),
			),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/team-grid' ),
			),
		),
	)
);
