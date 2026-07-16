<?php
/**
 * Render: cular/hero
 *
 * @package Cular
 *
 * @var array $block ACF block settings.
 */

defined( 'ABSPATH' ) || exit;

$eyebrow   = get_field( 'eyebrow' );
$heading   = get_field( 'heading' );
$subtext   = get_field( 'subtext' );
$cta_label = get_field( 'cta_label' );
$cta_url   = get_field( 'cta_url' );
$bg        = get_field( 'background' );

$anchor  = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
$classes = 'cular-hero';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}

$style = '';
if ( ! empty( $bg['url'] ) ) {
	$style = ' style="background-image:url(' . esc_url( $bg['url'] ) . ')"';
}
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="<?php echo esc_attr( $classes ); ?>"<?php echo $style; // phpcs:ignore ?>>
	<div class="cular-hero__inner">
		<?php if ( $eyebrow ) : ?>
			<p class="cular-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>

		<?php if ( $heading ) : ?>
			<h1 class="cular-hero__heading"><?php echo esc_html( $heading ); ?></h1>
		<?php endif; ?>

		<?php if ( $subtext ) : ?>
			<p class="cular-hero__subtext"><?php echo esc_html( $subtext ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_label && $cta_url ) : ?>
			<a class="cular-hero__cta" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
		<?php endif; ?>
	</div>
</section>
