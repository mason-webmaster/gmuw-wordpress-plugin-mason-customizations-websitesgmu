<?php

/**
 * Summary: php file which implements the plugin WP admin page interface
 */



/**
 * Builds hosting domain URLs
 */
function gmuj_websitesgmu_hosting_domain_url($web_host, $environment_name) {

	// Build hosting domain URL based on web host and environment name

	if ($web_host=='WPE') {
		$return_value = 'https://'.$environment_name.'.wpengine.com';
	} elseif ($web_host=='Materiell') {
		$return_value = 'https://'.$environment_name.'.materiellcloud.com';
	} else {
		$return_value = '';
	}

	// Return value
	return $return_value;

}

/**
 * Gets count of non-deleted website environments
 */
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


/**
 * Action that runs where the theme value is updated
 */
function gmuj_websitesgmu_website_action_update_theme() {

	//Set variables
	$post_id=$_GET["post_id"];
	$theme_name=$_GET["theme_name"];
	$theme_version=$_GET["theme_version"];

	// Start div for update theme process output
	echo "<div id='gmuj_websitesgmu_update_theme_output' class='updated fade'>";

	// Output debug info
	echo "<p>Updating theme...</p>".PHP_EOL;

	// Output debug info
	echo "<p>".PHP_EOL;
	echo "Post ID: ".$post_id."<br />".PHP_EOL;
	echo "Theme Name: ".$theme_name."<br />".PHP_EOL;
	echo "Theme Version: ".$theme_version."<br />".PHP_EOL;
	echo "</p>".PHP_EOL;

	//Update theme
	update_post_meta($post_id, 'wordpress_theme', $theme_name.' ('.$theme_version.')');

	//Output debug info
	echo "<p>Theme updated!</p>";

	// End div for update theme process output
	echo "</div>";

}

/**
 * Generates the plugin settings page
 */
function gmuj_websitesgmu_display_settings_page() {
	
	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	// Output basic plugin info
	//echo "<p>Mason WordPress database</p>";

	// Do we have a theme update action to perform
	if ($_GET["action"]=='update_theme') { 
		gmuj_websitesgmu_website_action_update_theme();
	}

	// Website statistics

	// Display heading
	echo '<h2>Statistics</h2>';

	//Get stats
		// Total number of websites
			$websites_all_count = gmuj_websitesgmu_total_websites();
		// Websites using official theme
			$websites_theme_count = gmuj_websitesgmu_websites_using_theme();
		// Theme percentage
			if ($websites_all_count>0) {
				$theme_percentage = round($websites_theme_count/$websites_all_count*100,2);
			} else {
				$theme_percentage=0;
			}

	// Display stats
	echo '<p>'.$websites_all_count . ' total websites.</p>';
	echo '<p>' . $websites_theme_count . ' on official theme.</p>';
	echo '<p>Official theme percentage: ' . $theme_percentage . '</p>';

	//Display all websites

	// Display heading
	echo '<h2>Websites</h2>';

	// Get posts
	$websites_all = get_posts(
		array(
			'post_type'  => 'website',
			'nopaging' => true,
			'order' => 'ASC',
			'orderby' => 'name',
		)
	);

	// Loop through theme websites
	echo '<table class="data_table">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Environment Name</th>';
	echo '<th>Post ID</th>';
	echo '<th>Web Host</th>';
	echo '<th>Hosting Domain</th>';
	echo '<th>Production Domain</th>';
	echo '<th>Theme</th>';
	echo '<th>Status</th>';
	echo '<th>Follow-Up</th>';
	echo '<th>Data Feeds</th>';
	echo '<th>Edit</th>';
	echo '<th>WP Login</th>';
	echo '<th>Web Host Admin</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach ( $websites_all as $post ) {
		// Begin table row for website environment
		echo '<tr class="';
		echo $post->followup_flag==1 ? 'followup ' : '';
		echo $post->website_status=='deleted' ? 'deleted ' : '';
		echo $post->website_status=='working' ? 'working ' : '';
		echo '">';
		// Output row data
		echo '<td>' . $post->environment_name.'</td>';
		echo '<td>' . $post->ID . '</td>';
		echo '<td>' . $post->web_host.'</td>';
		
		echo '<td><a href="'.gmuj_websitesgmu_hosting_domain_url($post->web_host,$post->environment_name).'">'.gmuj_websitesgmu_hosting_domain_url($post->web_host,$post->environment_name).'</a></td>';

		echo '<td><a href="https://'.$post->production_domain.'" target="_blank">' . $post->production_domain . '</a></td>';
		echo '<td>' . $post->wordpress_theme . '</td>';
		echo '<td>' . $post->website_status . '</td>';
		echo '<td>' . ($post->followup_flag==1 ? 'follow-up' : '').'</td>';
		
		if ($post->website_status=='deleted') {
			echo '<td>&nbsp;</td>';
		} else {
			echo '<td>' . '<a href="'.gmuj_websitesgmu_hosting_domain_url($post->web_host,$post->environment_name).'/wp-json/gmuj-sci/theme-info">theme info</a><br /><a href="'.gmuj_websitesgmu_hosting_domain_url($post->web_host,$post->environment_name).'/wp-json/gmuj-sci/most-recent-modifications">modifications</a><br /><a href="'.gmuj_websitesgmu_hosting_domain_url($post->web_host,$post->environment_name).'/wp-json/gmuj-mmi/mason-site-info">site info</a></td>';
		}
		
		echo '<td>' . '<a href="/wp-admin/post.php?post='.$post->ID.'&action=edit">Edit</a></td>';
		
		if ($post->website_status=='deleted') {
			echo '<td>&nbsp;</td>';
		} else {
			echo '<td><a href="'.gmuj_websitesgmu_hosting_domain_url($post->web_host,$post->environment_name).'/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wordpress.png'.'" /></a></td>';

		} 		

		if ($post->website_status=='deleted') {
			echo '<td>&nbsp;</td>';
		} else {
	 		echo '<td>';
			if ($post->web_host=='WPE') {
			//Store web host admin URL
			$web_host_admin_url='https://my.wpengine.com/installs/'.$post->environment_name.'/';
			// Output web host admin links
			echo '<a href="'.$web_host_admin_url.'" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> overview</a><br />';
			echo '<a href="'.$web_host_admin_url.'cache_dashboard" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> cache</a><br />';
			echo '<a href="'.$web_host_admin_url.'advanced" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> advanced</a><br />';
			}
	 		echo '</td>';
		}

		// Finish row
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';

	// Finish HTML output
	echo "</div>";

}
