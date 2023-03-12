<?php
/**
 * Handle custom post type
 */

/**
 * Register a custom post type
 */
add_action('init', 'gmuw_websitesgmu_register_custom_post_type_ga_accounts');
function gmuw_websitesgmu_register_custom_post_type_ga_accounts() {

    $labels = array(
        'name'                  => 'GA Account(s)',
        'singular_name'         => 'GA Account',
        'menu_name'             => 'GA Accounts',
        'name_admin_bar'        => 'GA Account',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GA Account',
        'new_item'              => 'New GA Account',
        'edit_item'             => 'Edit GA Account',
        'view_item'             => 'View GA Account',
        'all_items'             => 'All GA Accounts',
        'search_items'          => 'Search GA accounts',
        'parent_item_colon'     => 'Parent GA account:',
        'not_found'             => 'No GA accounts found.',
        'not_found_in_trash'    => 'No GA accounts found in Trash.',
        'featured_image'        => 'GA Account Image',
        'set_featured_image'    => 'Set GA account image',
        'remove_featured_image' => 'Remove GA account image',
        'use_featured_image'    => 'Use as GA account image',
        'archives'              => 'GA Accounts archives',
        'insert_into_item'      => 'Insert into GA account',
        'uploaded_to_this_item' => 'Uploaded to this GA account',
        'filter_items_list'     => 'Filter GA account list',
        'items_list_navigation' => 'GA account list navigation',
        'items_list'            => 'GA account list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'ga_account'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => gmuw_websitesgmu_get_cpt_icon('ga_account'),
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register custom post type
    register_post_type('ga_account', $args);

}

// Add additional columns to the admin list
add_filter ('manage_ga_account_posts_columns', 'gmuw_websitesgmu_add_columns_ga_account');
function gmuw_websitesgmu_add_columns_ga_account ($columns) {

    return array_merge ( $columns, array (
        //ACF fields
        'ga_account_id'   => 'GA Account ID',
        //Other fields
        'ga_account_link' => 'GA Account Link',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_ga_account_posts_custom_column', 'gmuw_websitesgmu_ga_account_custom_column', 10, 2);
function gmuw_websitesgmu_ga_account_custom_column ($column, $post_id) {

    switch ($column) {
        case 'ga_account_id':
            echo get_post_meta($post_id, 'ga_account_id', true);
            break;
        case 'ga_account_link':
            echo '<a href="https://analytics.google.com/analytics/web/#/a'.get_post_meta($post_id, 'ga_account_id', true).'p0/admin" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a><br />';
            break;
    }

}
