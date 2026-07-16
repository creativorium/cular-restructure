// Global styles.
import './styles/main.scss';

// Motion stack (ported from the old Elementor custom code: GSAP + Lenis).
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

// Expose for any inline/block scripts that expect globals.
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// Smooth scroll — replaces the old CDN Lenis snippet.
const lenis = new Lenis({
	duration: 1.2,
	easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
	smoothWheel: true,
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

// Reveal-on-scroll for any element marked [data-cular-reveal].
function initReveals() {
	const els = document.querySelectorAll('[data-cular-reveal]');
	els.forEach((el) => {
		gsap.from(el, {
			opacity: 0,
			y: 40,
			duration: 0.9,
			ease: 'power3.out',
			scrollTrigger: { trigger: el, start: 'top 85%' },
		});
	});
}

if (document.readyState !== 'loading') initReveals();
else document.addEventListener('DOMContentLoaded', initReveals);

document.documentElement.classList.add('cular-ready');
