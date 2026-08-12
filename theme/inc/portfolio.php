<?php
/**
 * Portfolio helpers shared by the case-study block and the portfolio archive.
 *
 * There are two records for every project and they are NOT redundant:
 *
 *   - the `portfolio_item` CPT holds the card (art, video, tags, link). It is
 *     what the homepage carousel and the archive are built from.
 *   - a page under /portfolio-cular/<slug>/ holds the long-form case study.
 *     These keep their original URLs — they are the ones with inbound links and
 *     search history, so converting them in place (as with every other page in
 *     this project) is worth more than tidier URLs would be.
 *
 * The CPT's `external_link` meta is what ties the two together.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Find the portfolio_item whose external_link points at a given page.
 *
 * Results are memoised per request: a case study asks for this once for its own
 * art and again for the related strip.
 *
 * @param int $page_id Page ID (defaults to the current post).
 * @return WP_Post|null
 */
function cular_case_study_item( $page_id = 0 ) {
	static $cache = array();

	$page_id = $page_id ? (int) $page_id : get_the_ID();
	if ( ! $page_id ) {
		return null;
	}
	if ( array_key_exists( $page_id, $cache ) ) {
		return $cache[ $page_id ];
	}

	$cache[ $page_id ] = null;

	// Match on the path rather than the full URL: the stored links were written
	// against whatever domain the site had at the time, and will be rewritten
	// again at launch.
	$path = wp_parse_url( get_permalink( $page_id ), PHP_URL_PATH );
	if ( ! $path ) {
		return null;
	}

	$found = get_posts(
		array(
			'post_type'      => 'portfolio_item',
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => 1,
			'meta_query'     => array(  // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => 'external_link',
					'value'   => untrailingslashit( $path ),
					'compare' => 'LIKE',
				),
			),
		)
	);

	if ( $found ) {
		$cache[ $page_id ] = $found[0];
	}
	return $cache[ $page_id ];
}

/**
 * The reverse lookup: the case-study page for a portfolio_item, if it has one.
 *
 * @param int|WP_Post $item Portfolio item.
 * @return string URL, or '' when the item has no case study.
 */
function cular_item_permalink( $item ) {
	$id   = $item instanceof WP_Post ? $item->ID : (int) $item;
	$link = (string) get_post_meta( $id, 'external_link', true );
	return $link ? $link : (string) get_permalink( $id );
}

/**
 * "Check some of our other work" — a strip of other projects.
 *
 * Prefers projects sharing a `portfolio_tag` with the current one, so the
 * internal links are topically related (which is what makes them worth
 * anything for SEO) and falls back to the most recent items.
 *
 * @param WP_Post|null $current Portfolio item this page documents.
 * @param int          $count   How many cards.
 * @param string       $heading Section heading.
 */
function cular_related_portfolio( $current, $count = 4, $heading = 'Check some of our other work' ) {
	$count = max( 0, (int) $count );
	if ( ! $count ) {
		return;
	}

	$exclude = $current ? array( $current->ID ) : array();
	$items   = array();

	if ( $current ) {
		$tags = wp_get_post_terms( $current->ID, 'portfolio_tag', array( 'fields' => 'ids' ) );
		if ( $tags && ! is_wp_error( $tags ) ) {
			$items = get_posts(
				array(
					'post_type'      => 'portfolio_item',
					'posts_per_page' => $count,
					'post__not_in'   => $exclude,
					'orderby'        => 'rand',
					'tax_query'      => array(  // phpcs:ignore WordPress.DB.SlowDBQuery
						array( 'taxonomy' => 'portfolio_tag', 'field' => 'term_id', 'terms' => $tags ),
					),
				)
			);
		}
	}

	// Top up with recent work if the tag match came back short.
	if ( count( $items ) < $count ) {
		$items = array_merge(
			$items,
			get_posts(
				array(
					'post_type'      => 'portfolio_item',
					'posts_per_page' => $count - count( $items ),
					'post__not_in'   => array_merge( $exclude, wp_list_pluck( $items, 'ID' ) ),
				)
			)
		);
	}

	if ( ! $items ) {
		return;
	}
	?>
	<aside class="cular-cs__related" data-cular-reveal>
		<h2 class="cular-cs__related-title"><?php echo esc_html( $heading ); ?></h2>

		<ul class="cular-cs__related-list" data-cular-reveal-items>
			<?php foreach ( $items as $item ) : ?>
				<?php
				$art   = cular_portfolio_image( $item->ID, 'medium_large' );
				$terms = wp_list_pluck( wp_get_post_terms( $item->ID, 'portfolio_tag' ), 'name' );
				$title = get_post_meta( $item->ID, 'card_title', true ) ?: get_the_title( $item );
				?>
				<li class="cular-cs__related-item">
					<a href="<?php echo esc_url( cular_item_permalink( $item ) ); ?>">
						<?php
						// The art box is rendered even when the item has no image:
						// several portfolio_items are art-less, and skipping the
						// box entirely made those cards ride up and left the grid
						// ragged. An empty box keeps every card on the same
						// baseline and reads as a deliberate placeholder.
						?>
						<span class="cular-cs__related-art<?php echo $art ? '' : ' is-empty'; ?>">
							<?php if ( $art ) : ?>
								<?php
								echo cular_img(
									get_post_thumbnail_id( $item->ID ) ?: (int) get_post_meta( $item->ID, 'portfolio_image_id', true ),
									array(
										'size'  => 'medium_large',
										'sizes' => '(max-width: 780px) 46vw, 260px',
										'alt'   => $title,
									)
								);
								?>
							<?php endif; ?>
						</span>

						<span class="cular-cs__related-name"><?php echo esc_html( $title ); ?></span>

						<?php if ( $terms ) : ?>
							<span class="cular-cs__related-tags"><?php echo esc_html( implode( ' · ', array_slice( $terms, 0, 3 ) ) ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</aside>
	<?php
}
