// Lightweight scroll-snap slider: arrows + dots, no library.
// The original had no way to navigate these rows, so we add both.

function initSlider(root) {
	const track = root.querySelector('[data-slider-track]');
	const prev = root.querySelector('[data-slider-prev]');
	const next = root.querySelector('[data-slider-next]');
	const dotsWrap = root.querySelector('[data-slider-dots]');
	if (!track) return;

	const slides = [...track.children];
	if (slides.length < 2) {
		root.querySelector('.cular-tst__nav')?.remove();
		return;
	}

	// How many slides fit at once (so dots represent real pages).
	const perView = () => {
		const slideW = slides[0].getBoundingClientRect().width;
		return Math.max(1, Math.round(track.clientWidth / (slideW + 12)));
	};
	const pageCount = () => Math.max(1, slides.length - perView() + 1);

	// Build dots.
	let dots = [];
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

	const currentIndex = () => {
		const slideW = slides[0].getBoundingClientRect().width + 12;
		return Math.round(track.scrollLeft / slideW);
	};

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

document.querySelectorAll('[data-cular-slider]').forEach(initSlider);
