<?php

/**
 * Summary: php file which implements the plugin lookup feature
 */


//function to get a random array element
function gmuw_websitesgmu_get_random_array_element($my_array) {

	return ! empty( $my_array )
		? $my_array[ array_rand( $my_array ) ]
		: 0;
}

//function get a list of all WPEngine WordPress website posts
function gmuw_websitesgmu_get_wpengine_site_ids() {

	$args = array(
	    'post_type'      => 'website',
	    'posts_per_page' => -1,
	    'fields'         => 'ids',
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
		),
	    'tax_query'      => array(
	        'relation' => 'AND',
	        array(
	            'taxonomy' => 'web_host',
	            'field'    => 'slug',
	            'terms'    => 'wpengine',
	        ),
	        array(
	            'taxonomy' => 'cms',
	            'field'    => 'slug',
	            'terms'    => 'wordpress',
	        ),
	    ),
	);

	$post_ids = get_posts( $args );

	if ($post_ids) {
		return $post_ids;
	} else {
		return array();
	}

}

//function to update plugins list for a particular site
function gmuw_websitesgmu_update_site_plugins_list($post_id) {

	//get post
	$mypost=get_post($post_id);

	// plugins list link
	if (!empty($mypost->environment_name)) {
		$mydomain=gmuw_websitesgmu_website_hosting_domain($post_id,false);
		$my_plugins_list_URL='https://'.$mydomain.'/wp-json/gmuw-sci/plugin-info';
	}	

	//get list from URL
	$response = wp_remote_get($my_plugins_list_URL);

	if ( is_wp_error( $response ) ) {
	    return false;
	}

	$body = wp_remote_retrieve_body( $response );

	$data = json_decode( $body, true ); // true = associative array

	if ( json_last_error() !== JSON_ERROR_NONE ) {
	    return false;
	}

	//prepare plugin list return value
	$return_value='';

	//loop through array and build plugin list
	foreach ($data as $plugin) {
		$return_value.=$plugin['plugin_name'];
		$return_value.=' (';
		$return_value.=$plugin['plugin_file'];
		$return_value.=') ';
		$return_value.=$plugin['plugin_version'];
		$return_value.=' (';
		$return_value.=$plugin['plugin_active']==1 ? 'A' : 'I';
		$return_value.=')';
		$return_value.="\n";
	}

	//write plugin list to postmeta
	update_post_meta($post_id,'gmuw_plugin_list',$return_value);
	update_post_meta($post_id,'gmuw_plugin_list_updated',time());

	// return value
	return true;

}

//function to lookup and save plugin list for random site
function gmuw_websitesgmu_store_plugin_list_for_random_site() {

	//get random wpengine site post id
	$my_post_id=gmuw_websitesgmu_get_random_array_element(gmuw_websitesgmu_get_wpengine_site_ids());

	//update plugin list postmeta
	gmuw_websitesgmu_update_site_plugins_list($my_post_id);

}

// add a 1-minute cron schedule.
add_filter( 'cron_schedules', 'gmuw_websitesgmu_add_one_minute_cron_schedule' );
function gmuw_websitesgmu_add_one_minute_cron_schedule( $schedules ) {

	$schedules['every_minute'] = array(
		'interval' => 60,
		'display'  => __( 'Every Minute', 'gmuw' ),
	);

	return $schedules;
}

// Schedule the plugin list update event
add_action( 'init', 'gmuw_websitesgmu_schedule_plugin_list_cron' );
function gmuw_websitesgmu_schedule_plugin_list_cron() {

	if ( ! wp_next_scheduled( 'gmuw_store_plugin_list_event' ) ) {

		wp_schedule_event(
			time(),
			'every_minute',
			'gmuw_store_plugin_list_event'
		);

	}
}

/**
 * Hook your function to the event.
 */
add_action(
	'gmuw_store_plugin_list_event',
	'gmuw_websitesgmu_store_plugin_list_for_random_site'
);
