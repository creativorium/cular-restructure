<?php
/**
 * Render: cular/cta-panel
 *
 * Centred heading, supporting line and one button — the "Ready to execute?"
 * hand-off at the foot of the Consultancy page.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$text    = get_field( 'text' );
$label   = get_field( 'button_label' );
$url     = get_field( 'button_url' );

if ( ! $heading && ! $text && ! $label ) {
	return;
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-ctap" data-cular-reveal>
	<?php if ( $heading ) : ?>
		<h2 class="cular-ctap__heading"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( $text ) : ?>
		<p class="cular-ctap__text"><?php echo esc_html( $text ); ?></p>
	<?php endif; ?>

	<?php if ( $label && $url ) : ?>
		<a class="cular-ctap__btn" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
	<?php endif; ?>
</section>
