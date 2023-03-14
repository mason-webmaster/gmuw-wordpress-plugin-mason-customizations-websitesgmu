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
