// Global styles.
import './styles/main.scss';
import './styles/pages.scss';
import './styles/single-post.scss';

// Motion stack. GSAP + ScrollTrigger used to live here too, at ~117KB minified
// for a fade-in and one parallax transform; both now run on IntersectionObserver
// and rAF in ./reveal.js. Lenis stays — it is the smooth-scroll feel and only
// ~15KB. Nothing in the theme or in any page's content referenced window.gsap.
import Lenis from 'lenis';
import { initSliders } from './slider.js';
import { initReveals, initHeroParallax, initSplitText } from './reveal.js';

// Smooth scroll — replaces the old CDN Lenis snippet.
const lenis = new Lenis({
	// Lower duration = snappier settle; higher multiplier = more travel per wheel tick.
	duration: 0.75,
	easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
	smoothWheel: true,
	wheelMultiplier: 1.25,
	touchMultiplier: 1.8,
});
window.lenis = lenis;

// Lenis needs a rAF pump; GSAP's ticker used to provide it.
const raf = (time) => {
	lenis.raf(time);
	requestAnimationFrame(raf);
};
requestAnimationFrame(raf);

// Pull in every block's SCSS + optional view.js automatically.
const blockStyles = import.meta.glob('../blocks/**/*.scss', { eager: true });
void blockStyles;
const blockScripts = import.meta.glob('../blocks/**/view.js', { eager: true });
void blockScripts;

/**
 * Two bits of scroll-linked chrome, on ONE listener and one rAF:
 *  - the "waterfall" scroll indicator fades out past the fold (homepage +
 *    About hero),
 *  - the fixed header gains its glass plate once the page leaves the top.
 *
 * They were two independent scroll handlers, each toggling a class on every
 * single scroll event. Coalescing them into one rAF-throttled pass that only
 * touches the DOM when a threshold is actually crossed keeps scrolling cheap.
 */
function initScrollChrome() {
	const indicator = document.querySelector('[data-cular-scroll]');
	const header = document.querySelector('.cular-header');
	if (!indicator && !header) return;

	let ticking = false;
	let hidden = null;
	let scrolled = null;

	const update = () => {
		ticking = false;
		const y = window.scrollY;

		if (indicator) {
			const next = y > 50;
			if (next !== hidden) {
				hidden = next;
				indicator.classList.toggle('is-hidden', next);
			}
		}
		if (header) {
			const next = y > 24;
			if (next !== scrolled) {
				scrolled = next;
				header.classList.toggle('is-scrolled', next);
			}
		}
	};

	window.addEventListener(
		'scroll',
		() => {
			if (ticking) return;
			ticking = true;
			requestAnimationFrame(update);
		},
		{ passive: true }
	);
	update();
}

function boot() {
	initReveals();
	initSplitText();
	initHeroParallax();
	initScrollChrome();
	initSliders();
}

if (document.readyState !== 'loading') boot();
else document.addEventListener('DOMContentLoaded', boot);

document.documentElement.classList.add('cular-ready');
