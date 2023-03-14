<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Builds hosting domain URLs
 */
function gmuw_websitesgmu_hosting_domain($web_host, $environment_name,$include_protocol=True) {

	// Do we have required paramters?
	if (empty($web_host) || empty($environment_name)) {
		return $return_value;
	}

	// Initialize return value
	$return_value='';

	// Build hosting domain URL based on web host and environment name
	if ($web_host=='wpengine') {
		$return_value = $environment_name.'.wpengine.com';
	} elseif ($web_host=='materiell') {
		$return_value = $environment_name.'.materiellcloud.com';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$return_value = $environment_name.'.sitemasonry.gmu.edu';
	}

	// Include protocol if specified
	if ($include_protocol && !empty($return_value)) {
		$return_value='https://'.$return_value;
	}

	// Return value
	return $return_value;

}

/**
 * Builds CMS login URL
 */
function gmuw_websitesgmu_cms_login_url($web_host, $environment_name) {

	// Do we have required paramters?
	if (empty($web_host) || empty($environment_name)) {
		return $return_value;
	}

	// Initialize return value
	$return_value='';

	// Get web hosting domain
	$return_value.=gmuw_websitesgmu_hosting_domain($web_host, $environment_name);

	// Build wp login URL based on web host
	if ($web_host=='wpengine') {
		$return_value.='/wp-admin/';
	} elseif ($web_host=='materiell') {
		$return_value.='/wp-admin/';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$return_value.='/user/login';
	}

	// Return value
	return $return_value;

}

/**
 * Builds CMS login HTML link element
 */
function gmuw_websitesgmu_cms_login_link($web_host, $environment_name) {

	// Do we have required paramters?
	if (empty($web_host) || empty($environment_name)) {
		return $return_value;
	}

	// Initialize return value
	$return_value='';
	$image_path='';

	// Get logo based on web host
	if ($web_host=='wpengine') {
		$image_path.=plugin_dir_url( __DIR__ ).'images/logo-wordpress.png';
	} elseif ($web_host=='materiell') {
		$image_path.=plugin_dir_url( __DIR__ ).'images/logo-wordpress.png';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$image_path.=plugin_dir_url( __DIR__ ).'images/logo-drupal.png';
	}

	// build link element
	$return_value.='<a href="'.gmuw_websitesgmu_cms_login_url($web_host, $environment_name).'" target="_blank" title="CMS login"><img style="width:25px;" src="'.$image_path.'" /></a>';

	// Return value
	return $return_value;

}

function gmuw_websitesgmu_admin_link($web_host, $environment_name){

	// Do we have required paramters?
	if (empty($web_host) || empty($environment_name)) {
		return $return_value;
	}

	// Initialize return value
	$return_value='';
	$image_path='';

	// Get logo based on web host
	if ($web_host=='wpengine') {
		$image_path.=plugin_dir_url( __DIR__ ).'images/logo-wpengine.png';
	} elseif ($web_host=='materiell') {
		$image_path.=plugin_dir_url( __DIR__ ).'images/logo-materiell.png';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$image_path.=plugin_dir_url( __DIR__ ).'images/logo-acsf.png';
	}

	// Get URL based on host
	if ($web_host=='wpengine') {
		$web_host_admin_url.='https://my.wpengine.com/installs/'.$environment_name.'/';
	} elseif ($web_host=='materiell') {
		$web_host_admin_url.='https://cloud.materiell.com/cloudsites';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$web_host_admin_url='https://www.georgemasonusf.acsitefactory.com/sites-by-group/list?field_domain_contains='.$environment_name.'';
	}

	// build link element
	$return_value.='<a href="'.$web_host_admin_url.'" target="_blank" title="web host admin"><img style="width:25px;" src="'.$image_path.'" /></a>';

	// Return value
	return $return_value;

}

/**
 * Displays website statistical information for the custom admin page
 */
function gmuw_websitesgmu_websites_content_statistics() {

		//Initialize variables
		$return_value = '';

		// Display heading
		$return_value .= '<h2>Statistics</h2>';

		// Display stats

		$return_value .= '<p>' . gmuw_websitesgmu_get_cpt_total('website','not-deleted') . ' current website records.</p>';
		$return_value .= '<p>(' . gmuw_websitesgmu_get_cpt_total('website','deleted') . ' deleted, for ' . gmuw_websitesgmu_get_cpt_total('website','all') . ' website records total).</p>';

		$return_value .= '<h3>Production</h3>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','production_domain');
		$return_value .= '<p>'.$count . ' production websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

		$return_value .= '<h3>Content Management Systems</h3>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','cms','wordpress');
		$return_value .= '<p>'.$count . ' WordPress websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','cms','drupal');
		$return_value .= '<p>'.$count . ' Drupal websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

		$return_value .= '<h3>Web Hosts</h3>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','web_host','materiell');
		$return_value .= '<p>'.$count . ' Materiell websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','web_host','wpengine');
		$return_value .= '<p>'.$count . ' WPEngine websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','web_host','acquia-cloud-site-factory');
		$return_value .= '<p>'.$count . ' Acquia Cloud SiteFactory websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

		$return_value .= '<h3>WordPress Info</h3>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','cms','wordpress');
		$return_value .= '<p>'.$count . ' WordPress websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

		$count=gmuw_websitesgmu_websites_using_theme();
		$percent=round($count/gmuw_websitesgmu_get_cpt_total('website','not-deleted','','','cms','wordpress')*100,2).'%';

		$return_value .= '<p>'.$count . ' on official theme ('.$percent.')'.'</p>';

		$return_value .= '<h3>GA/GTM Info</h3>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','website_gtm_container_post_id');
		$return_value .= '<p>'.$count . ' with GTM ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';
		$count=gmuw_websitesgmu_get_cpt_total('website','not-deleted','website_ga_property_post_id');
		$return_value .= '<p>'.$count . ' with GA4 ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

	// Return value
	return $return_value;

}

/**
 * Get website total percentages
 */
function gmuw_websitesgmu_get_website_total_percentage($number) {

	//Initialize variables
	$return_value = '';

	//Calculate value
	$return_value .=  round($number/gmuw_websitesgmu_get_cpt_total('website','not-deleted')*100,2) .'%';

	// Return value
	return $return_value;

}

/**
 * Gets count of website environments using the official theme
 */
function gmuw_websitesgmu_websites_using_theme() {

	// Get total number of wordpress websites using the official theme

	return count(
		get_posts(
			array(
		    'post_type'  => 'website',
		    'post_status' => 'publish',
		    'nopaging' => true,
		    'meta_query' => array(
		      array(
		        'relation' => 'AND',
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
		        ),
		        array(
							'key'   => 'wordpress_theme',
							'value' => 'gmuj|Mason Twenty Twenty Theme',
		          'compare' => 'REGEXP',
		        ),
		      )
		    )
			)
		)
	);

}

/**
 * Adds custom website statistcs meta box to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_websitesgmu_custom_dashboard_meta_box_websites_statistics');
function gmuw_websitesgmu_custom_dashboard_meta_box_websites_statistics() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_websites_statistics", "Website Statistics", "gmuw_websitesgmu_custom_dashboard_meta_box_websites_statistics_content", "dashboard","normal");

}

/**
 * Provides content for the custom website statistics dashboard meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_websites_statistics_content() {

  echo gmuw_websitesgmu_websites_content_statistics();

}

function gmuw_websitesgmu_get_live_website_theme($post_id){
  //Get the real-time theme info from the WP REST API on the live site

      // Get post object
      $post = get_post($post_id);
      //$return_value.='Post ID: '.$post_id.'<br />';

      // Get post hosting domain=
      $domain = gmuw_websitesgmu_hosting_domain(wp_get_post_terms($post_id,'web_host')[0]->slug, get_post_meta($post_id, 'environment_name', true),false);
      //$return_value.='Domain: '.$domain.'<br />';

      // Set URL for REST endpoint
      $mason_site_check_in_theme_info_endpoint_url='https://' . $domain . '/wp-json/gmuj-sci/theme-info';
      //$return_value.='URL: '.$mason_site_check_in_theme_info_endpoint_url.'<br />';

      // Try to get the info
      $mason_site_check_in_theme_info_response = gmuw_msc_get_url_content($mason_site_check_in_theme_info_endpoint_url);
        //$return_value.='<p><textarea>'.$mason_site_check_in_theme_info_response.'</textarea></p>';
      // Try to parse it as JSON
      $mason_site_check_in_theme_info_response_json = json_decode($mason_site_check_in_theme_info_response);
        //$return_value.=var_dump($mason_site_check_in_theme_info_response_json);

        // Did we get a json response?
        if (gettype($mason_site_check_in_theme_info_response_json)!='object'){
          $return_value.='<p><a href="'.$mason_site_check_in_theme_info_endpoint_url.'" target="_blank">Not JSON object.</a> &#128533;</p>';
        } else {
          if ($mason_site_check_in_theme_info_response_json->data->status==404){
              $return_value.='<p><a href="'.$mason_site_check_in_theme_info_endpoint_url.'" target="_blank">JSON 404.</a> &#128533;</p>';
          } else {
            $return_value.='<p>';
            $return_value.='<a href="'.$mason_site_check_in_theme_info_endpoint_url.'" target="_blank">'.$mason_site_check_in_theme_info_response_json->theme_name.' ('.$mason_site_check_in_theme_info_response_json->theme_version.')</a> &#128515;';
            $return_value.='</p>';

            // Is the post meta theme name different from the live theme name?
            if (get_post_meta($post_id, 'wordpress_theme', true ) != $mason_site_check_in_theme_info_response_json->theme_name.' ('.$mason_site_check_in_theme_info_response_json->theme_version.')') {
              $return_value.='<p>';
              $return_value.=gmuw_websitesgmu_update_theme_link($post_id,$mason_site_check_in_theme_info_response_json->theme_name,$mason_site_check_in_theme_info_response_json->theme_version);
              $return_value.='</p>';
            }

          }
        }

      // Return value
      return $return_value;

}

function gmuw_websitesgmu_update_theme_link($post_id,$theme_name,$theme_version){
  //Display theme update link

  //Return value
  return "<a class='button button-small' href='admin.php?page=gmuw_websitesgmu&action=update_theme&post_id=". $post_id ."&theme_name=". urlencode($theme_name) ."&theme_version=". $theme_version ."'>Update Post</a>";

}
