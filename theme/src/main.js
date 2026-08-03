// Global styles.
import './styles/main.scss';
import './styles/pages.scss';
import './styles/single-post.scss';

// Motion stack (ported from the old Elementor custom code: GSAP + Lenis).
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { initSliders } from './slider.js';

gsap.registerPlugin(ScrollTrigger);

// Expose for any inline/block scripts that expect globals.
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

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

// Keep ScrollTrigger in sync with Lenis.
lenis.on('scroll', ScrollTrigger.update);
gsap.ticker.add((time) => lenis.raf(time * 1000));
gsap.ticker.lagSmoothing(0);

// Pull in every block's SCSS + optional view.js automatically.
const blockStyles = import.meta.glob('../blocks/**/*.scss', { eager: true });
void blockStyles;
const blockScripts = import.meta.glob('../blocks/**/view.js', { eager: true });
void blockScripts;

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const isMobile = window.matchMedia('(max-width: 780px)').matches;

/**
 * Reveal-on-scroll.
 *  - [data-cular-reveal]        : the element itself eases up
 *  - [data-cular-reveal-items]  : its direct children stagger in
 * Motion is lighter on mobile and skipped entirely for reduced-motion users.
 */
function initReveals() {
	if (reduceMotion) return;

	const shift = isMobile ? 18 : 36;
	const dur = isMobile ? 0.6 : 0.85;

	document.querySelectorAll('[data-cular-reveal]').forEach((el) => {
		gsap.from(el, {
			opacity: 0,
			y: shift,
			duration: dur,
			ease: 'power3.out',
			scrollTrigger: { trigger: el, start: 'top 88%', once: true },
		});
	});

	document.querySelectorAll('[data-cular-reveal-items]').forEach((group) => {
		const kids = group.children;
		if (!kids.length) return;
		gsap.from(kids, {
			opacity: 0,
			y: shift,
			duration: dur,
			ease: 'power3.out',
			stagger: isMobile ? 0.06 : 0.1,
			scrollTrigger: { trigger: group, start: 'top 88%', once: true },
		});
	});
}

// Subtle parallax drift on hero video — desktop only, cheap (transform only).
function initHeroParallax() {
	if (reduceMotion || isMobile) return;
	const video = document.querySelector('.cular-hero__video');
	if (!video) return;
	gsap.to(video, {
		yPercent: 8,
		ease: 'none',
		scrollTrigger: {
			trigger: '.cular-hero',
			start: 'top top',
			end: 'bottom top',
			scrub: true,
		},
	});
}

function boot() {
	initReveals();
	initHeroParallax();
	initSliders();
	ScrollTrigger.refresh();
}

if (document.readyState !== 'loading') boot();
else document.addEventListener('DOMContentLoaded', boot);

document.documentElement.classList.add('cular-ready');
