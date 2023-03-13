<?php
/**
 * Handle custom post type
 */

/**
 * Register a custom post type
 */
add_action('init', 'gmuw_websitesgmu_register_custom_post_type_gtm_containers');
function gmuw_websitesgmu_register_custom_post_type_gtm_containers() {

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
        'menu_icon'         => gmuw_websitesgmu_get_cpt_icon('gtm_container'),
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register custom post type
    register_post_type('gtm_container', $args);

}

// Add additional columns to the admin list
add_filter ('manage_gtm_container_posts_columns', 'gmuw_websitesgmu_add_columns_gtm_container');
function gmuw_websitesgmu_add_columns_gtm_container ($columns) {

    return array_merge ( $columns, array (
        //ACF fields
        'deleted' => 'Deleted?',
        'follow_up' => 'Follow-Up?',
        'working' => 'Working?',
        'gtm_container_account_post_id' => 'GTM Account',
        'gtm_container_id'   => 'GTM Container ID',
        //Other fields
        'gtm_container_link' => 'GTM Container Link',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_gtm_container_posts_custom_column', 'gmuw_websitesgmu_gtm_container_custom_column', 10, 2);
function gmuw_websitesgmu_gtm_container_custom_column ($column, $post_id) {

    switch ($column) {
        case 'deleted':
            echo get_post_meta($post_id, 'deleted', true)==1 ? '<span class="record-status record-status-deleted">Deleted</span>' : '';
            break;
        case 'follow_up':
            echo get_post_meta($post_id, 'follow_up', true)==1 ? '<span class="record-status record-status-follow-up">Follow-Up</span>' : '';
            break;
        case 'working':
            echo get_post_meta($post_id, 'working', true)==1 ? '<span class="record-status record-status-working">Working</span>' : '';
            break;
        case 'gtm_container_account_post_id':
            echo get_the_title(get_post_meta($post_id, 'gtm_container_account_post_id', true)).'<br />';
            echo '<a href="'.get_edit_post_link(get_post_meta($post_id, 'gtm_container_account_post_id', true)).'">edit</a> | ';
            echo '<a href="'.get_post_permalink(get_post_meta($post_id, 'gtm_container_account_post_id', true)).'">view</a> ';
            break;
        case 'gtm_container_id':
            echo get_post_meta($post_id, 'gtm_container_id', true);
            break;
        case 'gtm_container_link':
            echo '<a href="https://tagmanager.google.com/#/container/accounts/'.get_post_meta(get_post_meta($post_id, 'gtm_container_account_post_id', true), 'gtm_account_id', true).'/containers/'.get_post_meta($post_id, 'gtm_container_id', true).'/" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_tag_manager.png'.'" /></a><br />';
            break;
    }

}

/**
 * Adds meta box to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_websitesgmu_custom_dashboard_meta_box_gtm_containers');
function gmuw_websitesgmu_custom_dashboard_meta_box_gtm_containers() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_gtm_containers", "GTM Containers", "gmuw_websitesgmu_custom_dashboard_meta_box_gtm_containers_content", "dashboard","normal");

}

/**
 * Provides content for the dashboard meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_gtm_containers_content() {

  //Initialize variables
  $cpt_slug='gtm_container';
  $content='';

  //basic totals
  $content.='<p>'.gmuw_websitesgmu_get_cpt_totals($cpt_slug).'</p>';

  //follow-up records
  $content.=gmuw_websitesgmu_meta_box_display_follow_up_records($cpt_slug);

  //Display meta box
  gmuw_websitesgmu_custom_dashboard_meta_box_cpt_summary($cpt_slug,$content);

}
