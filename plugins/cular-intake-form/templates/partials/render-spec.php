<?php
/**
 * Spec-driven form renderer.
 *
 * The hand-written templates (web, ads, seo, contact) each repeat the same
 * wrapper, stepper, progress bar and actions markup around a different set of
 * questions — several hundred lines of which only the questions differ. Forms
 * ported off Elementor are pure question lists, so they declare a spec and this
 * renders it. Same markup contract, same CSS, same generic JS driver.
 *
 * A spec looks like:
 *
 *   array(
 *     'type'  => 'social-media',          // must match the $form_types key
 *     'title' => 'Social Media Marketing Form',
 *     'intro' => 'One or two sentences.',
 *     'steps' => array(
 *       array(
 *         'name'     => 'Business',        // shown in the stepper
 *         'sections' => array(
 *           array(
 *             'heading' => 'Business & Contact',
 *             'fields'  => array(
 *               array( 'name' => 'business_name', 'label' => 'Business name',
 *                      'type' => 'text', 'required' => true, 'width' => 6 ),
 *             ),
 *           ),
 *         ),
 *       ),
 *     ),
 *   )
 *
 * Field keys: name, label, type, required, width (6|12), placeholder, help,
 * options (select/radio/checkbox), rows (textarea).
 *
 * @package CularIntakeForm
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cular_intake_render_field' ) ) {
	/**
	 * Render one field.
	 *
	 * @param array $f Field spec.
	 */
	function cular_intake_render_field( array $f ) {
		$name     = $f['name'];
		$type     = $f['type'] ?? 'text';
		$required = ! empty( $f['required'] );
		$width    = (int) ( $f['width'] ?? 12 );
		$id       = 'f_' . preg_replace( '/[^a-z0-9_]/i', '_', $name );
		$req_attr = $required ? ' required' : '';
		$options  = (array) ( $f['options'] ?? array() );
		?>
		<div class="col-<?php echo esc_attr( $width ); ?>">
			<?php // Radio/checkbox groups get a group label, not a <label for>. ?>
			<?php if ( in_array( $type, array( 'radio', 'checkbox' ), true ) ) : ?>
				<span class="label-text"><?php echo esc_html( $f['label'] ); ?><?php echo $required ? ' *' : ''; ?></span>
			<?php else : ?>
				<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $f['label'] ); ?><?php echo $required ? ' *' : ''; ?></label>
			<?php endif; ?>

			<?php
			switch ( $type ) {
				case 'textarea':
					printf(
						'<textarea id="%s" name="%s" rows="%d" placeholder="%s"%s></textarea>',
						esc_attr( $id ),
						esc_attr( $name ),
						(int) ( $f['rows'] ?? 4 ),
						esc_attr( $f['placeholder'] ?? '' ),
						$required ? ' required' : ''
					);
					break;

				case 'select':
					printf( '<select id="%s" name="%s"%s>', esc_attr( $id ), esc_attr( $name ), $required ? ' required' : '' );
					echo '<option value="">Select…</option>';
					foreach ( $options as $opt ) {
						printf( '<option value="%1$s">%1$s</option>', esc_attr( $opt ) );
					}
					echo '</select>';
					break;

				case 'radio':
				case 'checkbox':
					// `name[]` for checkboxes so multiple answers survive the POST.
					$field_name = 'checkbox' === $type ? $name . '[]' : $name;
					echo '<div class="opt-group" role="group">';
					foreach ( $options as $i => $opt ) {
						printf(
							'<label class="opt"><input type="%s" name="%s" value="%s"%s /> <span>%s</span></label>',
							esc_attr( $type ),
							esc_attr( $field_name ),
							esc_attr( $opt ),
							// Only the first input carries `required`; the JS
							// validates the group, and marking every box required
							// would demand all of them be ticked.
							( $required && 0 === $i ) ? ' required' : '',
							esc_html( $opt )
						);
					}
					echo '</div>';
					break;

				case 'tel':
					// Matches the hand-written forms: visible input + hidden E.164
					// value that the JS fills from intl-tel-input.
					printf(
						'<input id="phoneInput" name="%s" placeholder="%s" autocomplete="tel"%s />',
						esc_attr( $name ),
						esc_attr( $f['placeholder'] ?? 'Enter number' ),
						$required ? ' required' : ''
					);
					echo '<input type="hidden" name="contact_phone_e164" id="phoneE164" />';
					break;

				default:
					printf(
						'<input id="%s" type="%s" name="%s" placeholder="%s" autocomplete="%s"%s />',
						esc_attr( $id ),
						esc_attr( $type ),
						esc_attr( $name ),
						esc_attr( $f['placeholder'] ?? '' ),
						esc_attr( $f['autocomplete'] ?? 'off' ),
						$req_attr
					);
			}
			?>

			<?php if ( ! empty( $f['help'] ) ) : ?>
				<div class="help"><?php echo esc_html( $f['help'] ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cular_intake_contact_step' ) ) {
	/**
	 * The contact step every form opens with.
	 *
	 * Not optional and not per-form: `business_name` and `contact_email` are
	 * indexed columns on the submissions table and drive the admin list, so a
	 * form that skipped them would file rows nobody can identify.
	 *
	 * @return array Step spec.
	 */
	function cular_intake_contact_step() {
		return array(
			'name'     => 'Your details',
			'sections' => array(
				array(
					'heading' => 'Business & Contact',
					'fields'  => array(
						array( 'name' => 'business_name', 'label' => 'Business name', 'type' => 'text', 'required' => true, 'width' => 6, 'autocomplete' => 'organization', 'placeholder' => 'e.g. Cular Creative' ),
						array( 'name' => 'contact_name', 'label' => 'Contact person', 'type' => 'text', 'required' => true, 'width' => 6, 'autocomplete' => 'name', 'placeholder' => 'Full name' ),
						array( 'name' => 'contact_email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'width' => 6, 'autocomplete' => 'email', 'placeholder' => 'name@company.com', 'help' => "We'll use this to send next steps." ),
						array( 'name' => 'contact_phone_raw', 'label' => 'WhatsApp / Phone', 'type' => 'tel', 'width' => 6 ),
						array( 'name' => 'website_url', 'label' => 'Website or main social profile', 'type' => 'text', 'width' => 12, 'placeholder' => 'https://…' ),
					),
				),
			),
		);
	}
}

if ( ! function_exists( 'cular_intake_render_form' ) ) {
	/**
	 * Render a whole form from its spec.
	 *
	 * @param array $spec See the file docblock.
	 */
	function cular_intake_render_form( array $spec ) {
		$steps = $spec['steps'];
		$total = count( $steps );
		?>
		<div class="cular-intake-wrap">
			<div class="wrap">
				<header>
					<div class="brand">
						<h1><?php echo esc_html( $spec['title'] ); ?></h1>
						<?php if ( ! empty( $spec['intro'] ) ) : ?>
							<p><?php echo esc_html( $spec['intro'] ); ?></p>
						<?php endif; ?>
					</div>
				</header>

				<div class="card" id="app">
					<div class="topbar">
						<div class="stepper">
							<?php // One pill, not three: "Step 1 of 4 · Your details" reads as a single fact. ?>
							<div class="pill">
								<b id="stepLabel">Step 1</b>
								<span class="pill-of">of <?php echo (int) $total; ?></span>
								<span class="pill-sep" aria-hidden="true">·</span>
								<span id="stepName"><?php echo esc_html( $steps[0]['name'] ?? '' ); ?></span>
							</div>
						</div>
						<div class="progress" aria-label="progress">
							<div id="bar"></div>
						</div>
					</div>

					<form id="form" novalidate data-generic-driver data-total-steps="<?php echo (int) $total; ?>">
						<input type="hidden" name="form_type" value="<?php echo esc_attr( $spec['type'] ); ?>" />
						<div class="content">
							<?php foreach ( $steps as $i => $step ) : ?>
								<div class="step<?php echo $i ? ' hidden' : ''; ?>" data-step="<?php echo (int) ( $i + 1 ); ?>" data-step-name="<?php echo esc_attr( $step['name'] ); ?>">
									<?php foreach ( (array) $step['sections'] as $section ) : ?>
										<div class="section">
											<?php if ( ! empty( $section['heading'] ) ) : ?>
												<h2><?php echo esc_html( $section['heading'] ); ?></h2>
											<?php endif; ?>
											<?php if ( ! empty( $section['intro'] ) ) : ?>
												<p class="help"><?php echo esc_html( $section['intro'] ); ?></p>
											<?php endif; ?>
											<div class="grid">
												<?php foreach ( (array) $section['fields'] as $field ) : ?>
													<?php cular_intake_render_field( $field ); ?>
												<?php endforeach; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>

							<div id="reviewBox" class="section"></div>
						</div>

						<div class="actions">
							<button type="button" class="ghost" id="prevBtn">← Back</button>
							<div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
								<span class="badge warn" id="validationBadge" style="display:none;"></span>
								<button type="button" class="primary" id="nextBtn">Next →</button>
							</div>
						</div>

						<div id="successMessage" class="section" style="display:none;">
							<h2>Thank you — we've got it.</h2>
							<p>Your answers are with the Cular team. We'll be in touch within two working days.</p>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}
