<?php
/**
 * Custom functions related to custom post type
 */


/**
 * Builds hosting domain URLs
 */
function gmuj_websitesgmu_hosting_domain($web_host, $environment_name,$include_protocol=True) {

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
function gmuj_websitesgmu_cms_login_url($web_host, $environment_name) {

	// Do we have required paramters?
	if (empty($web_host) || empty($environment_name)) {
		return $return_value;
	}

	// Initialize return value
	$return_value='';

	// Get web hosting domain
	$return_value.=gmuj_websitesgmu_hosting_domain($web_host, $environment_name);

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
function gmuj_websitesgmu_cms_login_link($web_host, $environment_name) {

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
	$return_value.='<a href="'.gmuj_websitesgmu_cms_login_url($web_host, $environment_name).'" target="_blank" title="CMS login"><img style="width:25px;" src="'.$image_path.'" /></a>';

	// Return value
	return $return_value;

}

function gmuj_websitesgmu_admin_link($web_host, $environment_name){

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


function gmuj_websitesgmu_total_websites() {

	// Get total number of websites

	// Get count of websites that are not explicitly deleted. (They could either not have a status at all or have a status of 'deleted'.)
	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT LIKE',
						    'value' => 'deleted',
				        ),
				    )
			    )
			)
		)
	);

}

/**
 * Gets count of website environments using wordpress
 */
function gmuj_websitesgmu_websites_wordpress() {

	// Get count of website environments using wordpress

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'cms',
						'field'    => 'slug',
						'terms'    => array('wordpress')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments using drupal
 */
function gmuj_websitesgmu_websites_drupal() {

	// Get count of website environments using drupal

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'cms',
						'field'    => 'slug',
						'terms'    => array('drupal')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments hosted on Materiell
 */
function gmuj_websitesgmu_websites_materiell() {

	// Get count of website environments hosted on Materiell

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'web_host',
						'field'    => 'slug',
						'terms'    => array('materiell')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments hosted on WPEngine
 */
function gmuj_websitesgmu_websites_wpengine() {

	// Get count of website environments hosted on WPEngine

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'web_host',
						'field'    => 'slug',
						'terms'    => array('wpengine')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments hosted on ACSF
 */
function gmuj_websitesgmu_websites_acsf() {

	// Get count of website environments hosted on ACSF

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'web_host',
						'field'    => 'slug',
						'terms'    => array('acquia-cloud-site-factory')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments using the official theme
 */
function gmuj_websitesgmu_websites_using_theme() {

	// Get total number of websites using the official theme

	// Get count of websites that are explicitly known to use the official theme.
	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
			        array(
					    'key'   => 'wordpress_theme',
					    'value' => 'gmuj|Mason Twenty Twenty Theme',
			            'compare' => 'REGEXP',
			        ),
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    )
			)
		)
	);

}
