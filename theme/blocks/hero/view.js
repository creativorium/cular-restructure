// Fade the "waterfall" scroll indicator out once the user starts scrolling.
const indicator = document.querySelector('[data-cular-scroll]');

if (indicator) {
	const HIDE_AFTER = 50;
	const onScroll = () => {
		indicator.classList.toggle('is-hidden', window.scrollY > HIDE_AFTER);
	};
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
}
