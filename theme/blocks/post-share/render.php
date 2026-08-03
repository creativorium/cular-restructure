<?php
/**
 * Render: cular/post-share — "Share this Post" bar for single posts.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$url   = get_permalink();
$title = get_the_title();
if ( ! $url ) {
	return;
}

$fb = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url );
$li = 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $url );
?>
<div class="cular-share" data-cular-share>
	<h2 class="cular-share__title">Share this Post</h2>
	<div class="cular-share__buttons">
		<a class="cular-share__btn" href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
			<span>Facebook</span>
		</a>
		<a class="cular-share__btn" href="<?php echo esc_url( $li ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg>
			<span>LinkedIn</span>
		</a>
		<button class="cular-share__btn cular-share__btn--copy" type="button" data-copy="<?php echo esc_url( $url ); ?>" aria-label="Copy link">
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M3.9 12a3.1 3.1 0 0 1 3.1-3.1h4V7h-4a5 5 0 0 0 0 10h4v-1.9h-4A3.1 3.1 0 0 1 3.9 12zm4.6 1h7v-2h-7v2zm4.5-6h4a5 5 0 0 1 0 10h-4v-1.9h4a3.1 3.1 0 0 0 0-6.2h-4V7z"/></svg>
			<span data-copy-label>Copy Link</span>
		</button>
	</div>
</div>
