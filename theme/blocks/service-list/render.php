<?php
/**
 * Render: cular/service-list
 *
 * A gradient card listing services. Optional card header, then one row per
 * service: title, blurb and a "Discover More" button. Used by the Marketing
 * Services and Consultancy hubs.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$card_title = get_field( 'card_title' );
$variant    = get_field( 'variant' ) ?: 'green';
$btn_label  = get_field( 'button_label' ) ?: 'Discover More';
$items      = get_field( 'items' );

if ( empty( $items ) || ! is_array( $items ) ) {
	return;
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-svc cular-svc--<?php echo esc_attr( $variant ); ?>" data-cular-reveal>
	<div class="cular-svc__card">
		<?php if ( $card_title ) : ?>
			<h2 class="cular-svc__card-title"><?php echo esc_html( $card_title ); ?></h2>
		<?php endif; ?>

		<ul class="cular-svc__rows">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$url   = $item['url'] ?? '';
				$label = $item['button_label'] ?? '';
				if ( ! $label ) {
					$label = $btn_label;
				}
				?>
				<li class="cular-svc__row">
					<div class="cular-svc__copy">
						<h3 class="cular-svc__title">
							<?php if ( $url ) : ?>
								<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $item['title'] ?? '' ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item['title'] ?? '' ); ?>
							<?php endif; ?>
						</h3>
						<?php if ( ! empty( $item['text'] ) ) : ?>
							<p class="cular-svc__text"><?php echo esc_html( $item['text'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $url ) : ?>
						<a class="cular-svc__btn" href="<?php echo esc_url( $url ); ?>">
							<?php echo esc_html( $label ); ?>
							<span class="screen-reader-text">: <?php echo esc_html( $item['title'] ?? '' ); ?></span>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
