<?php
/**
 * Content Creation — Social Media intake form.
 *
 * Ported from the Elementor Pro form on /form/content-creation-for-social-media/
 * (21 questions, single page). Grouped into steps here so it does not present as
 * one intimidating wall, and given the standard contact step so submissions are
 * identifiable in the admin list.
 *
 * @package CularIntakeForm
 */

defined( 'ABSPATH' ) || exit;

require_once CULAR_INTAKE_PLUGIN_DIR . 'templates/partials/render-spec.php';

cular_intake_render_form(
	array(
		'type'  => 'content-social',
		'title' => 'Social Media Content Creation',
		'intro' => 'Tell us what you want the content to do, who it is for, and how it should feel — so what we produce lands the way you intend.',
		'steps' => array(
			cular_intake_contact_step(),

			array(
				'name'     => 'Goals & platforms',
				'sections' => array(
					array(
						'heading' => 'What this content is for',
						'fields'  => array(
							array( 'name' => 'primary_goal', 'label' => "What's the primary goal of your social media content?", 'type' => 'textarea', 'required' => true, 'rows' => 3, 'placeholder' => 'e.g. awareness, engagement, bookings…' ),
							array(
								'name'     => 'platforms',
								'label'    => 'Which platforms will this content be used on?',
								'type'     => 'checkbox',
								'required' => true,
								'options'  => array( 'Instagram', 'TikTok', 'Facebook', 'YouTube', 'LinkedIn', 'Pinterest', 'X / Twitter', 'Other' ),
							),
							array( 'name' => 'message_story', 'label' => 'What message or story do you want the content to convey?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'target_audience', 'label' => 'Who is your target audience, and how do you want them to interact with your content?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
						),
					),
				),
			),

			array(
				'name'     => 'Style & direction',
				'sections' => array(
					array(
						'heading' => 'Look and feel',
						'fields'  => array(
							array( 'name' => 'content_style', 'label' => 'Do you have a specific content style in mind?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'themes_topics', 'label' => 'Are there any themes or topics you want to focus on?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'tone_voice', 'label' => "What's the desired tone and voice for the content?", 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'visual_guidelines', 'label' => 'Do you have visual guidelines or brand colours to follow?', 'type' => 'textarea', 'rows' => 3, 'help' => 'Paste a link to your brand kit if you have one.' ),
							array( 'name' => 'content_types', 'label' => 'Are there specific types of content you want to create?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'inspiration', 'label' => 'Any competitors or accounts you admire we can draw inspiration from?', 'type' => 'textarea', 'rows' => 3 ),
						),
					),
				),
			),

			array(
				'name'     => 'Output & cadence',
				'sections' => array(
					array(
						'heading' => 'Volume, format and timing',
						'fields'  => array(
							array( 'name' => 'posting_frequency', 'label' => 'How frequently do you plan to post?', 'type' => 'select', 'width' => 6, 'options' => array( 'Daily', 'Several times a week', 'Weekly', 'Fortnightly', 'Monthly', 'Not sure yet' ) ),
							array( 'name' => 'video_length_pref', 'label' => 'Do you prefer short-form or longer-form videos?', 'type' => 'select', 'width' => 6, 'options' => array( 'Short-form', 'Longer-form', 'A mix of both', 'Not sure yet' ) ),
							array( 'name' => 'video_duration', 'label' => 'How long should the final edited videos be?', 'type' => 'text', 'width' => 6, 'placeholder' => 'e.g. 15–30 seconds' ),
							array( 'name' => 'video_count', 'label' => 'How many final edited videos do you expect?', 'type' => 'text', 'width' => 6, 'placeholder' => 'e.g. 8 per month' ),
							array( 'name' => 'photo_count', 'label' => 'How many final edited photos do you expect?', 'type' => 'text', 'width' => 6, 'placeholder' => 'e.g. 20 per month' ),
							array( 'name' => 'timeline', 'label' => "What's your timeline and deadline for delivery?", 'type' => 'text', 'width' => 6 ),
							array( 'name' => 'existing_content', 'label' => 'Any existing content you want to repurpose or build on?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'success_metrics', 'label' => 'What are your key metrics for success on social media?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'anything_else', 'label' => "Anything else we should know to keep this aligned with your brand's goals?", 'type' => 'textarea', 'rows' => 4 ),
						),
					),
				),
			),
		),
	)
);
