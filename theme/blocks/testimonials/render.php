<?php
/**
 * Render: cular/testimonials
 *
 * Two sliders side by side: video testimonials (left) and written quotes
 * (right). Both get arrows + dots, which the original layout lacked.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Testimonials';
$intro   = get_field( 'intro' );
$videos  = get_field( 'videos' );
$items   = get_field( 'items' );

if ( ! $intro ) {
	$intro = "We've been lucky enough to work with some amazing people, and this is what they say about us…";
}

if ( empty( $videos ) ) {
	$videos = array(
		array( 'video_url' => content_url( '/uploads/2024/12/Testimoni-Vifa_2_2.webm' ), 'name' => 'Bobby', 'company' => 'Vifa Holiday Indonesia', 'logo' => '' ),
		array( 'video_url' => content_url( '/uploads/2024/12/Testimoni-Inspiral_2.webm' ), 'name' => 'Bryan', 'company' => 'Inspiral Studios', 'logo' => '2024/06/Inspiral-Logo.png' ),
		array( 'video_url' => content_url( '/uploads/2025/11/Testimoni-kayu-2-1-1.mp4' ), 'name' => 'Ira', 'company' => 'Kayu &amp; Co.', 'logo' => '2025/07/Logo-Kayu-Co.png' ),
		array( 'video_url' => content_url( '/uploads/2026/02/Bali-Buda-testi-2-1.webm' ), 'name' => 'Ananda', 'company' => 'Bali Buda', 'logo' => '2025/12/Logo_BaliBuda.png' ),
		array( 'video_url' => content_url( '/uploads/2026/02/Testi-Luna-1-1.webm' ), 'name' => 'Yovi', 'company' => 'Luna &amp; Sol', 'logo' => '' ),
		array( 'video_url' => content_url( '/uploads/2026/01/Shenoa-Supatra-1-1-1.webm' ), 'name' => 'Shenoa', 'company' => 'SPB &amp; Kaiana Spa', 'logo' => '' ),
	);
}

if ( empty( $items ) ) {
	$items = array(
		array(
			'quote'  => "Working with Cular Creative has been so easy! They took the time to understand me, and my business which meant that they could create marketing material that felt genuinely aligned. It's been a huge relief being able to work on the things which I am good at and leave the marketing to a group of experts who I trust. Thank you, guys!",
			'author' => 'Nikki',
			'role'   => 'State of Soul',
		),
		array(
			'quote'  => "We have been working with Cular Creative for 3 years, and we can't thank them enough for all their hard work. They work more like an expanded part of our team then an outsourced company, and they always have solutions for our online issues.",
			'author' => 'Total Bali',
			'role'   => '',
		),
		array(
			'quote'  => "So I'm really happy working with Cular so far. I think it really helps me specifically to create content, because I feel like in this digital world, content is a king. So that's Cular help me with creating a content and also sharing the story of artisans to the world.",
			'author' => 'Shenoa',
			'role'   => 'SPB & Kaiana Spa',
		),
	);
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-tst" data-cular-reveal>
	<header class="cular-tst__head">
		<h2 class="cular-tst__heading"><?php echo esc_html( $heading ); ?></h2>
		<p class="cular-tst__intro"><?php echo esc_html( $intro ); ?></p>
	</header>

	<div class="cular-tst__cols">
		<?php if ( $videos ) : ?>
			<div class="cular-tst__videos" data-cular-slider="videos">
				<div class="cular-tst__viewport" data-slider-track>
					<?php foreach ( $videos as $v ) : ?>
						<?php
						$logo_url = '';
						if ( ! empty( $v['logo'] ) ) {
							$logo_url = is_array( $v['logo'] )
								? ( $v['logo']['url'] ?? '' )
								: content_url( '/uploads/' . $v['logo'] );
						}
						?>
						<figure class="cular-tst__vcard">
							<?php if ( $logo_url ) : ?>
								<img class="cular-tst__vlogo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $v['company'] ) ); ?>" loading="lazy" />
							<?php endif; ?>

							<video class="cular-tst__video" src="<?php echo esc_url( $v['video_url'] ); ?>" controls preload="metadata" playsinline></video>

							<figcaption class="cular-tst__vmeta">
								<span class="cular-tst__vname"><?php echo esc_html( $v['name'] ); ?></span>
								<span class="cular-tst__vcompany"><?php echo wp_kses_post( $v['company'] ); ?></span>
							</figcaption>
						</figure>
					<?php endforeach; ?>
				</div>

				<div class="cular-tst__nav">
					<button type="button" class="cular-tst__arrow" data-slider-prev aria-label="Previous video testimonial">&#8592;</button>
					<div class="cular-tst__dots" data-slider-dots></div>
					<button type="button" class="cular-tst__arrow" data-slider-next aria-label="Next video testimonial">&#8594;</button>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<div class="cular-tst__quotes" data-cular-slider="quotes">
				<div class="cular-tst__viewport cular-tst__viewport--single" data-slider-track>
					<?php foreach ( $items as $t ) : ?>
						<blockquote class="cular-tst__quote">
							<p class="cular-tst__quote-text">&ldquo;<?php echo esc_html( $t['quote'] ); ?>&rdquo;</p>
							<footer class="cular-tst__quote-meta">
								<span class="cular-tst__author"><?php echo esc_html( $t['author'] ); ?></span>
								<?php if ( ! empty( $t['role'] ) ) : ?>
									<span class="cular-tst__role"><?php echo esc_html( $t['role'] ); ?></span>
								<?php endif; ?>
							</footer>
						</blockquote>
					<?php endforeach; ?>
				</div>

				<div class="cular-tst__nav">
					<button type="button" class="cular-tst__arrow" data-slider-prev aria-label="Previous testimonial">&#8592;</button>
					<div class="cular-tst__dots" data-slider-dots></div>
					<button type="button" class="cular-tst__arrow" data-slider-next aria-label="Next testimonial">&#8594;</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
