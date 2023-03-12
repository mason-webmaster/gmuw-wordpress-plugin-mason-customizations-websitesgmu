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

/**
 * Register custom post types
 */

require('post-type-website.php');

require('post-type-gtm_account.php');

require('post-type-gtm_container.php');

require('post-type-ga_account.php');

require('post-type-ga_property.php');
