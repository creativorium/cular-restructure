(function ($) {
  'use strict';

  // Wait for DOM to be ready
  $(document).ready(function() {
    const form = document.getElementById("form");
    if (!form) return; // Exit if form not found

    const steps = Array.from(document.querySelectorAll(".step"));
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    const stepLabel = document.getElementById("stepLabel");
    const stepName = document.getElementById("stepName");
    const bar = document.getElementById("bar");
    const validationBadge = document.getElementById("validationBadge");

    const reviewBox = document.getElementById("reviewBox");
    const successMessage = document.getElementById("successMessage");

    // Initialize phone input
    let iti = null;
    const phoneEl = document.getElementById("phoneInput");
    if (phoneEl && window.intlTelInput) {
      iti = window.intlTelInput(phoneEl, {
        initialCountry: "auto",
        geoIpLookup: (cb) => {
          fetch("https://ipapi.co/json")
            .then((res) => res.json())
            .then((data) => cb(data.country_code))
            .catch(() => cb("ID"));
        },
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.5/build/js/utils.js",
      });
    }

    let currentStep = 1;
    const totalSteps = 4;

    // Step UI
    function setStep(n) {
      currentStep = Math.max(1, Math.min(totalSteps, n));
      steps.forEach((s) => s.classList.toggle("hidden", Number(s.dataset.step) !== currentStep));

      const names = { 1: "Business", 2: "Goals", 3: "Details", 4: "Review" };
      stepLabel.textContent = `Step ${currentStep}`;
      stepName.textContent = names[currentStep] || "";

      bar.style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;
      prevBtn.disabled = currentStep === 1;

      nextBtn.textContent = currentStep === totalSteps ? "Finish" : "Next →";
      validationBadge.style.display = "none";

      if (currentStep === totalSteps) buildReview();
    }

    // Validation
    function validateStep1() {
      const emailEl = form.querySelector('input[type="email"][name="contact_email"]');
      const websiteEl = form.querySelector('input[name="website_url"]');

      if (emailEl && !emailEl.checkValidity()) {
        emailEl.reportValidity();
        return false;
      }
      if (websiteEl && websiteEl.value.trim() === "") {
        websiteEl.focus();
        validationBadge.style.display = "inline-flex";
        return false;
      }
      return true;
    }

    // Build Review (data collection)
    function buildReview() {
      const data = {};
      const fd = new FormData(form);

      for (const [k, val] of fd.entries()) {
        if (k in data) {
          if (!Array.isArray(data[k])) data[k] = [data[k]];
          data[k].push(val);
        } else {
          data[k] = val;
        }
      }

      data.form_type = 'seo';
      data.service = 'seo';
      data.service_label = 'SEO Optimisation';

      if (iti && phoneEl) {
        data.contact_phone_e164 = iti.getNumber();
        data.contact_phone_country = iti.getSelectedCountryData()?.iso2 || "";
        data.contact_phone_valid = iti.isValidNumber();
      }

      if (reviewBox) {
        reviewBox.innerHTML = `
          <strong>Service:</strong> ${data.service_label}<br/>
          <strong>Business:</strong> ${data.business_name || "—"}<br/>
          <strong>Contact:</strong> ${data.contact_name || "—"} (${data.contact_email || "—"})
        `;
      }

      return data;
    }

    // Submit Form
    function submitForm() {
      const data = buildReview();

      nextBtn.disabled = true;
      nextBtn.textContent = "Submitting...";

      if (typeof cular_intake_ajax !== 'undefined') {
        $.ajax({
          url: cular_intake_ajax.ajax_url,
          type: 'POST',
          data: {
            action: 'cular_intake_submit',
            nonce: cular_intake_ajax.nonce,
            form_data: JSON.stringify(data)
          },
          success: function(response) {
            if (response.success) {
              successMessage.style.display = 'block';
              reviewBox.style.display = 'none';
              nextBtn.style.display = 'none';
              prevBtn.style.display = 'none';
            } else {
              alert('Error: ' + (response.data || 'Submission failed'));
              nextBtn.disabled = false;
              nextBtn.textContent = "Finish";
            }
          },
          error: function() {
            alert('Submission failed. Please try again.');
            nextBtn.disabled = false;
            nextBtn.textContent = "Finish";
          }
        });
      } else {
        alert('Form submitted successfully!');
        successMessage.style.display = 'block';
        reviewBox.style.display = 'none';
        nextBtn.style.display = 'none';
        prevBtn.style.display = 'none';
      }
    }

    prevBtn.addEventListener("click", () => setStep(currentStep - 1));

    nextBtn.addEventListener("click", () => {
      if (currentStep === 1 && !validateStep1()) return;

      if (currentStep === totalSteps) {
        submitForm();
        return;
      }
      setStep(currentStep + 1);
    });

    // Init
    setStep(1);
  });

})(jQuery);
