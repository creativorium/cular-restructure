<?php
/**
 * Single-post enhancements: SEO + content hygiene.
 *
 *  - Promote the first <h2> in the content to <h1> (posts author the title in
 *    the body; this gives one proper page heading without duplicating it).
 *  - Mark external links rel="noopener" + collect them into a "Sources"
 *    footnote list appended to the article.
 *  - Emit Article JSON-LD in <head>.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enhance single-post content: first h2 -> h1, external-link footnotes.
 *
 * @param string $content Post content.
 * @return string
 */
function cular_single_post_content( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( '' === trim( $content ) ) {
		return $content;
	}

	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<?xml encoding="utf-8"?><div id="cular-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	$xp = new DOMXPath( $dom );

	// 0) Drop the legacy in-content "Share this Post" widget. Posts imported
	// from Elementor carry a heading plus a paragraph of raw share SVGs; the
	// theme renders its own cular/post-share block, so this is a duplicate
	// (and the bare SVGs blow up to full width without Elementor's CSS).
	foreach ( iterator_to_array( $xp->query( '//h1|//h2|//h3|//h4' ) ) as $h ) {
		if ( 'share this post' !== strtolower( trim( preg_replace( '/\s+/u', ' ', $h->textContent ) ) ) ) {
			continue;
		}

		// Remove the following siblings that make up the widget — bare network
		// labels ("Facebook", "LinkedIn"), share links, or icon-only nodes —
		// stopping at the next heading or the first bit of real prose.
		$labels = '/^(facebook|linked ?in|twitter|x|instagram|whats ?app|telegram|pinterest|e-?mail|copy link|share)$/i';

		$node = $h->nextSibling;
		while ( $node ) {
			$next = $node->nextSibling;
			if ( XML_ELEMENT_NODE === $node->nodeType && preg_match( '/^h[1-6]$/i', $node->nodeName ) ) {
				break;
			}
			$text     = trim( preg_replace( '/\s+/u', ' ', $node->textContent ) );
			$is_share = '' === $text
				|| preg_match( $labels, $text )
				|| ( XML_ELEMENT_NODE === $node->nodeType
					&& ( $xp->query( './/*[local-name()="svg"]', $node )->length > 0
						|| $xp->query( './/a[contains(@href,"facebook.com/share") or contains(@href,"linkedin.com/share")]', $node )->length > 0 ) );

			if ( ! $is_share ) {
				break; // real content — leave it alone.
			}
			$node->parentNode->removeChild( $node );
			$node = $next;
		}

		$h->parentNode->removeChild( $h );
	}

	// 1) First h2 -> h1.
	$first_h2 = $xp->query( '//h2' )->item( 0 );
	if ( $first_h2 ) {
		$h1 = $dom->createElement( 'h1' );
		while ( $first_h2->firstChild ) {
			$h1->appendChild( $first_h2->firstChild );
		}
		$first_h2->parentNode->replaceChild( $h1, $first_h2 );
	}

	// 2) External links: rel + numbered footnotes.
	$home_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$sources   = array();
	foreach ( $xp->query( '//a[@href]' ) as $a ) {
		$href = $a->getAttribute( 'href' );
		$host = wp_parse_url( $href, PHP_URL_HOST );
		if ( ! $host || $host === $home_host ) {
			continue; // internal or anchor
		}
		$a->setAttribute( 'rel', 'noopener noreferrer' );
		$a->setAttribute( 'target', '_blank' );

		$n         = count( $sources ) + 1;
		$sources[] = array( 'n' => $n, 'href' => $href, 'text' => trim( $a->textContent ) );
		$sup       = $dom->createElement( 'sup' );
		$sup->setAttribute( 'class', 'cular-post__ref' );
		$link = $dom->createElement( 'a', '[' . $n . ']' );
		$link->setAttribute( 'href', '#cular-source-' . $n );
		$sup->appendChild( $link );
		if ( $a->nextSibling ) {
			$a->parentNode->insertBefore( $sup, $a->nextSibling );
		} else {
			$a->parentNode->appendChild( $sup );
		}
	}

	$root = $dom->getElementById( 'cular-root' );
	$out  = '';
	foreach ( $root->childNodes as $child ) {
		$out .= $dom->saveHTML( $child );
	}

	if ( $sources ) {
		$out .= '<aside class="cular-post__sources" aria-labelledby="cular-sources-title">'
			. '<h2 class="cular-post__sources-title" id="cular-sources-title">Sources</h2><ol class="cular-post__sources-list">';
		foreach ( $sources as $s ) {
			$host = preg_replace( '/^www\./', '', (string) wp_parse_url( $s['href'], PHP_URL_HOST ) );
			$out .= '<li class="cular-post__source" id="cular-source-' . (int) $s['n'] . '">'
				. '<a class="cular-post__source-link" href="' . esc_url( $s['href'] ) . '" target="_blank" rel="noopener noreferrer">'
				. '<span class="cular-post__source-label">' . esc_html( $s['text'] ? $s['text'] : $host ) . '</span>'
				. '<span class="cular-post__source-host">' . esc_html( $host ) . '</span>'
				. '</a></li>';
		}
		$out .= '</ol></aside>';
	}

	return $out;
}
add_filter( 'the_content', 'cular_single_post_content', 20 );

/**
 * Article JSON-LD for single posts.
 */
add_action(
	'wp_head',
	function () {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		$id  = get_queried_object_id();
		$img = get_the_post_thumbnail_url( $id, 'large' );
		$data = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'headline'         => wp_strip_all_tags( get_the_title( $id ) ),
			'datePublished'    => get_the_date( 'c', $id ),
			'dateModified'     => get_the_modified_date( 'c', $id ),
			'author'           => array( '@type' => 'Organization', 'name' => 'Cular Creative' ),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => 'Cular Creative',
				'logo'  => array( '@type' => 'ImageObject', 'url' => CULAR_URI . '/assets/img/logo-full.png' ),
			),
			'mainEntityOfPage' => get_permalink( $id ),
		);
		if ( $img ) {
			$data['image'] = $img;
		}
		echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
);
