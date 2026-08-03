<?php
/**
 * Render: cular/field-notes-archive
 *
 * Blog archive — title, intro and a paginated grid of post cards on rotating
 * brand colours. Cards carry Article microdata for SEO; each links internally
 * to the post and exposes a share button.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Field Notes';
$intro   = get_field( 'intro' );
if ( ! $intro ) {
	$intro = "Sharp insights. Real stories.\nPull up a chair — let's talk marketing.\nSign up for our newsletter to stay in the know.";
}

$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$q     = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'paged'          => $paged,
	)
);

$themes = array( 'sage', 'green', 'gold', 'coral', 'grey' );
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-fn">
	<header class="cular-fn__head" data-cular-reveal>
		<h1 class="cular-fn__heading"><?php echo esc_html( $heading ); ?></h1>
		<div class="cular-fn__intro">
			<?php foreach ( preg_split( '/\n/', trim( $intro ) ) as $line ) : ?>
				<p><?php echo esc_html( trim( $line ) ); ?></p>
			<?php endforeach; ?>
		</div>
	</header>

	<?php if ( $q->have_posts() ) : ?>
		<div class="cular-fn__grid" data-cular-reveal>
			<?php
			$i = 0;
			while ( $q->have_posts() ) :
				$q->the_post();
				$theme = $themes[ $i % count( $themes ) ];
				++$i;
				$img   = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
				$title = get_the_title();
				$url   = get_permalink();
				?>
				<article class="cular-fn__card cular-fn__card--<?php echo esc_attr( $theme ); ?>"
					itemscope itemtype="https://schema.org/BlogPosting">
					<a class="cular-fn__cardlink" href="<?php echo esc_url( $url ); ?>" itemprop="url">
						<div class="cular-fn__media">
							<?php if ( $img ) : ?>
								<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" itemprop="image" />
							<?php endif; ?>
						</div>

						<time class="cular-fn__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
						<h2 class="cular-fn__title" itemprop="headline"><?php echo esc_html( $title ); ?></h2>
						<meta itemprop="mainEntityOfPage" content="<?php echo esc_url( $url ); ?>" />
						<span class="cular-fn__more">Read More &raquo;</span>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<?php
		$links = paginate_links(
			array(
				'total'     => $q->max_num_pages,
				'current'   => $paged,
				'mid_size'  => 1,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type'      => 'array',
			)
		);
		if ( $links ) :
			?>
			<nav class="cular-fn__pagination" aria-label="Field notes pages">
				<?php echo implode( '', $links ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</nav>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p class="cular-fn__empty">No field notes yet — check back soon.</p>
	<?php endif; ?>
</section>
