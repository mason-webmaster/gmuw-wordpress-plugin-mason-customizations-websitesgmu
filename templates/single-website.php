<?php require('header.php'); ?>

<?php $post = get_post()?>

<p></p>

<h3>Website: <?php echo $post->post_title ?></h3>

<?php

// Is this record deleted?
if ($post->deleted==1) {

	// Display deleted message
	echo '<p>This website has been deleted.</p>';

} else {

	// Display website info

	echo '<h4>Links</h4>';

	// Production domain link
	if (!empty($post->production_domain)) {
		echo '<p>Live website link: <a href="https://'.$post->production_domain.'" target="_blank">'.$post->production_domain.'</a></p>';
	}

	// Admin links
	if (is_user_logged_in()) {

		echo '<table>';

		// Hosting domain link
		if (!empty($post->environment_name)) {
			$hosting_domain=gmuw_websitesgmu_website_hosting_domain($post->ID,false);
			echo '<tr><td>';
			echo 'Web host link</td><td><a href="https://'.$hosting_domain.'" target="_blank">https://'.$hosting_domain.'</a>';
			echo '</td></tr>';
		}

		// CMS login link
		echo '<tr><td>';
		echo 'CMS login link</td><td>'.gmuw_websitesgmu_website_cms_login_link($post->ID);
		echo '</td></tr>';

		// Web host admin link
		echo '<tr><td>';
		echo 'Web host admin link</td><td>'.gmuw_websitesgmu_website_web_host_admin_link($post->ID);
		echo '</td></tr>';

		// Sucuri firewall link
		if (!empty($post->production_domain)) {
			echo '<tr><td>';
			echo 'Sucuri firewall link</td><td>'.gmuw_websitesgmu_sucuri_link($post->ID,'firewall');
			echo '</td></tr>';
		}

		// Sucuri monitor link
		if (!empty($post->production_domain)) {
			echo '<tr><td>';
			echo 'Sucuri monitor link</td><td>'.gmuw_websitesgmu_sucuri_link($post->ID,'monitor');
			echo '</td></tr>';
		}

		// Dubbot link
		if (!empty($post->dubbot_site_id)) {
			echo '<tr><td>';
			echo 'Dubbot link</td><td>'.gmuw_websitesgmu_dubbot_link($post->ID);
			echo '</td></tr>';
		}

		// Dubbot admin link
		if (!empty($post->dubbot_site_id)) {
			echo '<tr><td>';
			echo 'Dubbot admin link</td><td>'.gmuw_websitesgmu_dubbot_link($post->ID,'admin');
			echo '</td></tr>';
		}

		echo '</table>';

	}

	echo '<h4>Web Analytics Information</h4>';

	echo '<table>';

	// GTM container
	if (!empty($post->website_gtm_container_post_id)) {
		echo '<tr>';
		echo '<td>Associated GTM Container</td>';
		echo '<td><!--<a href="'.get_post_permalink(get_post_meta($post->ID, 'website_gtm_container_post_id', true)).'">-->'.get_the_title(get_post_meta($post->ID, 'website_gtm_container_post_id', true)).' ('.get_post_meta($post->website_gtm_container_post_id, 'gtm_container_id', true).')<!--</a>--></td>';
		echo '<td><a href="https://tagmanager.google.com/#/container/accounts/'.get_post_meta(get_post_meta($post->website_gtm_container_post_id, 'gtm_container_account_post_id', true), 'gtm_account_id', true).'/containers/'.get_post_meta($post->website_gtm_container_post_id, 'gtm_container_id', true).'/" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_tag_manager.png'.'" /></a></td>';
		echo '</tr>';
	}

	// GA property
	if (!empty($post->website_ga_property_post_id)) {
		echo '<tr>';
		echo '<td>Associated GA4 Property</td>';
		echo '<td><!--<a href="'.get_post_permalink(get_post_meta($post->ID, 'website_ga_property_post_id', true)).'">-->'.get_the_title(get_post_meta($post->ID, 'website_ga_property_post_id', true)).' ('.get_post_meta($post->website_ga_property_post_id, 'ga_property_id', true).')<!--</a>--></td>';
		echo '<td><a href="https://analytics.google.com/analytics/web/#/a'.get_post_meta(get_post_meta($post->website_ga_property_post_id, 'ga_property_account_post_id', true), 'ga_account_id', true).'p'.get_post_meta($post->website_ga_property_post_id, 'ga_property_id', true).'/admin" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a></td>';
		echo '</tr>';
	}

	// Google search console
	if (!empty($post->production_domain)) {
		echo '<tr><td>';
		echo 'Google Search Console</td><td>'.gmuw_websitesgmu_google_search_console_link($post->ID);
		echo '</td></tr>';
	}

	echo '</table>';

	echo '<h4>Website Information</h4>';

	echo '<table>';

	// Mason Meta Information plugin API links
	echo '<tr><td>';
	echo '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-mmi/mason-site-info" target="_blank">Basic Site Information</a>';
	echo '</td></tr>';

	// Mason Site Check In plugin API links
	if (is_user_logged_in()) {
		echo '<tr><td>';
		echo '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-sci/theme-info" target="_blank">Current Theme Info</a>';
		echo '</td></tr>';
		echo '<tr><td>';
		echo '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-sci/most-recent-modifications" target="_blank">Most Recent Modifications</a>';
		echo '</td></tr>';
	}

	echo '</table>';

	// Private notes
	if (is_user_logged_in()) {

		// Private notes
		echo '<h4>Website Notes</h4>';
		echo '<pre>';
		if (!empty($post->notes_private)) {
			echo $post->notes_private;
		}
		echo '</pre>';

	}

}

?>

<?php require('footer.php'); ?>
