<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Builds hosting domain URLs
 */
function gmuw_websitesgmu_website_hosting_domain($post_id,$include_protocol=True) {

	// Initialize variables
	$return_value='';

	// Get web host
	$web_host = wp_get_post_terms($post_id,'web_host') ? wp_get_post_terms($post_id,'web_host')[0]->slug : 'unknown';

	// Get environment name
	$environment_name = get_post_meta($post_id,'environment_name',true );

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
function gmuw_websitesgmu_website_cms_login_url($post_id) {

	// Initialize variables
	$return_value='';

	// Get web hosting domain
	$return_value.=gmuw_websitesgmu_website_hosting_domain($post_id);

	// Get CMS
	$cms = wp_get_post_terms($post_id,'cms') ? wp_get_post_terms($post_id,'cms')[0]->slug : 'unknown';

	// Build CMS login URL based on CMS
	if ($cms=='wordpress') {
		$return_value.='/wp-admin/';
	} elseif ($cms=='drupal') {
		$return_value.='/user/login';
	}

	// Return value
	return $return_value;

}

/**
 * Builds web host admin URL
 */
function gmuw_websitesgmu_website_web_host_admin_url($post_id) {

	// Initialize variables
	$return_value='';

	// Get website web host
	$web_host = wp_get_post_terms($post_id,'web_host') ? wp_get_post_terms($post_id,'web_host')[0]->slug : 'unknown';


	// Get environment name
	$environment_name = get_post_meta($post_id,'environment_name',true );

	// Get URL admin URL based on web host
	if ($web_host=='wpengine') {
		$return_value.='https://my.wpengine.com/installs/'.$environment_name.'/';
	} elseif ($web_host=='materiell') {
		$return_value.='https://cloud.materiell.com/cloudsites';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$return_value='https://www.georgemasonusf.acsitefactory.com/sites-by-group/list?field_domain_contains='.$environment_name.'';
	}

	// Return value
	return $return_value;

}

/**
 * Gets URL of web host logo images in plugin
 */
function gmuw_websitesgmu_web_host_logo_url($web_host) {

	// Initialize variables
	$return_value='';

	// Get logo based on web host
	if ($web_host=='wpengine') {
		$return_value.=plugin_dir_url( __DIR__ ).'images/logo-wpengine.png';
	} elseif ($web_host=='materiell') {
		$return_value.=plugin_dir_url( __DIR__ ).'images/logo-materiell.png';
	} elseif ($web_host=='acquia-cloud-site-factory') {
		$return_value.=plugin_dir_url( __DIR__ ).'images/logo-acsf.png';
	}

	// Return value
	return $return_value;

}

/**
 * Gets URL of CMS logo images in plugin
 */
function gmuw_websitesgmu_cms_logo_url($cms) {

	// Initialize variables
	$return_value='';

	// Get logo based on CMS
	if ($cms=='wordpress') {
		$return_value.=plugin_dir_url( __DIR__ ).'images/logo-wordpress.png';
	} elseif ($cms=='drupal') {
		$return_value.=plugin_dir_url( __DIR__ ).'images/logo-drupal.png';
	}

	// Return value
	return $return_value;

}

/**
 * Builds CMS login HTML link element
 */
function gmuw_websitesgmu_website_cms_login_link($post_id) {
	// Supports wpengine, materiell, acquia-cloud-site-factory

	// Initialize variables
	$return_value='';

	// Get website web host
	$web_host = wp_get_post_terms($post_id,'web_host') ? wp_get_post_terms($post_id,'web_host')[0]->slug : 'unknown';


	// Are we using a supported web host?
	if ($web_host != 'wpengine' && $web_host != 'materiell' && $web_host != 'acquia-cloud-site-factory') {

			// Return early
			return $return_value;

	}

	// Get website CMS login URL
	$cms_login_url = gmuw_websitesgmu_website_cms_login_url($post_id);

	// Get website CMS
	$cms = wp_get_post_terms($post_id,'cms') ? wp_get_post_terms($post_id,'cms')[0]->slug : 'unknown';

	// Get logo based on CMS
	$logo_image_url = gmuw_websitesgmu_cms_logo_url($cms);

	// build link element
	$return_value.='<a href="'.$cms_login_url.'" target="_blank" title="CMS login"><img style="width:25px;" src="'.$logo_image_url.'" /></a>';

	// Return value
	return $return_value;

}

function gmuw_websitesgmu_website_web_host_admin_link($post_id){
	// Supports wpengine, materiell, acquia-cloud-site-factory

	// Initialize variables
	$return_value='';

	// Get website web host
	$web_host = wp_get_post_terms($post_id,'web_host') ? wp_get_post_terms($post_id,'web_host')[0]->slug : 'inknown';


	// Are we using a supported web host?
	if ($web_host != 'wpengine' && $web_host != 'materiell' && $web_host != 'acquia-cloud-site-factory') {

			// Return early
			return $return_value;

	}

	// Get web host admin URL
	$web_host_admin_url = gmuw_websitesgmu_website_web_host_admin_url($post_id);

	// Get logo based on web host
	$logo_image_url = gmuw_websitesgmu_web_host_logo_url($web_host);

	// build link element
	$return_value.='<a href="'.$web_host_admin_url.'" target="_blank" title="web host admin"><img style="width:25px;" src="'.$logo_image_url.'" /></a>';

	// Return value
	return $return_value;

}

function gmuw_websitesgmu_sucuri_link($post_id,$mode='firewall'){

	// Get post
	$post = get_post($post_id);

	// Get production domain
	$production_domain = get_post_meta($post_id,'production_domain',true);

	// If we don't have a production domain, exit
	if (empty($production_domain)) {
		return $return_value;
	}

	// Initialize variables
	$return_value='';
	$image_path=plugin_dir_url( __DIR__ ).'images/logo-sucuri.png';;

	// Get URL based on mode
	switch ($mode) {
		case 'firewall':
			$link_url='https://waf.sucuri.net/?settings&site='.$production_domain;
			break;
		case 'monitor':
			$link_url='https://monitor21.sucuri.net/m/site/?site='.$production_domain;
			break;
	}

	// build link element
	$return_value.='<a href="'.$link_url.'" target="_blank" title="sucuri '.$mode.'"><img style="width:50px;" src="'.$image_path.'" /></a>';

	// Return value
	return $return_value;

}

function gmuw_websitesgmu_google_search_console_link($post_id){

	// Get post
	$post = get_post($post_id);

	// Get production domain
	$production_domain = get_post_meta($post_id,'production_domain',true);

	// If we don't have a production domain, exit
	if (empty($production_domain)) {
		return $return_value;
	}

	// Initialize variables
	$return_value='';
	$image_path=plugin_dir_url( __DIR__ ).'images/logo-google.png';

	// Get URL
	$link_url='https://search.google.com/search-console?resource_id=https://'.$production_domain.'/';

	// build link element
	$return_value.='<a href="'.$link_url.'" target="_blank" title="sucuri '.$mode.'"><img style="width:25px;" src="'.$image_path.'" /></a>';

	// Return value
	return $return_value;

}

function gmuw_websitesgmu_dubbot_link($post_id,$mode=''){

	// Get post
	$post = get_post($post_id);

	// get dubbot site id
	$dubbot_site_id=get_post_meta($post_id,'dubbot_site_id',true);

	// If we don't have a production domain, exit
	if (empty($dubbot_site_id)) {
		return $return_value;
	}

	// Initialize variables
	$return_value='';
	$image_path=plugin_dir_url( __DIR__ ).'images/logo-dubbot.png';

	// Get URL
	if ($mode=='admin') {
		$link_url='https://app.dubbot.com/account/sites/'.$dubbot_site_id;
	} else {
		$link_url='https://app.dubbot.com/sites/'.$dubbot_site_id;
	}

	// build link element
	$return_value.='<a href="'.$link_url.'" target="_blank" title="DubBot '.$mode.'"><img style="width:25px;" src="'.$image_path.'" /></a>';

	// Return value
	return $return_value;

}

/**
 * Displays prepared HTML content representing a listing of website statistics by taxonomy
 */
function gmuw_websitesgmu_websites_display_stats_by_taxonomy($taxonomy) {

	// Initialize variables
	$return_value = '';

	// Display title
	$return_value.='<h3>';
	$return_value.=get_taxonomy($taxonomy)->labels->name; // Get display title from custom taxonomy registration
	$return_value.='</h3>';

	// Get all terms in taxonomy
	$terms=get_terms(array(
		'taxonomy' => $taxonomy,
		'orderby' => 'count',
		'order' => 'DESC',
	));
	// Loop through terms in taxonomy
	foreach ($terms as $term){
		// Get number of website posts which are not deleted which use this taxonomy term
		$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','','',$taxonomy,$term->slug));
		// Display website stats
		$return_value .= '<p>'.$count . ' ' .$term->name. ' websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';
	}

	// Return value
	return $return_value;

}

/**
 * Displays website statistical information for the custom admin page
 */
function gmuw_websitesgmu_websites_content_statistics() {

		//Initialize variables
		$return_value = '';

		// Display stats

		$return_value .= '<h3>Total Website Records</h3>';
		//calculate using transients
		$mytransientname='stats_total_website_records';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			$mydata .= '<p>' . count(gmuw_websitesgmu_get_custom_posts('website','not-deleted')) . ' current website records.</p>';
			$mydata .= '<p>(' . count(gmuw_websitesgmu_get_custom_posts('website','deleted')) . ' deleted; ' . count(gmuw_websitesgmu_get_custom_posts('website','all')) . ' total records)</p>';

			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

		$return_value .= '<h3>Production Websites</h3>';
		//calculate using transients
		$mytransientname='stats_prod_websites';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain'));
			$mydata .= '<p>'.$count . ' production websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';
			$mydata .= '<p>' . gmuw_websitesgmu_websites_using_php7_only_production() . ' production websites using PHP 7.x';

			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

		$return_value .= '<h3>WordPress Websites</h3>';
		//calculate using transients
		$mytransientname='stats_wp_websites';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			// calculate stats
				//how many instances are wordpress?
					$wordpress_instances = count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','','','cms','wordpress'));
				//how many wordpress instances are production?
					$production_wordpress_instances=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain','','cms','wordpress'));
				//how many instances are using official wordpress theme?
					$wordpress_instances_using_theme=gmuw_websitesgmu_websites_using_theme();
				// how many wordpress instances using the theme are production?
					$wordpress_production_instances_using_theme=gmuw_websitesgmu_websites_using_theme_only_production();
				// how many wordpress instances using the theme are production and have been updated to the new brand look?
					$wordpress_production_instances_using_theme_updated_brand=gmuw_websitesgmu_websites_using_theme_only_production_updated_brand();
				//how many instances are using Elementor?
					$wordpress_instances_using_elementor=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','uses_elementor','1'));

			// display stats
			$mydata .= '<p>'.$wordpress_instances . ' WordPress instances ('.gmuw_websitesgmu_get_website_total_percentage($wordpress_instances).')'.'</p>';

			$mydata .= '<p>'.$production_wordpress_instances . ' WordPress instances are production ('.round($production_wordpress_instances/$wordpress_instances*100,2).'%)'.'</p>';

			$mydata .= '<p>'.$wordpress_instances_using_theme . ' WordPress instances on official theme ('.round($wordpress_instances_using_theme/$wordpress_instances*100,2).'%)'.'</p>';

			$mydata .= '<p>'.$wordpress_production_instances_using_theme . ' <em>production</em> WordPress instances on official theme ('.round($wordpress_production_instances_using_theme/$production_wordpress_instances*100,2).'%)'.'</p>';

			$mydata .= '<p>'.$wordpress_production_instances_using_theme_updated_brand . ' <em>production</em> WordPress instances on official theme with the updated brand look ('.round($wordpress_production_instances_using_theme_updated_brand/$wordpress_production_instances_using_theme*100,2).'%)'.'</p>';

			$mydata .= '<p>'.$wordpress_instances_using_elementor . ' WordPress instances are using Elementor ('.round($wordpress_instances_using_elementor/$wordpress_instances*100,2).'%)'.'</p>';


			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

		$return_value .= '<h3>GA/GTM Info</h3>';
		//calculate using transients
		$mytransientname='stats_analytics';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain'));
			$mydata .= '<p>'.$count . ' production websites ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

			$count=count(gmuw_websitesgmu_production_websites_needing_analytics());
			$mydata .= '<p>'.$count . ' production websites need analytics ('.gmuw_websitesgmu_get_website_total_percentage($count).')'.'</p>';

			$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','website_gtm_container_post_id'));
			$mydata .= '<p>'.$count . ' with GTM ('.round($count/count(gmuw_websitesgmu_production_websites_needing_analytics())*100,2).'%)'.'</p>';
			$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','website_ga_property_post_id'));
			$mydata .= '<p>'.$count . ' with GA4 ('.round($count/count(gmuw_websitesgmu_production_websites_needing_analytics())*100,2).'%)'.'</p>';

			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

		// taxonomy: cms
		//calculate using transients
		$mytransientname='stats_taxonomy_cms';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			$mydata .= gmuw_websitesgmu_websites_display_stats_by_taxonomy('cms');

			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

		// taxonomy: web_host
		//calculate using transients
		$mytransientname='stats_taxonomy_webhost';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			$mydata .= gmuw_websitesgmu_websites_display_stats_by_taxonomy('web_host');

			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

		// taxonomy: department
		//calculate using transients
		$mytransientname='stats_taxonomy_dept';
		$mytransient=get_transient($mytransientname);
		if($mytransient) {
			$return_value .= $mytransient;
		} else {
			$mydata='';

			$mydata .= gmuw_websitesgmu_websites_display_stats_by_taxonomy('department');

			//store data in transient
			set_transient($mytransientname, $mydata, HOUR_IN_SECONDS/4);
			//append my data to output
			$return_value .= $mydata;
		}

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
	$return_value .=  round($number/count(gmuw_websitesgmu_get_custom_posts('website','not-deleted'))*100,2) .'%';

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
 * Gets count of website environments using the official theme
 */
function gmuw_websitesgmu_websites_using_theme_only_production() {

	// Get total number of production wordpress websites using the official theme

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
		        array(
		          'key'   => 'production_domain',
		          'compare' => 'EXISTS',
		        ),
		        array(
		          'key'   => 'production_domain',
		          'value' => '',
		          'compare' => '!=',
		        ),
		      )
		    )
			)
		)
	);

}

/**
 * Gets count of website environments using the official theme with the updated brand look
 */
function gmuw_websitesgmu_websites_using_theme_only_production_updated_brand() {

	// Get total number of production wordpress websites using the official theme with the updated brand look

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
		        array(
		          'key'   => 'production_domain',
		          'compare' => 'EXISTS',
		        ),
		        array(
		          'key'   => 'production_domain',
		          'value' => '',
		          'compare' => '!=',
		        ),
		        array(
		          'key'   => 'brand_update_completed',
		          'value' => '1',
		        ),
		      )
		    )
			)
		)
	);

}

/**
 * Gets count of website environments using PHP 7.x
 */
function gmuw_websitesgmu_websites_using_php7_only_production() {

	// Get total number of production wordpress websites using PHP 7

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
							'key'   => 'php_version',
							'value' => '^7',
		          'compare' => 'REGEXP',
		        ),
		        array(
		          'key'   => 'production_domain',
		          'compare' => 'EXISTS',
		        ),
		        array(
		          'key'   => 'production_domain',
		          'value' => '',
		          'compare' => '!=',
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
      $domain = gmuw_websitesgmu_website_hosting_domain($post_id,false);
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

function gmuw_websitesgmu_custom_website_list(){

	// Initialize variables
	$return_value='';

	// Build return value

		// Get posts
		$websites_all = get_posts(
			array(
				'post_type'  => 'website',
		    'post_status' => 'publish',
				'nopaging' => true,
				'order' => 'ASC',
				'orderby' => 'name',
			)
		);

		// Loop through theme websites
		$return_value .= '<table class="data_table">';
		$return_value .= '<thead>';
		$return_value .= '<tr>';
		$return_value .= '<th>Environment Name</th>';
		$return_value .= '<th>PHP Version</th>';
		$return_value .= '<th>Post ID</th>';
		$return_value .= '<th>Department</th>';
		$return_value .= '<th>Web Host</th>';
		$return_value .= '<th>CMS</th>';
		$return_value .= '<th>Hosting Domain</th>';
		$return_value .= '<th>Production Domain</th>';
		$return_value .= '<th>Theme</th>';
		$return_value .= '<th>New Brand?</th>';
		$return_value .= '<th>Notes</th>';
		$return_value .= '<th>Follow-Up</th>';
		$return_value .= '<th>Data Feeds</th>';
		$return_value .= '<th>View</th>';
		$return_value .= '<th>Edit</th>';
		$return_value .= '<th>Admin Login</th>';
		$return_value .= '<th>Web Host Admin</th>';
		$return_value .= '</tr>';
		$return_value .= '</thead>';
		$return_value .= '<tbody>';
		foreach ( $websites_all as $post ) {
			// Begin table row for website environment
			$return_value .= '<tr class="';
			$return_value .= $post->follow_up==1 ? 'follow_up ' : '';
			$return_value .= $post->deleted==1 ? 'deleted ' : '';
			$return_value .= $post->working==1 ? 'working ' : '';
			$return_value .= '">';
			// Output row data
			$return_value .= '<td>' . $post->environment_name.'</td>';
			$return_value .= '<td>' . $post->php_version.'</td>';
			$return_value .= '<td>' . $post->ID . '</td>';

			$return_value .= '<td>';
			foreach ( wp_get_post_terms($post->ID,'department') as $term ) {
				$return_value .= $term->name;
			}
			$return_value .= '</td>';

			$return_value .= '<td>';
			foreach ( wp_get_post_terms($post->ID,'web_host') as $term ) {
				$return_value .= $term->name;
			}
			$return_value .= '</td>';

			$return_value .= '<td>';
			foreach ( wp_get_post_terms($post->ID,'cms') as $term ) {
				$return_value .= $term->name;
			}
			$return_value .= '</td>';

			$return_value .= '<td><a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'" target="_blank">' . gmuw_websitesgmu_website_hosting_domain($post->ID,false) . '</a></td>';

			$return_value .= '<td><a href="https://'.$post->production_domain.'" target="_blank">' . $post->production_domain . '</a></td>';
			$return_value .= '<td>' . $post->wordpress_theme . '</td>';

			$return_value .= '<td>' . ($post->brand_update_completed==1?'YES':'') . '</td>';

			$return_value .= '<td>';
			if (substr($post->php_version, 0, 1)=='7') {
				$return_value .= 'PHP 7.x';
				$return_value .= get_userdata($post->website_task_upgrade_php_user) ? ' ('.get_userdata($post->website_task_upgrade_php_user)->user_login.')' : '';
				$return_value .= '<br />';
			}
			if ($post->uses_elementor) {
				$return_value .= 'Uses Elementor<br />';
			}
			if ($post->uses_amr_shortcode_any_widget) {
				$return_value .= 'Uses AMR Shortcode Any Widget<br />';
			}
			$return_value .= '</td>';

			$return_value .= '<td>' . ($post->followup_flag==1 ? 'follow-up' : '').'</td>';

			// WordPress data feeds
			$return_value .= '<td>';
			if ($post->deleted==1) {
				$return_value .= '&nbsp;';
			} else {

				if (has_term('wordpress', 'cms', $post)) {

					$return_value .= '' . '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-sci/theme-info">theme info</a><br /><a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-sci/most-recent-modifications">modifications</a><br /><a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-mmi/mason-site-info">site info</a>';

				}

			}
			$return_value .= '</td>';

			$return_value .= '<td>' . gmuw_websitesgmu_record_get_utility_link($post->ID,'view') .'</td>';

			$return_value .= '<td>' . gmuw_websitesgmu_record_get_utility_link($post->ID,'edit') .'</td>';

			if ($post->deleted==1) {
				$return_value .= '<td>&nbsp;</td>';
			} else {
				$return_value .= '<td>'.gmuw_websitesgmu_website_cms_login_link($post->ID).'</td>';
			}

			if ($post->deleted==1) {
				$return_value .= '<td>&nbsp;</td>';
			} else {
				$return_value .= '<td>'.gmuw_websitesgmu_website_web_host_admin_link($post->ID).'</td>';
			}

			// Finish row
			$return_value .= '</tr>';
		}
		$return_value .= '</tbody>';
		$return_value .= '</table>';

		// Finish HTML output
		$return_value .= "</div>";

	// Return value
	return $return_value;

}

function gmuw_websitesgmu_production_websites_needing_analytics(){

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => 'website',
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );  

  $args_meta = array(
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
          'relation' => 'AND',
          array(
            'key'   => 'production_domain',
            'compare' => 'EXISTS',
          ),
          array(
            'key'   => 'production_domain',
            'value' => '',
            'compare' => '!=',
          ),
        ),
        array(
          'relation' => 'OR',
          array(
            'key'   => 'doesnt_need_analytics',
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key'   => 'doesnt_need_analytics',
            'value' => '1',
            'compare' => '!=',
          ),
        ),
      )
    )
  );

  // merge arg and tax arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

	// Return posts
	return $posts;

}

function gmuw_websitesgmu_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug){

	// Initialize variables
	$return_value='';

	// Get posts
	$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain','',$taxonomy,$taxonomy_term_slug);

	// Do we have posts?
	if (empty($websites)) {
		echo '<p>There are no live/production websites associated with this department.</p>';
	} else {
		// setup display
		$return_value.='<table>';
		$return_value.='<thead>';
		$return_value.='<tr>';
		$return_value.='<th>Domain</th>';
		$return_value.='<th class="department">Department</th>';
		$return_value.='<th class="cms">CMS</th>';
		$return_value.='<th>GTM Container</th>';
		$return_value.='<th>GA4 Property</th>';
		$return_value.='<th>GA4 Roll-Up Property</th>';
		//$return_value.='<th>Details</th>';
	  if (is_user_logged_in()) {
		$return_value.='<th class="web_host">Web Host</th>';
		$return_value.='<th>Status</th>';
	  	$return_value.='<th>Edit</th>';
	  }
		$return_value.='</tr>';
		$return_value.='</thead>';
		$return_value.='<tbody>';

		// Loop through posts
		foreach ( $websites as $website ) {

			if ( $website->website_hide!=1 || current_user_can('manage_options') ) {

				if ( $website->website_hide!=1 ) {
					$return_value.='<tr>';
				} else {
					$return_value.='<tr class="hidden">';
				}

				$return_value.='<td>';
				$return_value.='<a href="'.get_permalink($website->ID).'" target="_blank">';
				$return_value.=$website->production_domain;
				$return_value.='</a>';
				$return_value.=' <a class="admin-icon admin-external-link" href="https://'.$website->production_domain.'/" target="_blank"></a>';
				$return_value.='</td>';

				$return_value.='<td class="department">';
				foreach (wp_get_post_terms($website->ID, 'department') as $department) {
					$return_value.=$department->name;
				}
				$return_value.='</td>';

				$return_value.='<td class="cms">';
				foreach (wp_get_post_terms($website->ID, 'cms') as $cms) {
					$return_value.=$cms->name;
				}
				$return_value.='</td>';

				$return_value.='<td style="white-space: nowrap;">';
				if (!empty(get_post_meta($website->ID, 'website_gtm_container_post_id', true))) {
				if (is_user_logged_in()) $return_value.= '<a href="'.gmuw_websitesgmu_gtm_container_admin_link_url(get_post_meta($website->ID, 'website_gtm_container_post_id', true)).'" target="_blank">';
		      $return_value.=get_post_meta(get_post_meta($website->ID, 'website_gtm_container_post_id', true),'gtm_container_id_public',true);
				if (is_user_logged_in()) $return_value.= '</a>';
		      $return_value.=get_post_meta(get_post_meta($website->ID, 'website_gtm_container_post_id', true),'gtm_container_non_standard',true)==1 ? ' <span class="admin-icon admin-code1 gmuw-warning" title="non-standard GTM container"></span>' : '';
				} else {
					if (get_post_meta($website->ID, 'doesnt_need_analytics', true)==1) {
						$return_value.= 'NA';
					}
				}
		    $return_value.='</td>';

				$return_value.='<td>';
				if (!empty(get_post_meta($website->ID, 'website_ga_property_post_id', true))) {
		      $return_value.='<a href="'.gmuw_websitesgmu_ga_property_admin_link_url(get_post_meta($website->ID, 'website_ga_property_post_id', true)).'" target="_blank">';
		      $return_value.=get_post_meta(get_post_meta($website->ID, 'website_ga_property_post_id', true),'ga_measurement_id',true);
		      $return_value.='</a>';
				} else {
					if (get_post_meta($website->ID, 'doesnt_need_analytics', true)==1) {
						$return_value.= 'NA';
					}
				}
				$return_value.='</td>';

				$return_value.='<td>';
				if (!empty(get_post_meta($website->ID, 'website_ga_property_rollup_post_id', true))) {
		      $return_value.='<a href="'.gmuw_websitesgmu_ga_property_admin_link_url(get_post_meta($website->ID, 'website_ga_property_rollup_post_id', true)).'" target="_blank">';
		      $return_value.=get_post_meta(get_post_meta($website->ID, 'website_ga_property_rollup_post_id', true),'ga_measurement_id',true);
		      $return_value.='</a>';
				} else {
					if (get_post_meta($website->ID, 'doesnt_need_analytics', true)==1) {
						$return_value.= 'NA';
					}
				}
				$return_value.='</td>';

		    //$return_value.='<td>'.gmuw_websitesgmu_record_get_utility_link($website->ID,'view').'</td>';

				if (is_user_logged_in()) {

				//web host
				$return_value.='<td class="web_host">';
				foreach (wp_get_post_terms($website->ID, 'web_host') as $web_host) {
					$return_value.=$web_host->name;
				}
				$return_value.='</td>';

				//status
				$return_value.='<td class="whitespace-nowrap">';
				//is this a wordpress website?
				if( has_term( 'wordpress', 'cms', $website->ID ) ) {
					//does it have a production domain?
					if (!empty($website->production_domain)) {
						//is it our theme?
						if (preg_match("/gmuj|Mason Twenty Twenty/i", $website->wordpress_theme)==0) {
							$return_value.=' <span class="admin-icon admin-theme-conversion gmuw-info" title="theme conversion candidate"></span> ';
						}
					}

				}
				//good example of our official theme?
				if ($website->good_example_official_theme == 1) {
					$return_value.=' <span class="admin-icon admin-good-example gmuw-good" title="good example of official WordPress theme"></span> ';

				}
				if (!empty($website->notes_private)) {
					$return_value.=' <span class="admin-icon admin-info gmuw-info" title="Has additional notes"></span> ';
				}
				if (!empty(get_post_meta($website->ID, 'working', true))) {
					$return_value.=' <span class="admin-icon admin-working gmuw-good" title="currently working on this website"></span> ';
				}
				if (!empty(get_post_meta($website->ID, 'follow_up', true))) {
					$return_value.=' <span class="admin-icon admin-follow-up gmuw-caution" title="needs follow-up"></span> ';
				}
				if (get_post_meta(get_post_meta($website->ID, 'website_gtm_container_post_id', true),'gtm_container_non_standard',true)==1) {
					$return_value.=' <span class="admin-icon admin-code1 gmuw-warning" title="non-standard GTM container"></span> ';

				}
				if (!empty(get_post_meta($website->ID, 'php_version', true)) && substr(get_post_meta($website->ID, 'php_version', true), 0, 1)=='7') {
					$return_value.=' <span class="admin-icon admin-config gmuw-warning" title="Website uses PHP 7.x"></span> ';
				}
				if (!empty(get_post_meta($website->ID, 'uses_elementor', true))) {
					$return_value.=' <span class="admin-icon admin-plugin gmuw-alert" title="Website uses the Elementor plugin"></span> ';
				}
				if (!empty(get_post_meta($website->ID, 'uses_amr_shortcode_any_widget', true))) {
					$return_value.=' <span class="admin-icon admin-plugin gmuw-alert" title="Website uses the AMR Shortcode Any Widget plugin"></span> ';
				}
				if (get_post_meta(get_post_meta($website->ID, 'website_ga_property_post_id', true),'ga_property_account_post_id',true)==1) {
					$return_value.=' <span class="admin-icon admin-code1" title="non-standard GTM container"></span> ';

				}
				if (get_post_meta(get_post_meta(get_post_meta($website->ID, 'website_ga_property_post_id', true),'ga_property_account_post_id',true),'ga_account_permissions_missing',true)==1) {
					$return_value.=' <span class="admin-icon admin-lock gmuw-alert" title="missing GA account permissions"></span> ';
				}
				if (!empty(get_post_meta($website->ID, 'website_hide', true))) {
					$return_value.=' <span class="admin-icon admin-hidden gmuw-special" title="Website is hidden on the front-end from non-admins"></span> ';
				}
				$return_value.='</td>';

				//edit
				$return_value.='<td>'.gmuw_websitesgmu_record_get_utility_link($website->ID,'edit').'</td>';
				}

				$return_value.='</tr>';

			}

		}

		// finish display
		$return_value.='</tbody>';
		$return_value.='</table>';

	}

	// Return value
	return $return_value;
}

function gmuw_websitesgmu_non_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug){

	// Initialize variables
	$return_value='';

	// Get posts
	$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','',$taxonomy,$taxonomy_term_slug);

	// do we have posts?
	if (empty($websites)) {
		echo '<p>There are no other website instances associated with this department.</p>';
	} else {

		// setup display
		$return_value.='<table>';
		$return_value.='<thead>';
		$return_value.='<tr>';
		$return_value.='<th>Environment/Instance Name</th>';
		$return_value.='<th>Details</th>';
	  if (is_user_logged_in()) {
	  	$return_value.='<th>Edit</th>';
	  }
		$return_value.='</tr>';
		$return_value.='</thead>';
		$return_value.='<tbody>';

		// Loop through posts
		foreach ( $websites as $website ) {
			if (empty($website->production_domain)) {
				$return_value.='<tr>';
				$return_value.='<td>';
				$return_value.='<a href="'.get_permalink($website->ID).'">';
				$return_value.=$website->post_title;
				$return_value.='</a>';
				$return_value.='</td>';
		    $return_value.='<td>'.gmuw_websitesgmu_record_get_utility_link($website->ID,'view').'</td>';
				if (is_user_logged_in()) {
		    	$return_value.='<td>'.gmuw_websitesgmu_record_get_utility_link($website->ID,'edit').'</td>';
		    }
				$return_value.='</tr>';
			}
		}

		// finish display
		$return_value.='</tbody>';
		$return_value.='</table>';

	}

	// Return value
	return $return_value;
}

//function to display links to website homepages by taxonomy and term
function gmuw_websitesgmu_websites_display_homepage_links_by_taxonomy($taxonomy,$term) {

  // Initialize variables
  $return_value = '';

  // is taxonomy specified?
  if (empty($taxonomy)) {
    //no taxonomy specified

    //get taxonomies that apply to websites
    $taxonomy_objects = get_object_taxonomies( 'website' );
    //loop through taxonomies and display links to current page with that taxonomy specified as a query parameter
    foreach ($taxonomy_objects as $taxonomy_object) {
      $return_value .= '<p><a href="?taxonomy='.$taxonomy_object.'">'.$taxonomy_object .'</a></p>';

    }

  } else {
    //we have a taxonomy

    // Display title
    $return_value.='<h3>';
    $return_value.=get_taxonomy($taxonomy)->labels->name; // Get display title from custom taxonomy registration
    $return_value.='</h3>';

    //do we have a term
    if (empty($term)) {
      //we do not have a term, show all

      // Get all terms in taxonomy
      $terms=get_terms(array(
        'taxonomy' => $taxonomy,
        'orderby' => 'count',
        'order' => 'DESC',
      ));
      // Loop through terms in taxonomy
      foreach ($terms as $term){
        // Get number of website posts which are not deleted which use this taxonomy term
        $count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain','',$taxonomy,$term->slug));
        // Display website stats
        $return_value .= '<p><a href="?taxonomy='.$taxonomy.'&term='.$term->slug.'">'.$count . ' ' .$term->name. ' websites'.'</a></p>';
      }

    } else {
      //we do have a term

      // Get number of website posts which are not deleted which use this taxonomy term
      $myposts=gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain','',$taxonomy,$term);
      // Display website stats
      $return_value .= '<p>'.count($myposts) . ' ' .$term. ' websites'.'</p>';

      // loop through posts
      foreach ($myposts as $mypost){
        // display website link
        $return_value .= '<p><a href="https://'.$mypost->production_domain.'">'.$mypost->production_domain .'</a></p>';
      }
    }
  }

  // Return value
  return $return_value;

}
