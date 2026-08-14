<?php
/**
 * Ads (Advertising) Intake Form Template
 * Single-purpose: paid advertising campaign management only.
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="cular-intake-wrap">
  <div class="wrap">
    <header>
      <div class="brand">
        <h1>Advertising (Ads) Services Form</h1>
        <p>Please share a few details so we can quickly understand your goals, setup, and what support you need for your ad campaigns.</p>
      </div>
    </header>

    <div class="card" id="app">
      <div class="topbar">
        <div class="stepper">
          <div class="pill"><b id="stepLabel">Step 1</b><span id="stepName">Business</span></div>
          <div class="pill">Service: <b id="pathLabel">Ads Management</b></div>
        </div>
        <div class="progress" aria-label="progress">
          <div id="bar"></div>
        </div>
      </div>

      <form id="form" novalidate>
        <input type="hidden" name="form_type" value="ads" />
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
                  <div class="help">The more specific, the better we can target your campaigns.</div>
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

          <!-- STEP 3: ADS DETAILS -->
          <div class="step hidden" data-step="3">
            <div class="section">
              <h2>Ads Details</h2>

              <div id="adsSection">
                <div class="grid">
                  <div class="col-12">
                    <label>Platforms</label>
                    <div class="grid">
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" id="pf_meta_ads" name="ads_platforms" value="meta_ads" />
                          <div class="txt"><b>Meta Ads</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" id="pf_meta_boost" name="ads_platforms" value="meta_boost" />
                          <div class="txt"><b>Meta Boost Post</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" id="pf_google_ads" name="ads_platforms" value="google_ads" />
                          <div class="txt"><b>Google Ads</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" id="pf_tiktok_ads" name="ads_platforms" value="tiktok_ads" />
                          <div class="txt"><b>TikTok Ads</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" id="pf_shopee_opt" name="ads_platforms" value="shopee_optimization" />
                          <div class="txt"><b>Shopee Optimization</b></div>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <label>Ads objective</label>
                    <div class="grid">
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" name="ads_objectives" value="awareness" />
                          <div class="txt"><b>Awareness</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" name="ads_objectives" value="consideration" />
                          <div class="txt"><b>Consideration</b></div>
                        </label>
                      </div>
                      <div class="col-4" id="conversionWrap">
                        <label class="chk">
                          <input type="checkbox" id="obj_conversion" name="ads_objectives" value="conversion" />
                          <div class="txt"><b>Conversion</b></div>
                        </label>
                      </div>
                    </div>
                    <div class="help" id="conversionHelp">Available for all platforms except Meta Boost Post only.</div>
                  </div>

                  <div class="col-6 hidden" id="boostObjectiveWrap">
                    <label>Boost objective</label>
                    <select name="boost_objective">
                      <option value="">Select…</option>
                      <option value="awareness">Awareness</option>
                      <option value="consideration">Consideration</option>
                    </select>
                  </div>

                  <div class="col-6 hidden" id="tiktokObjectiveWrap">
                    <label>TikTok ads objective</label>
                    <select id="tiktokObjective" name="tiktok_objective">
                      <option value="">Select…</option>
                      <option value="awareness">Awareness</option>
                      <option value="consideration">Consideration</option>
                      <option value="conversion">Conversion</option>
                    </select>
                  </div>

                  <div class="col-12 hidden" id="tiktokShopWrap">
                    <label>TikTok Shop Objective</label>
                    <div class="grid">
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" name="tiktok_shop_objectives" value="store_optimization" />
                          <div class="txt"><b>Store Optimization</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" name="tiktok_shop_objectives" value="sales_growth" />
                          <div class="txt"><b>Sales Growth</b></div>
                        </label>
                      </div>
                      <div class="col-4">
                        <label class="chk">
                          <input type="checkbox" name="tiktok_shop_objectives" value="no_tiktok_shop" />
                          <div class="txt"><b>I don't have TikTok Shop</b></div>
                        </label>
                      </div>
                    </div>
                    <div class="help">Shown when TikTok Ads is selected.</div>
                  </div>

                  <div class="col-6 hidden" id="landingPageWrap">
                    <label>Landing page ready?</label>
                    <select name="ads_landing_ready">
                      <option value="">Select…</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                      <option value="not_sure">Not sure</option>
                    </select>
                  </div>

                  <div class="col-6">
                    <label>Monthly budget range</label>
                    <select name="ads_budget">
                      <option value="">Select…</option>
                      <option>5M IDR</option>
                      <option>10M IDR</option>
                      <option>20M IDR</option>
                      <option>20M+ IDR</option>
                    </select>
                  </div>

                  <div class="col-6">
                    <label>Account status</label>
                    <select name="ads_status">
                      <option value="">Select…</option>
                      <option>New (no ads run yet)</option>
                      <option>Existing (running now)</option>
                      <option>Previously ran ads (stopped)</option>
                    </select>
                  </div>
                </div>

                <div class="divider"></div>

                <!-- ACCESS & TRACKING FOR ADS -->
                <h2>Access & Tracking</h2>
                <div class="grid">
                  <div class="col-6">
                    <label>General tracking tools:</label>

                    <div class="chk">
                      <input type="checkbox" name="access_tools" value="ga4" />
                      <div class="txt"><b>Google Analytics (GA4)</b></div>
                    </div>

                    <div style="height:8px"></div>
                    <div class="chk">
                      <input type="checkbox" name="access_tools" value="gtm" />
                      <div class="txt"><b>Google Tag Manager (GTM)</b></div>
                    </div>

                    <div style="height:8px"></div>
                    <div class="chk">
                      <input type="checkbox" name="access_tools" value="meta_bm" />
                      <div class="txt"><b>Meta Business Manager</b></div>
                    </div>

                    <div style="height:8px"></div>
                    <div class="chk">
                      <input type="checkbox" name="access_tools" value="google_ads" />
                      <div class="txt"><b>Google Ads</b></div>
                    </div>
                  </div>

                  <div class="col-6">
                    <div class="note note-inline">
                      If you can't share logins, you can invite us instead via email: <b style="margin-left:6px">team@cularcreative.com</b>
                    </div>

                    <div class="divider"></div>

                    <label>Anything important we should know about tracking?</label>
                    <textarea name="tracking_notes" placeholder="E.g. Pixel already installed, conversions not tracking, duplicated tags, etc."></textarea>
                  </div>
                </div>

                <!-- TikTok Credentials -->
                <div id="tiktokCredentialsWrap" class="hidden" style="margin-top:12px">
                  <div class="note">
                    <b>TikTok Account Access Required</b> - Please provide your TikTok credentials or invite us to your account.
                  </div>
                  <div class="grid" style="margin-top:10px">
                    <div class="col-6">
                      <label>TikTok username / email</label>
                      <input name="tiktok_username" placeholder="TikTok account username or email" />
                    </div>
                    <div class="col-6">
                      <label>TikTok password</label>
                      <input type="password" name="tiktok_password" placeholder="••••••••" autocomplete="new-password" />
                    </div>
                    <div class="col-12">
                      <div class="chk">
                        <input type="checkbox" name="tiktok_invite_instead" />
                        <div class="txt">I will invite <b>team@cularcreative.com</b> to my TikTok Ads account instead</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Shopee Credentials -->
                <div id="shopeeCredentialsWrap" class="hidden" style="margin-top:12px">
                  <div class="note">
                    <b>Shopee Account Access Required</b> - Please provide your Shopee credentials or invite us to your account.
                  </div>
                  <div class="grid" style="margin-top:10px">
                    <div class="col-6">
                      <label>Shopee username / email</label>
                      <input name="shopee_username" placeholder="Shopee account username or email" />
                    </div>
                    <div class="col-6">
                      <label>Shopee password</label>
                      <input type="password" name="shopee_password" placeholder="••••••••" autocomplete="new-password" />
                    </div>
                    <div class="col-12">
                      <div class="chk">
                        <input type="checkbox" name="shopee_invite_instead" />
                        <div class="txt">I will invite <b>team@cularcreative.com</b> to my Shopee account instead</div>
                      </div>
                    </div>
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
