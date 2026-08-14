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
    const pathLabel = document.getElementById("pathLabel");
    const bar = document.getElementById("bar");
    const validationBadge = document.getElementById("validationBadge");
  
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
            .catch(() => cb("US"));
        },
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.5/build/js/utils.js",
      });
    }
  
    // Domain logic
    const hasDomain = document.getElementById("hasDomain");
    const domainNameWrap = document.getElementById("domainNameWrap");
    const domainProviderWrap = document.getElementById("domainProviderWrap");
    const domainCredBox = document.getElementById("domainCredBox");
    const domainInviteInstead = document.getElementById("domainInviteInstead");
    const domainUserWrap = document.getElementById("domainUserWrap");
    const domainPassWrap = document.getElementById("domainPassWrap");
  
    // Hosting logic
    const hasHosting = document.getElementById("hasHosting");
    const hostingProviderWrap = document.getElementById("hostingProviderWrap");
    const hostingCredBox = document.getElementById("hostingCredBox");
    const hostingInviteInstead = document.getElementById("hostingInviteInstead");
    const hostingUserWrap = document.getElementById("hostingUserWrap");
    const hostingPassWrap = document.getElementById("hostingPassWrap");
  
    // Sections by path
    const maintenanceSection = document.getElementById("maintenanceSection");
    const strategySection = document.getElementById("strategySection");
    const fullSection = document.getElementById("fullSection");
    const unsureSection = document.getElementById("unsureSection");
  
    // Strategy optional links
    const needAds = document.getElementById("needAds");
    const needSEO = document.getElementById("needSEO");
    const serviceLinks = document.getElementById("serviceLinks");
    const adsLink = document.getElementById("adsLink");
    const seoLink = document.getElementById("seoLink");
  
    // Full path conditional fields
    const hasBrandkit = document.getElementById("hasBrandkit");
    const brandkitUploadWrap = document.getElementById("brandkitUploadWrap");
    const fontsColorsWrap = document.getElementById("fontsColorsWrap");
  
    const emailRemarketing = document.getElementById("emailRemarketing");
    const emailPlatformWrap = document.getElementById("emailPlatformWrap");
  
    const isEcom = document.getElementById("isEcom");
    const paymentGatewayWrap = document.getElementById("paymentGatewayWrap");
    const hasPayment = document.getElementById("hasPayment");
    const productsWrap = document.getElementById("productsWrap");
    const paymentProviderWrap = document.getElementById("paymentProviderWrap");
  
    const reviewBox = document.getElementById("reviewBox");
    const successMessage = document.getElementById("successMessage");
  
    function hideMetaNotes() {
      document.querySelectorAll(".meta-note").forEach((el) => el.remove());
    }
  
    let currentStep = 1;
    const totalSteps = 5;

    // Optional: a shortcode can lock the form to one service
    // (e.g. [cular_intake_form type="web-development" service="dev"]).
    // When set, we pre-select that service and skip the picker (step 1).
    const preselectService = (form.dataset.preselect || "").trim();
    const preselectRadio = preselectService
      ? form.querySelector('input[name="service"][value="' + preselectService + '"]')
      : null;
    if (preselectRadio) preselectRadio.checked = true;
    const minStep = preselectRadio ? 2 : 1;

    // Real service page URLs
    if (adsLink) adsLink.href = "https://cularcreative.com/services/ads";
    if (seoLink) seoLink.href = "https://cularcreative.com/services/seo";

    // Step UI
    function setStep(n) {
      currentStep = Math.max(minStep, Math.min(totalSteps, n));
      steps.forEach((s) => s.classList.toggle("hidden", Number(s.dataset.step) !== currentStep));
  
      const names = { 1: "Service", 2: "Business", 3: "Access", 4: "Details", 5: "Review" };
      stepLabel.textContent = `Step ${currentStep}`;
      stepName.textContent = names[currentStep] || "";
  
      bar.style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;
      prevBtn.disabled = currentStep === minStep;
  
      nextBtn.textContent = currentStep === totalSteps ? "Finish" : "Next →";
      validationBadge.style.display = "none";
  
      pathLabel.textContent = getServiceLabel() || "—";
  
      if (currentStep === 5) buildReview();
    }
  
    // Service selection
    function getServiceValue() {
      const picked = form.querySelector('input[name="service"]:checked');
      return picked ? picked.value : "";
    }
  
    function getServiceLabel() {
      const v = getServiceValue();
      const map = {
        new: "New website",
        redesign: "Redesign",
        dev: "Dev only",
        strategy: "Strategy & Planning",
        maintenance: "Maintenance",
        unsure: "Not sure",
      };
      return map[v] || "";
    }
  
    function showPathSections() {
      const v = getServiceValue();
  
      maintenanceSection?.classList.add("hidden");
      strategySection?.classList.add("hidden");
      fullSection?.classList.add("hidden");
      unsureSection?.classList.add("hidden");
  
      if (v === "maintenance") {
        maintenanceSection?.classList.remove("hidden");
      } else if (v === "strategy") {
        strategySection?.classList.remove("hidden");
      } else if (v === "unsure") {
        unsureSection?.classList.remove("hidden");
      } else {
        fullSection?.classList.remove("hidden");
      }
    }
  
    // Domain separate
    function domainLogic() {
      const v = hasDomain?.value || "";
      const show = v === "yes";
  
      domainNameWrap?.classList.toggle("hidden", !show);
      domainProviderWrap?.classList.toggle("hidden", !show);
      domainCredBox?.classList.toggle("hidden", !show);
  
      if (!show) {
        if (domainInviteInstead) domainInviteInstead.checked = false;
        domainUserWrap?.classList.remove("hidden");
        domainPassWrap?.classList.remove("hidden");
      } else {
        domainInviteLogic();
      }
    }
  
    function domainInviteLogic() {
      const hideCreds = !!domainInviteInstead?.checked;
      domainUserWrap?.classList.toggle("hidden", hideCreds);
      domainPassWrap?.classList.toggle("hidden", hideCreds);
    }
  
    // Hosting separate
    function hostingLogic() {
      const v = hasHosting?.value || "";
      const show = v === "yes";
  
      hostingProviderWrap?.classList.toggle("hidden", !show);
      hostingCredBox?.classList.toggle("hidden", !show);
  
      if (!show) {
        if (hostingInviteInstead) hostingInviteInstead.checked = false;
        hostingUserWrap?.classList.remove("hidden");
        hostingPassWrap?.classList.remove("hidden");
      } else {
        hostingInviteLogic();
      }
    }
  
    function hostingInviteLogic() {
      const hideCreds = !!hostingInviteInstead?.checked;
      hostingUserWrap?.classList.toggle("hidden", hideCreds);
      hostingPassWrap?.classList.toggle("hidden", hideCreds);
    }
  
    // Strategy optional links (ads/seo)
    function serviceLinkLogic() {
      const showAny = (!!needAds?.checked) || (!!needSEO?.checked);
      serviceLinks?.classList.toggle("hidden", !showAny);
  
      adsLink?.classList.toggle("hidden", !needAds?.checked);
      seoLink?.classList.toggle("hidden", !needSEO?.checked);
    }
  
    // FULL section conditional logic
    function brandkitLogic() {
      const v = hasBrandkit?.value || "";
      const isYes = v === "yes";
      const isNo = v === "no";
  
      brandkitUploadWrap?.classList.toggle("hidden", !(isYes || v === "partial"));
      fontsColorsWrap?.classList.toggle("hidden", !isNo);
    }
  
    function remarketingLogic() {
      const v = emailRemarketing?.value || "";
      emailPlatformWrap?.classList.toggle("hidden", v !== "yes");
    }
  
    function ecommerceLogic() {
      const v = isEcom?.value || "";
      const show = v === "yes";
  
      paymentGatewayWrap?.classList.toggle("hidden", !show);
      productsWrap?.classList.toggle("hidden", !show);
  
      if (!show && hasPayment) hasPayment.value = "";
      paymentLogic();
    }
  
    function paymentLogic() {
      const ecom = (isEcom?.value || "") === "yes";
      const v = hasPayment?.value || "";
      const showProvider = ecom && v === "yes";
      paymentProviderWrap?.classList.toggle("hidden", !showProvider);
    }
  
    // Validation
    function validateStep1() {
      const v = getServiceValue();
      if (!v) {
        validationBadge.style.display = "inline-flex";
        return false;
      }
      return true;
    }
  
    function validateEmailField() {
      const emailEl = form.querySelector('input[type="email"][name="contact_email"]');
      if (!emailEl) return true;
      if (!emailEl.checkValidity()) {
        emailEl.reportValidity();
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
  
      data.service = getServiceValue();
      data.service_label = getServiceLabel();

      // Form type identifier — read from the hidden field the template sets
      // (web-design / web-development), falling back to legacy 'web'.
      const formTypeEl = form.querySelector('input[name="form_type"]');
      data.form_type = formTypeEl && formTypeEl.value ? formTypeEl.value : 'web';
  
      if (iti && phoneEl) {
        data.contact_phone_e164 = iti.getNumber();
        data.contact_phone_country = iti.getSelectedCountryData()?.iso2 || "";
        data.contact_phone_valid = iti.isValidNumber();
      }
  
      data.opt_ads = !!needAds?.checked;
      data.opt_seo = !!needSEO?.checked;
  
      if (domainInviteInstead?.checked) {
        delete data.domain_username;
        delete data.domain_password;
        data.domain_access_method = "Invite it@cularcreative.com";
      } else if (hasDomain?.value === "yes") {
        data.domain_access_method = "Credentials provided (masked in UI)";
      }
  
      if (hostingInviteInstead?.checked) {
        delete data.hosting_username;
        delete data.hosting_password;
        data.hosting_access_method = "Invite it@cularcreative.com";
      } else if (hasHosting?.value === "yes") {
        data.hosting_access_method = "Credentials provided (masked in UI)";
      }
  
      if (reviewBox) {
        reviewBox.innerHTML = `
          <strong>Service:</strong> ${data.service_label || "—"}<br/>
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
  
    // Draft functionality removed as per client request
  
    // Events
    form.addEventListener("change", (e) => {
      if (e.target.name === "service") {
        showPathSections();
        pathLabel.textContent = getServiceLabel() || "—";
      }
    });
  
    hasDomain?.addEventListener("change", domainLogic);
    domainInviteInstead?.addEventListener("change", domainInviteLogic);
  
    hasHosting?.addEventListener("change", hostingLogic);
    hostingInviteInstead?.addEventListener("change", hostingInviteLogic);
  
    needAds?.addEventListener("change", serviceLinkLogic);
    needSEO?.addEventListener("change", serviceLinkLogic);
  
    hasBrandkit?.addEventListener("change", brandkitLogic);
    emailRemarketing?.addEventListener("change", remarketingLogic);
    isEcom?.addEventListener("change", ecommerceLogic);
    hasPayment?.addEventListener("change", paymentLogic);
  
    prevBtn.addEventListener("click", () => setStep(currentStep - 1));
  
    nextBtn.addEventListener("click", () => {
      if (currentStep === 1 && !validateStep1()) return;
      if (currentStep === 2 && !validateEmailField()) return;
  
      if (currentStep === totalSteps) {
        submitForm();
        return;
      }
      setStep(currentStep + 1);
    });
  
    // Init
    hideMetaNotes();
    showPathSections();
    domainLogic();
    hostingLogic();
    domainInviteLogic();
    hostingInviteLogic();
    serviceLinkLogic();
    brandkitLogic();
    remarketingLogic();
    ecommerceLogic();
    paymentLogic();
    pathLabel.textContent = getServiceLabel() || "—";
    setStep(minStep);
  });
  
})(jQuery);
