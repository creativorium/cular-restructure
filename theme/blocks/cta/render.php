<?php
/**
 * Render: cular/cta
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading   = get_field( 'heading' ) ?: 'Want to work with us?';
$highlight = trim( (string) get_field( 'highlight' ) );
$label     = get_field( 'button_label' ) ?: 'Start Our Partnership Here';
$url       = get_field( 'button_url' ) ?: home_url( '/contact/' );
$anchor    = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';

// Default the highlight to the last word of the heading.
if ( '' === $highlight ) {
	$words     = preg_split( '/\s+/', trim( $heading ) );
	$highlight = (string) end( $words );
}

// Wrap the highlighted phrase (if it sits at the end of the heading) in a gold span.
$heading_html = esc_html( $heading );
if ( $highlight && str_ends_with( $heading, $highlight ) ) {
	$base         = rtrim( substr( $heading, 0, -strlen( $highlight ) ) );
	$heading_html = esc_html( $base ) . ' <span class="cular-cta__hl">' . esc_html( $highlight ) . '</span>';
}
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-cta" data-cular-reveal>
	<div class="cular-cta__card">
		<h2 class="cular-cta__heading"><?php echo $heading_html; // phpcs:ignore ?></h2>
		<a class="cular-cta__link" href="<?php echo esc_url( $url ); ?>">
			<?php echo esc_html( $label ); ?><span class="cular-cta__arrow" aria-hidden="true">&rarr;</span>
		</a>
	</div>
</section>
