<?php
/**
 * Admin Form Types Page
 */
if (!defined('ABSPATH')) {
    exit;
}

$plugin = Cular_Intake_Form::get_instance();
$form_types = $plugin->get_form_types();
?>

<div class="wrap">
    <h1>Intake Form Types</h1>
    
    <p>Manage different intake forms for various services. Each form can be displayed using its unique shortcode.</p>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th width="20%">Form Type</th>
                <th width="30%">Description</th>
                <th width="15%">Status</th>
                <th width="35%">Shortcode</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($form_types as $key => $form): ?>
                <tr>
                    <td><strong><?php echo esc_html($form['name']); ?></strong></td>
                    <td><?php echo esc_html($form['description']); ?></td>
                    <td>
                        <?php if ($form['enabled']): ?>
                            <span style="color: #46b450; font-weight: 600;">● Active</span>
                        <?php else: ?>
                            <span style="color: #dc3232; font-weight: 600;">● Coming Soon</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($form['enabled']): ?>
                            <code style="background: #f0f0f1; padding: 5px 10px; border-radius: 3px;">[cular_intake_form type="<?php echo esc_attr($key); ?>"]</code>
                            <button class="button button-small copy-shortcode" data-shortcode='[cular_intake_form type="<?php echo esc_attr($key); ?>"]' style="margin-left: 10px;">Copy</button>
                        <?php else: ?>
                            <em style="color: #999;">Not available yet</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-left: 4px solid #498158;">
        <h2 style="margin-top: 0;">How to Add a New Form</h2>
        <ol>
            <li>Create a new template file in <code>templates/</code> folder (e.g., <code>intake-form-ads.php</code>)</li>
            <li>Add the form type to the <code>$form_types</code> array in <code>cular-intake-form.php</code></li>
            <li>Set <code>'enabled' => true</code> when the form is ready</li>
            <li>Update the JavaScript file to include the correct <code>form_type</code> identifier</li>
        </ol>
        
        <h3>Example Form Type Configuration:</h3>
        <pre style="background: white; padding: 15px; overflow: auto; border-radius: 5px;">'ads' => array(
    'name' => 'Advertising Services Form',
    'description' => 'Intake form for advertising and marketing services',
    'template' => 'intake-form-ads.php',
    'enabled' => true
),</pre>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('.copy-shortcode').on('click', function(e) {
            e.preventDefault();
            var shortcode = $(this).data('shortcode');
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(shortcode).select();
            document.execCommand('copy');
            $temp.remove();
            
            var $btn = $(this);
            var originalText = $btn.text();
            $btn.text('Copied!').css('color', '#46b450');
            setTimeout(function() {
                $btn.text(originalText).css('color', '');
            }, 2000);
        });
    });
    </script>
</div>
