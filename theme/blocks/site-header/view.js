// Simplified port of the old MDW side-menu:
// the pill button flips Menu <-> Close and the panel expands out of the button.
// Plain JS (no jQuery) + CSS transitions.

function initHeader(header) {
	const toggle = header.querySelector('[data-cular-menu-toggle]');
	const menu = header.querySelector('[data-cular-menu]');
	if (!toggle || !menu) return;

	// Measure the button so the panel's closed clip-path matches it exactly.
	const measure = () => {
		const r = toggle.getBoundingClientRect();
		header.style.setProperty('--btn-top', `${Math.round(r.top)}px`);
		header.style.setProperty('--btn-right', `${Math.round(window.innerWidth - r.right)}px`);
		header.style.setProperty('--btn-w', `${Math.round(r.width)}px`);
		header.style.setProperty('--btn-h', `${Math.round(r.height)}px`);
	};
	measure();
	window.addEventListener('resize', measure);

	const isOpen = () => header.classList.contains('is-open');

	const open = () => {
		measure();
		header.classList.add('is-open');
		toggle.setAttribute('aria-expanded', 'true');
		menu.setAttribute('aria-hidden', 'false');
		document.documentElement.style.overflow = 'hidden';
		window.lenis?.stop();
	};

	const close = () => {
		header.classList.remove('is-open');
		toggle.setAttribute('aria-expanded', 'false');
		menu.setAttribute('aria-hidden', 'true');
		document.documentElement.style.overflow = '';
		window.lenis?.start();
	};

	toggle.addEventListener('click', () => (isOpen() ? close() : open()));

	// Close when a link is followed, on Escape, or on outside click.
	menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', close));
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && isOpen()) close();
	});
	document.addEventListener('click', (e) => {
		if (isOpen() && !menu.contains(e.target) && !toggle.contains(e.target)) close();
	});

	// Hide the header chrome after scrolling past the fold.
	const HIDE_AFTER = 100;
	const onScroll = () => {
		if (isOpen()) return;
		header.classList.toggle('is-hidden', window.scrollY > HIDE_AFTER);
	};
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
}

document.querySelectorAll('[data-cular-header]').forEach(initHeader);
