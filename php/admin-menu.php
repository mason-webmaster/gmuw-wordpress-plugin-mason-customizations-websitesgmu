<?php

/**
 * Summary: php file which implements the plugin WP admin menu changes
 */


/**
 * Adds Mason admin menu item to Wordpress admin menu as a top-level item
 */
add_action('admin_menu', 'gmuj_add_admin_menu_mason');

// Function to add Mason admin menu item. If this shared function does not exist already, define it now.
if (!function_exists('gmuj_add_admin_menu_mason')) {

	function gmuj_add_admin_menu_mason() {

		// Add Wordpress admin menu item for mason stuff

		// If the Mason top-level admin menu item does not exist already, add it.
		if (menu_page_url('gmuw', false) == false) {

			// Add top admin menu page
			add_menu_page(
				'Mason WordPress',
				'Mason WordPress',
				'manage_options',
				'gmuw',
				function(){
					echo "<div class='wrap'>";
					echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
					echo '<p>Please use the links at left to access Mason WordPress platform features.</p>';
					echo "</div>";
				},
				gmuj_mason_svg_icon(),
				1
			);

		}

	}

}

/**
 * Adds link to plugin settings page to Wordpress admin menu as a sub-menu item under Mason
 */
add_action('admin_menu', 'gmuw_websitesgmu_add_sublevel_menu');
function gmuw_websitesgmu_add_sublevel_menu() {
	
	// Add Wordpress admin menu item under Mason for this plugin's settings
	add_submenu_page(
		'gmuw',
		'Mason Websites',
		'Mason Websites',
		'manage_options',
		'gmuw_websitesgmu',
		'gmuw_websitesgmu_display_settings_page',
		2
	);

	// Add Wordpress admin menu item under Mason for this plugin's website site audit tool
	add_submenu_page(
		'gmuw',
		'Mason Website Audit Tool',
		'Mason Website Audit Tool',
		'manage_options',
		'gmuw_websitesgmu_website_audit',
		'gmuw_websitesgmu_display_website_audit_tool_page',
		2
	);

	// Add admin menu item under Mason for this plugin's GTM import file generator
	add_submenu_page(
		'gmuw',
		'Website Analytics Implementation Tool',
		'Analytics Implementation Tool',
		'manage_options',
		'gmuw_websitesgmu_website_analytics_implementation_tool',
		'gmuw_websitesgmu_website_analytics_implementation_tool_page',
		2
	);

}

/**
 * Hides 'comments' admin menu item
 */
add_action('admin_menu', 'gmuw_websitesgmu_remove_admin_menu_items');
function gmuw_websitesgmu_remove_admin_menu_items() {

	//adapted from: https://managewp.com/blog/wordpress-admin-sidebar-remove-unwanted-items

	//set admin menu items to remove
	$remove_menu_items = array(__('Comments'));

	global $menu;
	end($menu);
	while(prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL ? $item[0] : '', $remove_menu_items)){
			unset($menu[key($menu)]);
		}
	}
}
