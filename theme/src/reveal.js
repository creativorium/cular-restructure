// Reveal-on-scroll, and the hero's parallax drift.
//
// This used to be GSAP + ScrollTrigger. Those two are ~117KB minified and we
// were using them for exactly two effects: a fade-and-rise on scroll into view,
// and one transform tied to scroll position. IntersectionObserver plus a CSS
// transition does the first natively, and the second is six lines of rAF — so
// the whole dependency has been dropped. The easing curve below is matched to
// GSAP's power3.out so the motion is unchanged.
//
//   [data-cular-reveal]        the element itself eases up
//   [data-cular-reveal-items]  its direct children stagger in
//
// The hidden state lives in CSS behind `html.cular-js` (set by an inline script
// in the document head, see inc/enqueue.php) so that a page whose JS never
// arrives simply shows all its content instead of staying blank.

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const isMobile = window.matchMedia('(max-width: 780px)').matches;

export function initReveals() {
	const groups = document.querySelectorAll('[data-cular-reveal-items]');

	// Stagger is a per-child transition-delay; the CSS reads --reveal-i.
	groups.forEach((group) => {
		[...group.children].forEach((kid, i) => kid.style.setProperty('--reveal-i', i));
	});

	const targets = [...document.querySelectorAll('[data-cular-reveal]'), ...groups];

	if (reduceMotion || !('IntersectionObserver' in window)) {
		targets.forEach((el) => el.classList.add('is-revealed'));
		return;
	}

	const io = new IntersectionObserver(
		(entries) => {
			for (const entry of entries) {
				if (!entry.isIntersecting) continue;
				entry.target.classList.add('is-revealed');
				io.unobserve(entry.target); // once only, same as ScrollTrigger's `once: true`
			}
		},
		// Matches the old `start: 'top 88%'` — fire when the element's top has
		// risen past 88% of the viewport height.
		{ rootMargin: '0px 0px -12% 0px', threshold: 0 }
	);

	targets.forEach((el) => io.observe(el));
}

/** Subtle parallax drift on the hero video — desktop only, transform only. */
export function initHeroParallax() {
	if (reduceMotion || isMobile) return;

	const video = document.querySelector('.cular-hero__video');
	const hero = document.querySelector('.cular-hero');
	if (!video || !hero) return;

	let ticking = false;

	const update = () => {
		ticking = false;
		const height = hero.offsetHeight;
		if (!height) return;
		// 0 at the top of the hero, 1 once it has fully scrolled past.
		const progress = Math.min(1, Math.max(0, window.scrollY / height));
		video.style.transform = `translate3d(0, ${(progress * 8).toFixed(2)}%, 0)`;
	};

	const onScroll = () => {
		if (ticking) return;
		ticking = true;
		requestAnimationFrame(update);
	};

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll, { passive: true });
	update();
}

/**
 * Line-by-line text reveal — the "each line slides up from behind a mask"
 * effect, on any element marked [data-cular-split].
 *
 * Done with Range + getClientRects() rather than a wrapper-per-word: the browser
 * has already worked out where the real line breaks are after wrapping, so we
 * read them back instead of guessing. That means it stays correct at any width
 * and with any font, which a word-count heuristic does not.
 *
 * Deliberately not GSAP/SplitText: this is ~60 lines and a CSS transition, where
 * the library was 117KB. See docs/PROJECT.md §9.
 *
 * Accessibility and SEO: the original text is restored into an aria-hidden-free
 * structure — the element keeps its exact text content, so screen readers and
 * crawlers see the same string as before. Reduced-motion users skip the whole
 * thing.
 */
export function initSplitText() {
	const targets = document.querySelectorAll('[data-cular-split]');
	if (!targets.length) return;

	if (reduceMotion || !('IntersectionObserver' in window)) {
		targets.forEach((el) => el.classList.add('is-revealed'));
		return;
	}

	const split = (el) => {
		const text = el.textContent.replace(/\s+/g, ' ').trim();
		if (!text) return false;

		// Measure the real line boxes of the existing text.
		const node = el.firstChild;
		if (!node || node.nodeType !== Node.TEXT_NODE) return false;

		const range = document.createRange();
		const lines = [];
		let start = 0;
		let lastBottom = null;

		for (let i = 1; i <= node.length; i++) {
			range.setStart(node, start);
			range.setEnd(node, i);
			const rects = range.getClientRects();
			if (!rects.length) continue;

			const bottom = Math.round(rects[rects.length - 1].bottom);
			if (lastBottom === null) lastBottom = bottom;

			if (bottom !== lastBottom) {
				// i-1 is the first character of the new line.
				lines.push(node.data.slice(start, i - 1).trim());
				start = i - 1;
				lastBottom = bottom;
			}
		}
		lines.push(node.data.slice(start).trim());

		const usable = lines.filter(Boolean);
		if (usable.length < 1) return false;

		el.textContent = '';
		usable.forEach((line, i) => {
			const mask = document.createElement('span');
			mask.className = 'cular-split__line';
			const inner = document.createElement('span');
			inner.className = 'cular-split__inner';
			inner.style.setProperty('--line-i', i);
			inner.textContent = line;
			mask.appendChild(inner);
			el.appendChild(mask);
			// A space between lines keeps the accessible text readable as prose.
			if (i < usable.length - 1) el.appendChild(document.createTextNode(' '));
		});
		el.classList.add('is-split');
		return true;
	};

	const io = new IntersectionObserver(
		(entries) => {
			for (const entry of entries) {
				if (!entry.isIntersecting) continue;
				entry.target.classList.add('is-revealed');
				io.unobserve(entry.target);
			}
		},
		{ rootMargin: '0px 0px -10% 0px', threshold: 0 }
	);

	targets.forEach((el) => {
		// Split after fonts land: line breaks measured against a fallback face
		// are wrong the moment the real font swaps in.
		const run = () => {
			split(el);
			io.observe(el);
		};
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(run);
		else run();
	});
}
