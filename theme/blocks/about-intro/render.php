<?php
/**
 * Render: cular/about-intro
 *
 * "About Us" heading + intro copy. Sits inside the shared
 * .cular-gradient-mesh wrapper.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading  = get_field( 'heading' ) ?: 'About Us';
$body     = get_field( 'body' );
$show_scr = null === get_field( 'show_scroll' ) ? true : (bool) get_field( 'show_scroll' );

if ( ! $body ) {
	$body = 'Founded in Bali in 2017 by Raluca Vicovan, Cular Creative is a digital marketing agency that has empowered over 300 local and international brands to achieve accelerated business growth online. We partner with purpose-driven organizations committed to making a positive impact, leveraging digital marketing as a catalyst for meaningful change and delivering measurable results.'
		. "\n\n"
		. "We're more than an agency, we're a cohesive team that balances hard work with fun, fostering a collaborative and supportive environment. We believe in ethical marketing practices centered on a human approach, building genuine connections, and fostering a community of aligned clients who are passionate about making a positive impact.";
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-about" data-cular-reveal>
	<h1 class="cular-about__heading"><?php echo esc_html( $heading ); ?></h1>

	<div class="cular-about__body">
		<?php foreach ( preg_split( '/\n\s*\n/', trim( $body ) ) as $para ) : ?>
			<p><?php echo esc_html( trim( $para ) ); ?></p>
		<?php endforeach; ?>
	</div>

</section>

<?php if ( $show_scr ) : ?>
	<?php
	// Sits outside the section on purpose: the reveal animation leaves a
	// transform on .cular-about, which would become the containing block for
	// this position:fixed indicator and pull it in off the viewport edge.
	?>
	<div class="cular-scroll" data-cular-scroll aria-hidden="true">
		<div class="cular-scroll__bar">
			<div class="cular-scroll__bar-inner"></div>
		</div>
		<span class="cular-scroll__text">Scroll Down</span>
	</div>
<?php endif; ?>
