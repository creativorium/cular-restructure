<?php
/**
 * Render: cular/portfolio — pulls recent portfolio_item posts.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'eyebrow' ) ?: 'Our Previous Work';
$heading = get_field( 'heading' );
$count   = (int) ( get_field( 'count' ) ?: 6 );
$all_url = get_field( 'all_url' ) ?: home_url( '/portfolio-cular/' );

$q = new WP_Query(
	array(
		'post_type'           => 'portfolio_item',
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-portfolio" data-cular-reveal>
	<header class="cular-portfolio__head">
		<p class="cular-portfolio__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php if ( $heading ) : ?><h2 class="cular-portfolio__heading"><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
	</header>

	<?php if ( $q->have_posts() ) : ?>
		<div class="cular-portfolio__grid">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
				?>
				<a class="cular-portfolio__item" href="<?php the_permalink(); ?>">
					<div class="cular-portfolio__media"<?php echo $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?>></div>
					<div class="cular-portfolio__meta">
						<h3><?php the_title(); ?></h3>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
		<a class="cular-portfolio__all" href="<?php echo esc_url( $all_url ); ?>">View all work →</a>
	<?php else : ?>
		<p>No portfolio items found yet.</p>
	<?php endif; ?>
</section>
