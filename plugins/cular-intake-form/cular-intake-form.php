<?php
/**
 * Plugin Name: Cular Creative Intake Forms
 * Plugin URI: https://cularcreative.com
 * Description: Multi-step intake forms for Cular Creative services (Web, Ads, SEO, and more)
 * Version: 1.4.0
 * Author: Cular Creative
 * Author URI: https://cularcreative.com
 * License: GPL v2 or later
 * Text Domain: cular-intake-form
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CULAR_INTAKE_VERSION', '1.4.0');
define('CULAR_INTAKE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CULAR_INTAKE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Submission notification recipients (comma-separated for wp_mail)
define(
    'CULAR_INTAKE_NOTIFICATION_TO',
    'hello@cularcreative.com, raluca@cularcreative.com, yani@cularcreative.com, management@cularcreative.com'
);
define('CULAR_INTAKE_NOTIFICATION_BCC', 'team@cularcreative.com');

class Cular_Intake_Form {
    
    private static $instance = null;
    
    // Available form types
    private $form_types = array(
        'contact' => array(
            'name' => 'Contact / General Enquiry Form',
            'description' => 'General "Book a Call with Us" form — Contact page and any service page without its own form',
            'template' => 'intake-form-contact.php',
            'script' => 'intake-form-contact.js',
            'enabled' => true
        ),
        'web-design' => array(
            'name' => 'Web Design Form',
            'description' => 'Intake form for website design (new sites & redesigns), plus strategy and maintenance',
            'template' => 'intake-form-web-design.php',
            'script' => 'intake-form.js',
            'enabled' => true
        ),
        'web-development' => array(
            'name' => 'Web Development Form',
            'description' => 'Intake form for website development / builds, plus strategy and maintenance',
            'template' => 'intake-form-web-development.php',
            'script' => 'intake-form.js',
            'enabled' => true
        ),
        'ads' => array(
            'name' => 'Advertising (Ads) Form',
            'description' => 'Intake form for paid advertising campaign management',
            'template' => 'intake-form-ads.php',
            'script' => 'intake-form-ads.js',
            'enabled' => true
        ),
        'seo' => array(
            'name' => 'SEO Form',
            'description' => 'Intake form for SEO / organic search optimisation',
            'template' => 'intake-form-seo.php',
            'script' => 'intake-form-seo.js',
            'enabled' => true
        ),
        // --- Forms ported off Elementor Pro / WPForms ---
        // These are spec-driven (templates/partials/render-spec.php) and share
        // the generic JS driver, so they carry no bespoke markup or script.
        'social-media' => array(
            'name' => 'Social Media Marketing Form',
            'description' => 'Full social media strategy intake — was the 36-question Elementor form on /form/social-media-marketing/',
            'template' => 'intake-form-social-media.php',
            'script' => 'intake-form-generic.js',
            'enabled' => true
        ),
        'content-social' => array(
            'name' => 'Content Creation — Social Media',
            'description' => 'Brief for social content production',
            'template' => 'intake-form-content-social.php',
            'script' => 'intake-form-generic.js',
            'enabled' => true
        ),
        'content-shoot' => array(
            'name' => 'Content Creation — Photo & Video Shoot',
            'description' => 'Brief for a photo/video shoot: look, mood and deliverables',
            'template' => 'intake-form-content-shoot.php',
            'script' => 'intake-form-generic.js',
            'enabled' => true
        ),
        'brand-identity' => array(
            'name' => 'Brand Identity Form',
            'description' => 'Brand meaning, audience and positioning — was WPForms #11073',
            'template' => 'intake-form-brand-identity.php',
            'script' => 'intake-form-generic.js',
            'enabled' => true
        ),
        'discovery' => array(
            'name' => 'New Client Discovery',
            'description' => 'Broad first-conversation form, before a service is chosen — was WPForms #11067',
            'template' => 'intake-form-discovery.php',
            'script' => 'intake-form-generic.js',
            'enabled' => true
        ),

        // Legacy combined web form. Kept so existing pages using
        // [cular_intake_form type="web"] keep working. Prefer the
        // Web Design / Web Development forms for new pages.
        'web' => array(
            'name' => 'Web Services Form (legacy)',
            'description' => 'Original combined web form — use Web Design / Web Development instead',
            'template' => 'intake-form-web.php',
            'script' => 'intake-form.js',
            'enabled' => true
        ),
    );
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_shortcode('cular_intake_form', array($this, 'render_form'));
        add_action('wp_ajax_cular_intake_submit', array($this, 'handle_submission'));
        add_action('wp_ajax_nopriv_cular_intake_submit', array($this, 'handle_submission'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    public function get_form_types() {
        return $this->form_types;
    }
    
    public function get_enabled_forms() {
        return array_filter($this->form_types, function($form) {
            return isset($form['enabled']) && $form['enabled'] === true;
        });
    }
    
    public function init() {
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    public function enqueue_assets() {
        // Only enqueue if shortcode is present
        global $post;
        $has_shortcode = is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'cular_intake_form');

        /**
         * Themes that render the form from a block (via do_shortcode) have no
         * literal shortcode in post_content, so they can opt in here.
         *
         * @param bool         $should_enqueue Whether to load the form assets.
         * @param WP_Post|null $post           Current post.
         */
        if (!apply_filters('cular_intake_should_enqueue', $has_shortcode, $post)) {
            return;
        }
        
        // Google Fonts
        wp_enqueue_style(
            'cular-intake-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap',
            array(),
            CULAR_INTAKE_VERSION
        );
        
        // intl-tel-input
        wp_enqueue_style(
            'intl-tel-input',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.5/build/css/intlTelInput.css',
            array(),
            '19.5.5'
        );
        
        wp_enqueue_script(
            'intl-tel-input',
            'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.5/build/js/intlTelInput.min.js',
            array(),
            '19.5.5',
            true
        );
        
        // Plugin styles (shared across all forms)
        wp_enqueue_style(
            'cular-intake-form',
            CULAR_INTAKE_PLUGIN_URL . 'assets/css/intake-form.css',
            array(),
            CULAR_INTAKE_VERSION
        );
        
        // Determine which script to load based on shortcode attributes
        $form_type = 'web'; // default
        if (is_a($post, 'WP_Post') && preg_match('/\[cular_intake_form[^\]]*type=["\']([^"\']+)/', $post->post_content, $matches)) {
            $form_type = $matches[1];
        }

        /**
         * Let a block-based theme declare which form type this page renders,
         * since a block calling do_shortcode() leaves no shortcode to sniff.
         *
         * @param string       $form_type Form type slug.
         * @param WP_Post|null $post      Current post.
         */
        $form_type = apply_filters('cular_intake_form_type', $form_type, $post);

        // Get the correct script file for this form type
        $script_file = isset($this->form_types[$form_type]['script']) 
            ? $this->form_types[$form_type]['script'] 
            : 'intake-form.js';
        
        // Plugin script (form-specific)
        wp_enqueue_script(
            'cular-intake-form-' . $form_type,
            CULAR_INTAKE_PLUGIN_URL . 'assets/js/' . $script_file,
            array('jquery', 'intl-tel-input'),
            CULAR_INTAKE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('cular-intake-form-' . $form_type, 'cular_intake_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cular_intake_nonce')
        ));
    }
    
    public function render_form($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'type' => 'web', // Default to web form
            'service' => '', // Optional: lock the form to one service and skip the picker
        ), $atts, 'cular_intake_form');

        $form_type = sanitize_text_field($atts['type']);
        // Made available inside the included template as $preselect_service.
        $preselect_service = sanitize_text_field($atts['service']);
        
        // Check if form type exists and is enabled
        if (!isset($this->form_types[$form_type])) {
            return '<p style="color: #ff0000;">Error: Form type "' . esc_html($form_type) . '" does not exist.</p>';
        }
        
        if (!$this->form_types[$form_type]['enabled']) {
            return '<p style="color: #ff6600;">This form is currently not available.</p>';
        }
        
        $template_file = CULAR_INTAKE_PLUGIN_DIR . 'templates/' . $this->form_types[$form_type]['template'];
        
        if (!file_exists($template_file)) {
            return '<p style="color: #ff0000;">Error: Form template not found.</p>';
        }
        
        ob_start();
        include $template_file;
        return ob_get_clean();
    }
    
    public function handle_submission() {
        // Verify nonce
        check_ajax_referer('cular_intake_nonce', 'nonce');
        
        // Get form data
        $form_data = isset($_POST['form_data']) ? json_decode(stripslashes($_POST['form_data']), true) : array();
        
        if (empty($form_data)) {
            wp_send_json_error('No form data received');
        }
        
        // Sanitize data
        $sanitized_data = $this->sanitize_form_data($form_data);
        
        // Save to database
        $saved = $this->save_submission($sanitized_data);
        
        if ($saved) {
            // Send email notification
            $this->send_email_notification($sanitized_data);
            
            // Send to webhook (Make.com / Zapier) if configured
            $this->send_to_webhook($sanitized_data);
            
            wp_send_json_success('Form submitted successfully');
        } else {
            wp_send_json_error('Failed to save submission');
        }
    }
    
    private function sanitize_form_data($data) {
        $sanitized = array();
        
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $sanitized[$key] = $value;
            } elseif (is_array($value)) {
                $sanitized[$key] = array_map('sanitize_text_field', $value);
            } else {
                if ($key === 'contact_email') {
                    $sanitized[$key] = sanitize_email($value);
                } else if ($key === 'website_url') {
                    $sanitized[$key] = esc_url_raw($value);
                } else {
                    $sanitized[$key] = sanitize_text_field((string) $value);
                }
            }
        }
        
        return $sanitized;
    }
    
    private function save_submission($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cular_intake_submissions';
        
        // Determine form type from data
        $form_type = isset($data['form_type']) ? $data['form_type'] : 'web';
        
        return $wpdb->insert(
            $table_name,
            array(
                'form_data' => json_encode($data),
                'form_type' => $form_type,
                'service_type' => isset($data['service']) ? $data['service'] : '',
                'business_name' => isset($data['business_name']) ? $data['business_name'] : '',
                'contact_email' => isset($data['contact_email']) ? $data['contact_email'] : '',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s')
        );
    }
    
    // Human-friendly label for each form type.
    private function get_form_type_label($raw_type) {
        $type_labels = array(
            'web'             => 'Web',
            'web-design'      => 'Web Design',
            'web-development' => 'Web Development',
            'ads'             => 'Ads',
            'seo'             => 'SEO',
            'contact'         => 'Contact',
            'social-media'    => 'Social Media Marketing',
            'content-social'  => 'Content — Social Media',
            'content-shoot'   => 'Content — Photo & Video Shoot',
            'brand-identity'  => 'Brand Identity',
            'discovery'       => 'New Client Discovery',
        );
        // Fall back to the registered form name before ucfirst()-ing the slug,
        // so a new type reads as "Brand Identity" rather than "Brand-identity"
        // in the notification subject even if nobody adds it here.
        if (isset($type_labels[$raw_type])) {
            return $type_labels[$raw_type];
        }
        if (isset($this->form_types[$raw_type]['name'])) {
            return $this->form_types[$raw_type]['name'];
        }
        return ucfirst($raw_type);
    }

    /**
     * Turn a single stored value into a readable string for the email.
     *
     * @param mixed $value
     * @return string  '' when the value is effectively empty.
     */
    private function format_email_value($value) {
        if (is_array($value)) {
            $parts = array();
            foreach ($value as $v) {
                if (is_array($v)) {
                    $parts[] = wp_json_encode($v);
                } elseif (is_bool($v)) {
                    $parts[] = $v ? 'Yes' : 'No';
                } elseif ($v !== '' && $v !== null) {
                    $parts[] = (string) $v;
                }
            }
            return implode(', ', $parts);
        }
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }
        if ($value === '' || $value === null) {
            return '';
        }
        return (string) $value;
    }

    /**
     * Readable list of the submitted fields for notification emails.
     * Empty fields and internal/duplicate keys are skipped so the email
     * stays short and easy to scan.
     *
     * @param array $data Sanitized submission data.
     * @return string
     */
    private function format_submission_email_body($data) {
        // Already shown in the summary header, or internal-only, or empty flags.
        $skip_keys = array(
            'form_type', 'service', 'service_label',
            'business_name', 'contact_name', 'contact_email',
            'contact_phone_raw', 'contact_phone_e164', 'contact_phone_country',
            'contact_phone_valid', 'opt_ads', 'opt_seo',
            'addon_ads', 'addon_seo', 'addon_strategy',
        );

        $lines = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $skip_keys, true)) {
                continue;
            }
            $display = $this->format_email_value($value);
            if ($display === '') {
                continue; // skip empty fields
            }
            $label = ucwords(str_replace(array('_', '-'), ' ', $key));
            $lines[] = $label . ': ' . $display;
        }
        return implode("\n", $lines);
    }

    // Notification recipients: dashboard settings take priority, then the
    // hardcoded constants act as a fallback/default.
    private function get_notification_to() {
        $to = trim((string) get_option('cular_intake_notify_to', ''));
        return $to !== '' ? $to : CULAR_INTAKE_NOTIFICATION_TO;
    }

    private function get_notification_bcc() {
        // Stored option may legitimately be empty (no BCC), so only fall back
        // to the constant when the option was never saved.
        $bcc = get_option('cular_intake_notify_bcc', null);
        return $bcc === null ? CULAR_INTAKE_NOTIFICATION_BCC : trim((string) $bcc);
    }

    private function send_email_notification($data) {
        $to = $this->get_notification_to();
        $bcc = $this->get_notification_bcc();

        $raw_type = isset($data['form_type']) ? $data['form_type'] : 'web';
        $form_type = $this->get_form_type_label($raw_type);
        $service_label = isset($data['service_label']) ? $data['service_label'] : 'Unknown';

        $subject = 'New ' . $form_type . ' Intake Form Submission - ' . $service_label;

        // Readable phone: prefer the normalised E.164 number, fall back to raw.
        $phone = '';
        if (!empty($data['contact_phone_e164'])) {
            $phone = $data['contact_phone_e164'];
        } elseif (!empty($data['contact_phone_raw'])) {
            $phone = $data['contact_phone_raw'];
        }

        // Summary header — the essentials at a glance.
        $summary = array();
        $summary[] = 'Service:  ' . $service_label;
        $summary[] = 'Form:     ' . $form_type;
        $summary[] = 'Business: ' . (isset($data['business_name']) && $data['business_name'] !== '' ? $data['business_name'] : '—');
        $summary[] = 'Contact:  ' . (isset($data['contact_name']) ? $data['contact_name'] : '') . (isset($data['contact_email']) && $data['contact_email'] !== '' ? ' <' . $data['contact_email'] . '>' : '');
        if ($phone !== '') {
            $summary[] = 'Phone:    ' . $phone;
        }

        $message  = "New intake form submission\n";
        $message .= "==========================\n\n";
        $message .= implode("\n", $summary);
        $message .= "\n\n--- Details ---\n";
        $message .= $this->format_submission_email_body($data);
        $message .= "\n\n---\nView full details in WordPress admin.\n";

        $headers = array('Content-Type: text/plain; charset=UTF-8');
        if ($bcc !== '') {
            $headers[] = 'Bcc: ' . $bcc;
        }

        wp_mail($to, $subject, $message, $headers);
    }
    
    private function send_to_webhook($data) {
        $webhook_url = get_option('cular_intake_webhook_url', '');
        
        if (empty($webhook_url)) {
            return;
        }
        
        wp_remote_post($webhook_url, array(
            'body' => json_encode($data),
            'headers' => array('Content-Type' => 'application/json'),
            'timeout' => 15
        ));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Intake Form Submissions',
            'Intake Forms',
            'manage_options',
            'cular-intake-submissions',
            array($this, 'render_admin_page'),
            'dashicons-forms',
            30
        );
        
        add_submenu_page(
            'cular-intake-submissions',
            'All Submissions',
            'All Submissions',
            'manage_options',
            'cular-intake-submissions',
            array($this, 'render_admin_page')
        );
        
        add_submenu_page(
            'cular-intake-submissions',
            'Form Types',
            'Form Types',
            'manage_options',
            'cular-intake-form-types',
            array($this, 'render_form_types_page')
        );
        
        add_submenu_page(
            'cular-intake-submissions',
            'Settings',
            'Settings',
            'manage_options',
            'cular-intake-settings',
            array($this, 'render_settings_page')
        );
    }
    
    public function render_admin_page() {
        include CULAR_INTAKE_PLUGIN_DIR . 'templates/admin-submissions.php';
    }
    
    public function render_form_types_page() {
        include CULAR_INTAKE_PLUGIN_DIR . 'templates/admin-form-types.php';
    }
    
    public function render_settings_page() {
        // Save settings
        if (isset($_POST['cular_intake_settings_submit'])) {
            check_admin_referer('cular_intake_settings');

            update_option('cular_intake_webhook_url', esc_url_raw($_POST['webhook_url']));

            $notify_to = isset($_POST['notify_to']) ? $this->sanitize_email_list($_POST['notify_to']) : '';
            $notify_bcc = isset($_POST['notify_bcc']) ? $this->sanitize_email_list($_POST['notify_bcc']) : '';
            update_option('cular_intake_notify_to', $notify_to);
            update_option('cular_intake_notify_bcc', $notify_bcc);

            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }

        include CULAR_INTAKE_PLUGIN_DIR . 'templates/admin-settings.php';
    }

    /**
     * Sanitize a comma-separated list of email addresses.
     * Invalid addresses are dropped; the rest are re-joined with ", ".
     *
     * @param string $raw
     * @return string
     */
    private function sanitize_email_list($raw) {
        $emails = array();
        foreach (explode(',', (string) $raw) as $email) {
            $email = sanitize_email(trim($email));
            if (!empty($email)) {
                $emails[] = $email;
            }
        }
        return implode(', ', $emails);
    }
}

// Activation hook - create database table
register_activation_hook(__FILE__, 'cular_intake_activate');
function cular_intake_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cular_intake_submissions';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        form_data longtext NOT NULL,
        form_type varchar(50) DEFAULT 'web',
        service_type varchar(50) DEFAULT '',
        business_name varchar(255) DEFAULT '',
        contact_email varchar(255) DEFAULT '',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY form_type (form_type),
        KEY created_at (created_at)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Initialize plugin
Cular_Intake_Form::get_instance();
