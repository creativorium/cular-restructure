<?php
/**
 * Render: cular/related-posts
 *
 * "More Field Notes" — recent posts sharing a category with the current one
 * (falls back to latest), for internal linking. Renders nothing off single.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$current = get_the_ID();
if ( ! is_singular( 'post' ) || ! $current ) {
	return;
}

$cats  = wp_get_post_categories( $current );
$args  = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 3,
	'post__not_in'        => array( $current ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( $cats ) {
	$args['category__in'] = $cats;
}
$q = new WP_Query( $args );

// Backfill with latest if a thin category returned too few.
if ( $q->post_count < 3 ) {
	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $current ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( ! $q->have_posts() ) {
	return;
}
$themes = array( 'gold', 'coral', 'green' );
?>
<section class="cular-related" data-cular-reveal>
	<h2 class="cular-related__title">More Field Notes</h2>
	<div class="cular-related__grid">
		<?php
		$i = 0;
		while ( $q->have_posts() ) :
			$q->the_post();
			$theme = $themes[ $i % count( $themes ) ];
			++$i;
			$img = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
			?>
			<a class="cular-related__card cular-related__card--<?php echo esc_attr( $theme ); ?>" href="<?php the_permalink(); ?>" rel="bookmark">
				<div class="cular-related__media"<?php echo $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?>></div>
				<time class="cular-related__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
				<h3 class="cular-related__name"><?php the_title(); ?></h3>
			</a>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
