// Testimonial videos show a first frame + play button; native controls only
// appear once the viewer starts it. Starting one pauses any other.
// (Slider behaviour is shared — see src/slider.js.)

document.querySelectorAll('[data-cular-video]').forEach((player) => {
	const video = player.querySelector('video');
	const button = player.querySelector('[data-cular-video-play]');
	if (!video || !button) return;

	const start = () => {
		document.querySelectorAll('[data-cular-video] video').forEach((other) => {
			if (other !== video && !other.paused) other.pause();
		});
		video.controls = true;
		button.classList.add('is-hidden');
		video.play().catch(() => {});
	};

	button.addEventListener('click', start);

	// Bring the button back when it finishes, so the card reads as a poster again.
	video.addEventListener('ended', () => {
		video.controls = false;
		video.load();
		button.classList.remove('is-hidden');
	});
});
