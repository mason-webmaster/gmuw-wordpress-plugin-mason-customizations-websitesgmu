<?php
/**
 * Handle custom post type
 */

/**
 * Register a custom post type
 */
add_action('init', 'gmuw_websitesgmu_register_custom_post_type_gtm_accounts');
function gmuw_websitesgmu_register_custom_post_type_gtm_accounts() {

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
        'menu_icon'         => gmuw_websitesgmu_get_cpt_icon('gtm_account'),
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register custom post type
    register_post_type('gtm_account', $args);

}

// Add additional columns to the admin list
add_filter ('manage_gtm_account_posts_columns', 'gmuw_websitesgmu_add_columns_gtm_account');
function gmuw_websitesgmu_add_columns_gtm_account ($columns) {

    return array_merge ( $columns, array (
        //ACF fields
        'deleted' => 'Deleted?',
        'follow_up' => 'Follow-Up?',
        'working' => 'Working?',
        'gtm_account_id'   => 'GTM Account ID',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_gtm_account_posts_custom_column', 'gmuw_websitesgmu_gtm_account_custom_column', 10, 2);
function gmuw_websitesgmu_gtm_account_custom_column ($column, $post_id) {

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
        case 'gtm_account_id':
            echo get_post_meta($post_id, 'gtm_account_id', true);
            break;
    }

}

/**
 * Adds meta box to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_websitesgmu_custom_dashboard_meta_box_gtm_accounts');
function gmuw_websitesgmu_custom_dashboard_meta_box_gtm_accounts() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_gtm_accounts", "GTM Accounts", "gmuw_websitesgmu_custom_dashboard_meta_box_gtm_accounts_content", "dashboard","normal");

}

/**
 * Provides content for the dashboard meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_gtm_accounts_content() {

  //Initialize variables
  $cpt_slug='gtm_account';
  $content='';

  //basic totals
  $content.='<p>'.gmuw_websitesgmu_get_cpt_totals($cpt_slug).'</p>';

  //working records
  $content.=gmuw_websitesgmu_meta_box_display_marked_records($cpt_slug,'working');

  //follow-up records
  $content.=gmuw_websitesgmu_meta_box_display_marked_records($cpt_slug,'follow_up');

  //Display meta box
  gmuw_websitesgmu_custom_dashboard_meta_box_cpt_summary($cpt_slug,$content);

}

/**
 * Setup custom template
 */

add_filter( 'template_include', 'gmuw_websitesgmu_custom_post_type_template_gtm_account' );
function gmuw_websitesgmu_custom_post_type_template_gtm_account($template) {

    $post_type = 'gtm_account';

    if ( is_singular( $post_type ) && file_exists( plugin_dir_path(__DIR__) . "templates/single-$post_type.php" ) ){
        $template = plugin_dir_path(__DIR__) . "templates/single-$post_type.php";
    }

    return $template;
}
