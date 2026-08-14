# How to Add a New Intake Form

This guide explains how to add a new intake form to the Cular Creative Intake Forms plugin (e.g., for Ads, SEO, or any other service).

## Quick Steps

1. Create the template file
2. Register the form type
3. Update the JavaScript
4. Activate and use

---

## Step 1: Create Template File

Create a new PHP file in the `templates/` folder:

**File**: `templates/intake-form-ads.php` (replace "ads" with your form type)

You can copy the existing `intake-form-web.php` and modify it for your needs, or create a completely new form structure.

**Example**: For an Ads form, copy and customize:
```bash
cp templates/intake-form-web.php templates/intake-form-ads.php
```

Then edit the form to include fields specific to advertising services:
- Campaign objectives
- Target audience
- Budget
- Ad platforms (Google, Facebook, etc.)
- Creative assets availability
- etc.

---

## Step 2: Register Form Type

Open `cular-intake-form.php` and find the `$form_types` array (around line 28).

Add your new form type:

```php
private $form_types = array(
    'web' => array(
        'name' => 'Web Services Form',
        'description' => 'Intake form for web design, development, and related services',
        'template' => 'intake-form-web.php',
        'enabled' => true
    ),
    'ads' => array(  // ← ADD THIS
        'name' => 'Advertising Services Form',
        'description' => 'Intake form for advertising and marketing campaigns',
        'template' => 'intake-form-ads.php',  // Your template filename
        'enabled' => true  // Set to false while developing, true when ready
    ),
    'seo' => array(
        'name' => 'SEO Services Form',
        'description' => 'Intake form for SEO services and optimization',
        'template' => 'intake-form-seo.php',
        'enabled' => false  // Coming soon
    ),
);
```

**Key Properties**:
- `'name'`: Display name shown in admin
- `'description'`: Brief description
- `'template'`: Template filename (must exist in `templates/` folder)
- `'enabled'`: Set to `true` to activate, `false` to hide

---

## Step 3: Update JavaScript (Optional)

If you created a separate JavaScript file for your form, or if you're using the same JS file:

Find where `form_type` is set and update it:

```javascript
// In your form's JavaScript
data.form_type = 'ads'; // Match the key you used in Step 2
```

**For Web form**, it's in `assets/js/intake-form.js` around line 341:
```javascript
data.form_type = 'web'; // This form is for web services
```

**For new forms**, either:
- Create `assets/js/intake-form-ads.js` and set `data.form_type = 'ads';`
- Or reuse the existing JS and make it dynamic based on form type

---

## Step 4: Use the Shortcode

Once your form is registered and enabled, use it anywhere with:

```
[cular_intake_form type="ads"]
```

Or for the web form (default):
```
[cular_intake_form]
[cular_intake_form type="web"]
```

---

## Admin Features

### View Form Types
Go to **Intake Forms > Form Types** to:
- See all registered forms
- Check which are active
- Copy shortcodes
- View setup instructions

### Filter Submissions
Go to **Intake Forms > All Submissions** to:
- Filter by form type
- See which form each submission came from
- View/delete submissions

### Email Notifications
Submissions automatically include the form type in the email subject:
> "New **Ads** Intake Form Submission - Campaign Name"

---

## File Structure Example

After adding an Ads form, your structure would look like:

```
cular-intake-form/
├── cular-intake-form.php (Register form here)
├── assets/
│   ├── css/
│   │   └── intake-form.css
│   └── js/
│       ├── intake-form.js (Web form JS)
│       └── intake-form-ads.js (Optional: Ads form JS)
└── templates/
    ├── intake-form-web.php
    ├── intake-form-ads.php (Your new form)
    ├── intake-form-seo.php (Future form)
    ├── admin-submissions.php
    ├── admin-settings.php
    └── admin-form-types.php
```

---

## Tips

### 1. Start with Disabled
Set `'enabled' => false` while developing, then switch to `true` when ready.

### 2. Reuse Styles
All forms share the same CSS (`assets/css/intake-form.css`), so they'll automatically have consistent branding.

### 3. Form Type Identifier
The `form_type` field in submissions helps you:
- Filter in admin
- Route to different webhooks
- Customize email templates
- Track which services get the most interest

### 4. Custom Fields
Each form can have completely different fields. You're not limited to the web form structure!

### 5. Testing
Before enabling, test with `'enabled' => false` and manually include the template in a test page.

---

## Need Help?

- Check `templates/intake-form-web.php` for a complete working example
- All forms inherit the Cular Creative branding automatically
- The plugin handles submission, storage, and notifications automatically

Happy form building! 🎨
