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
        'public'            => true,
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

    // Register website custom post type
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
        'public'            => true,
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

    // Register ga_account custom post type
    register_post_type('ga_account', $args);


    // Register ga_property custom post type.

    // Define labels for ga_property post type
    $labels = array(
        'name'                  => 'GA Property',
        'singular_name'         => 'GA Property',
        'menu_name'             => 'GA Properties',
        'name_admin_bar'        => 'GA Property',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GA Property',
        'new_item'              => 'New GA Property',
        'edit_item'             => 'Edit GA Property',
        'view_item'             => 'View GA Property',
        'all_items'             => 'All GA Properties',
        'search_items'          => 'Search GA properties',
        'parent_item_colon'     => 'Parent GA property:',
        'not_found'             => 'No GA properties found.',
        'not_found_in_trash'    => 'No GA properties found in Trash.',
        'featured_image'        => 'GA Property Image',
        'set_featured_image'    => 'Set GA property image',
        'remove_featured_image' => 'Remove GA property image',
        'use_featured_image'    => 'Use as GA property image',
        'archives'              => 'GA Properties archives',
        'insert_into_item'      => 'Insert into GA property',
        'uploaded_to_this_item' => 'Uploaded to this GA property',
        'filter_items_list'     => 'Filter GA property list',
        'items_list_navigation' => 'GA property list navigation',
        'items_list'            => 'GA property list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'ga_property'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-analytics',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register ga_property custom post type
    register_post_type('ga_property', $args);


    // Register gtm_account custom post type.

    // Define labels for gtm_account post type
    $labels = array(
        'name'                  => 'GTM Account(s)',
        'singular_name'         => 'GTM Account',
        'menu_name'             => 'GTM Accounts',
        'name_admin_bar'        => 'GTM Account',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GTM Account',
        'new_item'              => 'New GTM Account',
        'edit_item'             => 'Edit GTM Account',
        'view_item'             => 'View GTM Account',
        'all_items'             => 'All GTM Accounts',
        'search_items'          => 'Search GTM accounts',
        'parent_item_colon'     => 'Parent GTM account:',
        'not_found'             => 'No GTM accounts found.',
        'not_found_in_trash'    => 'No GTM accounts found in Trash.',
        'featured_image'        => 'GTM Account Image',
        'set_featured_image'    => 'Set GTM account image',
        'remove_featured_image' => 'Remove GTM account image',
        'use_featured_image'    => 'Use as GTM account image',
        'archives'              => 'GTM Accounts archives',
        'insert_into_item'      => 'Insert into GTM account',
        'uploaded_to_this_item' => 'Uploaded to this GTM account',
        'filter_items_list'     => 'Filter GTM account list',
        'items_list_navigation' => 'GTM account list navigation',
        'items_list'            => 'GTM account list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'gtm_account'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-category',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register gtm_account custom post type
    register_post_type('gtm_account', $args);


    // Register gtm_container custom post type.

    // Define labels for gtm_container post type
    $labels = array(
        'name'                  => 'GTM Container',
        'singular_name'         => 'GTM Container',
        'menu_name'             => 'GTM Containers',
        'name_admin_bar'        => 'GTM Container',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New GTM Container',
        'new_item'              => 'New GTM Container',
        'edit_item'             => 'Edit GTM Container',
        'view_item'             => 'View GTM Container',
        'all_items'             => 'All GTM Containers',
        'search_items'          => 'Search GTM containers',
        'parent_item_colon'     => 'Parent GTM container:',
        'not_found'             => 'No GTM containers found.',
        'not_found_in_trash'    => 'No GTM containers found in Trash.',
        'featured_image'        => 'GTM Container Image',
        'set_featured_image'    => 'Set GTM container image',
        'remove_featured_image' => 'Remove GTM container image',
        'use_featured_image'    => 'Use as GTM container image',
        'archives'              => 'GTM Containers archives',
        'insert_into_item'      => 'Insert into GTM container',
        'uploaded_to_this_item' => 'Uploaded to this GTM container',
        'filter_items_list'     => 'Filter GTM container list',
        'items_list_navigation' => 'GTM container list navigation',
        'items_list'            => 'GTM container list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'gtm_container'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => 'dashicons-index-card',
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register gtm_container custom post type
    register_post_type('gtm_container', $args);

});
