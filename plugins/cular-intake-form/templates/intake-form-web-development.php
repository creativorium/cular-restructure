<?php
/**
 * Web Development Intake Form Template
 * Development-focused website work (builds & implementation).
 * Strategy, Maintenance and Not-sure remain available as options.
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="cular-intake-wrap">
  <div class="wrap">
    <header>
      <div class="brand">
        <h1>Web Development Services Form</h1>
        <p>
          Please fill in this form to help us understand your needs and build your website project properly.
        </p>
      </div>
    </header>

    <div class="card" id="app">
      <div class="topbar">
        <div class="stepper">
          <div class="pill"><b id="stepLabel">Step 1</b><span id="stepName">Service</span></div>
          <div class="pill">Path: <b id="pathLabel">—</b></div>
        </div>
        <div class="progress" aria-label="progress">
          <div id="bar"></div>
        </div>
      </div>

      <form id="form" novalidate data-preselect="<?php echo isset($preselect_service) ? esc_attr($preselect_service) : ''; ?>">
        <input type="hidden" name="form_type" value="web-development" />
        <div class="content">

          <!-- STEP 1: SERVICE -->
          <div class="step" data-step="1">
            <div class="section">
              <h2>What are you looking for?</h2>
              <div class="choices" role="radiogroup" aria-label="Service selection">

                <label class="choice">
                  <input type="radio" name="service" value="dev" />
                  <div>
                    <h3>Web development only</h3>
                    <p>You already have a design — we implement it properly.</p>
                  </div>
                </label>

                <label class="choice">
                  <input type="radio" name="service" value="new" />
                  <div>
                    <h3>New website build (design + development)</h3>
                    <p>Full build: structure, design, development.</p>
                  </div>
                </label>

                <label class="choice">
                  <input type="radio" name="service" value="strategy" />
                  <div>
                    <h3>Website Strategy & Planning</h3>
                    <p>Technical planning: structure, priorities, roadmap.</p>
                  </div>
                </label>

                <label class="choice">
                  <input type="radio" name="service" value="maintenance" />
                  <div>
                    <h3>Website Maintenance & Support</h3>
                    <p>Ongoing technical support, fixes, stability.</p>
                  </div>
                </label>

                <label class="choice">
                  <input type="radio" name="service" value="unsure" />
                  <div>
                    <h3>Not sure yet</h3>
                    <p>We'll review and recommend the best approach.</p>
                  </div>
                </label>

              </div>
            </div>
          </div>

          <?php include CULAR_INTAKE_PLUGIN_DIR . 'templates/partials/web-shared-steps.php'; ?>

        </div>

        <div class="actions">
          <button type="button" class="ghost" id="prevBtn">← Back</button>
          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <span class="badge warn" id="validationBadge" style="display:none;">Please pick a service to continue</span>
            <button type="button" class="primary" id="nextBtn">Next →</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
