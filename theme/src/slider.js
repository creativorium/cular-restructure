// Shared scroll-snap slider used by any block with [data-cular-slider].
// Arrows + dots, no library. Dots map to real pages (accounting for how many
// cards fit at once) and arrows disable at the ends.

export function initSlider(root) {
	const track = root.querySelector('[data-slider-track]');
	const prev = root.querySelector('[data-slider-prev]');
	const next = root.querySelector('[data-slider-next]');
	const dotsWrap = root.querySelector('[data-slider-dots]');
	if (!track) return;

	const slides = [...track.children];
	if (slides.length < 2) {
		root.querySelector('[data-slider-dots]')?.remove();
		prev?.remove();
		next?.remove();
		return;
	}

	const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
	const slideSpan = () => slides[0].getBoundingClientRect().width + gap;
	const perView = () => Math.max(1, Math.round(track.clientWidth / slideSpan()));
	const pageCount = () => Math.max(1, slides.length - perView() + 1);
	const currentIndex = () => Math.round(track.scrollLeft / slideSpan());

	let dots = [];

	const goTo = (i) => {
		const clamped = Math.max(0, Math.min(i, pageCount() - 1));
		track.scrollTo({ left: slides[clamped].offsetLeft - track.offsetLeft, behavior: 'smooth' });
	};

	const sync = () => {
		const i = Math.max(0, Math.min(currentIndex(), pageCount() - 1));
		dots.forEach((d, n) => d.classList.toggle('is-active', n === i));
		if (prev) prev.disabled = i <= 0;
		if (next) next.disabled = i >= pageCount() - 1;
	};

	const buildDots = () => {
		if (!dotsWrap) return;
		dotsWrap.innerHTML = '';
		dots = Array.from({ length: pageCount() }, (_, i) => {
			const b = document.createElement('button');
			b.type = 'button';
			b.className = 'cular-tst__dot';
			b.setAttribute('aria-label', `Go to slide ${i + 1}`);
			b.addEventListener('click', () => goTo(i));
			dotsWrap.appendChild(b);
			return b;
		});
	};

	prev?.addEventListener('click', () => goTo(currentIndex() - 1));
	next?.addEventListener('click', () => goTo(currentIndex() + 1));
	track.addEventListener('scroll', () => window.requestAnimationFrame(sync), { passive: true });
	window.addEventListener('resize', () => {
		buildDots();
		sync();
	});

	buildDots();
	sync();
}

export function initSliders() {
	document.querySelectorAll('[data-cular-slider]').forEach(initSlider);
}
