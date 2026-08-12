<?php
/**
 * Render: cular/hero
 *
 * Matches the live site: full-bleed background video (showreel), optional
 * text overlay, and a "Scroll Down" indicator on the right edge.
 *
 * @package Cular
 *
 * @var array $block ACF block settings.
 */

defined( 'ABSPATH' ) || exit;

$video_land = get_field( 'video_url' );
$video_port = get_field( 'video_url_portrait' );
$bg         = get_field( 'background' );
$eyebrow    = get_field( 'eyebrow' );
$heading    = get_field( 'heading' );
$subtext    = get_field( 'subtext' );
$cta_label  = get_field( 'cta_label' );
$cta_url    = get_field( 'cta_url' );
$show_scr   = get_field( 'show_scroll' );

// Default to the site showreel used on the live homepage.
if ( ! $video_land ) {
	$video_land = content_url( '/uploads/2026/02/Cular-Website-Showreel-Land_1-1-1.webm' );
}
if ( ! $video_port ) {
	$video_port = content_url( '/uploads/2026/02/Cular-Website-Showreel-vertical-1-1.webm' );
}
if ( null === $show_scr ) {
	$show_scr = true;
}

$poster   = ! empty( $bg['url'] ) ? $bg['url'] : '';
$has_text = $eyebrow || $heading || $subtext || ( $cta_label && $cta_url );

$anchor  = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
$classes = 'cular-hero cular-hero--video';
if ( $has_text ) {
	$classes .= ' cular-hero--has-text';
}
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="<?php echo esc_attr( $classes ); ?>">
	<video
		class="cular-hero__video"
		autoplay muted loop playsinline
		<?php echo $poster ? 'poster="' . esc_url( $poster ) . '"' : ''; ?>
		data-src-landscape="<?php echo esc_url( $video_land ); ?>"
		data-src-portrait="<?php echo esc_url( $video_port ); ?>"
	>
		<source src="<?php echo esc_url( $video_land ); ?>" type="video/webm" />
	</video>

	<?php if ( ! $heading ) : ?>
		<?php
		// The homepage hero is the showreel and nothing else — by design, there is
		// no visible headline. That left the most important page on the site with
		// no <h1> at all, which is both an accessibility gap (screen-reader users
		// get no page title in the heading outline) and the one on-page SEO signal
		// you never want missing. A visually-hidden h1 supplies it without
		// touching the design.
		?>
		<h1 class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' ) ); ?></h1>
	<?php endif; ?>

	<?php if ( $has_text ) : ?>
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
	<?php endif; ?>

	<?php if ( $show_scr ) : ?>
		<div class="cular-scroll" data-cular-scroll aria-hidden="true">
			<div class="cular-scroll__bar">
				<div class="cular-scroll__bar-inner"></div>
			</div>
			<span class="cular-scroll__text">Scroll Down</span>
		</div>
	<?php endif; ?>
</section>
