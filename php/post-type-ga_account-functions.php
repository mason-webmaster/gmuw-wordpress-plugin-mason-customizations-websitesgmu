<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Gets URL for GA account admin link given ga_account post id
 */
function gmuw_websitesgmu_ga_account_admin_link_url($post_id) {

	// Initialize variables
	$return_value='';

	// build link
	$return_value.='https://analytics.google.com/analytics/web/#/a'.get_post_meta($post_id, 'ga_account_id', true).'p0/admin';

	// Return value
	return $return_value;

}

/**
 * Gets HTML for GA account admin link given ga_account post id
 */
function gmuw_websitesgmu_ga_account_admin_link($post_id) {

	// Initialize variables
	$return_value='';

	// build link
	$return_value.='<a href="'.gmuw_websitesgmu_ga_account_admin_link_url($post_id).'" target="_blank"><img class="gmuw_websitesgmu_offsitelink gmuw_websitesgmu_ga_account_link" style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a>';

	// Return value
	return $return_value;

}