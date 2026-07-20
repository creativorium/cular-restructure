<?php
/**
 * ACF fields for cular/team-intro.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

acf_add_local_field_group(
	array(
		'key'      => 'group_cular_team_intro',
		'title'    => 'Team Intro',
		'fields'   => array(
			array(
				'key'           => 'field_cular_team_image',
				'label'         => 'Poster image',
				'name'          => 'image',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
			),
			array(
				'key'          => 'field_cular_team_video',
				'label'        => 'Video URL',
				'name'         => 'video_url',
				'type'         => 'text',
				'instructions' => 'Plays inline when the poster is clicked. Leave blank for a static image.',
			),
			array( 'key' => 'field_cular_team_playlabel', 'label' => 'Play label', 'name' => 'play_label', 'type' => 'text', 'default_value' => 'Play Me' ),
			array( 'key' => 'field_cular_team_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
			array( 'key' => 'field_cular_team_body', 'label' => 'Body copy', 'name' => 'body', 'type' => 'textarea', 'rows' => 6, 'instructions' => 'Blank lines start a new paragraph.' ),
			array( 'key' => 'field_cular_team_link_label', 'label' => 'Link label', 'name' => 'link_label', 'type' => 'text', 'default_value' => 'Meet the Team' ),
			array( 'key' => 'field_cular_team_link_url', 'label' => 'Link URL', 'name' => 'link_url', 'type' => 'text' ),
		),
		'location' => array(
			array(
				array( 'param' => 'block', 'operator' => '==', 'value' => 'cular/team-intro' ),
			),
		),
	)
);
