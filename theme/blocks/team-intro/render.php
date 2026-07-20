<?php
/**
 * Render: cular/team-intro
 *
 * Poster/video card beside intro copy. Sits inside the shared
 * .cular-gradient-mesh wrapper alongside the services section.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$image      = get_field( 'image' );
$video_url  = get_field( 'video_url' );
$play_label = get_field( 'play_label' ) ?: 'Play Me';
$heading    = get_field( 'heading' ) ?: 'We work like your team, not a vendor';
$body       = get_field( 'body' );
$link_label = get_field( 'link_label' ) ?: 'Meet the Team';
$link_url   = get_field( 'link_url' ) ?: home_url( '/about/' );

if ( ! $body ) {
	$body = "Cular has spent 10 years building brands across Bali, Indonesia, and internationally. Our approach is straightforward: we embed into your business, learn what matters, and build marketing that reflects your brand — not just a brief.\n\nEvery project is guided by senior marketing leadership to ensure strategy, quality, and consistency.";
}

// The image field can arrive as an array (normal editing), a bare attachment
// ID (set programmatically), or a URL string — normalise all three.
$poster     = '';
$poster_alt = 'The Cular Creative team';
$image_id   = 0;

if ( is_array( $image ) ) {
	$image_id = (int) ( $image['ID'] ?? $image['id'] ?? 0 );
	$poster   = (string) ( $image['url'] ?? '' );
	if ( ! empty( $image['alt'] ) ) {
		$poster_alt = $image['alt'];
	}
} elseif ( is_numeric( $image ) ) {
	$image_id = (int) $image;
} elseif ( is_string( $image ) && '' !== $image ) {
	$poster = $image;
}

if ( '' === $poster && $image_id ) {
	$poster = (string) wp_get_attachment_image_url( $image_id, 'large' );
	$alt    = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	if ( $alt ) {
		$poster_alt = $alt;
	}
}
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-team" data-cular-reveal>
	<figure class="cular-team__media">
		<?php if ( $video_url ) : ?>
			<button class="cular-team__play" type="button" data-cular-play data-video="<?php echo esc_url( $video_url ); ?>" aria-label="<?php echo esc_attr( $play_label ); ?>">
				<span class="cular-team__play-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" width="34" height="34"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>
				</span>
				<span class="cular-team__play-label"><?php echo esc_html( $play_label ); ?></span>
			</button>
		<?php endif; ?>

		<?php if ( $poster ) : ?>
			<img src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( $poster_alt ); ?>" loading="lazy" />
		<?php endif; ?>
	</figure>

	<div class="cular-team__body">
		<h2 class="cular-team__heading"><?php echo esc_html( $heading ); ?></h2>

		<?php foreach ( preg_split( '/\n\s*\n/', trim( $body ) ) as $para ) : ?>
			<p><?php echo esc_html( trim( $para ) ); ?></p>
		<?php endforeach; ?>

		<a class="cular-team__link" href="<?php echo esc_url( $link_url ); ?>">
			<?php echo esc_html( $link_label ); ?><span aria-hidden="true">&#10230;</span>
		</a>
	</div>
</section>
