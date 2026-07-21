<?php
/**
 * Render: cular/why-us
 *
 * Client logo marquee + "Why work with us?" copy + certification badges.
 * Sits inside the shared .cular-gradient-mesh wrapper.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$logos   = get_field( 'logos' );
$heading = get_field( 'heading' ) ?: 'Why work with us?';
$body    = get_field( 'body' );
$badges  = get_field( 'badges' );

if ( ! $body ) {
	$body = "Your success is our success, which is why we are committed to building long-term partnerships fused with integrity.\n\n"
		. "Our ethical approach to business empowers you to trust that our solutions-oriented strategies prioritize your goals and values.\n\n"
		. "With a growth mindset and a relentless drive to deliver results, Cular is your reliable guide in today's constantly evolving digital landscape.";
}

// cular_gallery_urls() lives in inc/media.php

$logo_list = cular_gallery_urls(
	$logos,
	array(
		'2024/09/BLACasaWHITE2opy-copy.png',
		'2024/09/BLACK-STONE-WHITE2opy-copy.png',
		'2024/09/morabito-WHITE2opy-copy.png',
		'2024/09/madeWHITE2opy-copy.png',
		'2024/09/BLACK-sWHITE2opy.png',
		'2024/09/BLACK-STOa2opy-copy.png',
		'2024/09/BLACKdsa-WHITE2opy-copy.png',
		'2024/09/lombok-whote.png',
		'2024/09/BLACK-Sa-WHITE2opy-copy.png',
		'2024/09/BLACK-aE-WHITE2opy-copy.png',
		'2024/06/logo-kilo-19.png',
	)
);

$badge_list = cular_gallery_urls(
	$badges,
	array(
		'2024/12/2134213.png',
		'2024/12/54353-1.png',
	)
);

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-why">
	<?php if ( $logo_list ) : ?>
		<div class="cular-why__marquee" aria-label="Brands we have worked with">
			<?php // The track is duplicated so the loop is seamless. ?>
			<div class="cular-why__track">
				<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
					<?php foreach ( $logo_list as $logo ) : ?>
						<img class="cular-why__logo" src="<?php echo esc_url( $logo['url'] ); ?>"
							alt="<?php echo esc_attr( $logo['alt'] ); ?>"
							<?php echo $pass ? 'aria-hidden="true"' : ''; ?> loading="lazy" />
					<?php endforeach; ?>
				<?php endfor; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="cular-why__body" data-cular-reveal>
		<h2 class="cular-why__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php foreach ( preg_split( '/\n\s*\n/', trim( $body ) ) as $para ) : ?>
			<p><?php echo esc_html( trim( $para ) ); ?></p>
		<?php endforeach; ?>
	</div>

	<?php if ( $badge_list ) : ?>
		<div class="cular-why__badges" data-cular-reveal-items>
			<?php foreach ( $badge_list as $badge ) : ?>
				<img src="<?php echo esc_url( $badge['url'] ); ?>" alt="<?php echo esc_attr( $badge['alt'] ); ?>" loading="lazy" />
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
