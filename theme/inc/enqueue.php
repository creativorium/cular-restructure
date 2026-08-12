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

/**
 * The parsed Vite manifest, or an empty array in dev / before a first build.
 *
 * Read once per request — several hooks below need it.
 *
 * @return array<string,array>
 */
function cular_vite_manifest() {
	static $manifest = null;
	if ( null !== $manifest ) {
		return $manifest;
	}
	$path     = CULAR_DIR . '/dist/.vite/manifest.json';
	$manifest = file_exists( $path ) ? (array) json_decode( file_get_contents( $path ), true ) : array();
	return $manifest;
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
		$manifest = cular_vite_manifest();
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
 * Reveal-on-scroll bootstrap — inline, in <head>, deliberately.
 *
 * The reveal CSS hides `[data-cular-reveal]` elements only under `html.cular-js`
 * so this must land before the first paint (an external file would let the
 * content paint and then blink out). It is ~200 bytes; a request would cost far
 * more than it saves.
 *
 * The timeout is the safety net: our JS adds `cular-ready` when the bundle
 * boots, and if that never happens — bundle 404s, a JS error, an ancient
 * browser — this un-hides everything rather than leaving the visitor a page of
 * blank sections.
 */
add_action(
	'wp_head',
	function () {
		?>
<script>(function(d){d.classList.add('cular-js');setTimeout(function(){if(!d.classList.contains('cular-ready'))d.classList.add('cular-reveal-all')},3000)})(document.documentElement)</script>
		<?php
	},
	1
);

/**
 * Preload the two brand fonts.
 *
 * Both are used above the fold on every page (Luxia for the hero heading,
 * Montserrat for everything else) but the browser only discovers them after it
 * has fetched and parsed our CSS. Preloading overlaps those two round trips and
 * removes the swap-in flash. Worth doing only because the subset files are tiny
 * (42KB + 7KB) — preloading a 688KB TTF would have made things worse.
 *
 * The href has to come from the manifest: Vite content-hashes the fonts, and
 * preloading the un-hashed source path would fetch a second, different URL
 * instead of priming the one the CSS actually asks for.
 */
add_action(
	'wp_head',
	function () {
		if ( cular_vite_dev_server() ) {
			return;
		}
		$manifest = cular_vite_manifest();
		$dist     = CULAR_URI . '/dist/';

		foreach ( array( 'assets/fonts/Montserrat.woff2', 'assets/fonts/LuxiaDisplay.woff2' ) as $src ) {
			$file = $manifest[ $src ]['file'] ?? null;
			if ( ! $file ) {
				continue;
			}
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( $dist . $file )
			);
		}
	},
	2
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
