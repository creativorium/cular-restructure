/**
 * Generic multi-step intake form driver.
 *
 * Every form before this one shipped its own near-identical copy of the same
 * stepper + validate + submit logic (intake-form.js, -ads, -seo, -contact),
 * which is why adding a form meant writing 200 lines of JS you had already
 * written three times. This driver is form-agnostic: it reads everything it
 * needs from the markup, so a new form is a template file and nothing else.
 *
 * Markup contract — see templates/intake-form-social-media.php for a worked
 * example:
 *
 *   <form id="form" data-generic-driver data-total-steps="5">
 *     <input type="hidden" name="form_type" value="social-media">
 *     <div class="step" data-step="1" data-step-name="Business">…</div>
 *     …
 *
 * Optional chrome, all looked up by id and skipped when absent:
 *   #prevBtn #nextBtn #stepLabel #stepName #bar #validationBadge
 *   #reviewBox #successMessage #phoneInput #phoneE164
 */
(function ($) {
	'use strict';

	$(document).ready(function () {
		document.querySelectorAll('form[data-generic-driver]').forEach(initForm);
	});

	function initForm(form) {
		const steps = Array.from(form.querySelectorAll('.step'));
		const totalSteps = Number(form.dataset.totalSteps) || steps.length || 1;

		const $id = (id) => form.querySelector('#' + id) || document.getElementById(id);
		const prevBtn = $id('prevBtn');
		const nextBtn = $id('nextBtn');
		const stepLabel = $id('stepLabel');
		const stepName = $id('stepName');
		const bar = $id('bar');
		const badge = $id('validationBadge');
		const reviewBox = $id('reviewBox');
		const successMessage = $id('successMessage');

		// Step names come from the markup rather than a hardcoded map, so the
		// template stays the single source of truth for its own wording.
		const names = {};
		steps.forEach((s) => {
			names[Number(s.dataset.step)] = s.dataset.stepName || '';
		});

		// International phone input, matching the hand-written forms.
		let iti = null;
		const phoneEl = $id('phoneInput');
		if (phoneEl && window.intlTelInput) {
			iti = window.intlTelInput(phoneEl, {
				initialCountry: 'auto',
				geoIpLookup: (cb) => {
					fetch('https://ipapi.co/json')
						.then((r) => r.json())
						.then((d) => cb(d.country_code))
						.catch(() => cb('ID'));
				},
				utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.5/build/js/utils.js',
			});
		}

		let currentStep = 1;

		function setStep(n) {
			currentStep = Math.max(1, Math.min(totalSteps, n));
			steps.forEach((s) => s.classList.toggle('hidden', Number(s.dataset.step) !== currentStep));

			if (stepLabel) stepLabel.textContent = `Step ${currentStep}`;
			if (stepName) stepName.textContent = names[currentStep] || '';
			if (bar) bar.style.width = totalSteps > 1 ? `${((currentStep - 1) / (totalSteps - 1)) * 100}%` : '100%';
			if (prevBtn) prevBtn.disabled = currentStep === 1;
			if (nextBtn) nextBtn.textContent = currentStep === totalSteps ? 'Send' : 'Next';
			if (badge) badge.style.display = 'none';

			// Move focus to the new step so keyboard and screen-reader users are
			// not left at the bottom of the form after every "Next".
			const step = steps.find((s) => Number(s.dataset.step) === currentStep);
			if (step) {
				const first = step.querySelector('input, select, textarea');
				if (first) first.focus({ preventScroll: true });
				step.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}

			if (currentStep === totalSteps) buildReview();
		}

		function showError(msg) {
			if (!badge) {
				window.alert(msg);
				return;
			}
			badge.textContent = msg;
			badge.style.display = '';
		}

		function validateCurrent() {
			const step = steps.find((s) => Number(s.dataset.step) === currentStep);
			if (!step) return true;

			// Radio and checkbox groups are required as a GROUP — checking each
			// input's own .value would demand every box be ticked.
			const groups = new Set();
			for (const field of step.querySelectorAll('[required]')) {
				if (field.type === 'radio' || field.type === 'checkbox') {
					groups.add(field.name);
					continue;
				}
				if (!field.value.trim()) {
					showError('Please complete the required fields.');
					field.focus();
					return false;
				}
				if (field.type === 'email' && !/^[^@\s]+@[^@\s]+\.[^@\s]{2,}$/.test(field.value.trim())) {
					showError('Please enter a valid email address.');
					field.focus();
					return false;
				}
			}

			for (const name of groups) {
				if (!step.querySelector(`[name="${CSS.escape(name)}"]:checked`)) {
					showError('Please answer the required questions.');
					const first = step.querySelector(`[name="${CSS.escape(name)}"]`);
					if (first) first.focus();
					return false;
				}
			}

			return true;
		}

		function buildReview() {
			if (!reviewBox) return;
			const rows = [];
			const seen = {};

			for (const [k, v] of new FormData(form).entries()) {
				if (!v || k === 'form_type' || k === 'contact_phone_e164') continue;
				// Checkbox groups arrive once per ticked box; collapse them.
				if (seen[k] !== undefined) {
					rows[seen[k]].values.push(v);
					continue;
				}
				seen[k] = rows.length;
				rows.push({ key: k, values: [v] });
			}

			const esc = (s) => String(s).replace(/[<>&]/g, (c) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c]));
			const label = (k) => k.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());

			reviewBox.innerHTML = rows.length
				? `<h3>Review</h3>${rows
						.map((r) => `<div class="review-row"><span>${esc(label(r.key))}</span><b>${esc(r.values.join(', '))}</b></div>`)
						.join('')}`
				: '';
		}

		function submitForm() {
			if (iti && phoneEl && phoneEl.value) {
				const e164 = $id('phoneE164');
				if (e164) e164.value = iti.getNumber();
			}

			if (typeof cular_intake_ajax === 'undefined') {
				showError('Form is not configured. Please email hello@cularcreative.com.');
				return;
			}

			// The plugin's handler expects the answers as a JSON `form_data` blob.
			const payload = {};
			for (const [k, v] of new FormData(form).entries()) {
				if (payload[k] !== undefined) payload[k] = [].concat(payload[k], v);
				else payload[k] = v;
			}

			nextBtn.disabled = true;
			nextBtn.textContent = 'Sending…';

			$.ajax({
				url: cular_intake_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'cular_intake_submit',
					nonce: cular_intake_ajax.nonce,
					form_data: JSON.stringify(payload),
				},
			})
				.done(function (response) {
					if (response && response.success === false) {
						nextBtn.disabled = false;
						nextBtn.textContent = 'Send';
						showError('Error: ' + (response.data || 'Submission failed.'));
						return;
					}
					const content = form.querySelector('.content');
					const actions = form.querySelector('.actions');
					if (content) content.style.display = 'none';
					if (actions) actions.style.display = 'none';
					if (successMessage) successMessage.style.display = '';
					form.closest('.cular-intake-wrap')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
				})
				.fail(function () {
					nextBtn.disabled = false;
					nextBtn.textContent = 'Send';
					showError('Something went wrong. Please try again, or email hello@cularcreative.com.');
				});
		}

		nextBtn?.addEventListener('click', function () {
			if (!validateCurrent()) return;
			if (currentStep === totalSteps) submitForm();
			else setStep(currentStep + 1);
		});

		prevBtn?.addEventListener('click', function () {
			setStep(currentStep - 1);
		});

		// Enter should advance a step, not submit the form from step 1.
		form.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
				e.preventDefault();
				nextBtn?.click();
			}
		});

		form.addEventListener('submit', function (e) {
			e.preventDefault();
		});

		setStep(1);
	}
})(jQuery);
