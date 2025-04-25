<?php
/**
 * Employee Import Functionality
 * 
 * Handles importing employee data from CSV format
 */

if (!defined('ABSPATH')) {
    exit;
}

function register_employee_import() {
    add_action('admin_post_import_employees_csv', 'handle_employee_import');
    add_action('employee_dashboard_actions', 'add_import_button', 15);    
    add_action('admin_footer-toplevel_page_employee-dashboard', 'add_import_scripts');
}
add_action('init', 'register_employee_import');


function add_import_button() {
    ?>
<div class="csv-actions">
    <button id="import-csv-btn" class="import-csv-btn">Import from CSV</button>

    <div id="csv-import-form" class="csv-import-form">
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="import_employees_csv">
            <?php wp_nonce_field('import_employees_csv_nonce', 'import_nonce'); ?>

            <input type="file" name="employee_csv" accept=".csv" required>
            <p class="description">CSV should have columns: Name, Email, Position, Salary, Date of Hire</p>

            <button type="submit">Upload and Import</button>
        </form>
    </div>
</div>
<?php
}


function add_import_scripts() {
    ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const importBtn = document.getElementById('import-csv-btn');
    const importForm = document.getElementById('csv-import-form');

    if (importBtn && importForm) {
        importBtn.addEventListener('click', function() {
            importForm.classList.toggle('active');
        });
    }
});
</script>
<?php
}


function handle_employee_import() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    if (!isset($_POST['import_nonce']) || !wp_verify_nonce($_POST['import_nonce'], 'import_employees_csv_nonce')) {
        wp_die('Security check failed.');
    }
    
    if (!isset($_FILES['employee_csv']) || $_FILES['employee_csv']['error'] !== UPLOAD_ERR_OK) {
        wp_die('File upload failed. Please try again.');
    }
    
    $file = $_FILES['employee_csv']['tmp_name'];
    
    $handle = fopen($file, 'r');
    if (!$handle) {
        wp_die('Could not open the CSV file.');
    }
    
    $header = fgetcsv($handle);
    
    $expected_columns = array('Name', 'Email', 'Position', 'Salary', 'Date of Hire');
    if (count(array_intersect($header, $expected_columns)) !== count($expected_columns)) {
        fclose($handle);
        wp_die('CSV file does not have the required columns. Please check the format.');
    }
    
    $imported = 0;
    $errors = 0;
    
    while (($data = fgetcsv($handle)) !== false) {
        $employee_data = array_combine($header, $data);
        
        if (empty($employee_data['Name']) || empty($employee_data['Email'])) {
            $errors++;
            continue;
        }
        
        $name = sanitize_text_field($employee_data['Name']);
        $email = sanitize_email($employee_data['Email']);
        $position = sanitize_text_field($employee_data['Position']);
        $salary = sanitize_text_field($employee_data['Salary']);
        $date_of_hire = sanitize_text_field($employee_data['Date of Hire']);
        
        $post_id = wp_insert_post(array(
            'post_title'    => $name,
            'post_status'   => 'publish',
            'post_type'     => 'employee',
        ));
        
        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, 'emp_email', $email);
            update_post_meta($post_id, 'emp_position', $position);
            update_post_meta($post_id, 'emp_salary', $salary);
            update_post_meta($post_id, 'emp_date_of_hire', $date_of_hire);
            
            $imported++;
        } else {
            $errors++;
        }
    }
    
    fclose($handle);
    
    $redirect_url = add_query_arg(
        array(
            'page' => 'employee-dashboard',
            'imported' => $imported,
            'errors' => $errors,
        ),
        admin_url('admin.php')
    );
    
    wp_redirect($redirect_url);
    exit;
}

function display_import_status() {
    if (isset($_GET['imported']) || isset($_GET['errors'])) {
        $imported = isset($_GET['imported']) ? intval($_GET['imported']) : 0;
        $errors = isset($_GET['errors']) ? intval($_GET['errors']) : 0;
        
        if ($imported > 0) {
            echo '<div class="notice notice-success is-dismissible"><p>' . 
                sprintf(_n('%d employee imported successfully.', '%d employees imported successfully.', $imported), $imported) . 
                '</p></div>';
        }
        
        if ($errors > 0) {
            echo '<div class="notice notice-error is-dismissible"><p>' . 
                sprintf(_n('%d employee failed to import.', '%d employees failed to import.', $errors), $errors) . 
                '</p></div>';
        }
    }
}
add_action('admin_notices', 'display_import_status');