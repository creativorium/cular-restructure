<?php
/**
 * Sitewide SEO: structured data and the redirects the rebuild created.
 *
 * Complements Yoast rather than duplicating it (§10). Yoast owns titles, meta
 * descriptions, OG/Twitter tags, canonicals and sitemaps; this file adds the
 * schema Yoast does not emit for our custom page types, and is careful not to
 * emit a second Article/Organization node when Yoast is active.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is Yoast active and emitting its own schema graph?
 *
 * Two Organization nodes or two Article nodes on one page is worse than none —
 * Google picks one arbitrarily and the mismatch can suppress rich results.
 */
function cular_yoast_active() {
	return defined( 'WPSEO_VERSION' );
}

/**
 * The pages that document a project — everything under /portfolio-cular/.
 *
 * @param int $post_id Optional post ID.
 */
function cular_is_case_study( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
		return false;
	}
	return str_starts_with( get_page_uri( $post_id ), 'portfolio-cular/' );
}

/**
 * Emit structured data.
 *
 *  - Case studies get `CreativeWork` (the honest type for a portfolio piece —
 *    it is not an Article and it is not a Product) plus a `BreadcrumbList`.
 *  - The portfolio listing gets an `ItemList` naming every project, which is
 *    what lets the listing surface as a carousel rather than a bare link.
 *  - Every page gets `BreadcrumbList` unless Yoast is already providing it.
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_singular() ) {
			return;
		}

		$post_id = get_the_ID();
		$graph   = array();

		// --- Breadcrumbs (Yoast emits its own when active) ---
		if ( ! cular_yoast_active() ) {
			$crumbs   = array();
			$position = 1;
			$crumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => 'Home',
				'item'     => home_url( '/' ),
			);

			foreach ( array_reverse( (array) get_post_ancestors( $post_id ) ) as $ancestor ) {
				$crumbs[] = array(
					'@type'    => 'ListItem',
					'position' => $position++,
					'name'     => get_the_title( $ancestor ),
					'item'     => get_permalink( $ancestor ),
				);
			}

			$crumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => get_the_title( $post_id ),
				'item'     => get_permalink( $post_id ),
			);

			if ( count( $crumbs ) > 1 ) {
				$graph[] = array(
					'@type'           => 'BreadcrumbList',
					'itemListElement' => $crumbs,
				);
			}
		}

		// --- Case study ---
		if ( cular_is_case_study( $post_id ) ) {
			$item = function_exists( 'cular_case_study_item' ) ? cular_case_study_item( $post_id ) : null;

			$work = array(
				'@type'       => 'CreativeWork',
				'name'        => get_the_title( $post_id ),
				'url'         => get_permalink( $post_id ),
				'dateCreated' => get_the_date( 'c', $post_id ),
				'creator'     => array(
					'@type' => 'Organization',
					'name'  => get_bloginfo( 'name' ),
					'url'   => home_url( '/' ),
				),
			);

			$excerpt = wp_strip_all_tags( (string) get_the_excerpt( $post_id ) );
			if ( $excerpt ) {
				$work['description'] = $excerpt;
			}

			if ( $item ) {
				$art = cular_portfolio_image( $item->ID, 'large' );
				if ( $art ) {
					$work['image'] = $art;
				}
				$tags = wp_get_post_terms( $item->ID, 'portfolio_tag' );
				if ( $tags && ! is_wp_error( $tags ) ) {
					$work['keywords'] = implode( ', ', wp_list_pluck( $tags, 'name' ) );
					$work['about']    = array_map(
						function ( $t ) {
							return array( '@type' => 'Thing', 'name' => $t->name );
						},
						$tags
					);
				}
			}

			$graph[] = $work;
		}

		// --- Portfolio listing ---
		if ( in_array( get_page_uri( $post_id ), array( 'portfolio-cular', 'cular-portfolio' ), true ) ) {
			$items = get_posts(
				array(
					'post_type'      => 'portfolio_item',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
				)
			);
			$list = array();
			foreach ( $items as $i => $item ) {
				$list[] = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => get_post_meta( $item->ID, 'card_title', true ) ?: get_the_title( $item ),
					'url'      => cular_item_permalink( $item ),
				);
			}
			if ( $list ) {
				$graph[] = array(
					'@type'           => 'ItemList',
					'name'            => get_the_title( $post_id ),
					'numberOfItems'   => count( $list ),
					'itemListElement' => $list,
				);
			}
		}

		if ( ! $graph ) {
			return;
		}

		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode(
				array( '@context' => 'https://schema.org', '@graph' => $graph ),
				JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
			)
		);
	},
	5
);

/**
 * Demote the intake form's own <h1> to an <h2>.
 *
 * The Cular Intake Forms plugin renders the form's brand line as an <h1>, which
 * gave three service pages two <h1>s — the page hero's and the form's. A page
 * with competing top-level headings tells a crawler nothing about what it is
 * about, and it flattens the heading outline for screen-reader users.
 *
 * Wrapping the registered shortcode callback rather than editing the plugin
 * keeps the fix in the theme, so a plugin update cannot undo it and cannot
 * re-break the page either.
 */
add_action(
	'init',
	function () {
		global $shortcode_tags;

		if ( ! isset( $shortcode_tags['cular_intake_form'] ) ) {
			return;
		}

		$original = $shortcode_tags['cular_intake_form'];

		$shortcode_tags['cular_intake_form'] = function ( $atts, $content = null, $tag = '' ) use ( $original ) {
			$html = call_user_func( $original, $atts, $content, $tag );
			if ( ! is_string( $html ) || false === stripos( $html, '<h1' ) ) {
				return $html;
			}
			return preg_replace( '#<(/?)h1(\s|>)#i', '<$1h2$2', $html );
		};
	},
	20
);

/**
 * Redirects created by the rebuild.
 *
 * `/cular-creative/` was the old Elementor homepage. It is no longer linked
 * from anywhere (the Home menu item now points at `/`), but it has years of
 * inbound links and search history behind it, so it 301s to the front page
 * rather than being left as a duplicate of the homepage — two URLs serving the
 * same content is the exact thing canonicals and redirects exist to prevent.
 *
 * @return array<string,string> source path => destination.
 */
function cular_redirects() {
	return array(
		'/cular-creative/' => home_url( '/' ),
	);
}

add_action(
	'template_redirect',
	function () {
		if ( is_admin() ) {
			return;
		}

		$path = wp_parse_url( add_query_arg( array() ), PHP_URL_PATH );
		if ( ! $path ) {
			return;
		}
		$path = trailingslashit( $path );

		$redirects = cular_redirects();
		if ( isset( $redirects[ $path ] ) ) {
			wp_safe_redirect( $redirects[ $path ], 301 );
			exit;
		}
	}
);

/**
 * Keep the retired pages out of the index.
 *
 * The drafts, previews and internal one-offs are converted onto our blocks (so
 * they render correctly if someone opens them) but they are not content we want
 * competing in search results.
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_singular() || cular_yoast_active() ) {
			return;
		}

		$noindex = array(
			'field-note-preview',
			'case-study-test',
			'case-study-draft',
			'cular-creative-rate-card-preview',
			'let-us-know-how-we-did',
		);

		if ( in_array( get_page_uri( get_the_ID() ), $noindex, true ) ) {
			echo '<meta name="robots" content="noindex, follow" />' . "\n";
		}
	},
	1
);
