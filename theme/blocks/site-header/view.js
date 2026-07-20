// Simplified port of the old MDW side-menu:
// the pill button flips Menu <-> Close and the side panel expands out of the
// button. Plain JS (no jQuery) + CSS transitions.
//
// Note: the original hid the button after scrolling; we deliberately keep it
// visible so the menu is always reachable.

function initHeader(header) {
	const toggle = header.querySelector('[data-cular-menu-toggle]');
	const menu = header.querySelector('[data-cular-menu]');
	if (!toggle || !menu) return;

	// Measure the button relative to the PANEL so the closed clip-path sits
	// exactly on the button, even though the panel only covers the right side.
	const measure = () => {
		const b = toggle.getBoundingClientRect();
		const p = menu.getBoundingClientRect();
		if (!p.width || !p.height) return;
		const top = Math.max(0, b.top - p.top);
		const right = Math.max(0, p.right - b.right);
		const bottom = Math.max(0, p.bottom - b.bottom);
		const left = Math.max(0, b.left - p.left);
		header.style.setProperty('--btn-top', `${Math.round(top)}px`);
		header.style.setProperty('--btn-right', `${Math.round(right)}px`);
		header.style.setProperty('--btn-bottom', `${Math.round(bottom)}px`);
		header.style.setProperty('--btn-left', `${Math.round(left)}px`);
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
}

document.querySelectorAll('[data-cular-header]').forEach(initHeader);
