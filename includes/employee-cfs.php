<?php
function add_custom_fields()
{
    add_meta_box(
        'employee_details',
        'Employee Details',
        'render_employees_cfs',
        'employee',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_custom_fields');

function render_employees_cfs($post)
{
    wp_nonce_field('save_employee_fields', 'employee_nonce');

    $position = get_post_meta($post->ID, 'emp_position', true);
    $email = get_post_meta($post->ID, 'emp_email', true);
    $date_of_hire = get_post_meta($post->ID, 'emp_date_of_hire', true);
    $salary = get_post_meta($post->ID, 'emp_salary', true);

?>
<p><label>Position:</label><br />
    <input type="text" name="emp_position" value="<?= esc_attr($position) ?>" style="width:100%;" />
</p>

<p><label>Email:</label><br />
    <input type="email" name="emp_email" value="<?= esc_attr($email) ?>" style="width:100%;" />
</p>

<p><label>Date of Hire:</label><br />
    <input type="date" name="emp_date_of_hire" value="<?= esc_attr($date_of_hire) ?>" style="width:100%;" />
</p>

<p><label>Salary:</label><br />
    <input type="number" name="emp_salary" value="<?= esc_attr($salary) ?>" style="width:100%;" />
</p>
<?php
}


function save_employee_fields($post_id)
{
    if (get_post_type($post_id) != 'employee') return;

    if (!isset($_POST['employee_nonce']) || !wp_verify_nonce($_POST['employee_nonce'], 'save_employee_fields')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['emp_position'])) {
        update_post_meta($post_id, 'emp_position', sanitize_text_field($_POST['emp_position']));
    }
    if (isset($_POST['emp_email'])) {
        update_post_meta($post_id, 'emp_email', sanitize_email($_POST['emp_email']));
    }
    if (isset($_POST['emp_date_of_hire'])) {
        update_post_meta($post_id, 'emp_date_of_hire', sanitize_text_field($_POST['emp_date_of_hire']));
    }
    if (isset($_POST['emp_salary'])) {
        update_post_meta($post_id, 'emp_salary', floatval($_POST['emp_salary']));
    }
}
add_action('save_post', 'save_employee_fields');

function change_employee_title_placeholder($title)
{
    $screen = get_current_screen();
    if ('employee' == $screen->post_type) {
        $title = 'Employee Name';
    }
    return $title;
}
add_filter('enter_title_here', 'change_employee_title_placeholder');


function change_employee_title_column($columns)
{
    if (isset($columns['title'])) {
        $columns['title'] = 'Name';
    }
    return $columns;
}
add_filter('manage_employee_posts_columns', 'change_employee_title_column');