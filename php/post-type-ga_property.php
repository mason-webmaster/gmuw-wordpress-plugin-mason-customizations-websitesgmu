<?php
/**
 * Handle custom post type
 */

/**
 * Register a custom post type
 */
add_action('init', 'gmuw_websitesgmu_register_custom_post_type_ga_properties');
function gmuw_websitesgmu_register_custom_post_type_ga_properties() {

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
        'menu_icon'         => gmuw_websitesgmu_get_cpt_icon('ga_property'),
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register ga_property custom post type
    register_post_type('ga_property', $args);

}

// Add additional columns to the admin list
add_filter ('manage_ga_property_posts_columns', 'gmuw_websitesgmu_add_columns_ga_property');
function gmuw_websitesgmu_add_columns_ga_property ($columns) {

    return array_merge ( $columns, array (
        //ACF fields
        'deleted' => 'Deleted?',
        'follow_up' => 'Follow-Up?',
        'ga_property_account_post_id' => 'GA Account',
        'ga_property_id'   => 'GA Property ID',
        'ga_measurement_id'   => 'GA4 Data Stream Measurement ID',
        //Other fields
        'ga_property_link' => 'GA Property Link',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_ga_property_posts_custom_column', 'gmuw_websitesgmu_ga_property_custom_column', 10, 2);
function gmuw_websitesgmu_ga_property_custom_column ($column, $post_id) {

    switch ($column) {
        case 'deleted':
            echo get_post_meta($post_id, 'deleted', true)==1 ? '<span class="record-status record-status-deleted">Deleted</span>' : '';
            break;
        case 'follow_up':
            echo get_post_meta($post_id, 'follow_up', true)==1 ? '<span class="record-status record-status-follow-up">Follow-Up</span>' : '';
            break;
        case 'ga_property_account_post_id':
            echo get_the_title(get_post_meta($post_id, 'ga_property_account_post_id', true)).'<br />';
            echo '<a href="'.get_edit_post_link(get_post_meta($post_id, 'ga_property_account_post_id', true)).'">edit</a> | ';
            echo '<a href="'.get_post_permalink(get_post_meta($post_id, 'ga_property_account_post_id', true)).'">view</a> ';
            break;
        case 'ga_property_id':
            echo get_post_meta($post_id, 'ga_property_id', true);
            break;
        case 'ga_measurement_id':
            echo get_post_meta($post_id, 'ga_measurement_id', true);
            break;
        case 'ga_property_link':
            echo '<a href="https://analytics.google.com/analytics/web/#/a'.get_post_meta(get_post_meta($post_id, 'ga_property_account_post_id', true), 'ga_account_id', true).'p'.get_post_meta($post_id, 'ga_property_id', true).'/admin" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a><br />';
            break;
    }

}

/**
 * Adds meta box to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_websitesgmu_custom_dashboard_meta_box_ga_properties');
function gmuw_websitesgmu_custom_dashboard_meta_box_ga_properties() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_ga_properties", "GA Properties", "gmuw_websitesgmu_custom_dashboard_meta_box_ga_properties_content", "dashboard","normal");

}

/**
 * Provides content for the dashboard meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_ga_properties_content() {

  //Initialize variables
  $cpt_slug='ga_property';
  $content='';

  //basic totals
  $content.='<p>'.gmuw_websitesgmu_get_cpt_totals($cpt_slug).'</p>';

  //follow-up records
  $content.=gmuw_websitesgmu_meta_box_display_follow_up_records($cpt_slug);

  //Display meta box
  gmuw_websitesgmu_custom_dashboard_meta_box_cpt_summary($cpt_slug,$content);

}
