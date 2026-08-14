<?php
/**
 * Social Media Marketing intake form.
 *
 * Ported from the Elementor Pro form on /form/social-media-marketing/ — the
 * largest of the old forms (36 questions across 5 declared steps). The original
 * step structure is kept, since it was already a sensible grouping.
 *
 * The one field NOT carried over is the original's file upload. Accepting
 * uploads means handling storage, size limits, MIME validation and spam, none of
 * which the intake plugin does today; asking for a link to the same assets
 * collects the same information without opening that surface. Revisit if the
 * team finds clients won't share links.
 *
 * @package CularIntakeForm
 */

defined( 'ABSPATH' ) || exit;

require_once CULAR_INTAKE_PLUGIN_DIR . 'templates/partials/render-spec.php';

cular_intake_render_form(
	array(
		'type'  => 'social-media',
		'title' => 'Social Media Marketing Form',
		'intro' => 'This is the long one — it is what lets us build a strategy rather than guess. Answer what you can; anything you leave blank we will cover on the call.',
		'steps' => array(
			cular_intake_contact_step(),

			array(
				'name'     => 'Business & goals',
				'sections' => array(
					array(
						'heading' => 'Business Goals & Marketing',
						'fields'  => array(
							array( 'name' => 'business_about', 'label' => 'What is your business about, and what do you sell or offer?', 'type' => 'textarea', 'required' => true, 'rows' => 4 ),
							array( 'name' => 'competitors', 'label' => 'Who are your main competitors, and what sets you apart from them?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'past_marketing', 'label' => 'What marketing channels and initiatives have you tried before?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'social_goal', 'label' => 'What is your goal in using social media for your business?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'coordinated_initiatives', 'label' => 'Any marketing initiatives that should coordinate with social media efforts?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'goals_6_12', 'label' => 'What are your main goals for social media in the next 6–12 months?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'brand_guidelines_dos', 'label' => 'Do you have specific marketing guidelines? (dos & don\'ts)', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
						),
					),
				),
			),

			array(
				'name'     => 'Platforms & strategy',
				'sections' => array(
					array(
						'heading' => 'Platform Use & Strategy',
						'fields'  => array(
							array( 'name' => 'active_platforms', 'label' => 'Which platforms are you active on? Please share links.', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array(
								'name'     => 'kpis',
								'label'    => 'Which KPIs matter most to you?',
								'type'     => 'checkbox',
								'required' => true,
								'options'  => array( 'Followers', 'Reach / impressions', 'Engagement rate', 'Website clicks', 'Leads / enquiries', 'Conversions / sales', 'Brand awareness' ),
							),
							array( 'name' => 'current_challenges', 'label' => 'What challenges are you facing with your current social media efforts?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'tools_analytics', 'label' => 'What tools or analytics do you use? Any past reports or benchmarks?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'competitor_insights', 'label' => "Any competitor strategies you'd want to emulate?", 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'posting_cadence', 'label' => 'How often do you want to post on each format?', 'type' => 'textarea', 'required' => true, 'rows' => 2, 'placeholder' => 'e.g. 3 reels + 2 static + daily stories' ),
							array(
								'name'     => 'social_efforts',
								'label'    => 'Which social media efforts do you want to do with us?',
								'type'     => 'checkbox',
								'required' => true,
								'options'  => array( 'Content creation', 'Content strategy', 'Community management', 'Paid advertising', 'Influencer / KOL', 'Reporting & analytics', 'Full management' ),
							),
						),
					),
				),
			),

			array(
				'name'     => 'Audience',
				'sections' => array(
					array(
						'heading' => 'Audience & Engagement',
						'fields'  => array(
							array( 'name' => 'ideal_audience', 'label' => 'Who is your ideal audience or customer on social media?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'audience_interests', 'label' => 'What are their common interests or pain points?', 'type' => 'textarea', 'rows' => 3 ),
							array(
								'name'    => 'community_engagement',
								'label'   => 'Which community engagement do you prefer?',
								'type'    => 'checkbox',
								'options' => array( 'Replying to comments', 'Replying to DMs', 'Engaging on other accounts', 'Community / group management', 'UGC resharing' ),
							),
							array( 'name' => 'response_tone', 'label' => 'Do you prefer formal or casual responses to customers and comments?', 'type' => 'select', 'width' => 6, 'options' => array( 'Formal', 'Casual', 'Somewhere in between' ) ),
							array( 'name' => 'phrases', 'label' => 'Any phrases you like or avoid? (e.g. "luxury", "affordable", "on sale")', 'type' => 'textarea', 'rows' => 2 ),
							array( 'name' => 'complaints_handling', 'label' => 'How do you manage complaints or customer issues on social media?', 'type' => 'textarea', 'rows' => 3 ),
						),
					),
				),
			),

			array(
				'name'     => 'Content & approvals',
				'sections' => array(
					array(
						'heading' => 'Content Direction',
						'fields'  => array(
							array( 'name' => 'culture_messages', 'label' => 'Define your company culture and the main messages you want to convey', 'type' => 'textarea', 'rows' => 4 ),
							array( 'name' => 'brand_assets_link', 'label' => 'Link to your brand guidelines / visual references', 'type' => 'text', 'placeholder' => 'https://drive.google.com/…', 'help' => 'A shared Drive, Dropbox or Notion link is ideal.' ),
							array(
								'name'    => 'content_focus',
								'label'   => 'What types of content do you want to focus on?',
								'type'    => 'checkbox',
								'options' => array( 'Education', 'Entertainment', 'Humor / memes', 'Behind the scenes', 'Product / service', 'Testimonials', 'Trends' ),
							),
						),
					),
					array(
						'heading' => 'Content Production & Approval',
						'fields'  => array(
							array( 'name' => 'internal_team', 'label' => 'Do you have an internal content team, or rely on agencies / photographers?', 'type' => 'textarea', 'rows' => 2 ),
							array( 'name' => 'available_assets', 'label' => 'What assets do you have access to? (product photos, videos, renders, UGC…)', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'ai_generated_ok', 'label' => 'Are you open to AI-generated images or video?', 'type' => 'select', 'width' => 6, 'options' => array( 'Yes', 'No', 'Case by case' ) ),
							array( 'name' => 'approver', 'label' => 'Who should we coordinate with for approvals and sign-off?', 'type' => 'text', 'width' => 6 ),
							array( 'name' => 'approval_timeline', 'label' => "What's the usual timeline for approvals?", 'type' => 'text', 'width' => 6 ),
							array( 'name' => 'existing_calendar', 'label' => 'Is there an existing content calendar, or do we build one from scratch?', 'type' => 'select', 'width' => 6, 'options' => array( 'We have one', 'Build from scratch', 'Partly — needs work' ) ),
							array( 'name' => 'scheduling_tools', 'label' => 'Do you use scheduling or analytics tools? (Later, Buffer, Meta Suite, Hootsuite…)', 'type' => 'text' ),
						),
					),
				),
			),
		),
	)
);
