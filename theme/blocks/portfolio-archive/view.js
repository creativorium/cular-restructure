// Portfolio archive: search + service filter, the feature/pair layout rhythm,
// progressive reveal on scroll, and hover-to-play card video.

const BATCH = 7; // one feature card + three pairs

function initArchive(root) {
	const grid = root.querySelector('[data-grid]');
	const items = [...root.querySelectorAll('.cular-parch__item')];
	const empty = root.querySelector('.cular-parch__empty');
	const countEl = root.querySelector('[data-count]');
	const sentinel = root.querySelector('[data-sentinel]');

	const searchEl = root.querySelector('[data-search]');
	const chips = [...root.querySelectorAll('[data-filter]')];
	const toggle = root.querySelector('[data-services-toggle]');
	const panel = root.querySelector('[data-services-panel]');
	const current = root.querySelector('[data-services-current]');

	if (!grid) return;

	let filter = '*';
	let query = '';
	let shown = BATCH;
	let matches = items;

	/**
	 * Apply the feature/pair rhythm to the VISIBLE cards.
	 *
	 * This is why the pattern is set here and not with a CSS :nth-child rule:
	 * nth-child counts every card including the hidden ones, so the moment a
	 * search or filter hid anything the wide cards would land in the wrong
	 * places and the rhythm would fall apart.
	 */
	const applyRhythm = (visible) => {
		visible.forEach((item, i) => item.classList.toggle('is-wide', i % 3 === 0));
	};

	const render = () => {
		matches = items.filter((item) => {
			const tagOk = filter === '*' || item.dataset.tags.split(' ').includes(filter);
			const textOk = !query || item.dataset.search.includes(query);
			return tagOk && textOk;
		});

		const visible = matches.slice(0, shown);
		const visibleSet = new Set(visible);

		items.forEach((item) => {
			const on = visibleSet.has(item);
			// `hidden` rather than display:none so hidden cards leave the
			// accessibility tree too, not just the layout.
			item.hidden = !on;
		});

		applyRhythm(visible);

		if (empty) empty.hidden = matches.length > 0;
		// aria-live, so a screen reader hears the result change — otherwise
		// typing in the search box appears to do nothing.
		if (countEl) {
			countEl.textContent = matches.length ? `Showing ${visible.length} of ${matches.length}` : '';
		}
		if (sentinel) sentinel.hidden = visible.length >= matches.length;
	};

	const reset = () => {
		shown = BATCH;
		render();
	};

	// --- search ---
	if (searchEl) {
		let t;
		searchEl.addEventListener('input', () => {
			// Debounced: each pass re-lays out the grid, and doing that on every
			// keystroke of a fast typist is wasted work.
			clearTimeout(t);
			t = setTimeout(() => {
				query = searchEl.value.trim().toLowerCase();
				reset();
			}, 160);
		});
	}

	// --- service filter ---
	const closePanel = () => {
		if (!panel || !toggle) return;
		panel.hidden = true;
		toggle.setAttribute('aria-expanded', 'false');
	};

	if (toggle && panel) {
		toggle.addEventListener('click', (e) => {
			e.stopPropagation();
			const open = panel.hidden;
			panel.hidden = !open;
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});

		document.addEventListener('click', (e) => {
			if (!panel.hidden && !panel.contains(e.target) && !toggle.contains(e.target)) closePanel();
		});
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') closePanel();
		});
	}

	chips.forEach((chip) => {
		chip.addEventListener('click', () => {
			filter = chip.dataset.filter;
			chips.forEach((c) => {
				const on = c === chip;
				c.classList.toggle('is-active', on);
				c.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			if (current) {
				// Strip the trailing count from the chip label.
				current.textContent =
					filter === '*' ? 'All services' : chip.textContent.replace(/\s*\d+\s*$/, '').trim();
			}
			closePanel();
			reset();
		});
	});

	// --- progressive reveal ---
	//
	// Every card is already in the HTML — 43 of them is small, and it means the
	// whole catalogue is crawlable and searchable with no round trip. This only
	// controls how many are *shown*, so scrolling keeps uncovering work instead
	// of dropping every card and its image on the page at once.
	if (sentinel && 'IntersectionObserver' in window) {
		const io = new IntersectionObserver(
			(entries) => {
				for (const entry of entries) {
					if (!entry.isIntersecting || shown >= matches.length) continue;
					shown += BATCH;
					render();
				}
			},
			// Generous margin so the next batch is already in place by the time
			// the reader gets there, rather than popping in under them.
			{ rootMargin: '600px 0px' }
		);
		io.observe(sentinel);
	} else {
		shown = items.length;
	}

	render();

	// Card videos ship with preload="none"/"metadata" so they don't each start
	// downloading on load. Pull the file only on intent, and stop on the way out
	// so a background tab isn't decoding video.
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
