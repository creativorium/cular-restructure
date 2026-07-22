<?php
/**
 * Render: cular/cta
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Want to work with us?';
$label   = get_field( 'button_label' ) ?: 'Start Our Partnership Here';
$url     = get_field( 'button_url' ) ?: home_url( '/contact/' );
$anchor  = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-cta" data-cular-reveal>
	<div class="cular-cta__card">
		<?php // The duplicated text in data-spotlight is revealed in colour by a moving spotlight (::after). ?>
		<h2 class="cular-cta__heading" data-spotlight="<?php echo esc_attr( $heading ); ?>"><?php echo esc_html( $heading ); ?></h2>
		<a class="cular-cta__link" href="<?php echo esc_url( $url ); ?>">
			<?php echo esc_html( $label ); ?><span class="cular-cta__arrow" aria-hidden="true">&rarr;</span>
		</a>
	</div>
</section>
