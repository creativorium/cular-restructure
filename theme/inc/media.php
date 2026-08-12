<?php
/**
 * Media helpers shared by blocks.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Normalise an ACF gallery value (array of arrays or IDs) into a list of
 * image URLs, falling back to a list of upload-relative paths.
 *
 * @param mixed         $field    ACF gallery value.
 * @param array<string> $fallback Upload-relative paths, e.g. '2024/09/logo.png'.
 * @return array<int, array{url:string,alt:string}>
 */
function cular_gallery_urls( $field, array $fallback = array() ) {
	$out = array();

	if ( is_array( $field ) && $field ) {
		foreach ( $field as $item ) {
			if ( is_array( $item ) && ! empty( $item['url'] ) ) {
				$out[] = array(
					'url' => $item['url'],
					'alt' => isset( $item['alt'] ) ? $item['alt'] : '',
				);
			} elseif ( is_numeric( $item ) ) {
				$url = wp_get_attachment_image_url( (int) $item, 'medium_large' );
				if ( $url ) {
					$out[] = array(
						'url' => $url,
						'alt' => (string) get_post_meta( (int) $item, '_wp_attachment_image_alt', true ),
					);
				}
			}
		}
	}

	if ( ! $out ) {
		foreach ( $fallback as $path ) {
			$out[] = array(
				'url' => content_url( '/uploads/' . $path ),
				'alt' => '',
			);
		}
	}

	return $out;
}

/**
 * Card art for a portfolio_item: featured image first, then the
 * portfolio_image_id meta some entries use instead.
 *
 * @param int    $id   Portfolio item ID.
 * @param string $size Image size.
 * @return string URL, or '' when the item has no usable art.
 */
function cular_portfolio_image( $id, $size = 'medium_large' ) {
	$url = get_the_post_thumbnail_url( $id, $size );
	if ( $url ) {
		return $url;
	}
	$alt = (int) get_post_meta( $id, 'portfolio_image_id', true );
	return $alt ? (string) wp_get_attachment_image_url( $alt, $size ) : '';
}

/**
 * Render a responsive <img> from an ACF image array (or an attachment ID).
 *
 * Blocks were hand-writing <img> tags with a single `src`, which meant a phone
 * downloading a 2000px original to paint it at 380px — the biggest single
 * source of wasted bytes on the rebuilt pages. Going through
 * wp_get_attachment_image() gets us `srcset`, `sizes`, intrinsic width/height
 * (so no CLS) and lazy-loading for free.
 *
 * Falls back to a plain <img> when all we have is a bare URL, which happens for
 * content ported out of Elementor where the attachment row is gone.
 *
 * @param array|int $image ACF image array, or an attachment ID.
 * @param array     $args  class, sizes, size, loading, fetchpriority, alt.
 * @return string HTML.
 */
function cular_img( $image, array $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'         => '',
			'sizes'         => '',
			'size'          => 'large',
			'loading'       => 'lazy',
			'fetchpriority' => '',
			'alt'           => null,
		)
	);

	$id  = 0;
	$url = '';
	$alt = '';
	$w   = 0;
	$h   = 0;

	if ( is_numeric( $image ) ) {
		$id = (int) $image;
	} elseif ( is_array( $image ) ) {
		$id  = (int) ( $image['ID'] ?? $image['id'] ?? 0 );
		$url = (string) ( $image['url'] ?? '' );
		$alt = (string) ( $image['alt'] ?? '' );
		$w   = (int) ( $image['width'] ?? 0 );
		$h   = (int) ( $image['height'] ?? 0 );
	} elseif ( is_string( $image ) ) {
		$url = $image;
	}

	if ( null !== $args['alt'] ) {
		$alt = (string) $args['alt'];
	}

	$attr = array( 'class' => $args['class'], 'alt' => $alt );
	if ( $args['sizes'] ) {
		$attr['sizes'] = $args['sizes'];
	}
	if ( 'lazy' === $args['loading'] ) {
		$attr['loading']  = 'lazy';
		$attr['decoding'] = 'async';
	} else {
		// An eagerly-loaded image must NOT also say loading="lazy"; and decoding
		// sync avoids a paint delay on the one image we actually want first.
		$attr['loading']  = 'eager';
		$attr['decoding'] = 'sync';
	}
	if ( $args['fetchpriority'] ) {
		$attr['fetchpriority'] = $args['fetchpriority'];
	}

	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image( $id, $args['size'], false, $attr );
	}

	if ( ! $url ) {
		return '';
	}

	// No attachment row — emit a plain tag, still with dimensions when ACF gave
	// them to us, so the layout does not jump.
	$out = '<img src="' . esc_url( $url ) . '"';
	foreach ( $attr as $k => $v ) {
		if ( '' !== $v ) {
			$out .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
	}
	if ( $w && $h ) {
		$out .= ' width="' . (int) $w . '" height="' . (int) $h . '"';
	}
	return $out . ' />';
}
