<?php require('header.php'); ?>

<?php $post = get_post()?>

<p></p>

<h3><!--Website: -->
	<?php
	if (!empty($post->production_domain)) { 
		echo $post->production_domain; 
	} else {
		echo $post->post_title;
	}
	?>

</h3>

<?php

// Is this record deleted?
if ($post->deleted==1) {

	// Display deleted message
	echo '<p>This website has been deleted.</p>';

} else {

	// Display website info

	echo '<h4>Website Links</h4>';

	// Production domain link
	if (!empty($post->production_domain)) {
		echo '<p>Live website link: <a href="https://'.$post->production_domain.'" target="_blank">'.$post->production_domain.'</a></p>';
	}

	// Admin links
	if (is_user_logged_in()) {


		// Hosting domain link
		if (!empty($post->environment_name)) {
			$hosting_domain=gmuw_websitesgmu_website_hosting_domain($post->ID,false);
			echo '<p>';
			echo 'Web host link: <a href="https://'.$hosting_domain.'" target="_blank">https://'.$hosting_domain.'</a>';
			echo '</p>';
		}

		// CMS login link
		echo '<p>';
		echo 'CMS login: '.gmuw_websitesgmu_website_cms_login_link($post->ID);
		echo '</p>';

		echo '<h4>Web Hosting Links</h4>';

		// Web host admin link
		echo '<p>';
		echo 'Web host admin: '.gmuw_websitesgmu_website_web_host_admin_link($post->ID);
		echo '</p>';

		// Sucuri firewall link
		if (!empty($post->production_domain)) {
			echo '<p>';
			echo 'Sucuri firewall: '.gmuw_websitesgmu_sucuri_link($post->ID,'firewall');
			echo '</p>';
		}

		// Sucuri monitor link
		if (!empty($post->production_domain)) {
			echo '<p>';
			echo 'Sucuri monitor: '.gmuw_websitesgmu_sucuri_link($post->ID,'monitor');
			echo '</p>';
		}

	}

	echo '<h4>Web Analytics</h4>';

	if ($post->doesnt_need_analytics==1) {

		echo '<p>This website is marked as not needing web analytics implementation.</p>';

	} else {

		if (empty($post->website_gtm_container_post_id) || empty($post->website_ga_property_post_id) || empty($post->website_ga_property_rollup_post_id)) {

			if (is_user_logged_in()) {
		    	echo '<p><a href="https://websitesgmu.wpengine.com/wp-admin/admin.php?page=gmuw_websitesgmu_website_analytics_implementation_tool&ga4wf-website-post-id='.$post->ID.'" target="_blank">Launch the analytics implementation workflow for this website</a></p>';
			}

		}

		echo '<h5>Google Tag Manager (GTM)</h5>';

		if (!empty($post->website_gtm_container_post_id)) {

			echo '<p>Information about the GTM container associated with this website is shown below:</p>';

			echo '<table>';

			echo '<tr>';
			echo '<th>Container Name</th>';
			echo '<td>'.get_the_title(get_post_meta($post->ID, 'website_gtm_container_post_id', true)).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Container ID</th>';
			echo '<td>'.get_post_meta($post->website_gtm_container_post_id, 'gtm_container_id_public', true).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Internal Container ID</th>';
			echo '<td>'.get_post_meta($post->website_gtm_container_post_id, 'gtm_container_id', true).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Link</th>';
			echo '<td><a href="https://tagmanager.google.com/#/container/accounts/'.get_post_meta(get_post_meta($post->website_gtm_container_post_id, 'gtm_container_account_post_id', true), 'gtm_account_id', true).'/containers/'.get_post_meta($post->website_gtm_container_post_id, 'gtm_container_id', true).'/" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_tag_manager.png'.'" /></a></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Notes</th>';
			echo '<td>';
			echo get_post_meta(get_post_meta($post->ID, 'website_gtm_container_post_id', true),'gtm_container_non_standard',true)==1 ? '<p>This container is not Mason-standard.</p>' : '';
			echo '</td>';
			echo '</tr>';

			echo '</table>';

		} else {
			echo '<p>Website does not have an associated GTM container.</p>';
		}

		echo '<h5>Google Analytics (GA)</h5>';

		if (!empty($post->website_ga_property_post_id)) {

			echo '<p>Information about the GA property associated with this website is shown below:</p>';

			echo '<table>';

			echo '<tr>';
			echo '<th>Property Name</th>';
			echo '<td>'.get_the_title(get_post_meta($post->ID, 'website_ga_property_post_id', true)).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Property ID</th>';
			echo '<td>'.get_post_meta($post->website_ga_property_post_id, 'ga_property_id', true).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Measurement ID</th>';
			echo '<td>'.get_post_meta($post->website_ga_property_post_id, 'ga_measurement_id', true).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Link</th>';
			echo '<td><a href="https://analytics.google.com/analytics/web/#/a'.get_post_meta(get_post_meta($post->website_ga_property_post_id, 'ga_property_account_post_id', true), 'ga_account_id', true).'p'.get_post_meta($post->website_ga_property_post_id, 'ga_property_id', true).'/admin" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a></td>';
			echo '</tr>';

			echo '</table>';

			if (!empty($post->website_ga_property_rollup_post_id)) {
				echo '<p>This website also sends analytics data to a roll-up property:</p>';

				echo '<table>';

				echo '<tr>';
				echo '<th>Property Name</th>';
				echo '<td>'.get_the_title(get_post_meta($post->ID, 'website_ga_property_rollup_post_id', true)).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<th>Property ID</th>';
				echo '<td>'.get_post_meta($post->website_ga_property_rollup_post_id, 'ga_property_id', true).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<th>Measurement ID</th>';
				echo '<td>'.get_post_meta($post->website_ga_property_rollup_post_id, 'ga_measurement_id', true).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<th>Link</th>';
				echo '<td><a href="https://analytics.google.com/analytics/web/#/a'.get_post_meta(get_post_meta($post->website_ga_property_rollup_post_id, 'ga_property_account_post_id', true), 'ga_account_id', true).'p'.get_post_meta($post->website_ga_property_rollup_post_id, 'ga_property_id', true).'/admin" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-google_analytics.png'.'" /></a></td>';
				echo '</tr>';

				echo '</table>';

			}

		} else {
			echo '<p>This website does not have an associated GA property.</p>';
		}

	}
	
	echo '<h4>Google Search Console</h4>';

	// Google search console
	if (!empty($post->production_domain)) {
		echo '<table>';
		echo '<tr>';
		echo '<th>Link</th>';
		echo '<td>'.gmuw_websitesgmu_google_search_console_link($post->ID).'</td>';
		echo '</tr>';
		echo '</table>';
	} else {
		echo '<p>Site does not have a production domain.</p>';
	}

	// Dubbot links
	if (is_user_logged_in()) {
		echo '<h4>DubBot</h4>';
		if (!empty($post->dubbot_site_id)) {
			echo '<table>';
			echo '<tr>';
			echo '<th>Link</th>';
			echo '<td>'.gmuw_websitesgmu_dubbot_link($post->ID).'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Admin Link</th>';
			echo '<td>'.gmuw_websitesgmu_dubbot_link($post->ID,'admin').'</td>';
			echo '</tr>';
			echo '</table>';
		} else {
			echo '<p>Site does not have a DubBot ID.</p>';		
		}
	}

	// Website Information
	if (is_user_logged_in()) {
		echo '<h4>Website Information</h4>';

		echo '<table>';

		// Mason Meta Information plugin API links
		echo '<tr><td>';
		echo '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-mmi/mason-site-info" target="_blank">Basic Site Information</a>';
		echo '</td></tr>';

		// Mason Site Check In plugin API links
		echo '<tr><td>';
		echo '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-sci/theme-info" target="_blank">Current Theme Info</a>';
		echo '</td></tr>';
		echo '<tr><td>';
		echo '<a href="'.gmuw_websitesgmu_website_hosting_domain($post->ID).'/wp-json/gmuj-sci/most-recent-modifications" target="_blank">Most Recent Modifications</a>';
		echo '</td></tr>';

		echo '</table>';
	}

	// Contact info
	if (is_user_logged_in()) {

		echo '<h4>Contact Information</h4>';
		echo '<p>';
		if (!empty($post->contact_person_email_primary) || !empty($post->contact_person_email_secondary)) {
			echo 'Primary: '.$post->contact_person_email_primary.'<br />';
			echo 'Secondary: '.$post->contact_person_email_secondary.'<br />';
		} else {
			echo 'There is no contact information available for this website.';
		}
		echo '</p>';

	}

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
