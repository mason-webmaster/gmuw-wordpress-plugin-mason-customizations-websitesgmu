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

//Display marked records in the CPTs custom dashboard box
function gmuw_websitesgmu_meta_box_display_marked_records($cpt_slug,$mark_type){

    //Initialize variables
    $return_value='';

    $marked_records_content=gmuw_websitesgmu_display_marked_records($cpt_slug,$mark_type);
    if ($marked_records_content) {

        // Display heading
        $return_value.='<h3>';
        switch ($mark_type){
            case 'working':
                $return_value.='Working';
                break;
            case 'follow_up':
                $return_value.='Follow-Up';
                break;
            default:
                $return_value.=$mark_type;
                break;
        }
        $return_value.=':';
        $return_value.='</h3>';

        $return_value.=$marked_records_content;
    }

    //Return value
    return $return_value;

}

//Get the marked records for a given post type (or any) and mark type
function gmuw_websitesgmu_display_marked_records($post_type='any',$mark_type){

    //Initialize variables
    $return_value='';

    //Set whether to show post type in output
    $post_type=='any' ? $show_post_type=1 : $show_post_type=0;

    //Get marked records
    $args = array(
    'post_type'   => $post_type,
    'nopaging' => true,
    'orderby' => 'post_type title',
    'order' => 'ASC',
    'fields' => 'ids',
    'meta_key' => $mark_type,
    'meta_value' => 1,
    );

    $record_ids = get_posts($args);

    //Build return value
    foreach($record_ids as $record_id) {
        $return_value.=gmuw_websitesgmu_display_marked_record($record_id,$show_post_type,$mark_type);
    }

    //Return value
    return $return_value;

}

// Get record utility link
function gmuw_websitesgmu_record_get_utility_link($post_id,$link_type){

    // Initialize variables
    $return_value='';

    // Build link based on link_type
    switch($link_type) {
        case 'view';
            $return_value.='<a class="admin-icon admin-view" href="'.get_permalink($post_id).'"></a>';
            break;
        case 'edit':
            $return_value.=' <a class="admin-icon admin-edit" href="/wp-admin/post.php?post='.$post_id.'&action=edit"></a>';
            break;
    }

    // Return value
    return $return_value;

}

//Display Links to marked records
function gmuw_websitesgmu_display_marked_record($post_id,$show_post_type=0,$mark_type){

    //Initialize variables
    $return_value='';

    // Set CSS class for displayed record
    switch ($mark_type){
        case 'working':
            $css_class.='working';
            break;
        case 'follow_up':
            $css_class.='follow_up';
            break;
        default:
            break;
    }

    //Begin return value
    $return_value.='<p class="marked-record '.$css_class.'">';

    $return_value.='<a href="'.get_permalink($post_id).'">';
    if ($show_post_type==1) $return_value.=get_post_type($post_id).': ';
    $return_value.=get_the_title($post_id);
    //$return_value.=' ('.$post_id.')';
    $return_value.='</a>';
    $return_value.=' '.gmuw_websitesgmu_record_get_utility_link($post_id,'view');
    $return_value.=' '.gmuw_websitesgmu_record_get_utility_link($post_id,'edit');

    // Content specific to 'working' records
    if ($mark_type=='working'){

        // Who is working this record?
        $working_user=get_post_meta($post_id,'working_user',true);
        if (!empty($working_user)){
            $return_value.=' ('.get_userdata($working_user)->user_login.')';
        }

    }

    //Finish return value
    $return_value.='</p>';

    //Return value
    return $return_value;

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
 * Additional post type analysis functions
 */
require('post-types-analysis.php');


/**
 * Register custom post types
 */
require('post-type-website.php');
require('post-type-gtm_account.php');
require('post-type-gtm_container.php');
require('post-type-ga_account.php');
require('post-type-ga_property.php');

/**
 * Custom functions related to post types
 */
require('post-type-website-functions.php');
require('post-type-gtm_container-functions.php');
require('post-type-ga_property-functions.php');