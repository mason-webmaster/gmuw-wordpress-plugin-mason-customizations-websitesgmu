<?php

/**
 * Summary: php file which implements the custom post types
 */


/**
 * Return the appropriate dashicon for the custom post type
 */
function gmuw_websitesgmu_get_cpt_icon($post_type){

    // Initialize array
    $cpt_icons = array(
        'website'=>'dashicons-admin-site',
        'gtm_account'=>'dashicons-category',
        'gtm_container'=>'dashicons-index-card',
        'ga_account'=>'dashicons-category',
        'ga_property'=>'dashicons-analytics',
    );

    //Return value
    return $cpt_icons[$post_type];

}

//Display follow-up records in the CPTs custom dashboard box
function gmuw_websitesgmu_meta_box_display_follow_up_records($cpt_slug){

    //Initialize variables
    $return_value='';

    $follow_up_records_content=gmuw_websitesgmu_display_follow_up_records($cpt_slug);
    if ($follow_up_records_content) {
        $return_value.='<h3>Follow-Up: </h3>';
        $return_value.=$follow_up_records_content;
    }

    //Return value
    return $return_value;

}

//Get the follow-up records for a given post type (or any)
function gmuw_websitesgmu_display_follow_up_records($post_type='any'){

    //Initialize variables
    $return_value='';

    //Set whether to show post type in output
    $post_type=='any' ? $show_post_type=1 : $show_post_type=0;

    //Get follow-up records
    $args = array(
    'post_type'   => $post_type,
    'nopaging' => true,
    'orderby' => 'post_type title',
    'order' => 'ASC',
    'fields' => 'ids',
    'meta_key' => 'follow_up',
    'meta_value' => 1,
    );


    $follow_up_record_ids = get_posts($args);

    //Build return value
    foreach($follow_up_record_ids as $follow_up_record_id) {
        $return_value.=gmuw_websitesgmu_display_follow_up_record($follow_up_record_id,$show_post_type);
    }

    //Return value
    return $return_value;

}

//Display Links to follow-up records
function gmuw_websitesgmu_display_follow_up_record($post_id,$show_post_type=0){

    //Initialize variables
    $return_value='';

    //Build return value
    $return_value.='<p class="follow_up">';
    $return_value.='<a href="'.get_permalink($post_id).'">';
    if ($show_post_type==1) $return_value.=get_post_type($post_id).': ';
    $return_value.=get_the_title($post_id);
    //$return_value.=' ('.$post_id.')';
    $return_value.=' <a class="admin-icon admin-view" href="'.get_permalink($post_id).'"></a><a class="admin-icon admin-edit" href="/wp-admin/post.php?post='.$post_id.'&action=edit"></a>';
    $return_value.='</a>';
    $return_value.='</p>';

    //Return value
    return $return_value;

}

/**
 * Get totals summaries for a custom post type
 */
function gmuw_websitesgmu_get_cpt_totals($post_type) {

  // Initialize variables
  $return_value='';

  //Get numbers of deleted/non-deleted
  $count_non_deleted=gmuw_websitesgmu_get_cpt_total($post_type,'not-deleted');
  $count_deleted=gmuw_websitesgmu_get_cpt_total($post_type,'deleted');

  //Start building return value
  $return_value.='<p>';
  $return_value.='<strong>'.$count_non_deleted.' Records</strong><br />';
  
  //Do we have deleted?
  if ($count_deleted>1) {
    $return_value.='('.$count_deleted.' deleted; '.gmuw_websitesgmu_get_cpt_total($post_type,'all').' total)';
  }

  //Finish building return value
  $return_value.='</p>';

  //Return value
  return $return_value;

}

/**
 * Get the total number of posts for a custom post type
 */
function gmuw_websitesgmu_get_cpt_total($post_type,$count_mode) {

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );  

  // get meta query args based on mode
  switch ($count_mode) {
    case 'all':
      $args_meta = array();
      break;
    case 'deleted':
      $args_meta = array(
        'meta_query' => array(
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '=',
          ),
        )
      );
      break;
    case 'not-deleted':
      $args_meta = array(
        'meta_query' => array(
          array(
            'relation' => 'OR',
            array(
              'key'   => 'deleted',
              'compare' => 'NOT EXISTS',
            ),
            array(
              'key'   => 'deleted',
              'value' => '1',
              'compare' => '!=',
            ),
          )
        )
      );
      break;
  }

  // merge arg arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

  // Get count
  $posts_count = count($posts);

  return $posts_count;

}

/**
 * Get the total number of posts non-deleted with meta key value
 */
function gmuw_websitesgmu_get_total_non_delete_with_meta($post_type,$meta_key,$meta_value) {

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );  

  // set meta query args
  $args_meta = array(
    'meta_query' => array(
      array(
        'relation' => 'AND',
        array(
          'key'   => $meta_key,
          'value' => $meta_value,
          'compare' => '=',
        ),
        array(
          'relation' => 'OR',
          array(
            'key'   => 'deleted',
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '!=',
          ),
        )
      )
    )
  );

  // merge arg arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

  // Get count
  $posts_count = count($posts);

  return $posts_count;

}

/**
 * Get the total number of posts non-deleted where some meta key value exists
 */
function gmuw_websitesgmu_get_total_non_delete_with_meta_exists($post_type,$meta_key) {

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );

  // set meta query args
  $args_meta = array(
    'meta_query' => array(
      array(
        'relation' => 'AND',
        array(
          'relation' => 'AND',
          array(
            'key'   => $meta_key,
            'compare' => 'EXISTS',
          ),
          array(
            'key'   => $meta_key,
            'value' => '',
            'compare' => '!=',
          ),
        ),
        array(
          'relation' => 'OR',
          array(
            'key'   => 'deleted',
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '!=',
          ),
        )
      )
    )
  );

  // merge arg arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

  // Get count
  $posts_count = count($posts);

  return $posts_count;

}

/**
 * Provides format for the dashboard custom post type summary meta boxes
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_cpt_summary($post_type,$content) {

  //Output content

  //start flex container
  echo '<div class="dash-meta-cpt-summary">';

  //icon
  echo '<a href="/wp-admin/edit.php?post_type='.$post_type.'"><div class="dashicons-before '.gmuw_websitesgmu_get_cpt_icon($post_type).'"></div></a>';

  //start cpt summary info
  echo '<div>';

  //output cpt summary data
  echo $content;

  //end cpt summary info
  echo '</div>';

  //end flex container
  echo '</div>';

}

/**
 * Register custom post types
 */

require('post-type-website.php');

require('post-type-gtm_account.php');

require('post-type-gtm_container.php');

require('post-type-ga_account.php');

require('post-type-ga_property.php');
