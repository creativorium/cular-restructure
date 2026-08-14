<?php
/**
 * Admin Settings Page
 */
if (!defined('ABSPATH')) {
    exit;
}

$webhook_url = get_option('cular_intake_webhook_url', '');

// Effective recipients: saved dashboard value, else the built-in defaults.
$notify_to = get_option('cular_intake_notify_to', '');
if ($notify_to === '') {
    $notify_to = defined('CULAR_INTAKE_NOTIFICATION_TO') ? CULAR_INTAKE_NOTIFICATION_TO : '';
}
$notify_bcc = get_option('cular_intake_notify_bcc', null);
if ($notify_bcc === null) {
    $notify_bcc = defined('CULAR_INTAKE_NOTIFICATION_BCC') ? CULAR_INTAKE_NOTIFICATION_BCC : '';
}
?>

<div class="wrap">
    <h1>Intake Form Settings</h1>

    <form method="post" action="">
        <?php wp_nonce_field('cular_intake_settings'); ?>

        <h2>Email Notifications</h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="notify_to">Send submissions to</label>
                </th>
                <td>
                    <textarea name="notify_to" id="notify_to" rows="2" class="large-text" placeholder="hello@cularcreative.com, raluca@cularcreative.com"><?php echo esc_textarea($notify_to); ?></textarea>
                    <p class="description">Who receives every new submission. Separate multiple addresses with commas.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="notify_bcc">BCC (optional)</label>
                </th>
                <td>
                    <textarea name="notify_bcc" id="notify_bcc" rows="1" class="large-text" placeholder="team@cularcreative.com"><?php echo esc_textarea($notify_bcc); ?></textarea>
                    <p class="description">Hidden copy recipients. Separate multiple addresses with commas. Leave blank for none.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Sender (From) address</th>
                <td>
                    <p class="description" style="margin-top:0;">
                        The <code>From</code> name/address is controlled by your site's mail setup
                        (e.g. WP Mail SMTP), not this plugin. Change it there.
                    </p>
                </td>
            </tr>
        </table>

        <h2>Webhook</h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="webhook_url">Webhook URL (Optional)</label>
                </th>
                <td>
                    <input type="url" name="webhook_url" id="webhook_url"
                           value="<?php echo esc_attr($webhook_url); ?>"
                           class="regular-text" placeholder="https://hooks.make.com/..." />
                    <p class="description">Send form data to Make.com, Zapier, or any webhook endpoint</p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="cular_intake_settings_submit" class="button button-primary" value="Save Settings" />
        </p>
    </form>

    <hr />

    <h2>Shortcode Usage</h2>
    <p>Put each shortcode on its own page so customers land directly on the right form.</p>

    <table class="form-table">
        <tr>
            <th scope="row">Web Design</th>
            <td><code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[cular_intake_form type="web-design"]</code></td>
        </tr>
        <tr>
            <th scope="row">Web Development</th>
            <td><code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[cular_intake_form type="web-development"]</code></td>
        </tr>
        <tr>
            <th scope="row">Ads</th>
            <td><code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[cular_intake_form type="ads"]</code></td>
        </tr>
        <tr>
            <th scope="row">SEO</th>
            <td><code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[cular_intake_form type="seo"]</code></td>
        </tr>
        <tr>
            <th scope="row">Lock to one service</th>
            <td>
                <code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[cular_intake_form type="web-development" service="dev"]</code>
                <p class="description">Add <code>service="…"</code> to skip the picker (e.g. <code>dev</code>, <code>redesign</code>, <code>new</code>, <code>maintenance</code>, <code>strategy</code>).</p>
            </td>
        </tr>
    </table>

    <p><a href="<?php echo admin_url('admin.php?page=cular-intake-form-types'); ?>" class="button">View All Form Types →</a></p>
    
    <hr />
    
    <h2>Form Styling</h2>
    <p>The form uses the Cular Creative brand colors:</p>
    <ul>
        <li><strong>Primary Green:</strong> #498158</li>
        <li><strong>Secondary Green:</strong> #2f593a</li>
        <li><strong>Accent Light Green:</strong> #6b9e7a</li>
        <li><strong>Coral:</strong> #FF715D</li>
        <li><strong>Highlight Yellow:</strong> #FFE47E</li>
    </ul>
    <p>The form uses <strong>Luxia Display</strong> font for headings and <strong>Montserrat</strong> for body text.</p>
</div>
