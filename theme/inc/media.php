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
