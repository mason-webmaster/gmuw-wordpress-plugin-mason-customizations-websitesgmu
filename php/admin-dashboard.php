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

  /* Add 'links' meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_links", "Links", "gmuw_websitesgmu_custom_dashboard_meta_box_links", "dashboard","normal");

  /* Add 'working' meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_working", "Working Records", "gmuw_websitesgmu_custom_dashboard_meta_box_working", "dashboard","normal");

  /* Add 'follow-up' meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_follow_up", "Follow-up Records", "gmuw_websitesgmu_custom_dashboard_meta_box_follow_up", "dashboard","normal");

  /* Add 'sites pending deletion' meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_sites_pending_deletion", "Sites Pending Deletion", "gmuw_websitesgmu_custom_dashboard_meta_box_sites_pending_deletion", "dashboard","normal");

}

/**
 * Provides content for the dashboard 'working' meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_working() {

  //Output links to follow-up records
  echo gmuw_websitesgmu_display_marked_records('any','working');

}

/**
 * Provides content for the dashboard 'follow-up' meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_follow_up() {

  //Output links to follow-up records
  echo gmuw_websitesgmu_display_marked_records('any','follow_up');

}

/**
 * Provides content for the dashboard 'links' meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_links() {

  //Output content

  echo '<h3>Tools</h3>';
  echo '<p><a href="https://toolbox.googleapps.com/apps/dig/" target="_blank">Google Dig</a></p>';

  echo '<h3>Google</h3>';
  echo '<p><a href="https://analytics.google.com/" target="_blank">Google Analytics</a></p>';
  echo '<p><a href="https://tagmanager.google.com/" target="_blank">Google Tag Manager</a></p>';
  echo '<p><a href="https://search.google.com/search-console" target="_blank">Google Search Console</a></p>';

  echo '<h3>Web Hosting</h3>';
  echo '<p><a href="https://dashboard.sucuri.net/" target="_blank">Sucuri</a></p>';
  echo '<p><a href="https://my.wpengine.com/" target="_blank">WPEngine</a></p>';
  echo '<p><a href="https://cloud.materiell.com/login" target="_blank">Materiell Cloud</a></p>';
  echo '<p><a href="https://materiell.zendesk.com/hc/en-us/restricted?return_to=https%3A%2F%2Fmateriell.zendesk.com%2Fhc%2Fen-us" target="_blank">Materiell Support</a></p>';
  echo '<p><a href="https://www.georgemasonusf.acsitefactory.com/" target="_blank">Acquia Cloud SiteFactory</a></p>';

  echo '<h3>Mason ITS</h3>';
  echo '<p><a href="https://its.gmu.edu/service/web-hosting/" target="_blank">ITS Website Web Hosting Service Page</a></p>';
  echo '<p><a href="https://gmu.teamdynamix.com/TDNext/Home/Desktop/Default.aspx" target="_blank">TeamDynamix</a></p>';

}

/**
 * Provides content for the dashboard 'sites pending deletion' meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_sites_pending_deletion() {

  //Get posts
  $args = array(
    'post_type'  => 'website',
    'post_status' => 'publish',
    'nopaging' => true,
    'meta_key' => 'deletion_date',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'meta_query' => array(
      array(
        'key'   => 'marked_for_deletion',
        'value' => '1',
        'compare' => '=',
      ),
    ),
  );

  $myposts = get_posts($args);

  //if we have posts
  if ($myposts) {

    //start display table
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Website</th>';
    echo '<th>Deletion Date</th>';
    echo '<th>&nbsp;</th>';
    echo '</tr>';
    echo '</thead>';

    //loop through posts
    foreach($myposts as $mypost) {

        //display fields
        echo '<tr>';
        echo '<td>'.$mypost->post_title.'</td>';
        echo '<td>'.($mypost->deletion_date ? DateTime::createFromFormat('Ymd',$mypost->deletion_date)->format('Y-m-d') : 'no date set').'</td>';
        echo '<td>'.gmuw_websitesgmu_record_get_utility_link($mypost->ID,'edit').'</td>';
        echo '</tr>';

    }

    //end display table
    echo '</table>';

  }

}
