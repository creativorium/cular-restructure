<?php
/**
 * Content Creation — Photo & Video Shoot intake form.
 *
 * Ported from the Elementor Pro form on
 * /form/content-creation-for-photo-and-video-shoot/ (20 questions).
 *
 * @package CularIntakeForm
 */

defined( 'ABSPATH' ) || exit;

require_once CULAR_INTAKE_PLUGIN_DIR . 'templates/partials/render-spec.php';

cular_intake_render_form(
	array(
		'type'  => 'content-shoot',
		'title' => 'Photo & Video Shoot Brief',
		'intro' => 'The more precisely you can describe the look, mood and deliverables, the closer the first cut will be to what you pictured.',
		'steps' => array(
			cular_intake_contact_step(),

			array(
				'name'     => 'The brief',
				'sections' => array(
					array(
						'heading' => 'Purpose & story',
						'fields'  => array(
							array( 'name' => 'shoot_purpose', 'label' => "What's the primary purpose of this photoshoot / video project?", 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'message_story', 'label' => 'What message or story should the visuals convey?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'themes_concepts', 'label' => 'Are there specific themes or concepts you want to explore?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'audience_perception', 'label' => 'Who is your target audience, and how should they perceive your brand through these visuals?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'emotions', 'label' => 'What emotions or feelings should the audience experience?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
						),
					),
				),
			),

			array(
				'name'     => 'Look & feel',
				'sections' => array(
					array(
						'heading' => 'Visual direction',
						'fields'  => array(
							array( 'name' => 'visual_style', 'label' => 'Do you have a particular visual style in mind?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'colour_palette', 'label' => 'Preferred colour palette, or any colours to avoid?', 'type' => 'textarea', 'required' => true, 'rows' => 2 ),
							array( 'name' => 'references', 'label' => 'Reference images, videos or moodboards that match your vision', 'type' => 'textarea', 'required' => true, 'rows' => 3, 'help' => 'Paste links — Pinterest, Drive, Instagram saves all work.' ),
							array( 'name' => 'atmosphere', 'label' => 'Desired atmosphere or setting for the shoot', 'type' => 'textarea', 'required' => true, 'rows' => 2 ),
							array( 'name' => 'lighting', 'label' => 'Preferences for lighting', 'type' => 'select', 'required' => true, 'width' => 6, 'options' => array( 'Natural / daylight', 'Soft & diffused', 'Bright & high-key', 'Moody & low-key', 'Golden hour', 'Studio', 'No preference' ) ),
							array( 'name' => 'aspect_ratio', 'label' => 'Format / aspect ratio for the finals', 'type' => 'checkbox', 'required' => true, 'options' => array( 'Vertical 9:16', 'Square 1:1', 'Portrait 4:5', 'Landscape 16:9', 'Not sure yet' ) ),
							array( 'name' => 'props_outfits', 'label' => 'Specific props, outfits or accessories to include?', 'type' => 'textarea', 'required' => true, 'rows' => 2 ),
							array( 'name' => 'locations', 'label' => 'Locations or backgrounds you envision', 'type' => 'textarea', 'required' => true, 'rows' => 2 ),
						),
					),
				),
			),

			array(
				'name'     => 'Deliverables',
				'sections' => array(
					array(
						'heading' => 'What you expect to receive',
						'fields'  => array(
							array( 'name' => 'video_length_pref', 'label' => 'Do you prefer short-form or longer-form videos?', 'type' => 'select', 'width' => 6, 'options' => array( 'Short-form', 'Longer-form', 'A mix of both', 'Not sure yet' ) ),
							array( 'name' => 'video_duration', 'label' => 'How long should the final edited videos be?', 'type' => 'text', 'width' => 6, 'placeholder' => 'e.g. 15–30 seconds' ),
							array( 'name' => 'video_count', 'label' => 'How many final edited videos do you expect?', 'type' => 'text', 'width' => 6 ),
							array( 'name' => 'photo_count', 'label' => 'How many final edited photos do you expect?', 'type' => 'text', 'width' => 6 ),
							array( 'name' => 'timeline', 'label' => "What's your timeline and deadline for this project?", 'type' => 'text', 'required' => true, 'width' => 12 ),
							array( 'name' => 'anything_else', 'label' => 'Anything else we should know?', 'type' => 'textarea', 'rows' => 4 ),
						),
					),
				),
			),
		),
	)
);
