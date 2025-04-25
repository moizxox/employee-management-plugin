<?php
/*  Plugin Name: Employee Management
*   Description: A plugin that will manage employee data.
*   Version: 1.0
*   Author: Mian Moiz   
*   Author URI: https://moiz.codeletdigital.com
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '/includes/employee-cpt.php';
require_once plugin_dir_path(__FILE__) . '/includes/employee-cfs.php';
require_once plugin_dir_path(__FILE__) . '/includes/employee-export.php';
require_once plugin_dir_path(__FILE__) . '/includes/employee-import.php';
require_once plugin_dir_path(__FILE__) . '/includes/employee-ajax.php';
require_once plugin_dir_path(__FILE__) . '/templates/admin.php';

function employee_management_enqueue_assets($hook) {
    if ($hook != 'toplevel_page_employee-dashboard') {
        return;
    }
    
    $plugin_url = plugin_dir_url(__FILE__);
    
    wp_enqueue_style('employee-styles', $plugin_url . 'assets/css/employee-styles.css', array(), '1.0.0');
    wp_enqueue_script('employee-scripts', $plugin_url . 'assets/js/employee-scripts.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
    wp_enqueue_script('employee-charts', $plugin_url . 'assets/js/employee-charts.js', array('jquery', 'chartjs'), '1.0.0', true);
    wp_enqueue_script('employee-ajax', $plugin_url . 'assets/js/employee-ajax.js', array('jquery'), '1.0.0', true);
    wp_localize_script('employee-ajax', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('admin_enqueue_scripts', 'employee_management_enqueue_assets');