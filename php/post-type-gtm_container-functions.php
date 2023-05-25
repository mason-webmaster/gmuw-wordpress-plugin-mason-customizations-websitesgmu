<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Gets URL of gtm container admin link given post id
 */
function gmuw_websitesgmu_gtm_container_admin_link($post_id) {

	// Initialize variables
	$return_value='';

	// build link
  $return_value= '<a href="https://tagmanager.google.com/#/container/accounts/'.get_post_meta(get_post_meta($post_id, 'gtm_container_account_post_id', true), 'gtm_account_id', true).'/containers/'.get_post_meta($post_id, 'gtm_container_id', true).'/" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_tag_manager.png'.'" /></a><br />';

	// Return value
	return $return_value;

}