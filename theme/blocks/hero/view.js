// Hero behaviour: swap the background video for the portrait cut on mobile,
// and fade the "waterfall" scroll indicator once the user scrolls.

// --- Responsive background video -------------------------------------------
const video = document.querySelector('.cular-hero__video');

if (video) {
	const MOBILE = '(max-width: 900px)';
	const mq = window.matchMedia(MOBILE);

	const applySource = () => {
		const wanted = mq.matches ? video.dataset.srcPortrait : video.dataset.srcLandscape;
		if (!wanted) return;
		// Compare filenames so we don't reload the same clip on every resize.
		const current = (video.currentSrc || video.src || '').split('/').pop();
		if (current === wanted.split('/').pop()) return;

		video.src = wanted;
		video.load();
		// Autoplay must be muted+inline to be allowed on mobile.
		video.play().catch(() => {});
	};

	applySource();
	// Only react when crossing the breakpoint, not on every resize tick.
	if (mq.addEventListener) mq.addEventListener('change', applySource);
	else mq.addListener(applySource);
}

// --- Scroll indicator -------------------------------------------------------
const indicator = document.querySelector('[data-cular-scroll]');

if (indicator) {
	const HIDE_AFTER = 50;
	const onScroll = () => {
		indicator.classList.toggle('is-hidden', window.scrollY > HIDE_AFTER);
	};
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
}
