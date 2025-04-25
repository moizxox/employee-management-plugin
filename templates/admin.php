<?php
function add_employee_dashboard()
{
    add_menu_page(
        'Employee Dashboard',
        'Employee Dashboard',
        'manage_options',
        'employee-dashboard',
        'render_employee_dashboard',
        'dashicons-groups',
        21
    );
}
add_action('admin_menu', 'add_employee_dashboard');

function render_employee_dashboard()
{
    global $wpdb;

    $employees = $wpdb->get_results("
        SELECT 
            p.ID, 
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

    $employees_json = json_encode($employees);
?>
<div class="wrap">


    <h1 class="wp-heading-inline">Employee List</h1>
    <div class="action-sec">
        <?php do_action('employee_dashboard_stats'); ?>
        <div class="action-btns">
            <?php
        do_action('employee_dashboard_actions');
        ?>
        </div>
    </div>

    <table class="wp-list-table employee-table" id="employee-table">
        <thead>
            <tr>
                <th class="sortable" data-sort="name">Name</th>
                <th>Email</th>
                <th>Position</th>
                <th class="sortable" data-sort="salary">Salary</th>
                <th class="sortable" data-sort="date_of_hire">Date of Hire</th>
            </tr>
        </thead>
        <tbody id="employee-table-body">
            <?php if (!empty($employees)) : ?>
            <?php foreach ($employees as $employee) : ?>
            <tr>
                <td><?php echo esc_html($employee->name); ?></td>
                <td><?php echo esc_html($employee->email); ?></td>
                <td><?php echo esc_html($employee->position); ?></td>
                <td><?php echo esc_html($employee->salary); ?></td>
                <td><?php echo esc_html($employee->date_of_hire); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="5" class="empty-message">No employees found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="dashboard-charts">
        <div class="chart-container">
            <h2>Salary Distribution</h2>
            <canvas id="salaryChart"></canvas>
        </div>
        <div class="chart-container">
            <h2>Employees by Position</h2>
            <canvas id="positionChart"></canvas>
        </div>
        <div class="chart-container">
            <h2>Hiring Timeline</h2>
            <canvas id="hiringChart"></canvas>
        </div>
    </div>
</div>

<script>
var employeeData = <?php echo $employees_json; ?>;
</script>
<?php
}