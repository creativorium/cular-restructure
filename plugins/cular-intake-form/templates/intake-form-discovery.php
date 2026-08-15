<?php
/**
 * New Client Discovery intake form.
 *
 * Reshaped from WPForms #11067 ("New Client Discovery") on
 * /form/new-client-discovery/. This is the broad first-conversation form used
 * before a specific service has been chosen.
 *
 * @package CularIntakeForm
 */

defined( 'ABSPATH' ) || exit;

require_once CULAR_INTAKE_PLUGIN_DIR . 'templates/partials/render-spec.php';

cular_intake_render_form(
	array(
		'type'  => 'discovery',
		'title' => 'Client Discovery Form',
		'intro' => 'A broad first look at your business, so we arrive at the call already understanding what you do and where you want to go.',
		'steps' => array(
			cular_intake_contact_step(),

			array(
				'name'     => 'Your company',
				'sections' => array(
					array(
						'heading' => 'Background',
						'fields'  => array(
							array( 'name' => 'company_description', 'label' => 'Describe what your company does, in a few short sentences', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array( 'name' => 'years_in_business', 'label' => 'How long have you been in business?', 'type' => 'text', 'width' => 6 ),
							array( 'name' => 'company_reach', 'label' => 'Are you a local, national or international company?', 'type' => 'radio', 'width' => 6, 'options' => array( 'Local', 'National', 'International' ) ),
							array( 'name' => 'founding_vision', 'label' => 'Why was this company started? Have the vision or goals changed recently?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'strengths', 'label' => 'Can you describe your company\'s strengths?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'weaknesses', 'label' => 'Can you describe your company\'s weaknesses?', 'type' => 'textarea', 'rows' => 3 ),
						),
					),
				),
			),

			array(
				'name'     => 'Market & audience',
				'sections' => array(
					array(
						'heading' => 'Competition and customers',
						'fields'  => array(
							array( 'name' => 'competition', 'label' => 'Who is your competition?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'differentiation', 'label' => 'How are you different from your competition?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'target_audience', 'label' => 'Who is your target audience, and how do they currently discover you? Do you have more than one?', 'type' => 'textarea', 'required' => true, 'rows' => 4 ),
							array( 'name' => 'past_marketing', 'label' => 'What did you like or dislike about your past marketing material? What worked and what did not?', 'type' => 'textarea', 'rows' => 4 ),
						),
					),
				),
			),

			array(
				'name'     => 'Working together',
				'sections' => array(
					array(
						'heading' => 'Scope and expectations',
						'fields'  => array(
							array( 'name' => 'goals_with_cular', 'label' => 'What are your long-term and short-term goals while working with Cular?', 'type' => 'textarea', 'required' => true, 'rows' => 3 ),
							array(
								'name'    => 'services_interested',
								'label'   => 'What services are you interested in pursuing with Cular?',
								'type'    => 'checkbox',
								'options' => array( 'Social Media Marketing', 'Content Creation', 'Brand Identity', 'Web Design', 'Web Development', 'SEO', 'Digital Advertising', 'Graphic Design', 'Copywriting', 'Consultancy' ),
							),
							array( 'name' => 'design_preferences', 'label' => 'Any new design elements you would like to try, or design styles you do not like?', 'type' => 'textarea', 'rows' => 3 ),
							array( 'name' => 'budget', 'label' => 'Do you have a budget for this project?', 'type' => 'text', 'width' => 6, 'help' => 'A range is fine — it helps us scope realistically.' ),
							array( 'name' => 'anything_else', 'label' => 'Is there anything else you would like Cular to know?', 'type' => 'textarea', 'rows' => 4 ),
						),
					),
				),
			),
		),
	)
);
