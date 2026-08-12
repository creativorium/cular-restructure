<?php
/**
 * Render: cular/portfolio-archive
 *
 * The full portfolio grid. Every card comes from the `portfolio_item` CPT and
 * links to that project's case study (§7 / inc/portfolio.php).
 *
 * Filtering is done in the browser rather than by reloading the page: the whole
 * catalogue is ~45 cards, so shipping them all once and hiding the non-matching
 * ones costs less than a round trip per filter click, keeps the URL clean, and
 * means every project is in the initial HTML for crawlers. Cards past the first
 * row load lazily so the up-front cost stays small.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading      = get_field( 'heading' ) ?: 'A showcase of some of our work, in no particular order.';
$intro        = get_field( 'intro' );
$show_filters = get_field( 'show_filters' );
$filter_label = get_field( 'filter_label' ) ?: 'Curious to see what we can do? Select a service to browse our portfolio.';
$cta_text     = get_field( 'cta_text' ) ?: 'Want to work with us?';
$cta_label    = get_field( 'cta_label' ) ?: 'Get in Touch';
$cta_url      = get_field( 'cta_url' ) ?: home_url( '/contact/' );

$items = get_posts(
	array(
		'post_type'      => 'portfolio_item',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order date',
		'order'          => 'DESC',
	)
);

// Only offer filters for services that actually have work behind them —
// a chip that yields an empty grid is worse than no chip.
$terms = get_terms(
	array(
		'taxonomy'   => 'portfolio_tag',
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);
$terms = is_wp_error( $terms ) ? array() : $terms;

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="cular-parch" data-cular-portfolio-archive>

	<div class="cular-parch__head" data-cular-reveal>
		<h2 class="cular-parch__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php if ( $intro ) : ?>
			<p class="cular-parch__intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $show_filters && $terms ) : ?>
		<div class="cular-parch__filters" data-cular-reveal>
			<p class="cular-parch__filter-label" id="cular-parch-filter-label"><?php echo esc_html( $filter_label ); ?></p>

			<div class="cular-parch__chips" role="group" aria-labelledby="cular-parch-filter-label">
				<button type="button" class="cular-parch__chip is-active" data-filter="*" aria-pressed="true">
					All <span class="cular-parch__chip-count"><?php echo count( $items ); ?></span>
				</button>
				<?php foreach ( $terms as $term ) : ?>
					<button type="button" class="cular-parch__chip" data-filter="<?php echo esc_attr( $term->slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $term->name ); ?>
						<span class="cular-parch__chip-count"><?php echo (int) $term->count; ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<ul class="cular-parch__grid" data-cular-reveal-items>
		<?php foreach ( $items as $i => $item ) : ?>
			<?php
			$title = get_post_meta( $item->ID, 'card_title', true ) ?: get_the_title( $item );
			$tags  = wp_get_post_terms( $item->ID, 'portfolio_tag' );
			$tags  = is_wp_error( $tags ) ? array() : $tags;
			$slugs = wp_list_pluck( $tags, 'slug' );
			$video = (string) get_post_meta( $item->ID, 'video_url', true );
			$art   = get_post_thumbnail_id( $item->ID ) ?: (int) get_post_meta( $item->ID, 'portfolio_image_id', true );
			// The first row is above the fold on most viewports; everything after
			// it waits until the user scrolls to it.
			$eager = $i < 3;
			?>
			<li class="cular-parch__item" data-tags="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
				<a class="cular-parch__card" href="<?php echo esc_url( cular_item_permalink( $item ) ); ?>">
					<span class="cular-parch__art<?php echo ( $art || $video ) ? '' : ' is-empty'; ?>">
						<?php
						// Muted, hover-to-play only: with a poster we use
						// preload="none" so 45 cards do not each start pulling a
						// video file on load. Items with no card art have no
						// poster either, and preload="none" would paint them as
						// blank boxes — those fall back to "metadata", enough for
						// the browser to show a first frame.
						$poster = $art ? (string) wp_get_attachment_image_url( $art, 'medium_large' ) : '';
						?>
						<?php if ( $video ) : ?>
							<video
								class="cular-parch__video"
								src="<?php echo esc_url( $video ); ?>"
								<?php if ( $poster ) : ?>poster="<?php echo esc_url( $poster ); ?>"<?php endif; ?>
								preload="<?php echo $poster ? 'none' : 'metadata'; ?>"
								muted
								loop
								playsinline
								tabindex="-1"
								aria-hidden="true"
							></video>
						<?php elseif ( $art ) : ?>
							<?php
							echo cular_img(
								$art,
								array(
									'size'          => 'medium_large',
									'sizes'         => '(max-width: 640px) 92vw, (max-width: 1100px) 46vw, 380px',
									'alt'           => $title,
									'loading'       => $eager ? 'eager' : 'lazy',
									'fetchpriority' => 0 === $i ? 'high' : '',
								)
							);
							?>
						<?php endif; ?>
					</span>

					<span class="cular-parch__body">
						<span class="cular-parch__name"><?php echo esc_html( $title ); ?></span>
						<?php if ( $tags ) : ?>
							<span class="cular-parch__tags">
								<?php foreach ( array_slice( $tags, 0, 3 ) as $tag ) : ?>
									<span class="cular-parch__tag"><?php echo esc_html( $tag->name ); ?></span>
								<?php endforeach; ?>
							</span>
						<?php endif; ?>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<p class="cular-parch__empty" hidden>No projects match that service yet.</p>

	<div class="cular-parch__cta" data-cular-reveal>
		<p class="cular-parch__cta-text"><?php echo esc_html( $cta_text ); ?></p>
		<a class="cular-parch__cta-btn" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
	</div>
</section>
