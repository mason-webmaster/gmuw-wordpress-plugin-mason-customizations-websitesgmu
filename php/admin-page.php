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
	if (isset($_GET["action"]) && $_GET["action"]=='update_theme') {
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

/**
 * Generates the plugin website analytics implementation tool page
 */
function gmuw_websitesgmu_website_analytics_implementation_tool_page() {

	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

	echo '<div style="display:flex">';

	echo '<div style="margin-right:2em;">';

	echo '<h2>GA/GTM Setup</h2>';

	echo '<form method="post" action="admin.php?page=gmuw_websitesgmu_website_analytics_implementation_tool">';

	echo '<table>';

	echo '<tr>';
	echo '<td>website post id</td>';
	echo '<td><input class="ga4wf" id="ga4wf-website-post-id" name="ga4wf-website-post-id" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-website-post-id"].'" placeholder="website post id" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>domain name</td>';
	echo '<td><input class="ga4wf" id="ga4wf-domain-name" name="ga4wf-domain-name" type="text" style="width:20em;" value="'.get_post_meta ($_REQUEST["ga4wf-website-post-id"], 'production_domain', true).'" placeholder="domain name" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Open site to check for existing analytics</td>';
	echo '<td><p><a href="https://'.get_post_meta ($_REQUEST["ga4wf-website-post-id"], 'production_domain', true).'">'.get_post_meta ($_REQUEST["ga4wf-website-post-id"], 'production_domain', true).'</a></p></td></tr>';

	echo '<tr>';
	echo '<td>Create GA property record?</td>';
	echo '<td><input type="checkbox" name="ga4wf-ga-property-record-create" value="true"';
	if ($_REQUEST["ga4wf-ga-property-record-create"]=='true') { echo ' checked'; }
	echo '></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GA account</td>';
	echo '<td>';

	echo  '<select id="ga4wf-ga-account-post-id" name="ga4wf-ga-account-post-id">';
	// Loop through posts
	$ga_accounts = get_posts(
		array(
			'post_type'  => 'ga_account',
			'post_status' => 'publish',
			'nopaging' => true,
			'order' => 'ASC',
			'orderby' => 'name',
		)
	);
	foreach ( $ga_accounts as $post ) {
		echo '<option ';
		if ($_REQUEST["ga4wf-ga-account-post-id"]==$post->ID) { echo 'selected '; }
		echo 'value="'.$post->ID.'">' . $post->post_title . '</option>';
	}
	echo  '</select>';

	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GA4 property name</td>';
	echo '<td><input class="ga4wf" id="ga4wf-property-name" name="ga4wf-property-name" type="text" style="width:20em;" value="" placeholder="GA4 property name" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Create new property</td>';
	echo '<td>';
	echo '<p><a href="https://analytics.google.com/analytics/web/#/a28237179p362853265/admin" target="_blank"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a></p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GA4 data stream name</td>';
	echo '<td><input class="ga4wf" id="ga4wf-data-stream-name" name="ga4wf-data-stream-name" type="text" style="width:20em;" value="" placeholder="GA4 data stream name" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GA4 property ID</td>';
	echo '<td><input class="ga4wf" id="ga4wf-ga-property-id" name="ga4wf-ga-property-id" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-ga-property-id"].'" placeholder="GA4 property id" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GA4 measurement ID</td>';
	echo '<td><input class="ga4wf" id="ga4wf-ga-measurement-id" name="ga4wf-ga-measurement-id" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-ga-measurement-id"].'" placeholder="GA4 measurement ID" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GA4 measurement ID (roll-up)</td>';
	echo '<td><input class="ga4wf" id="ga4wf-ga-measurement-id-rollup2" name="ga4wf-ga-measurement-id-rollup2" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-ga-measurement-id-rollup2"].'" placeholder="GA4 measurement ID (roll-up)" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>UA ID</td>';
	echo '<td><input class="ga4wf" id="ga4wf-ga-ua-id" name="ga4wf-ga-ua-id" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-ga-ua-id"].'" placeholder="UA ID" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>UA ID (roll-up)</td>';
	echo '<td><input class="ga4wf" id="ga4wf-ga-ua-id-rollup2" name="ga4wf-ga-ua-id-rollup2" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-ga-ua-id-rollup2"].'" placeholder="UA ID (roll-up)" /></td>';
	echo '</tr>';

	echo '<tr><td colspan="2"><h3>Google Tag Manager</h3></td></tr>';

	echo '<tr>';
	echo '<td>Create GTM container record?</td>';
	echo '<td><input type="checkbox" name="ga4wf-gtm-container-record-create" value="true"';
	if ($_REQUEST["ga4wf-gtm-container-record-create"]=='true') { echo ' checked'; }
	echo '></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GTM account</td>';
	echo '<td>';

	echo  '<select id="ga4wf-gtm-account-post-id" name="ga4wf-gtm-account-post-id">';
	// Loop through posts
	$gtm_accounts = get_posts(
		array(
			'post_type'  => 'gtm_account',
			'post_status' => 'publish',
			'nopaging' => true,
			'order' => 'ASC',
			'orderby' => 'name',
		)
	);
	foreach ( $gtm_accounts as $post ) {
		echo '<option ';
		if ($_REQUEST["ga4wf-gtm-account-post-id"]==$post->ID) { echo 'selected '; }
		echo 'value="'.$post->ID.'">' . $post->post_title . '</option>';
	}
	echo  '</select>';

	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GTM container name</td>';
	echo '<td><input class="ga4wf" id="ga4wf-gtm-container-name" name="ga4wf-gtm-container-name" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-gtm-container-name"].'" placeholder="GTM container name" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Create GTM container</td>';
	echo '<td><p><a href="https://tagmanager.google.com/" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_tag_manager.png'.'" /></a></p></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GTM container ID</td>';
	echo '<td><input class="ga4wf" id="ga4wf-gtm-container-id" name="ga4wf-gtm-container-id" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-gtm-container-id"].'" placeholder="GTM container ID" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>GTM container ID (public)</td>';
	echo '<td><input class="ga4wf" id="ga4wf-gtm-container-id-public" name="ga4wf-gtm-container-id-public" type="text" style="width:20em;" value="'.$_REQUEST["ga4wf-gtm-container-id-public"].'" placeholder="GTM container ID (public)" /></td>';
	echo '</tr>';

	if ($_POST['submit']=='submit') {
		echo '<tr>';
		echo '<td style="vertical-align: top;">GTM container import file</td>';

		echo '<td>';
		echo '<textarea class="ga4wf" id="ga4wf-gtm-container-import" name="ga4wf-gtm-container-import" style="width:30em; height:10em;">';

		$gtm_import_content='';
		$fh = fopen(plugin_dir_url( __DIR__ ).'assets/json/gtm-import.json','r');
		while ($line = fgets($fh)) {
		  $gtm_import_content.=$line;
		}
		fclose($fh);

		//replace content
		$gtm_import_content=str_replace("[[[domainname]]]",$_POST['ga4wf-domain-name'],$gtm_import_content);
		$gtm_import_content=str_replace("[[[measurementidlocal]]]",$_POST['ga4wf-ga-measurement-id'],$gtm_import_content);
		if (empty($_REQUEST["ga4wf-ga-measurement-id-rollup2"])) {
			$gtm_import_content=str_replace("[[[measurementidrollup2]]]",'G-XXXXXXXXXX',$gtm_import_content);
		} else {
			$gtm_import_content=str_replace("[[[measurementidrollup2]]]",$_POST['ga4wf-ga-measurement-id-rollup2'],$gtm_import_content);
		}

		if (empty($_REQUEST["ga4wf-ga-ua-id"])) {
			$gtm_import_content=str_replace("[[[uaidlocal]]]",'UA-XXXXXXX-X',$gtm_import_content);
		} else {
			$gtm_import_content=str_replace("[[[uaidlocal]]]",$_POST['ga4wf-ga-ua-id'],$gtm_import_content);
		}
		if (empty($_REQUEST["ga4wf-ga-ua-id-rollup2"])) {
			$gtm_import_content=str_replace("[[[uaidrollup2]]]",'G-XXXXXXXXXX',$gtm_import_content);
		} else {
			$gtm_import_content=str_replace("[[[uaidrollup2]]]",$_POST['ga4wf-ga-ua-id-rollup2'],$gtm_import_content);
		}

		echo $gtm_import_content;

		echo '</textarea>';

		echo '<p><button type="button" value="save" id="ga4wf-gtm-import-save">Save</button></p>';

		echo '</td>';
		echo '</tr>';
	}

	echo '<tr>';
	echo '<td>GTM container version name</td>';
	echo '<td><input class="ga4wf" id="ga4wf-container-version-name" name="ga4wf-container-version-name" type="text" style="width:20em;" value="Mason standard container 3.4.1" placeholder="GTM container version name" /></td>';
	echo '</tr>';

	echo '</table>';

	if ($_POST['submit']=='submit') {

		if ($_POST['ga4wf-ga-property-record-create'] == 'true') {

			//echo '<p>ga property name: '.$_POST['ga4wf-property-name'].'</p>';
			//echo '<p>ga account post: '.$_POST['ga4wf-ga-account-post-id'].'</p>';
			//echo '<p>ga property id: '.$_POST['ga4wf-ga-property-id'].'</p>';
			//echo '<p>ga measurement id: '.$_POST['ga4wf-ga-measurement-id'].'</p>';

			// Create post object
			$post_arr = array(
				'post_title'   => $_POST['ga4wf-property-name'],
				'post_type'   => 'ga_property',
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'tax_input'    => array(
					'hierarchical_tax'     => array( 13 ),
				),
				'meta_input'   => array(
					'ga_property_id' => $_POST['ga4wf-ga-property-id'],
					'_ga_property_id' => 'field_64094fd7ad81c',
					'ga_measurement_id' => $_POST['ga4wf-ga-measurement-id'],
					'_ga_measurement_id' => 'field_6409522762b84',
					'ga_property_account_post_id' => $_POST['ga4wf-ga-account-post-id'],
					'_ga_property_account_post_id' => 'field_640a92b2646fe',
				),
			);

			// Insert the post into the database
			$new_ga_property_post_id = wp_insert_post( $post_arr );

			//link the website post to the property
			update_post_meta( $_REQUEST["ga4wf-website-post-id"], 'website_ga_property_post_id', $new_ga_property_post_id );
			update_post_meta( $_REQUEST["ga4wf-website-post-id"], '_website_ga_property_post_id', 'field_640d375cd984d' );

			echo '<p>GA property record created and linked to website!</p>';

		}

		if ($_POST['ga4wf-gtm-container-record-create'] == 'true') {

			//echo '<p>gtm container name: '.$_POST['ga4wf-domain-name'].'</p>';
			//echo '<p>gtm account post: '.$_POST['ga4wf-gtm-account-post-id'].'</p>';
			//echo '<p>ga container id: '.$_POST['ga4wf-gtm-container-id'].'</p>';
			//echo '<p>ga container id (public): '.$_POST['ga4wf-gtm-container-id-public'].'</p>';

			// Create post object
			$post_arr = array(
				'post_title'   => $_POST['ga4wf-domain-name'],
				'post_type'   => 'gtm_container',
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'meta_input'   => array(
					'gtm_container_id' => $_POST['ga4wf-gtm-container-id'],
					'_gtm_container_id' => 'field_64095b002bfeb',
					'gtm_container_account_post_id' => $_POST['ga4wf-gtm-account-post-id'],
					'_gtm_container_account_post_id' => 'field_640aa575a9b1c',
					'gtm_container_id_public' => $_POST['ga4wf-gtm-container-id-public'],
					'_gtm_container_id_public' => 'field_6478d4899eafe',
				),
			);

			// Insert the post into the database
			$new_gtm_container_post_id = wp_insert_post( $post_arr );

			//link the website post to the property
			update_post_meta( $_REQUEST["ga4wf-website-post-id"], 'website_gtm_container_post_id', $new_gtm_container_post_id );
			update_post_meta( $_REQUEST["ga4wf-website-post-id"], '_website_gtm_container_post_id', 'field_640d3a8dd4880' );

			echo '<p>GTM container record created and linked to website!</p>';

		}

	}

	echo '<p><input type="submit" name="submit" value="submit" /></p>';

	echo '</form>';

	echo '</div>';

	echo '<div>';

	echo '<h2>WP-CLI Commands</h2>';

	echo '<table>';

	echo '<tr>';
	echo '<td>List plugins</td>';
	echo '<td><input class="ga4wf" type="text" style="width:30em;" value="wp plugin list" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Access web host admin advanced tab</td>';
	echo '<td>';
	if (wp_get_post_terms($_REQUEST["ga4wf-website-post-id"],'web_host')[0]->slug=='wpengine') {
		echo '<p>'.'<a href="'.gmuw_websitesgmu_website_web_host_admin_url($_REQUEST["ga4wf-website-post-id"]).'advanced/" target="_blank" title="web host admin"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /></a>'.'</p>';
	}
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Deactivate other WP analytics plugins</td>';
	echo '<td><input class="ga4wf" type="text" style="width:40em;" value="wp plugin deactivate ga-google-analytics google-analytics-dashboard google-analytics-dashboard-for-wp google-analyticator google-analytics-for-wordpress" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Install and activate GTM4WP plugin</td>';
	echo '<td><input class="ga4wf" type="text" style="width:30em;" value="wp plugin install duracelltomi-google-tag-manager --activate" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Check database prefix</td>';
	echo '<td><input class="ga4wf" type="text" style="width:30em;" value="wp db prefix" /></td>';
	echo '</tr>';

	//get sql content
	$gtm_sql_content='';
	$fh = fopen(plugin_dir_url( __DIR__ ).'assets/txt/wp-gtm4wp-options-value.txt','r');
	while ($line = fgets($fh)) {
	  $gtm_sql_content.=$line;
	}
	fclose($fh);

	//replace content
	$gtm_sql_content=str_replace("[[[gtmid]]]",$_POST['ga4wf-gtm-container-id-public'],$gtm_sql_content);

	echo '<tr>';
	echo '<td>Set GTM4WP plugin settings (insert)</td>';
	echo '<td>';
	echo '<textarea class="ga4wf" id="ga4wf-gtm-plugin-sql-script" name="ga4wf-gtm-plugin-sql-script" style="width:30em; height:5em;">';

	echo 'wp db query "INSERT INTO wp_options (option_name, option_value, autoload) VALUES (&#39;gtm4wp-options&#39;, &#39;' . $gtm_sql_content . '&#39;, &#39;yes&#39;);"';

	echo '</textarea>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Set GTM4WP plugin settings (update)</td>';
	echo '<td>';
	echo '<textarea class="ga4wf" id="ga4wf-gtm-plugin-sql-script" name="ga4wf-gtm-plugin-sql-script" style="width:30em; height:5em;">';

	echo 'wp db query "UPDATE wp_options SET option_value=&#39;' . $gtm_sql_content . '&#39; WHERE option_name=&#39;gtm4wp-options&#39;;"';

	echo '</textarea>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Clear web host cache</td>';
	echo '<td>';
	if (wp_get_post_terms($_REQUEST["ga4wf-website-post-id"],'web_host')[0]->slug=='wpengine') {
		echo '<p>'.'<a href="'.gmuw_websitesgmu_website_web_host_admin_url($_REQUEST["ga4wf-website-post-id"]).'cache_dashboard/" target="_blank" title="web host admin"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /></a>'.'</p>';
	}
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Open analytics real-time view:</td>';
	echo '<td>';
	echo '<p>'.'<a id="ga4wf-analytics-real-time" href="https://analytics.google.com/analytics/web/#/p'.$_POST['ga4wf-ga-property-id'].'/realtime/overview" target="_blank" title="analytics real-time view"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a>'.'</p>';

	echo '</td>';
	echo '</tr>';

	echo '</table>';

	echo '</div>';

	echo '</div>';

}
