<?php
/**
 * Admin Submissions Page
 */
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'cular_intake_submissions';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    check_admin_referer('delete_submission_' . intval($_GET['id']));
    $wpdb->delete($table_name, array('id' => intval($_GET['id'])), array('%d'));
    echo '<div class="notice notice-success"><p>Submission deleted successfully!</p></div>';
}

// Filter by form type
$filter_form_type = isset($_GET['form_type']) ? sanitize_text_field($_GET['form_type']) : '';

// Get submissions
if ($filter_form_type) {
    $submissions = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE form_type = %s ORDER BY created_at DESC",
        $filter_form_type
    ));
} else {
    $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
}

// Get all unique form types for filter
$form_types = $wpdb->get_col("SELECT DISTINCT form_type FROM $table_name ORDER BY form_type");

?>

<div class="wrap">
    <h1>Intake Form Submissions</h1>
    
    <!-- Filter by Form Type -->
    <?php if (!empty($form_types)): ?>
        <div style="margin: 20px 0;">
            <label for="form-type-filter" style="margin-right: 10px; font-weight: 600;">Filter by Form Type:</label>
            <select id="form-type-filter" onchange="window.location.href='?page=cular-intake-submissions&form_type=' + this.value;">
                <option value="">All Forms</option>
                <?php foreach ($form_types as $type): ?>
                    <option value="<?php echo esc_attr($type); ?>" <?php selected($filter_form_type, $type); ?>>
                        <?php echo esc_html(ucfirst($type)); ?> Services
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($filter_form_type): ?>
                <a href="?page=cular-intake-submissions" class="button" style="margin-left: 10px;">Clear Filter</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($submissions)): ?>
        <p>No submissions found<?php echo $filter_form_type ? ' for ' . esc_html(ucfirst($filter_form_type)) . ' form' : ''; ?>.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="10%">Form Type</th>
                    <th width="15%">Service</th>
                    <th width="18%">Business</th>
                    <th width="17%">Contact Email</th>
                    <th width="13%">Date</th>
                    <th width="22%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): 
                    $form_type_display = isset($submission->form_type) ? ucfirst($submission->form_type) : 'Web';
                ?>
                    <tr>
                        <td><?php echo esc_html($submission->id); ?></td>
                        <td><span style="background: #498158; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;"><?php echo esc_html($form_type_display); ?></span></td>
                        <td><?php echo esc_html($submission->service_type); ?></td>
                        <td><?php echo esc_html($submission->business_name); ?></td>
                        <td><?php echo esc_html($submission->contact_email); ?></td>
                        <td><?php echo esc_html($submission->created_at); ?></td>
                        <td>
                            <a href="#" class="button view-submission" data-id="<?php echo esc_attr($submission->id); ?>">View Details</a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=cular-intake-submissions&action=delete&id=' . $submission->id), 'delete_submission_' . $submission->id); ?>" 
                               class="button" 
                               onclick="return confirm('Are you sure you want to delete this submission?');">Delete</a>
                        </td>
                    </tr>
                    <tr class="submission-details" id="details-<?php echo esc_attr($submission->id); ?>" style="display:none;">
                        <td colspan="6">
                            <div style="background: #f5f5f5; padding: 15px; margin: 10px 0;">
                                <h3>Submission Details</h3>
                                <pre style="background: white; padding: 15px; overflow: auto; max-height: 500px;"><?php 
                                    $data = json_decode($submission->form_data, true);
                                    echo esc_html(print_r($data, true)); 
                                ?></pre>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <script>
        jQuery(document).ready(function($) {
            $('.view-submission').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#details-' + id).toggle();
                $(this).text($(this).text() === 'View Details' ? 'Hide Details' : 'View Details');
            });
        });
        </script>
    <?php endif; ?>
</div>
