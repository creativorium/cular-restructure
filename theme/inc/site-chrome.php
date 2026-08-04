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
		// Elementor-built pages already ship their own floating WhatsApp
		// widget — don't add a second one while both systems coexist.
		if ( function_exists( 'cular_is_elementor_page' ) && cular_is_elementor_page() ) {
			return;
		}

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
			<svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true" focusable="false">
				<path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.4"/>
			</svg>
		</a>
		<?php
	}
);

/**
 * Flag pages whose background is solid brand green, so the header can flip its
 * logo and menu pill to white — a green pill on a green page is invisible.
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( ! is_singular() ) {
			return $classes;
		}
		$content = get_post_field( 'post_content', get_queried_object_id() );
		$green = array( 'cular-faq-page', 'cular-services-page', 'cular-contact-hero' );
		foreach ( $green as $marker ) {
			if ( $content && str_contains( $content, $marker ) ) {
				$classes[] = 'cular-on-green';
				break;
			}
		}
		return $classes;
	}
);

/**
 * Cular Intake Forms only loads its CSS/JS when the literal shortcode is in
 * post_content. Our blocks render it through do_shortcode(), so tell the
 * plugin which form a page uses and let it enqueue accordingly.
 *
 * @return string Form type slug found in this page's blocks, or ''.
 */
function cular_page_intake_form_type( $post = null ) {
	$post = $post ?: get_post();
	if ( ! $post instanceof WP_Post ) {
		return '';
	}
	if ( preg_match( '/"form_type":"([a-z0-9\-]+)"/i', $post->post_content, $m ) ) {
		return $m[1];
	}
	return '';
}

add_filter(
	'cular_intake_should_enqueue',
	function ( $should, $post ) {
		return $should || '' !== cular_page_intake_form_type( $post );
	},
	10,
	2
);

add_filter(
	'cular_intake_form_type',
	function ( $type, $post ) {
		$found = cular_page_intake_form_type( $post );
		return $found ?: $type;
	},
	10,
	2
);

/**
 * Echo a brand SVG for a social network, matched loosely on its menu label.
 * Falls back to a generic link glyph for networks we don't have a mark for.
 *
 * @param string $label Menu label, e.g. "Instagram".
 */
function cular_social_icon( $label ) {
	$key = strtolower( preg_replace( '/[^a-z]/i', '', (string) $label ) );

	$paths = array(
		'facebook'  => 'M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z',
		'instagram' => 'M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63a5.9 5.9 0 0 0-2.13 1.38A5.9 5.9 0 0 0 .63 4.14c-.3.76-.5 1.64-.56 2.91C.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.27.26 2.15.56 2.91a5.9 5.9 0 0 0 1.38 2.13 5.9 5.9 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24s3.67-.01 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.9 5.9 0 0 0 2.13-1.38 5.9 5.9 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.9 5.9 0 0 0-1.38-2.13A5.9 5.9 0 0 0 19.86.63c-.76-.3-1.64-.5-2.91-.56C15.67.01 15.26 0 12 0zm0 5.84a6.16 6.16 0 1 0 0 12.32 6.16 6.16 0 0 0 0-12.32zm0 10.16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm7.85-10.4a1.44 1.44 0 1 1-2.88 0 1.44 1.44 0 0 1 2.88 0z',
		'linkedin'  => 'M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z',
		'youtube'   => 'M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.5A3.02 3.02 0 0 0 .5 6.19C0 8.08 0 12 0 12s0 3.92.5 5.81a3.02 3.02 0 0 0 2.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 0 0 2.12-2.14C24 15.92 24 12 24 12s0-3.92-.5-5.81zM9.6 15.6V8.4l6.24 3.6-6.24 3.6z',
		'tiktok'    => 'M16.6 5.82A4.28 4.28 0 0 1 15.54 3h-3.09v12.4a2.59 2.59 0 0 1-2.59 2.5 2.59 2.59 0 0 1 0-5.18c.27 0 .52.04.76.12v-3.2a5.9 5.9 0 0 0-.76-.05 5.72 5.72 0 1 0 5.72 5.72V9.01a7.35 7.35 0 0 0 4.29 1.38V7.3a4.29 4.29 0 0 1-3.27-1.48z',
		'x'         => 'M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.22-6.82-5.96 6.82H1.68l7.73-8.84L1.25 2.25h6.82l4.71 6.23zm-1.16 17.52h1.83L7.02 4.13H5.06z',
		'twitter'   => 'M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.22-6.82-5.96 6.82H1.68l7.73-8.84L1.25 2.25h6.82l4.71 6.23zm-1.16 17.52h1.83L7.02 4.13H5.06z',
	);

	$path = $paths[ $key ] ?? 'M10.6 13.4a1 1 0 0 0 1.4 0l4-4a3 3 0 0 0-4.2-4.2l-1 1 1.4 1.4 1-1a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 0 0 1.4zm2.8-2.8a1 1 0 0 0-1.4 0l-4 4a3 3 0 0 0 4.2 4.2l1-1-1.4-1.4-1 1a1 1 0 0 1-1.4-1.4l4-4a1 1 0 0 0 0-1.4z';
	?>
	<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="<?php echo esc_attr( $path ); ?>"/></svg>
	<?php
}
