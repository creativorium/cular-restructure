// Swap the poster for an inline video when "Play Me" is clicked.

document.querySelectorAll('[data-cular-play]').forEach((button) => {
	button.addEventListener('click', () => {
		const figure = button.closest('.cular-team__media');
		const src = button.dataset.video;
		if (!figure || !src || figure.querySelector('video')) return;

		const video = document.createElement('video');
		video.src = src;
		video.controls = true;
		video.autoplay = true;
		video.playsInline = true;

		figure.appendChild(video);
		button.classList.add('is-hidden');
		const poster = figure.querySelector('img');
		if (poster) poster.style.visibility = 'hidden';

		video.play().catch(() => {});
	});
});
