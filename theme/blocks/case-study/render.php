<?php
/**
 * Render: cular/case-study
 *
 * Body of a client case study:
 *   - client logo + intro + service chips,
 *   - heading-led sections, each with copy and any number of images/videos,
 *   - a dynamically generated "other work" strip.
 *
 * The related-work strip is generated from the `portfolio_item` CPT rather than
 * stored per page. The old Elementor pages hand-copied four client cards onto
 * every case study, which went stale the moment a client was added and
 * duplicated content sitewide 47 times over. Here it is always current, shares
 * the tags of the current project so the links are relevant, and costs nothing
 * to maintain.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$logo      = get_field( 'client_logo' );
$intro     = get_field( 'intro' );
$services  = get_field( 'services' );
$sections  = get_field( 'sections' );
$rel_head  = get_field( 'related_heading' ) ?: 'Check some of our other work';
$rel_count = null === get_field( 'related_count' ) ? 4 : (int) get_field( 'related_count' );

// The portfolio_item this page documents, matched by the CPT's external_link.
// Gives us the card art and the service tags as fallbacks.
$item = cular_case_study_item( get_the_ID() );

if ( ! $logo && $item ) {
	$card = (int) get_post_meta( $item->ID, 'overlay_logo_id', true );
	if ( ! $card ) {
		$card = (int) get_post_meta( $item->ID, 'portfolio_image_id', true );
	}
	if ( $card ) {
		$logo = acf_get_attachment( $card );
	}
}

// Services: explicit lines, else the item's portfolio_tag terms.
$service_list = array();
if ( $services ) {
	foreach ( preg_split( '/\r?\n/', $services ) as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$service_list[] = $line;
		}
	}
} elseif ( $item ) {
	$service_list = wp_list_pluck( wp_get_post_terms( $item->ID, 'portfolio_tag' ), 'name' );
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="cular-cs">

	<?php if ( $logo || $intro || $service_list ) : ?>
		<div class="cular-cs__intro" data-cular-reveal>
			<?php if ( ! empty( $logo['url'] ) ) : ?>
				<div class="cular-cs__logo">
					<?php
					// The logo is the first thing in the viewport on this page, so
					// it is the likely LCP element: eager, high priority, no lazy.
					echo cular_img(
						$logo,
						array(
							'class'         => 'cular-cs__logo-img',
							'sizes'         => '(max-width: 780px) 60vw, 320px',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<div class="cular-cs__intro-copy">
				<?php if ( $intro ) : ?>
					<div class="cular-cs__lead"><?php echo wp_kses_post( $intro ); ?></div>
				<?php endif; ?>

				<?php if ( $service_list ) : ?>
					<h2 class="cular-cs__services-title">Services</h2>
					<ul class="cular-cs__services">
						<?php foreach ( $service_list as $service ) : ?>
							<li><?php echo esc_html( $service ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $sections ) : ?>
		<?php foreach ( $sections as $i => $section ) : ?>
			<?php
			$title = trim( (string) ( $section['title'] ?? '' ) );
			$body  = $section['body'] ?? '';
			$media = $section['media'] ?? array();
			if ( ! $title && ! $body && ! $media ) {
				continue;
			}
			?>
			<div class="cular-cs__section" data-cular-reveal>
				<?php if ( $title ) : ?>
					<h2 class="cular-cs__section-title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $body ) : ?>
					<div class="cular-cs__body"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>

				<?php if ( $media ) : ?>
					<div class="cular-cs__media cular-cs__media--<?php echo esc_attr( min( 3, count( $media ) ) ); ?>" data-cular-reveal-items>
						<?php foreach ( $media as $m ) : ?>
							<?php
							$video = trim( (string) ( $m['video'] ?? '' ) );
							$image = $m['image'] ?? null;
							?>
							<?php if ( $video ) : ?>
								<?php
								// With a poster we can afford preload="none" — the
								// still carries the visual until the user asks to
								// play, and a case study can carry a dozen of these.
								// Without one, preload="none" paints a dead black
								// box, so fall back to "metadata": enough for the
								// browser to show the first frame, still far short
								// of pulling the whole file.
								$has_poster = ! empty( $image['url'] );
								?>
								<video
									class="cular-cs__video"
									src="<?php echo esc_url( $video ); ?>"
									<?php if ( $has_poster ) : ?>poster="<?php echo esc_url( $image['url'] ); ?>"<?php endif; ?>
									preload="<?php echo $has_poster ? 'none' : 'metadata'; ?>"
									controls
									playsinline
									muted
									loop
								></video>
							<?php elseif ( ! empty( $image['url'] ) ) : ?>
								<?php
								echo cular_img(
									$image,
									array(
										'class' => 'cular-cs__image',
										'sizes' => '(max-width: 780px) 92vw, min(45vw, 620px)',
									)
								);
								?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php
	if ( $rel_count > 0 ) {
		cular_related_portfolio( $item, $rel_count, $rel_head );
	}
	?>
</section>
