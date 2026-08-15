<?php
/**
 * Render: cular/page-hero
 *
 * Green-to-sage band with a display title, intro copy and an optional cut-out
 * image sitting behind the title. Used by the Marketing Services / Consultancy
 * hubs and the individual service pages.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$title = get_field( 'title' ) ?: get_the_title();
$lead  = get_field( 'lead' );
$body  = get_field( 'body' );
$image = get_field( 'image' );
$size  = get_field( 'size' ) ?: 'large';
$bare  = (bool) get_field( 'bare' ); // let a wrapper gradient show through
$wide  = (bool) get_field( 'wide' ); // match the wider service-detail container

// Prefer a sized variant — the source cut-outs are multi-megabyte PNGs and
// ACF's array form hands back the original.
$img_url = '';
if ( is_array( $image ) ) {
	$img_url = $image['sizes']['1536x1536'] ?? $image['sizes']['large'] ?? $image['url'] ?? '';
} elseif ( is_numeric( $image ) ) {
	$img_url = wp_get_attachment_image_url( $image, '1536x1536' );
} elseif ( $image ) {
	$img_url = $image;
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-phero cular-phero--<?php echo esc_attr( $size ); ?><?php echo $bare ? ' cular-phero--bare' : ''; ?><?php echo $wide ? ' cular-phero--wide' : ''; ?><?php echo $img_url ? ' cular-phero--has-media' : ''; ?>">
	<?php if ( $img_url ) : ?>
		<div class="cular-phero__media" aria-hidden="true">
			<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="eager" fetchpriority="high" />
		</div>
	<?php endif; ?>

	<div class="cular-phero__inner<?php echo get_field( 'flush' ) ? ' cular-phero__inner--flush' : ''; ?>">
		<h1 class="cular-phero__title" data-cular-split><?php echo esc_html( $title ); ?></h1>

		<?php if ( $lead ) : ?>
			<p class="cular-phero__lead"><?php echo esc_html( $lead ); ?></p>
		<?php endif; ?>

		<?php if ( $body ) : ?>
			<div class="cular-phero__body">
				<?php foreach ( preg_split( '/\n\s*\n/', trim( $body ) ) as $para ) : ?>
					<p><?php echo esc_html( trim( $para ) ); ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( get_field( 'show_scroll' ) ) : ?>
		<?php // Same shared chrome the homepage and About heroes render (main.scss). ?>
		<div class="cular-scroll" data-cular-scroll aria-hidden="true">
			<div class="cular-scroll__bar">
				<div class="cular-scroll__bar-inner"></div>
			</div>
			<span class="cular-scroll__text">Scroll Down</span>
		</div>
	<?php endif; ?>
</section>
