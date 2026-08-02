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

$themes = array( 'sage', 'green', 'gold', 'coral', 'cream', 'grey' );
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
		<div class="cular-fn__grid" data-cular-reveal-items>
			<?php
			$i = 0;
			while ( $q->have_posts() ) :
				$q->the_post();
				$theme = $themes[ $i % count( $themes ) ];
				++$i;
				$img   = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
				$title = get_the_title();
				$url   = get_permalink();
				$share = 'https://wa.me/?text=' . rawurlencode( $title . ' — ' . $url );
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
					</a>

					<div class="cular-fn__actions">
						<span class="cular-fn__more" aria-hidden="true">Read More &raquo;</span>
						<button class="cular-fn__share" type="button"
							data-share-url="<?php echo esc_url( $url ); ?>"
							data-share-title="<?php echo esc_attr( $title ); ?>"
							data-share-fallback="<?php echo esc_url( $share ); ?>"
							aria-label="<?php echo esc_attr( 'Share: ' . $title ); ?>">
							<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/></svg>
						</button>
					</div>
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
