<?php
/**
 * Render: cular/cta
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Want to work with us?';
$label   = get_field( 'button_label' ) ?: "Let's talk";
$url     = get_field( 'button_url' ) ?: home_url( '/contact/' );
$anchor  = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-cta" data-cular-reveal>
	<h2 class="cular-cta__heading"><?php echo esc_html( $heading ); ?></h2>
	<a class="cular-cta__btn" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
</section>
