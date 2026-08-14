<?php
/**
 * Brand Identity intake form.
 *
 * Reshaped from WPForms #11073 ("Branding Identity Form") on
 * /form/brand-identity/. Same questions, now on our own form so the answers
 * land in the Intake Forms admin alongside every other enquiry instead of in a
 * separate plugin's tables.
 *
 * @package CularIntakeForm
 */

defined( 'ABSPATH' ) || exit;

require_once CULAR_INTAKE_PLUGIN_DIR . 'templates/partials/render-spec.php';

cular_intake_render_form(
	array(
		'type'  => 'brand-identity',
		'title' => 'Brand Identity Form',
		'intro' => 'Branding work is only as good as the understanding behind it. These questions are about meaning and audience, not logos — that part comes later.',
		'steps' => array(
			cular_intake_contact_step(),

			array(
				'name'     => 'Your brand',
				'sections' => array(
					array(
						'heading' => 'What you do and why',
						'fields'  => array(
							array( 'name' => 'business_description', 'label' => 'Describe what your business provides, in a few short sentences', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'brand_story', 'label' => 'What is the story behind your brand?', 'type' => 'textarea', 'rows' => 4 ),
							array( 'name' => 'brand_why', 'label' => 'What is your why?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'brand_meaning', 'label' => 'How is your brand meaningful? How do you make your customers feel?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'pain_points', 'label' => 'What pain points does your brand solve? How does it benefit your customers?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'five_words', 'label' => 'What are five words that describe your brand?', 'type' => 'text', 'placeholder' => 'e.g. warm, precise, playful, honest, local' ),
						),
					),
				),
			),

			array(
				'name'     => 'Market & audience',
				'sections' => array(
					array(
						'heading' => 'Where you sit',
						'fields'  => array(
							array( 'name' => 'competition', 'label' => 'Who is your competition, and why?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'uniqueness', 'label' => 'How is your brand or service unique compared to others?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'ideal_customer', 'label' => 'Who is your ideal customer? Please be as specific as possible.', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'customer_trust', 'label' => 'Why do your customers trust you?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'admired_brands', 'label' => 'What other brands do you admire, and why?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'desired_experience', 'label' => 'How do you want customers to describe their experience?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'brand_perception', 'label' => 'How do you want others to see your brand?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'anything_else', 'label' => 'Anything else you would like Cular to know?', 'type' => 'textarea', 'rows' => 4 ),
						),
					),
				),
			),
		),
	)
);
