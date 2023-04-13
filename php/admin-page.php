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
 * Generates the plugin general website audit tool page
 */
function gmuw_websitesgmu_display_website_audit_tool_page() {

	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	echo '<p>Gets a list of website instances (ACSF Sites or WPE installs/environments) from the selected API and compares it to the database to identify any inconsistencies.</p>';

	echo '<h2>Get Data From API</h2>';

	// input form
	echo '<form method="post" action="admin.php?page=gmuw_websitesgmu_website_audit">';

		echo '<h3>Enter API Credentials:</h3>';
		echo '<p>Username: <input type="text" name="api_user" placeholder="API user ID/name" value="'.$_POST['api_user'].'" /></p>';
		echo '<p>Password: <input type="password" name="api_pass" placeholder="API password" value="'.$_POST['api_pass'].'" /></p>';

		echo '<h3>Execute API Request:</h3>';
		echo '<p><input name="submit" type="submit" value="WPEngine" /></p>';
		echo '<p><input name="submit" type="submit" value="Acquia Cloud Site Factory" /></p>';

	echo '</form>';

	// result

	if ($_POST['submit']=='WPEngine' || $_POST['submit']=='Acquia Cloud Site Factory'){

		$api_user=$_POST['api_user'];
		$api_pass=$_POST['api_pass'];

		// make cURL request
		if ($_POST['submit']=='WPEngine') {
			$result = gmuw_websitesgmu_wpe_api_request($api_user,$api_pass,'https://api.wpengineapi.com/v1/installs?offset=0');

		}
		if ($_POST['submit']=='Acquia Cloud Site Factory') {
			$result = gmuw_websitesgmu_acsf_api_request($api_user,$api_pass,'https://www.georgemasonusf.acsitefactory.com/api/v1/sites?limit=1000');

		}

		// append this batch of results to full results (there may be many pages of results)
		$full_result = $result . PHP_EOL;

		if ($_POST['submit']=='WPEngine') {

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

   		}

        // format result (pretty-print)
        $full_result=gmuw_websitesgmu_json_pretty_print($full_result);

		// display API results

		echo '<h3>' . $_POST['submit'] . ' API Output:</h3>';

		echo '<textarea style="width:100%; height:10em;">'.$full_result.'</textarea>';
		
		// process API results

		echo '<h2>Process the API Output</h2>';

		// processing api results - first pass
		if ($_POST['submit']=='WPEngine') {
			$pattern = '/.*"name": "([^"]*)".*/i';
			$api_result_processed_step_1= preg_replace($pattern, 'keep\1', $full_result);
		}
		if ($_POST['submit']=='Acquia Cloud Site Factory') {
			$pattern = '/.*"site": "([^"]*)".*/i';
			$api_result_processed_step_1= preg_replace($pattern, 'keep\1', $full_result);
		}

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

		// put processed results representing list of instances/sites/environments/installs into array
		$api_website_instances = explode(PHP_EOL,$api_result_processed_step_3);

		// output array
		//print_r($api_website_instances);

		// get count of WPE installs from API
		$api_website_instances_count=count($api_website_instances);

		echo '<h3>Found '.$api_website_instances_count.' website instances:</h3>';
		echo '<textarea style="width:100%; height:10em;">'.$api_result_processed_step_3.'</textarea>';

		echo '<h2>Compare Instance List to Database and Identify Issues</h2>';

		// look for website instances that are missing records in database
		echo '<h3>Website instances listed in the API output that are not listed in our database...</h3>';

		# loop through array
		foreach ($api_website_instances as $api_website_instance) {

			#echo 'Install: '.$api_website_instance.'<br />';

			# get count
			if ($_POST['submit']=='WPEngine') {
				$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','environment_name',$api_website_instance,'web_host','wpengine'));
			}
			if ($_POST['submit']=='Acquia Cloud Site Factory') {
				$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','environment_name',$api_website_instance,'web_host','acquia-cloud-site-factory'));
			}

			#echo 'Count: '.$count.'<br />';

			# do we have a website instance record by that name?

			if ($count==0) {
				echo 'Missing website instance in database: '.$api_website_instance.'<br />';
			}

			if ($count>1) {
				echo 'Duplicate website instance install in database: '.$api_website_instance.'<br />';
			}

		}

		// look for records in database that don't have corresponding website instances in API
		echo '<h3>Website instances listed in our database that are not found in the API results...</h3>';

		if ($_POST['submit']=='WPEngine') {
			$website_instance_records = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','','web_host','wpengine');
		}
		if ($_POST['submit']=='Acquia Cloud Site Factory') {
			$website_instance_records = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','','web_host','acquia-cloud-site-factory');
		}

		foreach ($website_instance_records as $website_instance_record) {

			//echo '<p>'.$website_instance_record->environment_name.'</p>';
			if( !in_array( $website_instance_record->environment_name ,$api_website_instances ) ){
				echo 'Instance listed in database doesn\'t exist in WPE: '.$website_instance_record->environment_name.'<br />';
			}

		}

	}

}
