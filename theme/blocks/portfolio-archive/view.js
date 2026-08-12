// Portfolio archive: client-side service filter + hover-to-play card video.

function initArchive(root) {
	const chips = root.querySelectorAll('[data-filter]');
	const items = [...root.querySelectorAll('.cular-parch__item')];
	const empty = root.querySelector('.cular-parch__empty');

	if (chips.length) {
		chips.forEach((chip) => {
			chip.addEventListener('click', () => {
				const filter = chip.dataset.filter;

				chips.forEach((c) => {
					const on = c === chip;
					c.classList.toggle('is-active', on);
					c.setAttribute('aria-pressed', on ? 'true' : 'false');
				});

				let shown = 0;
				items.forEach((item) => {
					const match = filter === '*' || item.dataset.tags.split(' ').includes(filter);
					// `hidden` rather than display:none so the card leaves the
					// accessibility tree too, not just the visual layout.
					item.hidden = !match;
					if (match) shown++;
				});

				if (empty) empty.hidden = shown > 0;
			});
		});
	}

	// Card videos ship with preload="none" so 45 of them don't each start
	// downloading on page load. Pull the file only when the user shows intent,
	// and pause on the way out so background tabs aren't decoding video.
	root.querySelectorAll('.cular-parch__video').forEach((video) => {
		const card = video.closest('.cular-parch__card');
		if (!card) return;

		const play = () => {
			if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
			video.preload = 'metadata';
			video.play().catch(() => {});
		};
		const stop = () => {
			video.pause();
			video.currentTime = 0;
		};

		card.addEventListener('mouseenter', play);
		card.addEventListener('focusin', play);
		card.addEventListener('mouseleave', stop);
		card.addEventListener('focusout', stop);
	});
}

document.querySelectorAll('[data-cular-portfolio-archive]').forEach(initArchive);
