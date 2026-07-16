<?php
/**
 * Render: cular/field-notes — latest blog posts.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'eyebrow' ) ?: 'Our Field Notes';
$count   = (int) ( get_field( 'count' ) ?: 3 );

$q = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-notes" data-cular-reveal>
	<header class="cular-notes__head">
		<p class="cular-notes__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<a class="cular-notes__all" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">All field notes →</a>
	</header>

	<?php if ( $q->have_posts() ) : ?>
		<div class="cular-notes__grid">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$img = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
				?>
				<a class="cular-notes__card" href="<?php the_permalink(); ?>">
					<div class="cular-notes__media"<?php echo $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?>></div>
					<h3><?php the_title(); ?></h3>
					<p class="cular-notes__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
				</a>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</section>
