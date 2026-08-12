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
