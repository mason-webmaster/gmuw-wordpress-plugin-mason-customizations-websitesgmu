<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Gets URL for GA property admin link given ga_property post id
 */
function gmuw_websitesgmu_ga_property_admin_link_url($post_id) {

	// Initialize variables
	$return_value='';

	// build link
	$return_value.='https://analytics.google.com/analytics/web/#/a'.get_post_meta(get_post_meta($post_id, 'ga_property_account_post_id', true), 'ga_account_id', true).'p'.get_post_meta($post_id, 'ga_property_id', true).'/admin';

	// Return value
	return $return_value;

}

/**
 * Gets HTML for GA property admin link given ga_property post id
 */
function gmuw_websitesgmu_ga_property_admin_link($post_id) {

	// Initialize variables
	$return_value='';

	// build link
	echo '<a href="'.gmuw_websitesgmu_ga_property_admin_link_url($post_id).'" target="_blank"><img class="gmuw_websitesgmu_offsitelink gmuw_websitesgmu_ga_property_link" style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a><br />';

	// Return value
	return $return_value;

}