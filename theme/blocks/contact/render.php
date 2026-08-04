<?php
/**
 * Render: cular/contact
 *
 * Two columns: contact details (intro, email, phone, studio address, socials)
 * beside the "Book a Call with Us" intake form.
 *
 * Socials come from the Social Links menu via inc/nav.php, so they stay in
 * step with the header and footer.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$intro   = get_field( 'intro' );
$email   = get_field( 'email' ) ?: 'hello@cularcreative.com';
$phone   = get_field( 'phone' ) ?: '+6281338571823';
$company = get_field( 'company' ) ?: 'PT Cular Creative Studio';
$address = get_field( 'address' ) ?: 'Jalan Nakula Komplex Nakula Plaza B1, Kel. Legian, Kec. Kuta, Badung 80361, Bali';
$map_url = get_field( 'map_url' ) ?: 'https://maps.app.goo.gl/';
$form    = get_field( 'form_type' ) ?: 'contact';
$form_h  = get_field( 'form_heading' ) ?: 'Book a Call with Us';

if ( ! $intro ) {
	$intro = "Ready to start a project or just have a question? We're passionate about creating ethical and impactful digital experiences. Let's partner to elevate your brand and connect with your audience.";
}

$socials = function_exists( 'cular_menu_items' ) ? cular_menu_items( 'social' ) : array();
if ( empty( $socials ) ) {
	$socials = array(
		array( 'label' => 'Facebook', 'url' => 'https://www.facebook.com/cularcreative/' ),
		array( 'label' => 'Instagram', 'url' => 'https://www.instagram.com/cularcreative/' ),
		array( 'label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/cular-creative/' ),
		array( 'label' => 'YouTube', 'url' => 'https://www.youtube.com/channel/UCLL8OTY2tGxaeoX1YNUN2vA' ),
	);
}
$tel     = preg_replace( '/[^0-9+]/', '', $phone );
$anchor  = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-contact">
	<div class="cular-contact__details" data-cular-reveal>
		<p class="cular-contact__intro"><?php echo esc_html( $intro ); ?></p>

		<h2 class="cular-contact__heading">Contact Details</h2>

		<ul class="cular-contact__list">
			<li>
				<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4.24-8 4.76-8-4.76V6l8 4.76L20 6z"/></svg>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</li>
			<li>
				<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1z"/></svg>
				<a href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a>
			</li>
		</ul>

		<div class="cular-contact__studio">
			<h3 class="cular-contact__company"><?php echo esc_html( $company ); ?></h3>
			<p class="cular-contact__address"><?php echo esc_html( $address ); ?></p>

			<?php if ( $map_url ) : ?>
				<a class="cular-contact__directions" href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer">
					<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2 2 12l10 10 10-10zm-1 6h2v3h3v2h-3v3h-2v-3H8v-2h3z"/></svg>
					Get Directions
				</a>
			<?php endif; ?>
		</div>

		<?php if ( $socials ) : ?>
			<div class="cular-contact__social">
				<span>Social Media :</span>
				<ul>
					<?php foreach ( $socials as $s ) : ?>
						<li>
							<a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $s['label'] ); ?>">
								<?php cular_social_icon( $s['label'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>

	<div class="cular-contact__form" id="cular-intake" data-cular-reveal>
		<h2 class="cular-contact__form-heading"><?php echo esc_html( $form_h ); ?></h2>

		<?php
		if ( shortcode_exists( 'cular_intake_form' ) ) {
			echo do_shortcode( '[cular_intake_form type="' . esc_attr( $form ) . '"]' );
		} else {
			echo '<p>The booking form is unavailable. Please email <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>.</p>';
		}
		?>
	</div>
</section>
