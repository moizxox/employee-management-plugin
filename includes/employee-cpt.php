<?php
function make_employee_cpt()
{
    $labels = [
        'name'               => 'Employees',
        'singular_name'      => 'Employee',
        'menu_name'          => 'Employees',
        'name_admin_bar'     => 'Employee',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Employee',
        'edit_item'          => 'Edit Employee',
        'new_item'           => 'New Employee',
        'view_item'          => 'View Employee',
        'all_items'          => 'All Employees',
        'search_items'       => 'Search Employees',
        'not_found'          => 'No employees found',
        'not_found_in_trash' => 'No employees found in Trash',
        
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'rewrite'            => ['slug' => 'employee'],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-admin-users',
        'menu_position'      => 20,
        'supports'           => ['title',],
    ];
    register_post_type('employee', $args);
}

add_action('init', 'make_employee_cpt');