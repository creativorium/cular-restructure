// Simplified port of the old MDW side-menu: the pill button flips Menu <->
// Close and the side panel expands out of the button. Plain JS, CSS transitions.
//
// Note: the original hid the button after scrolling; we deliberately keep it
// visible so the menu is always reachable.

function initHeader(header) {
	const toggle = header.querySelector('[data-cular-menu-toggle]');
	const menu = header.querySelector('[data-cular-menu]');
	if (!toggle || !menu) return;

	const fullWidth = window.matchMedia('(max-width: 900px)');

	// --- Measurement -------------------------------------------------------
	//
	// The closed clip-path has to sit exactly on the pill, so we measure the
	// button relative to the PANEL (the panel only covers the right side).
	//
	// Measuring is deliberately kept OUT of open(): writing --btn-* and adding
	// .is-open in the same frame changes the transition's start value at the
	// same moment as its end value, and the browser then sometimes skips the
	// interpolation entirely — the panel snaps open instead of growing. That
	// was the intermittent glitch. Instead we keep the vars always current and
	// only ever re-measure while the menu is CLOSED.

	let dirty = false;

	const measure = () => {
		const b = toggle.getBoundingClientRect();
		const p = menu.getBoundingClientRect();
		if (!p.width || !p.height || !b.height) return;
		header.style.setProperty('--btn-top', `${Math.round(Math.max(0, b.top - p.top))}px`);
		header.style.setProperty('--btn-right', `${Math.round(Math.max(0, p.right - b.right))}px`);
		header.style.setProperty('--btn-bottom', `${Math.round(Math.max(0, p.bottom - b.bottom))}px`);
		header.style.setProperty('--btn-left', `${Math.round(Math.max(0, b.left - p.left))}px`);
		header.style.setProperty('--btn-h', `${Math.round(b.height)}px`);
		dirty = false;
	};

	const isOpen = () => header.classList.contains('is-open');

	// Re-measuring while the panel is open would animate the clip-path to a new
	// origin mid-flight (mobile URL-bar show/hide fires resize constantly), so
	// defer it until the menu closes.
	const remeasure = () => {
		if (isOpen()) {
			dirty = true;
			return;
		}
		measure();
	};

	measure();

	// Enable the clip-path transition only after the first measurement, so the
	// correction from the CSS placeholder values isn't animated on load.
	requestAnimationFrame(() => requestAnimationFrame(() => header.classList.add('is-ready')));

	// The pill's width depends on Montserrat; with font-display:swap the font
	// lands after first paint and the button changes size. Without this the
	// closed clip-path stays parked on the fallback-font geometry and a sliver
	// of the panel gradient peeks out beside the button.
	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(remeasure);
	}

	// Coalesce resize bursts into one measurement per frame.
	let raf = 0;
	const onResize = () => {
		if (raf) return;
		raf = requestAnimationFrame(() => {
			raf = 0;
			remeasure();
		});
	};
	window.addEventListener('resize', onResize, { passive: true });
	if ('ResizeObserver' in window) new ResizeObserver(onResize).observe(toggle);

	// --- Open / close ------------------------------------------------------

	const open = () => {
		if (isOpen()) return;

		// If a measurement was deferred, apply it and force a style flush so the
		// browser commits the new closed geometry as the transition's start
		// value BEFORE .is-open lands.
		if (dirty) {
			measure();
			void menu.offsetWidth;
		}

		// Promote for the duration of the animation only — leaving will-change
		// on permanently keeps a compositor layer alive for a panel that is
		// hidden 99% of the time.
		menu.style.willChange = 'clip-path';

		header.classList.add('is-open');
		toggle.setAttribute('aria-expanded', 'true');
		menu.setAttribute('aria-hidden', 'false');

		// Full-width panel = nothing meaningful left to scroll behind it, and
		// Lenis happily keeps smooth-scrolling the page under an open menu.
		if (fullWidth.matches) {
			document.documentElement.classList.add('cular-menu-open');
			window.lenis?.stop();
		}
	};

	const close = () => {
		if (!isOpen()) return;

		menu.style.willChange = 'clip-path';
		header.classList.remove('is-open');
		toggle.setAttribute('aria-expanded', 'false');
		menu.setAttribute('aria-hidden', 'true');

		document.documentElement.classList.remove('cular-menu-open');
		window.lenis?.start();

		if (dirty) requestAnimationFrame(measure);
	};

	menu.addEventListener('transitionend', (e) => {
		if (e.target === menu && e.propertyName === 'clip-path') menu.style.willChange = '';
	});

	toggle.addEventListener('click', (e) => {
		e.stopPropagation();
		isOpen() ? close() : open();
	});

	// Close when a link is followed, on Escape, or on outside click.
	menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', close));
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') close();
	});
	document.addEventListener('click', (e) => {
		if (isOpen() && !menu.contains(e.target)) close();
	});
}

document.querySelectorAll('[data-cular-header]').forEach(initHeader);
