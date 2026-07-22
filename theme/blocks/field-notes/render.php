<?php
/**
 * Render: cular/field-notes
 *
 * Intro column beside a slider of latest posts, each on a rotating brand
 * colour with cover image, date, title and "Read More".
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Our Field Notes';
$intro   = get_field( 'intro' );
$count   = (int) ( get_field( 'count' ) ?: 6 );
$all_url = get_field( 'all_url' ) ?: home_url( '/blog/' );

if ( ! $intro ) {
	$intro = 'Join the conversation! Stay up to date with our latest insights on online marketing trends by reading our Field Notes and sharing your thoughts in the comments.';
}

// Over-fetch so we can skip posts that reuse the same featured image (several
// recent posts are duplicates that share one placeholder image).
$q = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => $count + 10,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$used_thumbs = array();

$themes = array( 'gold', 'coral', 'sage' );
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-notes">
	<div class="cular-notes__intro" data-cular-reveal>
		<h2 class="cular-notes__heading"><?php echo esc_html( $heading ); ?></h2>
		<p class="cular-notes__text"><?php echo esc_html( $intro ); ?></p>
		<a class="cular-notes__all" href="<?php echo esc_url( $all_url ); ?>">
			Explore All Field Notes<span aria-hidden="true">&#10230;</span>
		</a>
	</div>

	<?php if ( $q->have_posts() ) : ?>
		<div class="cular-notes__slider" data-cular-slider="notes">
			<button type="button" class="cular-notes__arrow cular-notes__arrow--prev" data-slider-prev aria-label="Previous field notes">&#8249;</button>

			<div class="cular-notes__viewport" data-slider-track>
				<?php
				$i = 0;
				while ( $q->have_posts() ) :
					$q->the_post();
					if ( $i >= $count ) {
						break;
					}

					// Skip posts that reuse a featured image already shown.
					$thumb_id = (int) get_post_thumbnail_id( get_the_ID() );
					if ( $thumb_id && in_array( $thumb_id, $used_thumbs, true ) ) {
						continue;
					}
					if ( $thumb_id ) {
						$used_thumbs[] = $thumb_id;
					}

					$theme = $themes[ $i % count( $themes ) ];
					++$i;
					$img = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
					?>
					<a class="cular-notes__card cular-notes__card--<?php echo esc_attr( $theme ); ?>" href="<?php the_permalink(); ?>">
						<div class="cular-notes__media">
							<?php if ( $img ) : ?>
								<img src="<?php echo esc_url( $img ); ?>" alt="" loading="lazy" />
							<?php endif; ?>
						</div>

						<time class="cular-notes__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>

						<h3 class="cular-notes__title"><?php the_title(); ?></h3>

						<span class="cular-notes__more">Read More <span aria-hidden="true">&raquo;</span></span>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<button type="button" class="cular-notes__arrow cular-notes__arrow--next" data-slider-next aria-label="Next field notes">&#8250;</button>

			<div class="cular-notes__dots" data-slider-dots></div>
		</div>
	<?php endif; ?>
</section>
