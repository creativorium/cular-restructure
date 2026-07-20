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
		<div class="cular-hero__scroll" aria-hidden="true">
			<span class="cular-hero__scroll-line"></span>
			<span class="cular-hero__scroll-label">Scroll Down</span>
		</div>
	<?php endif; ?>
</section>
