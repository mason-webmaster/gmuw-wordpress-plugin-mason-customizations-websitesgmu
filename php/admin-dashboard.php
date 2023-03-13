<?php

/**
 * php file to handle WordPress dashboard customizations
 */


/**
 * Adds meta boxes to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_websitesgmu_custom_dashboard_meta_boxes');
function gmuw_websitesgmu_custom_dashboard_meta_boxes() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add 'follow-up' meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_follow_up", "Follow-up Records", "gmuw_websitesgmu_custom_dashboard_meta_box_follow_up", "dashboard","normal");

}

/**
 * Provides content for the dashboard 'follow-up' meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_follow_up() {

  //Output links to follow-up records
  echo gmuw_websitesgmu_display_follow_up_records();

}
