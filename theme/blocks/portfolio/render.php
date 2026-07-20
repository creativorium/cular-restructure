<?php
/**
 * Render: cular/portfolio
 *
 * "Our Previous Work" — an inner gradient panel holding colour-coded project
 * cards (title, media, service tags). Sits inside the shared
 * .cular-gradient-mesh wrapper.
 *
 * Portfolio items carry custom meta from the Cular portfolio plugin:
 *   card_title, video_url, overlay_logo_id, external_link
 * plus their services as `portfolio_tag` terms.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Our Previous Work';
$intro   = get_field( 'intro' );
$count   = (int) ( get_field( 'count' ) ?: 5 );
$all_url = get_field( 'all_url' ) ?: home_url( '/portfolio-cular/' );

if ( ! $intro ) {
	$intro = "We're super proud to have worked with some amazing brands in Bali, Indonesia, and internationally. Check out our portfolio for more details of our work.";
}

// Curated selection (Featured projects field). Falls back to the set used on
// the live homepage, then to the most recent items.
$featured = get_field( 'featured_items' );
$featured = is_array( $featured ) ? array_map( 'intval', $featured ) : array();

if ( ! $featured ) {
	$defaults = array( 22580, 22416, 23517, 22412, 22427 ); // Sunset Pilates, Inspiral, Bali Buda, Bali Event Hire, Livingstone
	foreach ( $defaults as $default_id ) {
		if ( 'publish' === get_post_status( $default_id ) ) {
			$featured[] = $default_id;
		}
	}
}

if ( $featured ) {
	$q = new WP_Query(
		array(
			'post_type'           => 'portfolio_item',
			'post__in'            => $featured,
			'orderby'             => 'post__in',
			'posts_per_page'      => count( $featured ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	$count = count( $featured );
} else {
	// Over-fetch so we can skip items with no artwork and still fill the row.
	$q = new WP_Query(
		array(
			'post_type'           => 'portfolio_item',
			'posts_per_page'      => $count + 8,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

// Card colours cycle through the brand palette.
$themes = array( 'gold', 'sage', 'coral', 'green', 'sage' );
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-portfolio">
	<header class="cular-portfolio__head">
		<h2 class="cular-portfolio__heading"><?php echo esc_html( $heading ); ?></h2>
		<p class="cular-portfolio__intro"><?php echo esc_html( $intro ); ?></p>
	</header>

	<?php if ( $q->have_posts() ) : ?>
		<div class="cular-portfolio__panel">
			<div class="cular-portfolio__grid">
				<?php
				$i = 0;
				while ( $q->have_posts() ) :
					$q->the_post();
					$id = get_the_ID();

					$title = get_post_meta( $id, 'card_title', true ) ?: get_the_title();
					$video = get_post_meta( $id, 'video_url', true );
					$link  = get_post_meta( $id, 'external_link', true ) ?: get_permalink();
					$img   = get_the_post_thumbnail_url( $id, 'medium_large' );

					if ( ! $img ) {
						$logo_id = (int) get_post_meta( $id, 'overlay_logo_id', true );
						if ( $logo_id ) {
							$img = wp_get_attachment_image_url( $logo_id, 'medium_large' );
						}
					}

					// When auto-filling, skip items with no artwork rather than
					// showing a blank card. Curated picks are always shown.
					if ( ! $featured && ! $video && ! $img ) {
						continue;
					}
					if ( $i >= $count ) {
						break;
					}

					$theme = $themes[ $i % count( $themes ) ];
					++$i;

					$tags = wp_get_post_terms( $id, 'portfolio_tag', array( 'fields' => 'names' ) );
					$tags = is_wp_error( $tags ) ? array() : array_slice( $tags, 0, 4 );
					?>
					<a class="cular-portfolio__card cular-portfolio__card--<?php echo esc_attr( $theme ); ?>" href="<?php echo esc_url( $link ); ?>">
						<h3 class="cular-portfolio__title"><?php echo esc_html( $title ); ?></h3>

						<div class="cular-portfolio__media">
							<?php if ( $video ) : ?>
								<video src="<?php echo esc_url( $video ); ?>" autoplay muted loop playsinline
									<?php echo $img ? 'poster="' . esc_url( $img ) . '"' : ''; ?>></video>
							<?php elseif ( $img ) : ?>
								<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
							<?php endif; ?>
						</div>

						<?php if ( $tags ) : ?>
							<ul class="cular-portfolio__tags">
								<?php foreach ( $tags as $tag ) : ?>
									<li><?php echo esc_html( $tag ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</a>
				<?php endwhile; ?>
			</div>

			<a class="cular-portfolio__all" href="<?php echo esc_url( $all_url ); ?>">
				Explore<span aria-hidden="true">&#10230;</span>
			</a>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</section>
