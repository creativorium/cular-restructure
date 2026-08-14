<?php
/**
 * SEO Intake Form Template
 * Single-purpose: SEO / organic search optimisation only.
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="cular-intake-wrap">
  <div class="wrap">
    <header>
      <div class="brand">
        <h1>SEO Services Form</h1>
        <p>Please share a few details so we can quickly understand your goals, setup, and what support you need to grow your organic search visibility.</p>
      </div>
    </header>

    <div class="card" id="app">
      <div class="topbar">
        <div class="stepper">
          <div class="pill"><b id="stepLabel">Step 1</b><span id="stepName">Business</span></div>
          <div class="pill">Service: <b id="pathLabel">SEO Optimisation</b></div>
        </div>
        <div class="progress" aria-label="progress">
          <div id="bar"></div>
        </div>
      </div>

      <form id="form" novalidate>
        <input type="hidden" name="form_type" value="seo" />
        <div class="content">

          <!-- STEP 1: BUSINESS -->
          <div class="step" data-step="1">
            <div class="section">
              <h2>Business & Contact</h2>
              <div class="grid">
                <div class="col-6">
                  <label>Business name</label>
                  <input name="business_name" placeholder="e.g., Foundations Marketing" autocomplete="organization" required />
                </div>
                <div class="col-6">
                  <label>Contact person</label>
                  <input name="contact_name" placeholder="Full name" autocomplete="name" required />
                </div>

                <div class="col-6">
                  <label>Email</label>
                  <input type="email" name="contact_email" placeholder="name@company.com" autocomplete="email" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" />
                  <div class="help">We'll use this to send the proposal / next steps.</div>
                </div>
                <div class="col-6">
                  <label>WhatsApp / Phone</label>
                  <input id="phoneInput" name="contact_phone_raw" placeholder="Enter number" autocomplete="tel" />
                  <input type="hidden" name="contact_phone_e164" id="phoneE164" />
                  <div class="help">Country selector included for easier input.</div>
                </div>

                <div class="col-12">
                  <label>Website URL</label>
                  <input name="website_url" placeholder="https://yourwebsite.com" required />
                </div>

                <div class="col-6">
                  <label>Business location</label>
                  <select name="business_location">
                    <option value="">Select…</option>
                    <option>Bali</option>
                    <option>Indonesia</option>
                    <option>Australia</option>
                    <option>US</option>
                    <option>Europe</option>
                    <option>Other</option>
                  </select>
                </div>
                <div class="col-6">
                  <label>Industry</label>
                  <input name="industry" placeholder="Villa rentals, restaurant, fashion, etc" />
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 2: GOALS & SCOPE -->
          <div class="step hidden" data-step="2">
            <div class="section">
              <h2>Goals & Scope</h2>

              <div class="grid">
                <div class="col-6">
                  <label>Primary goal</label>
                  <select name="primary_goal" required>
                    <option value="">Select…</option>
                    <option value="leads">Generate leads</option>
                    <option value="sales">Increase sales</option>
                    <option value="traffic">Increase traffic/visibility</option>
                    <option value="local">Local visibility (maps/calls)</option>
                  </select>
                </div>
                <div class="col-6">
                  <label>Target locations</label>
                  <input name="target_locations" placeholder="E.g. Bali, Sydney, New York, Global" />
                  <div class="help">Where do you want to reach your audience?</div>
                </div>

                <div class="col-12">
                  <label>Your audience market</label>
                  <textarea name="audience_market" placeholder="Describe your target audience in detail (age, interests, behavior, demographics, etc.)"></textarea>
                  <div class="help">The more specific, the better we can target your content.</div>
                </div>

                <div class="col-12">
                  <label>What are you promoting? (services/products)</label>
                  <input name="offer_summary" placeholder="E.g. villa bookings, private tours, restaurant reservations, course enrolments" />
                </div>

                <div class="col-6">
                  <label>Competitors (URLs)</label>
                  <input name="competitors" placeholder="https://competitor1.com, https://competitor2.com" />
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 3: SEO DETAILS -->
          <div class="step hidden" data-step="3">
            <div class="section">
              <h2>SEO Details</h2>

              <div class="grid">
                <div class="col-12">
                  <label>SEO objective</label>
                  <div class="grid">
                    <div class="col-6">
                      <label class="chk">
                        <input type="radio" name="seo_objective" value="rankings" />
                        <div class="txt"><b>Improve Google rankings</b></div>
                      </label>
                    </div>
                    <div class="col-6">
                      <label class="chk">
                        <input type="radio" name="seo_objective" value="organic_traffic" />
                        <div class="txt"><b>Increase organic search traffic</b></div>
                      </label>
                    </div>
                    <div class="col-6">
                      <label class="chk">
                        <input type="radio" name="seo_objective" value="visibility" />
                        <div class="txt"><b>Increase visibility (brand exposure)</b></div>
                      </label>
                    </div>
                    <div class="col-6">
                      <label class="chk">
                        <input type="radio" name="seo_objective" value="local_visibility" />
                        <div class="txt"><b>Improve local visibility</b></div>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <label>SEO package</label>
                  <div class="grid">
                    <div class="col-6">
                      <label class="chk">
                        <input type="radio" name="seo_package" value="basic" />
                        <div class="txt">
                          <b>Basic</b><br>
                          Structure preparation + implementation, and ensure it's crawled by search engines.
                        </div>
                      </label>
                    </div>
                    <div class="col-6">
                      <label class="chk">
                        <input type="radio" name="seo_package" value="advanced" />
                        <div class="txt">
                          <b>Advanced</b><br>
                          Includes planning & strategy, and possibility for AEO (AI search optimisation).
                        </div>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <label>Priority services / products to improve</label>
                  <input name="seo_focus" placeholder="E.g. villa rental Bali, private chef Ubud, digital marketing Bali" />
                </div>

                <div class="col-12">
                  <label>Do you already have target keywords? (optional)</label>
                  <input name="seo_keywords" placeholder="E.g. 'digital marketing bali', 'villa seminyak family'" />
                </div>
              </div>

              <div class="divider"></div>

              <!-- ACCESS & TRACKING FOR SEO -->
              <h2>Access & Tracking</h2>
              <div class="grid">
                <div class="col-6">
                  <label>SEO tracking tools:</label>

                  <div class="chk">
                    <input type="checkbox" name="access_tools_seo" value="ga4" />
                    <div class="txt"><b>Google Analytics (GA4)</b></div>
                  </div>

                  <div style="height:8px"></div>
                  <div class="chk">
                    <input type="checkbox" name="access_tools_seo" value="search_console" />
                    <div class="txt"><b>Google Search Console</b></div>
                  </div>

                  <div style="height:8px"></div>
                  <div class="chk">
                    <input type="checkbox" name="access_tools_seo" value="gbp" />
                    <div class="txt"><b>Google Business Profile</b> (Local SEO)</div>
                  </div>
                </div>

                <div class="col-6">
                  <div class="note note-inline">
                    If you can't share logins, you can invite us instead via email: <b style="margin-left:6px">team@cularcreative.com</b>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 4: REVIEW -->
          <div class="step hidden" data-step="4">
            <div class="section">
              <h2>Review & Submit</h2>
              <div class="summary" id="reviewBox">
                Please review your information before submitting.
              </div>
            </div>
            <div class="section" id="successMessage" style="display:none;">
              <div class="summary" style="background: rgba(73,129,88,.15); border-color: var(--accent);">
                <strong style="color: var(--accent);">✓ Success!</strong> Your form has been submitted. We'll get back to you soon.
              </div>
            </div>
          </div>

        </div>

        <div class="actions">
          <button type="button" class="ghost" id="prevBtn">← Back</button>
          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <span class="badge warn" id="validationBadge" style="display:none;">Please complete required fields</span>
            <button type="button" class="primary" id="nextBtn">Next →</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
