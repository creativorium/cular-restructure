<?php
/**
 * Global site chrome: floating WhatsApp button.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_footer',
	function () {
		$number  = apply_filters( 'cular_whatsapp_number', '6281338571823' );
		$message = apply_filters(
			'cular_whatsapp_message',
			"Hi Cular! I'm interested in your digital marketing services. Could you tell me more about what you offer and your pricing?"
		);
		if ( ! $number ) {
			return;
		}
		$href = 'https://wa.me/' . rawurlencode( $number ) . '?text=' . rawurlencode( $message );
		?>
		<a class="cular-wa" href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
			<svg viewBox="0 0 32 32" width="30" height="30" aria-hidden="true">
				<path fill="currentColor" d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.2 1.6 6L4 29l8.2-1.6c1.7.9 3.6 1.4 5.6 1.4h.2c6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-4.9 1 1-4.8-.3-.4C5.5 18.9 5 17 5 15 5 9.9 9.9 5 16 5s11 4.9 11 10-4.9 9.8-11 9.8zm5.6-7.4c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2-.8 1-.9 1.2-.3.2-.6.1c-1.8-.9-3-1.6-4.2-3.6-.3-.5.3-.5.8-1.5.1-.2 0-.4 0-.5s-.7-1.7-1-2.3c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.2.2 2.1 3.3 5.2 4.6 2 .8 2.7.9 3.7.8.6-.1 1.8-.7 2.1-1.5.3-.7.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/>
			</svg>
		</a>
		<?php
	}
);
