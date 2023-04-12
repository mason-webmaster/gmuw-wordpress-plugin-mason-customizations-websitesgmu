<?php

/**
 * Summary: php file which implements the plugin WP admin page interface
 */


/**
 * Action that runs where the theme value is updated
 */
function gmuw_websitesgmu_website_action_update_theme() {

	//Set variables
	$post_id=$_GET["post_id"];
	$theme_name=$_GET["theme_name"];
	$theme_version=$_GET["theme_version"];

	// Start div for update theme process output
	echo "<div id='gmuw_websitesgmu_update_theme_output' class='updated fade'>";

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
function gmuw_websitesgmu_display_settings_page() {
	
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
		gmuw_websitesgmu_website_action_update_theme();
	}

	// Statistics
	//echo '<h2>Statistics</h2>';
	//echo gmuw_websitesgmu_websites_content_statistics();

	// Website list
	echo '<h2>All Website Records</h2>';
	echo gmuw_websitesgmu_custom_website_list();



}

/**
 * Generates the plugin wpe audit tool page
 */
function gmuw_websitesgmu_display_wpe_audit_tool_page() {

	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	// step 1
	echo '<h2>Get List of Installs (Environments) from WPE API</h2>';

	// input form 1
	echo '<form method="post" action="admin.php?page=gmuw_websitesgmu_wpe_audit">';

		echo '<h3>WPE API Credentials:</h3>';
		echo '<p>User: <input type="text" name="wpe_api_user" placeholder="WPE API user ID" value="'.$_POST['wpe_api_user'].'" /></p>';
		echo '<p>Pass: <input type="password" name="wpe_api_pass" placeholder="WPE API password" value="'.$_POST['wpe_api_pass'].'" /></p>';

		echo '<p><input name="submit" type="submit" value="submit" /></p>';

	echo '</form>';

	// result 1
	if ($_POST['submit']=='submit'){

		$api_user=$_POST['wpe_api_user'];
		$api_pass=$_POST['wpe_api_pass'];

		// make cURL request
		$result = gmuw_websitesgmu_wpe_api_request($api_user,$api_pass,'https://api.wpengineapi.com/v1/installs?offset=0');

		// append this batch of results to full results (there may be many pages of results)
		$full_result = $result . PHP_EOL;

		// parse resulting JSON
        $json = json_decode($result);

		// do we have a "next" element in the results?
        while ($json->next) {

			//echo '<p>We have more records!</p>';
			//echo '<p>The next request should be: '.$json->next.'</p>';

			// get next 'page' of results
			$result = gmuw_websitesgmu_wpe_api_request($api_user,$api_pass,$json->next);

			// append this batch of results to full results (there may be many pages of results)
			$full_result .= $result . PHP_EOL;

			// parse resulting JSON
			$json = json_decode($result);

        }

        // format result (pretty-print)
        $full_result=gmuw_websitesgmu_json_pretty_print($full_result);

		// display results
		echo '<h3>WPE API Output:</h3>';
		echo '<textarea style="width:100%; height:10em;">'.$full_result.'</textarea>';

		// processing api results - first pass
		$pattern = '/.*"name": "([^"]*)".*/i';
		$api_result_processed_step_1= preg_replace($pattern, 'keep\1', $full_result);

		//echo '<p>First pass:</p>';
		//echo '<textarea style="width:100%; height:10em;">'.$api_result_processed_step_1.'</textarea>';

		// processing api results - second pass
		$pattern = '/[}{ \t].*\n?/i';
		$api_result_processed_step_2= preg_replace($pattern, '', $api_result_processed_step_1);

		//echo '<p>Second pass:</p>';
		//echo '<textarea style="width:100%; height:10em;">'.$api_result_processed_step_2.'</textarea>';

		// processing api results - third pass
		$pattern = '/keep/i';
		$api_result_processed_step_3=trim(preg_replace($pattern, '', $api_result_processed_step_2));

		//echo '<p>Third pass:</p>';
		//echo '<textarea style="width:100%; height:10em;">'.$api_result_processed_step_3.'</textarea>';

		// put processed results representing list of WPE installs into array
		$wpe_installs = explode(PHP_EOL,$api_result_processed_step_3);

		// output array
		//print_r($wpe_installs);

		// get count of WPE installs from API
		$wpe_installs_count=count($wpe_installs);

		echo '<h2>Found '.$wpe_installs_count.' WPE Installs:</h2>';
		echo '<textarea style="width:100%; height:10em;">'.$api_result_processed_step_3.'</textarea>';

		echo '<h2>Scanning for Issues...</h2>';

		// look for WPE installs that are missing records in database
		echo '<h3>Looking for any environments in WPE that are not listed in our database...</h3>';

		# loop through array
		foreach ($wpe_installs as $wpe_install) {

			#echo 'Install: '.$wpe_install.'<br />';

			# get count
			$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','environment_name',$wpe_install,'web_host','wpengine'));
			#echo 'Count: '.$count.'<br />';

			# do we have an install by that name?

			if ($count==0) {
				echo 'Missing WPE install in database: '.$wpe_install.'<br />';
			}

			if ($count>1) {
				echo 'Duplicate WPE install in database: '.$wpe_install.'<br />';
			}

		}

		// look for records in database that don't have corresponding installs in WPE
		echo '<h3>Looking for any environments listed in our database that are not found in WPE...</h3>';

		$wpe_environment_records = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','','web_host','wpengine');

		foreach ($wpe_environment_records as $wpe_environment_record) {

			//echo '<p>'.$wpe_environment_record->environment_name.'</p>';
			if( !in_array( $wpe_environment_record->environment_name ,$wpe_installs ) ){
				echo 'Install listed in database doesn\'t exist in WPE: '.$wpe_environment_record->environment_name.'<br />';
			}

		}

	}

}
