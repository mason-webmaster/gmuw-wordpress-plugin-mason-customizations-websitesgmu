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

	// Output info based on mode
	if (empty($_GET["mode"])) {
		echo '<p><a href="?page=gmuw_websitesgmu&mode=stats">Statistics</a></p>';
		echo '<p><a href="edit.php?post_type=website">Websites Admin</a></p>';
		echo '<p><a href="?page=gmuw_websitesgmu&mode=sites">List Sites</a></p>';
	}

	// Website statistics
	if ($_GET["mode"]=='stats') {

		echo gmuw_websitesgmu_websites_content_statistics();

	}

	// Website statistics
	if ($_GET["mode"]=='sites') {

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
		echo '<th>Department</th>';
		echo '<th>Web Host</th>';
		echo '<th>CMS</th>';
		echo '<th>Production Domain</th>';
		echo '<th>Theme</th>';
		echo '<th>Follow-Up</th>';
		echo '<th>Data Feeds</th>';
		echo '<th>Edit</th>';
		echo '<th>Admin Login</th>';
		echo '<th>Web Host Admin</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ( $websites_all as $post ) {
			// Begin table row for website environment
			echo '<tr class="';
			echo $post->follow_up==1 ? 'follow_up ' : '';
			echo $post->deleted==1 ? 'deleted ' : '';
			echo $post->working==1 ? 'working ' : '';
			echo '">';
			// Output row data
			echo '<td>' . $post->environment_name.'</td>';
			echo '<td>' . $post->ID . '</td>';

			echo '<td>';
			foreach ( wp_get_post_terms($post->ID,'department') as $term ) {
				echo $term->name;
			}
			echo '</td>';

			echo '<td>';
			foreach ( wp_get_post_terms($post->ID,'web_host') as $term ) {
				echo $term->name;
			}
			echo '</td>';

			echo '<td>';
			foreach ( wp_get_post_terms($post->ID,'cms') as $term ) {
				echo $term->name;
			}
			echo '</td>';

			echo '<td><a href="https://'.$post->production_domain.'" target="_blank">' . $post->production_domain . '</a></td>';
			echo '<td>' . $post->wordpress_theme . '</td>';

			echo '<td>' . ($post->followup_flag==1 ? 'follow-up' : '').'</td>';

			if ($post->deleted==1) {
				echo '<td>&nbsp;</td>';
			} else {
				echo '<td>' . '<a href="'.gmuw_websitesgmu_hosting_domain($post->web_host,$post->environment_name).'/wp-json/gmuj-sci/theme-info">theme info</a><br /><a href="'.gmuw_websitesgmu_hosting_domain($post->web_host,$post->environment_name).'/wp-json/gmuj-sci/most-recent-modifications">modifications</a><br /><a href="'.gmuw_websitesgmu_hosting_domain($post->web_host,$post->environment_name).'/wp-json/gmuj-mmi/mason-site-info">site info</a></td>';
			}

			echo '<td>' . '<a href="/wp-admin/post.php?post='.$post->ID.'&action=edit">Edit</a></td>';

			if ($post->deleted==1) {
				echo '<td>&nbsp;</td>';
			} else {
				echo '<td>'.gmuw_websitesgmu_cms_login_link(wp_get_post_terms($post->ID,'web_host')[0]->slug,$post->environment_name).'</td>';
			}

			if ($post->deleted==1) {
				echo '<td>&nbsp;</td>';
			} else {
				echo '<td>'.gmuw_websitesgmu_admin_link(wp_get_post_terms($post->ID,'web_host')[0]->slug,$post->environment_name).'</td>';
			}

			// Finish row
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';

		// Finish HTML output
		echo "</div>";

	}

}
