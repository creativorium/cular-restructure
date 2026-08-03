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

$img_url = '';
if ( $image ) {
	$img_url = is_array( $image ) ? ( $image['url'] ?? '' ) : ( is_numeric( $image ) ? wp_get_attachment_image_url( $image, 'full' ) : $image );
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-phero cular-phero--<?php echo esc_attr( $size ); ?>">
	<?php if ( $img_url ) : ?>
		<div class="cular-phero__media" aria-hidden="true">
			<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="eager" fetchpriority="high" />
		</div>
	<?php endif; ?>

	<div class="cular-phero__inner">
		<h1 class="cular-phero__title"><?php echo esc_html( $title ); ?></h1>

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
</section>
