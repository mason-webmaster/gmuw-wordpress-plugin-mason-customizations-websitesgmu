<?php

/**
 * Summary: php file which implements the custom post types
 */


/**
 * Register custom post types
 */
add_action('init', function(){

    // Register generic custom post type. Register additional custom post types here as needed.

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
        'set_featured_image'    => 'Set thing image',
        'remove_featured_image' => 'Remove thing image',
        'use_featured_image'    => 'Use as thing image',
        'archives'              => 'Websites archives',
        'insert_into_item'      => 'Insert into thing',
        'uploaded_to_this_item' => 'Uploaded to this thing',
        'filter_items_list'     => 'Filter thing list',
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

});
