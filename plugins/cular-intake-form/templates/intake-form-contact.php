<?php
/**
 * Contact Intake Form Template
 *
 * The general "Book a Call with Us" form used on the Contact page and on any
 * service page that has no service-specific form of its own. Three steps:
 * Profile Data -> Goal & Timeline -> About You.
 */
if (!defined('ABSPATH')) {
    exit;
}

$services = array(
    'Marketing Audit',
    'Strategy Blueprint',
    'Consultation',
    'Brand Identity',
    'Graphic Design',
    'Website Development',
    'Social Media',
    'Digital Advertising',
    'Content Creation',
    'SEO',
    'Not sure yet',
);

// Optionally locked to one service by the shortcode's `service` attribute.
$preselect = isset($preselect_service) ? $preselect_service : '';
?>

<div class="cular-intake-wrap cular-intake-contact">
  <div class="wrap">
    <div class="card" id="app">
      <div class="topbar">
        <div class="stepper">
          <div class="pill"><b id="stepLabel">Step 1</b><span id="stepName">Profile Data</span></div>
        </div>
        <div class="progress" aria-label="progress">
          <div id="bar"></div>
        </div>
      </div>

      <form id="form" novalidate data-total-steps="3">
        <input type="hidden" name="form_type" value="contact" />
        <input type="hidden" name="locked_service" value="<?php echo esc_attr($preselect); ?>" />

        <div class="content">

          <!-- STEP 1: PROFILE DATA -->
          <div class="step" data-step="1">
            <div class="section">
              <div class="grid">
                <div class="col-12">
                  <label for="ci-name">Client Name</label>
                  <input id="ci-name" name="contact_name" placeholder="Client Name" autocomplete="name" required />
                </div>
                <div class="col-12">
                  <label for="ci-business">Business Name</label>
                  <input id="ci-business" name="business_name" placeholder="Business Name" autocomplete="organization" />
                </div>
                <div class="col-12">
                  <label for="phoneInput">Phone</label>
                  <input id="phoneInput" name="contact_phone_raw" placeholder="Phone" autocomplete="tel" />
                  <input type="hidden" name="contact_phone_e164" id="phoneE164" />
                </div>
                <div class="col-12">
                  <label for="ci-email">Email</label>
                  <input id="ci-email" type="email" name="contact_email" placeholder="Email" autocomplete="email" required
                    pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" />
                </div>
                <?php if (!$preselect) : ?>
                  <div class="col-12">
                    <label for="ci-service">What does your brand need right now?</label>
                    <select id="ci-service" name="cular_service" required>
                      <option value="">What does your brand need right now?</option>
                      <?php foreach ($services as $s) : ?>
                        <option value="<?php echo esc_attr($s); ?>"><?php echo esc_html($s); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- STEP 2: GOAL & TIMELINE -->
          <div class="step hidden" data-step="2">
            <div class="section">
              <div class="grid">
                <div class="col-12">
                  <label for="ci-goal">What are you hoping to achieve?</label>
                  <textarea id="ci-goal" name="project_goal" rows="4"
                    placeholder="Tell us the outcome you're after — more leads, a rebrand, a launch…"></textarea>
                </div>
                <div class="col-6">
                  <label for="ci-timeline">Timeline</label>
                  <select id="ci-timeline" name="project_timeline">
                    <option value="">Select a timeline</option>
                    <option>As soon as possible</option>
                    <option>Within 1–3 months</option>
                    <option>In 3–6 months</option>
                    <option>Just exploring</option>
                  </select>
                </div>
                <div class="col-6">
                  <label for="ci-budget">Indicative budget</label>
                  <select id="ci-budget" name="project_budget">
                    <option value="">Prefer not to say</option>
                    <option>Under IDR 10m / month</option>
                    <option>IDR 10–25m / month</option>
                    <option>IDR 25–50m / month</option>
                    <option>IDR 50m+ / month</option>
                    <option>One-off project</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 3: ABOUT YOU -->
          <div class="step hidden" data-step="3">
            <div class="section">
              <div class="grid">
                <div class="col-12">
                  <label for="ci-website">Website or social handle</label>
                  <input id="ci-website" name="business_website" placeholder="cularcreative.com or @yourbrand" />
                </div>
                <div class="col-12">
                  <label for="ci-how">How did you hear about us?</label>
                  <select id="ci-how" name="referral_source">
                    <option value="">Select one</option>
                    <option>Google</option>
                    <option>Instagram</option>
                    <option>LinkedIn</option>
                    <option>Referral from a friend or client</option>
                    <option>Event or workshop</option>
                    <option>Other</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="ci-notes">Anything else we should know?</label>
                  <textarea id="ci-notes" name="extra_notes" rows="4" placeholder="Optional"></textarea>
                </div>
              </div>

              <div id="reviewBox" class="review"></div>
            </div>
          </div>

        </div>

        <div class="actions">
          <button type="button" class="btn ghost" id="prevBtn" disabled>← Previous</button>
          <span id="validationBadge" class="badge" style="display:none"></span>
          <button type="button" class="btn primary" id="nextBtn">Next</button>
        </div>

        <div id="successMessage" class="success" style="display:none">
          <h3>Thank you — we've got it.</h3>
          <p>One of the team will be in touch shortly.</p>
        </div>
      </form>
    </div>
  </div>
</div>
