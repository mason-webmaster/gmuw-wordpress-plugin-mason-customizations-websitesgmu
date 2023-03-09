<?php

/**
 * Summary: php file which implements the custom post types
 */


/**
 * Register custom post types
 */
add_action('init', function(){

    // Register website custom post type.

    // Define labels for website post type
    $labels = array(
        'name'                  => 'Websites',
        'singular_name'         => 'Website',
        'menu_name'             => 'Websites',
        'name_admin_bar'        => 'Website',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Website',
        'new_item'              => 'New Website',
        'edit_item'             => 'Edit Website',
        'view_item'             => 'View Website',
        'all_items'             => 'All Websites',
        'search_items'          => 'Search Websites',
        'parent_item_colon'     => 'Parent Website:',
        'not_found'             => 'No Websites found.',
        'not_found_in_trash'    => 'No Websites found in Trash.',
        'featured_image'        => 'Website Image',
        'set_featured_image'    => 'Set website image',
        'remove_featured_image' => 'Remove website image',
        'use_featured_image'    => 'Use as website image',
        'archives'              => 'Websites archives',
        'insert_into_item'      => 'Insert into website',
        'uploaded_to_this_item' => 'Uploaded to this website',
        'filter_items_list'     => 'Filter website list',
        'items_list_navigation' => 'Website list navigation',
        'items_list'            => 'Website list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'website'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-admin-site',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register generic custom post type
    register_post_type('website', $args);


    // Register ga_account custom post type.

    // Define labels for ga_account post type
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
        'public'            => false,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'ga_account'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-category',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register generic custom post type
    register_post_type('ga_account', $args);

});
