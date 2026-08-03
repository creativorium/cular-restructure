// Copy-link button for the share bar.
document.querySelectorAll('.cular-share__btn--copy').forEach((btn) => {
	btn.addEventListener('click', async () => {
		try {
			await navigator.clipboard.writeText(btn.dataset.copy);
			const label = btn.querySelector('[data-copy-label]');
			const prev = label.textContent;
			btn.classList.add('is-copied');
			label.textContent = 'Copied!';
			setTimeout(() => {
				btn.classList.remove('is-copied');
				label.textContent = prev;
			}, 1600);
		} catch {
			window.prompt('Copy this link:', btn.dataset.copy);
		}
	});
});
