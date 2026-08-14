# Cular Creative Intake Forms - WordPress Plugin

A beautiful, multi-form intake system for Cular Creative services with Cular branding. Supports multiple form types for different services (Web, Ads, SEO, and more).

## Features

- **Multiple Form Types**: Web Services, Advertising & SEO forms
- **Multi-Step Forms**: 5-6 step process for collecting detailed client information
- **Service Selection**: Customized service options for each form type
- **Smart Conditional Fields**: Fields appear/hide based on user selections
- **International Phone Input**: Full country selector with validation
- **Email Notifications**: Automatic notifications when forms are submitted
- **Webhook Integration**: Send data to Make.com, Zapier, or custom endpoints
- **Database Storage**: All submissions stored in WordPress database
- **Admin Interface**: View and manage submissions from WordPress admin
- **Cular Brand Colors**: #498158 green primary, #2F593A secondary, with Coral (#FF715D) and Yellow (#FFE47E) accents
- **Custom Typography**: Luxia Display for headings, Montserrat for body text
- **Fully Responsive**: Optimized for mobile, tablet, and desktop

## Installation

1. Upload the `cular-intake-form` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Intake Forms' > 'Settings' to configure email and webhook
4. Use the shortcode `[cular_intake_form]` on any page

## Shortcode Usage

Each service has its own dedicated form so customers land directly on the
right one. Put each shortcode on its own page.

### Web Design Form
```
[cular_intake_form type="web-design"]
```
Website design (new sites & redesigns). Strategy, Maintenance and "Not sure"
remain available as options inside the form.

### Web Development Form
```
[cular_intake_form type="web-development"]
```
Website development / builds. Strategy, Maintenance and "Not sure" remain
available as options inside the form.

### Ads Form
```
[cular_intake_form type="ads"]
```
Paid advertising campaign management only.

### SEO Form
```
[cular_intake_form type="seo"]
```
SEO / organic search optimisation only.

### Legacy combined web form (backward compatible)
```
[cular_intake_form type="web"]
```
The original combined web form. Kept so existing pages keep working — prefer
the Web Design / Web Development forms above for new pages.

> **Where do submissions go?** Every form (web, ads, seo) emails the same
> recipients defined by `CULAR_INTAKE_NOTIFICATION_TO` / `CULAR_INTAKE_NOTIFICATION_BCC`
> in `cular-intake-form.php`, saves to the database, and (optionally) posts to
> the webhook set in **Intake Forms > Settings**. The email subject includes a
> friendly form-type label, e.g. "New Ads Intake Form Submission".

## Configuration

### Email Notifications
1. Go to **Intake Forms > Settings**
2. Enter your notification email address
3. Submissions will be sent to this email automatically

### Webhook Integration
1. Go to **Intake Forms > Settings**
2. Enter your webhook URL (Make.com, Zapier, etc.)
3. Form data will be automatically posted as JSON

## Managing Forms

### View Form Types
1. Go to **Intake Forms > Form Types**
2. See all available form types and their status
3. Copy shortcodes for each form
4. Instructions for adding new forms

### Viewing Submissions
1. Go to **Intake Forms > All Submissions**
2. Filter submissions by form type
3. View all submissions in a table format
4. Click "View Details" to see complete submission data
5. Delete submissions as needed

## Form Steps

1. **Service Selection**: Choose the type of service needed
2. **Business & Contact**: Company and contact information
3. **Domain & Hosting Access**: Technical access details
4. **Details**: Service-specific questions based on selection
5. **Review & Submit**: Final review and submission

## Conditional Logic

The form adapts based on the service selected:

- **New Website / Redesign / Development**: Full project intake fields
- **Strategy & Planning**: Strategic planning specific questions
- **Website Maintenance**: Maintenance plan and task selection
- **Not Sure**: Simplified submission

## Brand Colors

- **Primary Green**: `#498158` (Eden Green)
- **Secondary Green**: `#2F593A` (Dark Eden)
- **Light Green**: `#6B9E7A` (Sage)
- **Coral Red**: `#FF715D`
- **Highlight Yellow**: `#FFE47E`
- **Background**: `#F5F5F0` (Warm Beige)

## Typography

- **Headings**: Luxia Display (Serif)
- **Body**: Montserrat (Sans-serif)

## File Structure

```
cular-intake-form/
├── cular-intake-form.php          # Main plugin file
├── README.md                       # Documentation
├── HOW-TO-ADD-NEW-FORM.md         # Guide for adding forms
├── assets/
│   ├── css/
│   │   └── intake-form.css        # Shared styles
│   └── js/
│       ├── intake-form.js         # Shared web form JavaScript (design + development + legacy)
│       ├── intake-form-ads.js     # Ads form JavaScript
│       └── intake-form-seo.js     # SEO form JavaScript
└── templates/
    ├── intake-form-web-design.php        # Web Design form template
    ├── intake-form-web-development.php    # Web Development form template
    ├── intake-form-web.php                # Legacy combined web form template
    ├── intake-form-ads.php                # Ads form template
    ├── intake-form-seo.php                # SEO form template
    ├── partials/
    │   └── web-shared-steps.php           # Shared steps 2-5 for web forms
    ├── admin-submissions.php              # Submissions list page
    ├── admin-settings.php                 # Settings page
    └── admin-form-types.php               # Form types management
```

## Technical Details

- **WordPress Version**: 5.0 or higher
- **PHP Version**: 7.4 or higher
- **Dependencies**: jQuery, intl-tel-input library
- **Database**: Creates custom table `wp_cular_intake_submissions` with `form_type` column

## Support

For support, please contact Cular Creative at support@cularcreative.com

## Adding New Forms

To add a new intake form (e.g., for Ads or SEO services):

1. **Create Template File**: Create `templates/intake-form-[type].php` (e.g., `intake-form-ads.php`)
2. **Register Form Type**: Add to `$form_types` array in `cular-intake-form.php`:
```php
'ads' => array(
    'name' => 'Advertising Services Form',
    'description' => 'Intake form for advertising services',
    'template' => 'intake-form-ads.php',
    'enabled' => true  // Set to false until ready
),
```
3. **Update JavaScript**: In your form's JS, set the correct `form_type`:
```javascript
data.form_type = 'ads'; // Match your form type key
```
4. **Use Shortcode**: `[cular_intake_form type="ads"]`

The plugin automatically handles routing, submissions, and filtering!

## Version

1.4.0 - July 2026
- Split the combined Ads & SEO form into separate **Ads** and **SEO** forms
- Split the Web form into **Web Design** and **Web Development** forms (each keeps
  Strategy / Maintenance / Not-sure as options)
- Web forms now share steps 2-5 via `templates/partials/web-shared-steps.php`
- Friendly form-type labels in notification email subjects
- Kept legacy `type="web"` shortcode working for existing pages

1.1.0 - January 2026
- Added Advertising & SEO Services Form
- Improved responsive design for mobile devices
- Removed draft save functionality
- Enhanced font sizing (12px-16px range)

## Author

Cular Creative - https://cularcreative.com

## License

GPL v2 or later
