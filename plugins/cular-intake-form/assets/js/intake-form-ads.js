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

    // Credential wrappers
    const tiktokCredentialsWrap = document.getElementById("tiktokCredentialsWrap");
    const shopeeCredentialsWrap = document.getElementById("shopeeCredentialsWrap");

    const reviewBox = document.getElementById("reviewBox");
    const successMessage = document.getElementById("successMessage");

    // Ads conditional refs
    const pfMetaAds = document.getElementById("pf_meta_ads");
    const pfMetaBoost = document.getElementById("pf_meta_boost");
    const pfGoogleAds = document.getElementById("pf_google_ads");
    const pfTikTokAds = document.getElementById("pf_tiktok_ads");
    const pfShopeeOpt = document.getElementById("pf_shopee_opt");

    const conversionWrap = document.getElementById("conversionWrap");
    const objConversion = document.getElementById("obj_conversion");
    const boostObjectiveWrap = document.getElementById("boostObjectiveWrap");
    const tiktokObjectiveWrap = document.getElementById("tiktokObjectiveWrap");
    const tiktokShopWrap = document.getElementById("tiktokShopWrap");
    const landingPageWrap = document.getElementById("landingPageWrap");
    const tiktokObjective = document.getElementById("tiktokObjective");

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

    // Platform credentials visibility
    function updatePlatformCredentials() {
      tiktokCredentialsWrap?.classList.toggle("hidden", !pfTikTokAds?.checked);
      shopeeCredentialsWrap?.classList.toggle("hidden", !pfShopeeOpt?.checked);
    }

    // Ads conditional logic
    function updateAdsConditionalLogic() {
      // Show conversion for all platforms EXCEPT when ONLY Meta Boost is selected
      const onlyMetaBoost = pfMetaBoost?.checked &&
                           !pfMetaAds?.checked &&
                           !pfGoogleAds?.checked &&
                           !pfTikTokAds?.checked &&
                           !pfShopeeOpt?.checked;

      conversionWrap?.classList.toggle("hidden", onlyMetaBoost);
      if (onlyMetaBoost && objConversion?.checked) {
        objConversion.checked = false;
      }

      updatePlatformCredentials();

      // Boost objective only for boost post
      boostObjectiveWrap?.classList.toggle("hidden", !pfMetaBoost?.checked);
      if (!pfMetaBoost?.checked) {
        const sel = form.querySelector('select[name="boost_objective"]');
        if (sel) sel.value = "";
      }

      // TikTok objective only for TikTok
      tiktokObjectiveWrap?.classList.toggle("hidden", !pfTikTokAds?.checked);
      if (!pfTikTokAds?.checked && tiktokObjective) tiktokObjective.value = "";

      // TikTok shop objective only for TikTok
      tiktokShopWrap?.classList.toggle("hidden", !pfTikTokAds?.checked);
      if (!pfTikTokAds?.checked) {
        form.querySelectorAll('input[name="tiktok_shop_objectives"]').forEach(el => el.checked = false);
      }

      // Landing page only for Google Ads
      landingPageWrap?.classList.toggle("hidden", !pfGoogleAds?.checked);
      if (!pfGoogleAds?.checked) {
        const landSel = form.querySelector('select[name="ads_landing_ready"]');
        if (landSel) landSel.value = "";
      }
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

      data.form_type = 'ads';
      data.service = 'ads';
      data.service_label = 'Ads Management';

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

    // Events
    form.addEventListener("change", (e) => {
      if (e.target.name === "ads_platforms" || e.target.name === "ads_objectives") {
        updateAdsConditionalLogic();
      }
      if (e.target.id === "tiktokObjective") {
        updateAdsConditionalLogic();
      }
    });

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
    updateAdsConditionalLogic();
    updatePlatformCredentials();
    setStep(1);
  });

})(jQuery);
