/**
 * Contact intake form — generic multi-step driver.
 *
 * Reads the step count from the form's data-total-steps, so the same script
 * can drive any simple step form that follows this markup.
 */
(function ($) {
  'use strict';

  $(document).ready(function () {
    const form = document.getElementById('form');
    if (!form || form.querySelector('input[name="form_type"]')?.value !== 'contact') return;

    const steps = Array.from(form.querySelectorAll('.step'));
    const totalSteps = Number(form.dataset.totalSteps) || steps.length;

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const stepLabel = document.getElementById('stepLabel');
    const stepName = document.getElementById('stepName');
    const bar = document.getElementById('bar');
    const badge = document.getElementById('validationBadge');
    const reviewBox = document.getElementById('reviewBox');
    const successMessage = document.getElementById('successMessage');

    const NAMES = { 1: 'Profile Data', 2: 'Goal & Timeline', 3: 'About You' };

    // International phone input, same as the other forms.
    let iti = null;
    const phoneEl = document.getElementById('phoneInput');
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
      if (stepName) stepName.textContent = NAMES[currentStep] || '';
      if (bar) bar.style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;
      if (prevBtn) prevBtn.disabled = currentStep === 1;
      if (nextBtn) nextBtn.textContent = currentStep === totalSteps ? 'Send' : 'Next';
      if (badge) badge.style.display = 'none';

      if (currentStep === totalSteps) buildReview();
    }

    function showError(msg) {
      if (!badge) return;
      badge.textContent = msg;
      badge.style.display = '';
    }

    function validateCurrent() {
      const step = steps.find((s) => Number(s.dataset.step) === currentStep);
      if (!step) return true;

      for (const field of step.querySelectorAll('[required]')) {
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
      return true;
    }

    function buildReview() {
      if (!reviewBox) return;
      const fd = new FormData(form);
      const rows = [];
      for (const [k, v] of fd.entries()) {
        if (!v || k === 'form_type' || k.startsWith('contact_phone_e164')) continue;
        const label = k.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
        rows.push(`<div class="review-row"><span>${label}</span><b>${String(v).replace(/</g, '&lt;')}</b></div>`);
      }
      reviewBox.innerHTML = rows.length ? `<h3>Review</h3>${rows.join('')}` : '';
    }

    function submitForm() {
      if (iti && phoneEl && phoneEl.value) {
        const e164 = document.getElementById('phoneE164');
        if (e164) e164.value = iti.getNumber();
      }

      if (typeof cular_intake_ajax === 'undefined') {
        showError('Form is not configured. Please email hello@cularcreative.com.');
        return;
      }

      // The plugin's handler expects the answers as a JSON `form_data` blob.
      const payload = {};
      for (const [k, v] of new FormData(form).entries()) {
        if (payload[k] !== undefined) {
          payload[k] = [].concat(payload[k], v);
        } else {
          payload[k] = v;
        }
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
          form.querySelector('.content').style.display = 'none';
          form.querySelector('.actions').style.display = 'none';
          if (successMessage) successMessage.style.display = '';
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

    form.addEventListener('submit', function (e) {
      e.preventDefault();
    });

    setStep(1);
  });
})(jQuery);
