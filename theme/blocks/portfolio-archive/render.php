<?php
/**
 * Render: cular/portfolio-archive
 *
 * The full portfolio. Every card comes from the `portfolio_item` CPT and links
 * to that project's case study (§7 / inc/portfolio.php).
 *
 * Layout is a deliberate rhythm rather than a uniform grid: one full-width
 * feature card, then a pair, then a feature again. The pattern is applied by
 * view.js to the *visible* cards, so it stays correct after searching or
 * filtering — a CSS `:nth-child` rule would keep counting hidden cards and the
 * rhythm would collapse the moment anything was filtered out.
 *
 * Everything ships in the initial HTML (43 cards is small, and it means the
 * whole catalogue is crawlable and searchable without a round trip), but only a
 * batch is *shown* at a time and more reveal as you scroll. Combined with lazy
 * images past the first row, that keeps the page feeling light without paging.
 *
 * The "Want to work with us?" CTA is the shared cular/cta block appended to the
 * page, not a copy living in here.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading      = get_field( 'heading' ) ?: 'A showcase of some of our work, in no particular order.';
$intro        = get_field( 'intro' );
$show_filters = get_field( 'show_filters' );
$filter_label = get_field( 'filter_label' ) ?: 'Curious to see what we can do? Select a service to browse our portfolio.';

$items = get_posts(
	array(
		'post_type'      => 'portfolio_item',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order date',
		'order'          => 'DESC',
	)
);

// Only offer a service that actually has work behind it — a filter that yields
// an empty grid is worse than no filter.
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
		<h2 class="cular-parch__heading" data-cular-split><?php echo esc_html( $heading ); ?></h2>
		<?php if ( $intro ) : ?>
			<p class="cular-parch__intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( $show_filters && $terms ) : ?>
		<div class="cular-parch__bar" data-cular-reveal>
			<div class="cular-parch__search">
				<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0A4.5 4.5 0 1 1 14 9.5 4.49 4.49 0 0 1 9.5 14z"/></svg>
				<label class="screen-reader-text" for="cular-parch-search">Search projects</label>
				<input
					type="search"
					id="cular-parch-search"
					class="cular-parch__search-input"
					placeholder="Search projects…"
					autocomplete="off"
					data-search
				/>
			</div>

			<?php // A disclosure panel, not a <select>: 26 services want room to breathe, and the counts help people pick. ?>
			<div class="cular-parch__services">
				<button type="button" class="cular-parch__services-btn" data-services-toggle aria-expanded="false" aria-controls="cular-parch-services">
					<span class="cular-parch__services-label" data-services-current>All services</span>
					<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7 10l5 5 5-5z"/></svg>
				</button>

				<div class="cular-parch__panel" id="cular-parch-services" data-services-panel hidden>
					<p class="cular-parch__panel-label"><?php echo esc_html( $filter_label ); ?></p>
					<div class="cular-parch__chips" role="group">
						<button type="button" class="cular-parch__chip is-active" data-filter="*" aria-pressed="true">
							All <span><?php echo count( $items ); ?></span>
						</button>
						<?php foreach ( $terms as $term ) : ?>
							<button type="button" class="cular-parch__chip" data-filter="<?php echo esc_attr( $term->slug ); ?>" aria-pressed="false">
								<?php echo esc_html( $term->name ); ?> <span><?php echo (int) $term->count; ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<p class="cular-parch__count" data-count aria-live="polite"></p>
		</div>
	<?php endif; ?>

	<ul class="cular-parch__grid" data-grid>
		<?php foreach ( $items as $i => $item ) : ?>
			<?php
			$title = get_post_meta( $item->ID, 'card_title', true ) ?: get_the_title( $item );
			$tags  = wp_get_post_terms( $item->ID, 'portfolio_tag' );
			$tags  = is_wp_error( $tags ) ? array() : $tags;
			$slugs = wp_list_pluck( $tags, 'slug' );
			$names = wp_list_pluck( $tags, 'name' );
			$video = (string) get_post_meta( $item->ID, 'video_url', true );
			$art   = get_post_thumbnail_id( $item->ID ) ?: (int) get_post_meta( $item->ID, 'portfolio_image_id', true );
			// Only the first feature card is worth loading eagerly.
			$eager = 0 === $i;
			?>
			<li
				class="cular-parch__item"
				data-tags="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>"
				data-search="<?php echo esc_attr( strtolower( $title . ' ' . implode( ' ', $names ) ) ); ?>"
			>
				<a class="cular-parch__card" href="<?php echo esc_url( cular_item_permalink( $item ) ); ?>">
					<span class="cular-parch__art<?php echo ( $art || $video ) ? '' : ' is-empty'; ?>">
						<?php
						// With a poster we can afford preload="none". Without one,
						// preload="none" paints a black box — the #t=0.1 media
						// fragment makes the browser seek just past the start and
						// show that frame as the still instead.
						$poster    = $art ? (string) wp_get_attachment_image_url( $art, 'large' ) : '';
						$video_src = $poster ? $video : $video . '#t=0.1';
						?>
						<?php if ( $video ) : ?>
							<video
								class="cular-parch__video"
								src="<?php echo esc_url( $video_src ); ?>"
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
									'size'          => 'large',
									'sizes'         => '(max-width: 640px) 92vw, (max-width: 1100px) 46vw, 560px',
									'alt'           => $title,
									'loading'       => $eager ? 'eager' : 'lazy',
									'fetchpriority' => $eager ? 'high' : '',
								)
							);
							?>
						<?php endif; ?>

						<span class="cular-parch__view" aria-hidden="true">
							View details
							<svg viewBox="0 0 24 24" width="15" height="15" focusable="false"><path fill="currentColor" d="M13.2 5.2 12 6.4l4.8 4.8H4v1.6h12.8L12 17.6l1.2 1.2 7-7z"/></svg>
						</span>
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

	<?php // Watched by the observer; crossing it reveals the next batch. ?>
	<div class="cular-parch__sentinel" data-sentinel aria-hidden="true"></div>

	<p class="cular-parch__empty" hidden>No projects match that search.</p>
</section>
