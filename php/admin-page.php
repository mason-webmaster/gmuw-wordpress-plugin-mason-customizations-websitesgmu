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

	// Output basic plugin info
	//echo "<p>Mason WordPress database</p>";

	if ($_POST['submit']=='submit'){

		$initial_content=str_replace('\"','"',$_POST['wpe_api_output']);

	}

	# input form
	echo '<h2>Output from WPE API:</h2>';
	echo '<form method="post" action="admin.php?page=gmuw_websitesgmu_wpe_audit">';
	echo '<textarea name="wpe_api_output" style="width:100%; height:10em;">'.$initial_content.'</textarea>';
	echo '<br />';
	echo '<input name="submit" type="submit" value="submit" />';
	echo '</form>';

	if ($_POST['submit']=='submit'){

		#echo '<p>We have a form submission!</p>';
		#echo '<p>The content to process is:</p>';
		#echo '<textarea style="width:100%; height:10em;">'.$initial_content.'</textarea>';

		# first pass
		$pattern = '/.*"name": "([^"]*)".*/i';
		$content_processed_1= preg_replace($pattern, 'keep\1', $initial_content);

		#echo '<p>First pass:</p>';
		#echo '<textarea style="width:100%; height:10em;">'.$content_processed_1.'</textarea>';

		# second pass
		$pattern = '/[}{ \t].*\n?/i';
		$content_processed_2= preg_replace($pattern, '', $content_processed_1);

		#echo '<p>Second pass:</p>';
		#echo '<textarea style="width:100%; height:10em;">'.$content_processed_2.'</textarea>';

		# third pass
		$pattern = '/keep/i';
		$content_processed_3=trim(preg_replace($pattern, '', $content_processed_2));

		#echo '<p>Third pass:</p>';
		#echo '<textarea style="width:100%; height:10em;">'.$content_processed_3.'</textarea>';

		echo '<h2>Found WPE Installs:</h2>';
		echo '<textarea style="width:100%; height:10em;">'.$content_processed_3.'</textarea>';

		# put into array
		$wpe_installs = explode(PHP_EOL,$content_processed_3);

		# output array
		#print_r($wpe_installs);

		echo '<h2>Scanning for Issues:</h2>';

		# loop through array
		foreach ($wpe_installs as $wpe_install) {

			#echo 'Install: '.$wpe_install.'<br />';

			# get count
			$count=count(gmuw_websitesgmu_get_custom_posts('website','not-deleted','environment_name',$wpe_install,'web_host','wpengine'));
			#echo 'Count: '.$count.'<br />';

			# do we have an install by that name?

			if ($count==0) {
				echo 'Missing WPE install: '.$wpe_install.'<br />';
			}

			if ($count>1) {
				echo 'Duplicate WPE install: '.$wpe_install.'<br />';
			}

		}

	}

}
