<?php
/**
 * Intake Form Template
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="cular-intake-wrap">
  <div class="wrap">
    <header>
      <div class="brand">
        <h1>Website Services Form</h1>
        <p>
          Please fill in this form to help us understand your needs and provide you with the best possible service for your website project.
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
        <?php
        // Every other template declares its type in the markup; this one relied
        // solely on intake-form.js setting data.form_type = 'web' at submit
        // time, so the form was not self-describing and anything inspecting the
        // DOM (tests, tracking) could not tell what it was.
        ?>
        <input type="hidden" name="form_type" value="web" />
        <div class="content">

          <!-- STEP 1: SERVICE -->
          <div class="step" data-step="1">
            <div class="section">
              <h2>What are you looking for?</h2>
              <div class="choices" role="radiogroup" aria-label="Service selection">

                <label class="choice">
                  <input type="radio" name="service" value="new" />
                  <div>
                    <h3>New website (from scratch)</h3>
                    <p>Full build: structure, design, development.</p>
                  </div>
                </label>

                <label class="choice">
                  <input type="radio" name="service" value="redesign" />
                  <div>
                    <h3>Redesign existing website</h3>
                    <p>Improve UI/UX, rebuild pages, keep what works.</p>
                  </div>
                </label>

                <label class="choice">
                  <input type="radio" name="service" value="dev" />
                  <div>
                    <h3>Web development only</h3>
                    <p>You already have design — we implement it properly.</p>
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

          <!-- STEP 2: BUSINESS -->
          <div class="step hidden" data-step="2">
            <div class="section">
              <h2>Business & Contact</h2>
              <div class="grid">
                <div class="col-6">
                  <label>Business name</label>
                  <input name="business_name" placeholder="e.g., Foundations Marketing" required />
                </div>
                <div class="col-6">
                  <label>Website (if any)</label>
                  <input name="website_url" placeholder="https://example.com" />
                </div>

                <div class="col-4">
                  <label>Contact person</label>
                  <input name="contact_name" placeholder="Full name" required />
                </div>
                <div class="col-4">
                  <label>Role / position</label>
                  <input name="contact_role" placeholder="Owner / Manager / Marketing" />
                </div>
                <div class="col-4">
                  <label>Email</label>
                  <input name="contact_email" type="email" placeholder="name@company.com" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" />
                </div>

                <div class="col-6">
                  <label>WhatsApp / phone</label>
                  <input id="phoneInput" name="contact_phone_raw" placeholder="Enter number" />
                  <input type="hidden" name="contact_phone_e164" id="phoneE164" />
                </div>
                <div class="col-6">
                  <label>Country / timezone</label>
                  <input name="timezone" placeholder="e.g., Indonesia (GMT+8)" />
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 3: DOMAIN + HOSTING -->
          <div class="step hidden" data-step="3">
            <div class="section">
              <h2>Domain & Hosting Access</h2>

              <div class="summary">
                <strong>Domain</strong> = where your website name is managed (example.com).<br/>
                <strong>Hosting</strong> = where your website files/database live.
              </div>

              <div class="divider"></div>

              <!-- DOMAIN -->
              <div class="grid">
                <div class="col-6">
                  <label>Do you already have a domain?</label>
                  <select name="has_domain" id="hasDomain">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    <option value="unsure">Not sure</option>
                  </select>
                </div>

                <div class="col-6 hidden" id="domainNameWrap">
                  <label>Domain name</label>
                  <input name="domain_name" placeholder="example.com" />
                </div>

                <div class="col-6 hidden" id="domainProviderWrap">
                  <label>Domain provider</label>
                  <input name="domain_provider" placeholder="GoDaddy / Namecheap / Cloudflare..." />
                </div>

                <div class="col-12 hidden" id="domainCredBox">
                  <div class="note">
                    If you can't share domain credentials, please invite <code class="k">it@cularcreative.com</code> to your domain provider account.
                  </div>

                  <div class="chk" style="margin-top:10px;">
                    <input type="checkbox" id="domainInviteInstead" />
                    <div class="txt">
                      I can't share <b>domain</b> credentials. I will invite <b>it@cularcreative.com</b> via email access.
                    </div>
                  </div>

                  <div class="grid" style="margin-top:10px;">
                    <div class="col-6" id="domainUserWrap">
                      <label>Domain username</label>
                      <input name="domain_username" autocomplete="off" placeholder="Username" />
                    </div>
                    <div class="col-6" id="domainPassWrap">
                      <label>Domain password</label>
                      <input name="domain_password" type="password" autocomplete="new-password" placeholder="••••••••" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="divider"></div>

              <!-- HOSTING -->
              <div class="grid">
                <div class="col-6">
                  <label>Do you already have hosting?</label>
                  <select name="has_hosting" id="hasHosting">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    <option value="unsure">Not sure</option>
                  </select>
                </div>

                <div class="col-6 hidden" id="hostingProviderWrap">
                  <label>Hosting provider</label>
                  <input name="hosting_provider" placeholder="SiteGround / Hostinger / AWS / etc" />
                </div>

                <div class="col-12 hidden" id="hostingCredBox">
                  <div class="note">
                    If you can't share hosting credentials, please invite <code class="k">it@cularcreative.com</code> to your hosting account.
                  </div>

                  <div class="chk" style="margin-top:10px;">
                    <input type="checkbox" id="hostingInviteInstead" />
                    <div class="txt">
                      I can't share <b>hosting</b> credentials. I will invite <b>it@cularcreative.com</b> via email access.
                    </div>
                  </div>

                  <div class="grid" style="margin-top:10px;">
                    <div class="col-6" id="hostingUserWrap">
                      <label>Hosting username</label>
                      <input name="hosting_username" autocomplete="off" placeholder="Username" />
                    </div>
                    <div class="col-6" id="hostingPassWrap">
                      <label>Hosting password</label>
                      <input name="hosting_password" type="password" autocomplete="new-password" placeholder="••••••••" />
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- STEP 4: DETAILS -->
          <div class="step hidden" data-step="4">

            <!-- FULL PROJECT (new/redesign/dev) -->
            <div class="section hidden" id="fullSection">
              <h2>Website Build Details</h2>

              <div class="grid">
                <div class="col-6">
                  <label>Landing page or full website?</label>
                  <select name="full_scope_type">
                    <option value="">Select…</option>
                    <option value="landing">Landing page</option>
                    <option value="website">Full website (Home / About / Services / etc)</option>
                    <option value="unsure">Not sure</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Website main goal</label>
                  <select name="full_main_goal">
                    <option value="">Select…</option>
                    <option value="leads">Get leads</option>
                    <option value="sales">Sell products</option>
                    <option value="bookings">Get bookings</option>
                    <option value="brand">Brand awareness</option>
                    <option value="other">Other</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Current CMS (if any)</label>
                  <select name="full_current_cms">
                    <option value="">Select…</option>
                    <option value="none">None / new</option>
                    <option value="wordpress">WordPress</option>
                    <option value="shopify">Shopify</option>
                    <option value="wix">Wix</option>
                    <option value="webflow">Webflow</option>
                    <option value="custom">Custom</option>
                    <option value="unknown">Not sure</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Do you want to change CMS? (Recommended: WordPress)</label>
                  <select name="full_change_cms">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    <option value="unsure">Not sure</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Email provider</label>
                  <select name="full_email_provider">
                    <option value="">Select…</option>
                    <option value="google_workspace">Google Workspace</option>
                    <option value="zoho">Zoho</option>
                    <option value="microsoft_365">Microsoft 365</option>
                    <option value="hosting_email">Hosting (cPanel email)</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Email remarketing platform</label>
                  <select name="full_email_remarketing">
                    <option value="">None / not needed</option>
                    <option value="mailchimp">Mailchimp</option>
                    <option value="klaviyo">Klaviyo</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Image / media assets</label>
                  <select name="full_assets_images">
                    <option value="">Select…</option>
                    <option value="final">Yes (final)</option>
                    <option value="some">Yes (some)</option>
                    <option value="no_need_help">No (need help)</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Copy content (text)</label>
                  <select name="full_assets_copy">
                    <option value="">Select…</option>
                    <option value="final">Yes (final)</option>
                    <option value="draft">Yes (draft)</option>
                    <option value="no_copywriting">No (need copywriting)</option>
                  </select>
                  <div class="help">If you need copywriting, we can provide it (additional cost applies).</div>
                </div>

                <div class="col-6">
                  <label>Do you have a brand kit?</label>
                  <select name="full_has_brandkit" id="hasBrandkit">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="partial">Partial</option>
                    <option value="no">No</option>
                  </select>
                </div>

                <div class="col-6 hidden" id="brandkitUploadWrap">
                  <label>Brand kit link (optional)</label>
                  <input name="full_brandkit_link" placeholder="Drive/Dropbox link" />
                </div>

                <div class="col-12 hidden" id="fontsColorsWrap">
                  <div class="grid">
                    <div class="col-6">
                      <label>If no brand kit: Please Provide Fonts You Want to Use</label>
                      <div class="help" style="margin-top:0; margin-bottom:6px;">If you are not sure, please visit <a href="https://fonts.google.com/" target="_blank" style="color: var(--accent); text-decoration: underline;">Google Fonts</a> to browse fonts</div>
                      <input name="full_fonts" placeholder="e.g., Inter + Playfair Display" />
                    </div>

                    <div class="col-6">
                      <label>If no brand kit: Please provide Color palette You Want to Use</label>
                      <div class="help" style="margin-top:0; margin-bottom:6px;">If you are not sure, please visit <a href="https://colorhunt.co/" target="_blank" style="color: var(--accent); text-decoration: underline;">this site</a> to get hex color codes</div>
                      <input name="full_colors" placeholder="e.g., #111827, #60A5FA, #34D399" />
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <label>Third-party integrations needed?</label>
                  <div class="grid">
                    <div class="col-4">
                      <div class="chk">
                        <input type="checkbox" name="full_integrations" value="booking" />
                        <div class="txt"><b>Booking / reservations</b><div class="help">Appointment scheduling system</div></div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="chk">
                        <input type="checkbox" name="full_integrations" value="calendar" />
                        <div class="txt"><b>Calendar</b><div class="help">Calendar integration</div></div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="chk">
                        <input type="checkbox" name="full_integrations" value="automation" />
                        <div class="txt"><b>Automation</b><div class="help">Make.com / Zapier</div></div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="chk">
                        <input type="checkbox" name="full_integrations" value="ga4" />
                        <div class="txt"><b>Google Analytics (GA4)</b><div class="help">Website traffic analytics</div></div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="chk">
                        <input type="checkbox" name="full_integrations" value="gsc" />
                        <div class="txt"><b>Google Search Console</b><div class="help">SEO performance tracking</div></div>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="chk">
                        <input type="checkbox" name="full_integrations" value="gbp" />
                        <div class="txt"><b>Google Business Profile</b><div class="help">Local business listing</div></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <label>Is it an e-commerce website?</label>
                  <select name="full_is_ecommerce" id="isEcom">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </div>

                <div class="col-6 hidden" id="productsWrap">
                  <label>How many products?</label>
                  <input name="full_product_count" placeholder="e.g., 10 / 50 / 200" />
                </div>

                <div class="col-6 hidden" id="paymentGatewayWrap">
                  <label>Have payment gateway?</label>
                  <select name="full_has_payment" id="hasPayment">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    <option value="unsure">Not sure</option>
                  </select>
                </div>

                <div class="col-6 hidden" id="paymentProviderWrap">
                  <label>Payment gateway provider (if known)</label>
                  <input name="full_payment_provider" placeholder="Stripe / PayPal / Xendit / Midtrans / etc" />
                </div>

                <div class="col-6">
                  <label>Will you need ongoing maintenance after?</label>
                  <select name="full_need_maintenance">
                    <option value="">Select…</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    <option value="unsure">Not sure</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Any deadlines / preferred timeline?</label>
                  <input name="full_deadline" placeholder="e.g., 2 weeks / Feb 2026 / ASAP" />
                </div>
              </div>
            </div>

            <!-- STRATEGY -->
            <div class="section hidden" id="strategySection">
              <h2>Website Strategy & Planning</h2>

              <div class="grid">
                <div class="col-6">
                  <label>Current platform</label>
                  <select name="strategy_platform">
                    <option value="">Select…</option>
                    <option value="wordpress">WordPress</option>
                    <option value="shopify">Shopify</option>
                    <option value="webflow">Webflow</option>
                    <option value="wix">Wix</option>
                    <option value="custom">Custom</option>
                    <option value="unknown">Not sure</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>Business model</label>
                  <select name="strategy_model">
                    <option value="">Select…</option>
                    <option value="services">Services</option>
                    <option value="ecommerce">E-commerce</option>
                    <option value="portfolio">Portfolio</option>
                    <option value="startup">Startup / SaaS</option>
                  </select>
                </div>

                <div class="col-12">
                  <label>What do you want to improve?</label>
                  <div class="grid">
                    <!-- Left 4 -->
                    <div class="col-6">
                      <div class="chk"><input type="checkbox" name="strategy_scope" value="structure"><div class="txt"><b>Website structure</b><div class="help">Sitemap, hierarchy, user flow.</div></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="strategy_scope" value="ux"><div class="txt"><b>UX improvements</b><div class="help">Clarity, layout, experience.</div></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="strategy_scope" value="performance"><div class="txt"><b>Performance / speed</b><div class="help">Technical performance plan.</div></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="strategy_scope" value="scalability"><div class="txt"><b>Scalability</b><div class="help">Future-proof architecture.</div></div></div>
                    </div>

                    <!-- Right 4 -->
                    <div class="col-6">
                      <div class="chk"><input type="checkbox" name="strategy_scope" value="conversion"><div class="txt"><b>Conversion flow</b><div class="help">Lead/sales journey improvements.</div></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="strategy_scope" value="design_tweaks"><div class="txt"><b>Design tweaks</b><div class="help">UI improvements recommendation.</div></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="strategy_scope" value="new_features"><div class="txt"><b>New features</b><div class="help">Feature planning + scoping.</div></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="strategy_scope" value="seo_fixes"><div class="txt"><b>SEO fixes</b><div class="help">Technical SEO recommendations.</div></div></div>
                    </div>
                  </div>
                </div>

                <div class="col-6">
                  <label>Budget range</label>
                  <select name="strategy_budget" required>
                    <option value="">Select…</option>
                    <option value="5m">5M IDR</option>
                    <option value="7_5m">7.5M IDR</option>
                    <option value="10m">10M IDR</option>
                    <option value="10m_plus">10M+ IDR</option>
                    <option value="unsure">Not sure yet</option>
                  </select>
                </div>

                <div class="col-6">
                  <label>After planning, who will implement?</label>
                  <select name="strategy_followup">
                    <option value="">Select…</option>
                    <option value="plan_only">Your team / another vendor (Plan only)</option>
                    <option value="plan_plus_maintenance">Cular – Monthly implementation (Maintenance)</option>
                    <option value="plan_plus_development">Cular – Project implementation (Development)</option>
                  </select>
                </div>

                <div class="col-12">
                  <div class="note">
                    <b>Important:</b> Maintenance covers ongoing improvements and fixes only.
                    Major new features, redesigns, or core system changes are handled as a Development project.
                  </div>
                </div>

                <div class="col-12">
                  <div class="note">
                    If you also need <a href="https://cularcreative.com/services/ads" target="_blank" style="color: var(--accent); text-decoration: underline; font-weight: 600;">Ads</a> or <a href="https://cularcreative.com/services/seo" target="_blank" style="color: var(--accent); text-decoration: underline; font-weight: 600;">SEO</a>, please visit the service pages.
                  </div>
                </div>
              </div>
            </div>

            <!-- MAINTENANCE -->
            <div class="section hidden" id="maintenanceSection">
              <h2>Website Maintenance & Support</h2>

              <div class="grid">
                <div class="col-6">
                  <label>Maintenance plan</label>
                  <select name="maintenance_plan">
                    <option value="">Select…</option>
                    <option value="5">5 hours / month</option>
                    <option value="10">10 hours / month</option>
                    <option value="custom">Custom (more than 10)</option>
                  </select>
                </div>

                <div class="col-12">
                  <label>What do you need help with?</label>
                  <div class="grid">
                    <div class="col-6">
                      <div class="chk"><input type="checkbox" name="maint_tasks" value="bug_fixes"><div class="txt"><b>Bug fixes</b></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="maint_tasks" value="content_updates"><div class="txt"><b>Content updates</b></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="maint_tasks" value="updates"><div class="txt"><b>Plugin / system updates</b></div></div>
                    </div>
                    <div class="col-6">
                      <div class="chk"><input type="checkbox" name="maint_tasks" value="speed"><div class="txt"><b>Speed optimization</b></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="maint_tasks" value="security"><div class="txt"><b>Security & backups</b></div></div>
                      <div class="chk" style="margin-top:10px;"><input type="checkbox" name="maint_tasks" value="hosting_support"><div class="txt"><b>Server / hosting support</b></div></div>
                    </div>
                  </div>

                  <div class="help">
                    Design tweaks, new features, and SEO fixes are handled under <b>Strategy & Planning</b>.
                  </div>
                </div>
              </div>
            </div>

            <!-- UNSURE -->
            <div class="section hidden" id="unsureSection">
              <h2>Not sure yet</h2>
              <div class="note">
                No problem — we'll review your info and recommend the best approach.
              </div>
            </div>

          </div>

          <!-- STEP 5: REVIEW -->
          <div class="step hidden" data-step="5">
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
            <span class="badge warn" id="validationBadge" style="display:none;">Please pick a service to continue</span>
            <button type="button" class="primary" id="nextBtn">Next →</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
