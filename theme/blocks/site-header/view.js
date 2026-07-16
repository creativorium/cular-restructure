// Animated side menu — open/close with GSAP, staggered links.
import { gsap } from 'gsap';

function initHeader(root) {
	const menu = root.querySelector('[data-cular-menu]');
	const openBtn = root.querySelector('[data-cular-menu-open]');
	const closeBtn = root.querySelector('[data-cular-menu-close]');
	if (!menu || !openBtn) return;

	const links = menu.querySelectorAll('.cular-menu__nav a');

	const open = () => {
		menu.classList.add('is-open');
		menu.setAttribute('aria-hidden', 'false');
		document.documentElement.style.overflow = 'hidden';
		window.lenis?.stop();
		gsap.timeline()
			.to(menu, { clipPath: 'inset(0 0 0% 0)', duration: 0.7, ease: 'power4.inOut' })
			.from(links, { yPercent: 120, opacity: 0, duration: 0.5, stagger: 0.06, ease: 'power3.out' }, '-=0.25');
	};

	const close = () => {
		gsap.to(menu, {
			clipPath: 'inset(0 0 100% 0)',
			duration: 0.6,
			ease: 'power4.inOut',
			onComplete: () => {
				menu.classList.remove('is-open');
				menu.setAttribute('aria-hidden', 'true');
				document.documentElement.style.overflow = '';
				window.lenis?.start();
			},
		});
	};

	openBtn.addEventListener('click', open);
	closeBtn?.addEventListener('click', close);
	menu.querySelectorAll('.cular-menu__nav a').forEach((a) => a.addEventListener('click', close));
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && menu.classList.contains('is-open')) close();
	});
}

document.querySelectorAll('[data-cular-header]').forEach(initHeader);
