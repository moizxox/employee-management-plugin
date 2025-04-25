<?php
/**
 * Employee AJAX Functionality
 * 
 * Handles AJAX requests for employee data
 */

if (!defined('ABSPATH')) {
    exit;
}


function register_employee_ajax_handlers() {
    add_action('wp_ajax_calculate_average_salary', 'handle_calculate_average_salary');
}
add_action('init', 'register_employee_ajax_handlers');

function handle_calculate_average_salary() {
    check_ajax_referer('avg_salary_nonce', 'security');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
        return;
    }
    
    global $wpdb;
    
    $salaries = $wpdb->get_col("
        SELECT em.meta_value
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} em ON p.ID = em.post_id AND em.meta_key = 'emp_salary'
        WHERE p.post_type = 'employee' AND p.post_status = 'publish'
    ");
    
    $total_salary = 0;
    $count = 0;
    
    foreach ($salaries as $salary) {
        $salary_value = floatval($salary);
        if ($salary_value > 0) {
            $total_salary += $salary_value;
            $count++;
        }
    }
    
    $average_salary = ($count > 0) ? ($total_salary / $count) : 0;
    
    wp_send_json_success(array(
        'average' => $average_salary,
        'count' => $count
    ));
}

function add_average_salary_section() {
    ?>
<div class="salary-stats-container">
    <div class="salary-stat">
        <div class="head-container">
            <h3>Average Employee Salary</h3>
            <a href="#" id="refresh-average" class="refresh-stat">
                <span class="dashicons dashicons-update"></span>
            </a>
        </div>
        <div class="stat-value" id="average-salary-container">
            <span class="loading">Calculating...</span>
        </div>

    </div>
</div>
<script>
var avgSalaryNonce = '<?php echo wp_create_nonce('avg_salary_nonce'); ?>';
</script>
<?php
}
add_action('employee_dashboard_stats', 'add_average_salary_section');