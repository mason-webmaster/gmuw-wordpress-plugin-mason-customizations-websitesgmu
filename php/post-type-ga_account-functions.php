<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Gets URL of GA account admin link given post id
 */
function gmuw_websitesgmu_ga_account_admin_link($post_id) {

	// Initialize variables
	$return_value='';

	// build link
	$return_value.='<a href="https://analytics.google.com/analytics/web/#/a'.get_post_meta($post_id, 'ga_account_id', true).'p0/admin" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a><br />';

	// Return value
	return $return_value;

}