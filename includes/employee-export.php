<?php
/**
 * Employee Export Functionality
 * 
 * Handles exporting employee data to CSV format
 */

if (!defined('ABSPATH')) {
    exit;
}


function register_employee_export() {
    add_action('admin_post_export_employees_csv', 'handle_employee_export');
    add_action('employee_dashboard_actions', 'add_export_button', 10);
}
add_action('init', 'register_employee_export');

function add_export_button() {
    $export_url = admin_url('admin-post.php?action=export_employees_csv');
    echo '<a href="' . esc_url($export_url) . '" class="export-csv-btn">Export to CSV</a>';
}

function handle_employee_export() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    global $wpdb;
    
    $employees = $wpdb->get_results("
        SELECT 
            p.post_title as name, 
            em1.meta_value AS email,
            em2.meta_value AS position,
            em3.meta_value AS salary,
            em4.meta_value AS date_of_hire
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} em1 ON p.ID = em1.post_id AND em1.meta_key = 'emp_email'
        LEFT JOIN {$wpdb->postmeta} em2 ON p.ID = em2.post_id AND em2.meta_key = 'emp_position'
        LEFT JOIN {$wpdb->postmeta} em3 ON p.ID = em3.post_id AND em3.meta_key = 'emp_salary'
        LEFT JOIN {$wpdb->postmeta} em4 ON p.ID = em4.post_id AND em4.meta_key = 'emp_date_of_hire'
        WHERE p.post_type = 'employee' AND p.post_status = 'publish'
    ");
    
    $filename = 'employee-data-' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    
    fputcsv($output, array('Name', 'Email', 'Position', 'Salary', 'Date of Hire'));
    
    foreach ($employees as $employee) {
        $row = array(
            sanitize_text_field($employee->name),
            sanitize_email($employee->email),
            sanitize_text_field($employee->position),
            sanitize_text_field($employee->salary),
            sanitize_text_field($employee->date_of_hire)
        );
        
        fputcsv($output, $row);
    }
    
    fclose($output);
    
    exit;
}