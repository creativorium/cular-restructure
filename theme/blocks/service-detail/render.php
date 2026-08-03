<?php
/**
 * Render: cular/service-detail
 *
 * Body of an individual service page: the "Approach and work specifics" copy
 * with a Get in Touch button, followed by a few related services.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading  = get_field( 'heading' ) ?: 'Approach and work specifics';
$body     = get_field( 'body' );
$btn      = get_field( 'button_label' ) ?: 'Get in Touch';
$btn_url  = get_field( 'button_url' ) ?: home_url( '/contact/' );
$rel_head = get_field( 'related_heading' ) ?: 'You might also like these Marketing Services';
$related  = get_field( 'related' );

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-sdet">
	<?php if ( $body ) : ?>
		<div class="cular-sdet__approach" data-cular-reveal>
			<h2 class="cular-sdet__heading"><?php echo esc_html( $heading ); ?></h2>

			<div class="cular-sdet__body">
				<?php foreach ( preg_split( '/\n\s*\n/', trim( $body ) ) as $para ) : ?>
					<p><?php echo esc_html( trim( $para ) ); ?></p>
				<?php endforeach; ?>
			</div>

			<a class="cular-sdet__btn" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn ); ?></a>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $related ) && is_array( $related ) ) : ?>
		<div class="cular-sdet__related" data-cular-reveal>
			<h2 class="cular-sdet__related-heading"><?php echo esc_html( $rel_head ); ?></h2>

			<div class="cular-sdet__cards">
				<?php foreach ( $related as $r ) : ?>
					<?php
					$url = $r['url'] ?? '';
					if ( ! $url ) {
						continue;
					}
					$img = $r['image'] ?? '';
					if ( is_array( $img ) ) {
						$img = $img['url'] ?? '';
					} elseif ( is_numeric( $img ) ) {
						$img = wp_get_attachment_image_url( $img, 'medium_large' );
					}
					?>
					<a class="cular-sdet__card" href="<?php echo esc_url( $url ); ?>">
						<span class="cular-sdet__card-media"<?php echo $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?>></span>
						<span class="cular-sdet__card-title"><?php echo esc_html( $r['title'] ?? '' ); ?></span>
						<span class="cular-sdet__card-cta">Read More</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
