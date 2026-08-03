<?php
/**
 * Render: cular/service-detail
 *
 * Body of an individual service page:
 *   - "Approach and work specifics" copy beside an expandable list of what the
 *     service covers,
 *   - a Get in Touch button,
 *   - related service cards (colour-coded, each listing its own sub-topics),
 *   - the intake form.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading  = get_field( 'heading' ) ?: 'Approach and work specifics';
$body     = get_field( 'body' );
$details  = get_field( 'details' );
$btn      = get_field( 'button_label' ) ?: 'Get in Touch';
$btn_url  = get_field( 'button_url' );
$rel_head = get_field( 'related_heading' ) ?: 'You might also like these Marketing Services';
$related  = get_field( 'related' );
$form     = get_field( 'form_type' );
$form_head = get_field( 'form_heading' ) ?: 'Book a Call with Us';

// Without an explicit link the button jumps to the form further down the page.
if ( ! $btn_url ) {
	$btn_url = $form ? '#cular-intake' : home_url( '/contact/' );
}

$themes = array( 'gold', 'green', 'coral' );
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-sdet">
	<?php if ( $body || $details ) : ?>
		<div class="cular-sdet__approach" data-cular-reveal>
			<div class="cular-sdet__copy">
				<h2 class="cular-sdet__heading"><?php echo esc_html( $heading ); ?></h2>

				<?php if ( $body ) : ?>
					<div class="cular-sdet__body">
						<?php foreach ( preg_split( '/\n\s*\n/', trim( $body ) ) as $para ) : ?>
							<p><?php echo esc_html( trim( $para ) ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<a class="cular-sdet__btn" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn ); ?></a>
			</div>

			<?php if ( ! empty( $details ) && is_array( $details ) ) : ?>
				<div class="cular-sdet__list">
					<?php foreach ( $details as $d ) : ?>
						<details class="cular-sdet__item">
							<summary class="cular-sdet__q">
								<span><?php echo esc_html( $d['q'] ?? '' ); ?></span>
								<span class="cular-sdet__chev" aria-hidden="true"></span>
							</summary>
							<?php if ( ! empty( $d['a'] ) ) : ?>
								<div class="cular-sdet__a"><?php echo wp_kses_post( wpautop( $d['a'] ) ); ?></div>
							<?php endif; ?>
						</details>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $related ) && is_array( $related ) ) : ?>
		<div class="cular-sdet__related" data-cular-reveal>
			<h2 class="cular-sdet__related-heading"><?php echo esc_html( $rel_head ); ?></h2>

			<div class="cular-sdet__cards">
				<?php
				$i = 0;
				foreach ( $related as $r ) :
					$url = $r['url'] ?? '';
					if ( ! $url ) {
						continue;
					}
					$theme = $themes[ $i % count( $themes ) ];
					++$i;

					$img = $r['image'] ?? '';
					if ( is_array( $img ) ) {
						$img = $img['url'] ?? '';
					} elseif ( is_numeric( $img ) ) {
						$img = wp_get_attachment_image_url( $img, 'medium_large' );
					}

					$sub = array_filter( array_map( 'trim', preg_split( '/\r?\n/', (string) ( $r['items'] ?? '' ) ) ) );
					?>
					<article class="cular-sdet__card cular-sdet__card--<?php echo esc_attr( $theme ); ?>">
						<span class="cular-sdet__card-media"<?php echo $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?>></span>
						<h3 class="cular-sdet__card-title"><?php echo esc_html( $r['title'] ?? '' ); ?></h3>

						<?php if ( $sub ) : ?>
							<ul class="cular-sdet__card-list">
								<?php foreach ( $sub as $s ) : ?>
									<li><span><?php echo esc_html( $s ); ?></span><span class="cular-sdet__dot" aria-hidden="true"></span></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<a class="cular-sdet__card-cta" href="<?php echo esc_url( $url ); ?>">
							Read More<span class="screen-reader-text"> about <?php echo esc_html( $r['title'] ?? '' ); ?></span>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $form && shortcode_exists( 'cular_intake_form' ) ) : ?>
		<div class="cular-sdet__form" id="cular-intake" data-cular-reveal>
			<h2 class="cular-sdet__form-heading"><?php echo esc_html( $form_head ); ?></h2>
			<?php echo do_shortcode( '[cular_intake_form type="' . esc_attr( $form ) . '"]' ); ?>
		</div>
	<?php endif; ?>
</section>
