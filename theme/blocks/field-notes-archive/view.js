// Share button: native Web Share where available, otherwise copy the link.

document.querySelectorAll('.cular-fn__share').forEach((btn) => {
	btn.addEventListener('click', async (e) => {
		e.preventDefault();
		e.stopPropagation();
		const url = btn.dataset.shareUrl;
		const title = btn.dataset.shareTitle || document.title;

		if (navigator.share) {
			try {
				await navigator.share({ title, url });
				return;
			} catch {
				return; // user dismissed the share sheet
			}
		}

		// Fallback: copy to clipboard and show a brief confirmation.
		try {
			await navigator.clipboard.writeText(url);
			const original = btn.getAttribute('aria-label');
			btn.classList.add('is-copied');
			btn.setAttribute('aria-label', 'Link copied');
			setTimeout(() => {
				btn.classList.remove('is-copied');
				btn.setAttribute('aria-label', original);
			}, 1500);
		} catch {
			window.open(btn.dataset.shareFallback, '_blank', 'noopener');
		}
	});
});
