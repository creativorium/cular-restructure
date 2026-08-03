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

// Brand palette of the live archive — no greys/creams, cards are always one of
// these five.
$themes = array( 'sage', 'green', 'gold', 'peach', 'green', 'coral' );
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
				$thumb_id = get_post_thumbnail_id();
				$title    = get_the_title();
				$url      = get_permalink();

				// Cards lead with the hand-written excerpt (as on the live site);
				// fall back to the title when a post has none.
				$blurb = has_excerpt() ? get_the_excerpt() : $title;
				?>
				<article class="cular-fn__card cular-fn__card--<?php echo esc_attr( $theme ); ?>"
					itemscope itemtype="https://schema.org/BlogPosting">
					<a class="cular-fn__cardlink" href="<?php echo esc_url( $url ); ?>" itemprop="url">
						<div class="cular-fn__media">
							<?php
							if ( $thumb_id ) {
								echo wp_get_attachment_image(
									$thumb_id,
									'large',
									false,
									array(
										'alt'      => $title,
										'loading'  => 'lazy',
										'itemprop' => 'image',
										'sizes'    => '(max-width: 640px) 92vw, (max-width: 1000px) 46vw, 30vw',
									)
								);
							}
							?>
						</div>

						<time class="cular-fn__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>

						<?php // The title carries the heading/schema; the excerpt is what's shown. ?>
						<h2 class="screen-reader-text" itemprop="headline"><?php echo esc_html( $title ); ?></h2>
						<p class="cular-fn__title" itemprop="description"><?php echo esc_html( $blurb ); ?></p>

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
