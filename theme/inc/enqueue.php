<?php
/**
 * Vite asset loader.
 *
 * In dev (VITE_DEV_SERVER reachable), loads modules from the Vite dev server
 * for HMR. In production, reads theme/dist/.vite/manifest.json and enqueues
 * the hashed built files.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

/**
 * Toggle dev mode by creating theme/.dev-server (or set CULAR_VITE_DEV true).
 * Keeps prod safe: if no manifest and no dev flag, nothing breaks.
 */
function cular_vite_dev_server() {
	if ( defined( 'CULAR_VITE_DEV' ) && CULAR_VITE_DEV ) {
		return 'http://localhost:5173';
	}
	if ( file_exists( CULAR_DIR . '/.dev-server' ) ) {
		return trim( file_get_contents( CULAR_DIR . '/.dev-server' ) ) ?: 'http://localhost:5173';
	}
	return null;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		$dev = cular_vite_dev_server();

		if ( $dev ) {
			// Dev: Vite client + main entry as ES modules.
			add_action(
				'wp_head',
				function () use ( $dev ) {
					echo '<script type="module" src="' . esc_url( $dev . '/@vite/client' ) . '"></script>' . "\n";
					echo '<script type="module" src="' . esc_url( $dev . '/src/main.js' ) . '"></script>' . "\n";
				},
				1
			);
			return;
		}

		// Production: read manifest.
		$manifest_path = CULAR_DIR . '/dist/.vite/manifest.json';
		if ( ! file_exists( $manifest_path ) ) {
			return;
		}
		$manifest = json_decode( file_get_contents( $manifest_path ), true );
		$entry    = $manifest['src/main.js'] ?? null;
		if ( ! $entry ) {
			return;
		}

		$dist = CULAR_URI . '/dist/';

		if ( ! empty( $entry['css'] ) ) {
			foreach ( $entry['css'] as $i => $css ) {
				wp_enqueue_style( 'cular-' . $i, $dist . $css, array(), CULAR_VERSION );
			}
		}
		wp_enqueue_script( 'cular-main', $dist . $entry['file'], array(), CULAR_VERSION, true );
	}
);

/**
 * Load main JS as a module (needed for Vite ESM output).
 */
add_filter(
	'script_loader_tag',
	function ( $tag, $handle, $src ) {
		if ( 'cular-main' === $handle ) {
			return '<script type="module" src="' . esc_url( $src ) . '" id="' . esc_attr( $handle ) . '-js"></script>' . "\n";
		}
		return $tag;
	},
	10,
	3
);
