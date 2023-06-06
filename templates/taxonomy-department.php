<?php require('header.php'); ?>

<p></p>

<h3>Google Analytics Account(s)</h3>

<?php

// setup display
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<td>Account Name</td>';
echo '<td>Account ID</td>';
echo '<td>Admin Link</td>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Get posts
$ga_accounts = gmuw_websitesgmu_get_custom_posts('ga_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $ga_accounts as $ga_account ) {
	echo '<tr>';
	echo '<td>';
	//echo '<a href="'.gmuw_websitesgmu_ga_account_admin_link_url($ga_account->ID).'">';
	echo $ga_account->post_title;
	//echo '</a>';
	echo '</td>';
	echo '<td>';
	echo $ga_account->ga_account_id;
	echo '</td>';
	echo '<td>';
	echo gmuw_websitesgmu_ga_account_admin_link($ga_account->ID);
	echo '</td>';
	echo '</tr>';

}

// finish display
echo '</tbody>';
echo '</table>';

?>

<h3>Google Tag Manager Account(s)</h3>

<?php

// setup display
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<td>Account Name</td>';
echo '<td>Account ID</td>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Get posts
$gtm_accounts = gmuw_websitesgmu_get_custom_posts('gtm_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $gtm_accounts as $gtm_account ) {
	echo '<tr>';
	echo '<td>';
	//echo '<a href="'.get_permalink($gtm_account->ID).'">';
	echo $gtm_account->post_title;
	//echo '</a>';
	echo '</td>';
	echo '<td>';
	echo $gtm_account->gtm_account_id;
	echo '</td>';
	echo '</tr>';
}

// finish display
echo '</tbody>';
echo '</table>';

?>

<h3>Live/Production Website(s)</h3>

<?php

// setup display
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<td colspan="2"></td>';
echo '<td colspan="3">Google Tag Manager (GTM)</td>';
echo '<td colspan="3">Google Analytics (GA)</td>';
echo '<td colspan="2"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Domain</td>';
echo '<td>Instance/Environment Name</td>';
echo '<td>Container Name</td>';
echo '<td>ID</td>';
echo '<td>Link</td>';
echo '<td>GA4 Property Name</td>';
echo '<td>ID</td>';
echo '<td>Link</td>';
echo '<td>Search Console</td>';
echo '<td>More details</td>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Get posts
$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $websites as $website ) {
	echo '<tr>';

	echo '<td>';
	echo '<a href="https://'.$website->production_domain.'/" target="_blank">';
	echo $website->production_domain;
	echo '</a>';
	echo '</td>';

	echo '<td>';
	echo $website->environment_name;
	echo '</td>';

	if (!empty(get_post_meta($website->ID, 'website_gtm_container_post_id', true))) {
		echo '<td>';
        echo get_the_title(get_post_meta($website->ID, 'website_gtm_container_post_id', true));
        echo '</td>';
		echo '<td>';
        echo get_post_meta(get_post_meta($website->ID, 'website_gtm_container_post_id', true),'gtm_container_id_public',true);
        echo '</td>';
        echo '<td>';
        echo gmuw_websitesgmu_gtm_container_admin_link(get_post_meta($website->ID, 'website_gtm_container_post_id', true));
		echo '</td>';
	} else {
		echo '<td></td><td></td><td></td>';
	}
	if (!empty(get_post_meta($website->ID, 'website_ga_property_post_id', true))) {
		echo '<td>';
        echo get_the_title(get_post_meta($website->ID, 'website_ga_property_post_id', true));
        echo '</td>';
		echo '<td>';
        echo get_post_meta(get_post_meta($website->ID, 'website_ga_property_post_id', true),'ga_measurement_id',true);
        echo '</td>';
        echo '<td>';
        echo gmuw_websitesgmu_ga_property_admin_link(get_post_meta($website->ID, 'website_ga_property_post_id', true));
		echo '</td>';
	} else {
		echo '<td></td><td></td><td></td>';
	}

	echo '<td>';
	echo gmuw_websitesgmu_google_search_console_link($website->ID);
	echo '</td>';

	echo '<td>';
	echo '<a href="'.get_permalink($website->ID).'">';
	echo 'more details';
	echo '</a>';
	echo '</td>';

	echo '</tr>';
}

// finish display
echo '</tbody>';
echo '</table>';

?>

<h3>Other Website Instances</h3>

<?php

if (is_user_logged_in()) {

	// setup display
	echo '<table>';
	echo '<thead>';
	echo '<tr>';
	echo '<td>Environment/Instance Name</td>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

	// Get posts
	$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','',$taxonomy,$taxonomy_term_slug);

	// Loop through posts
	foreach ( $websites as $website ) {
		if (empty($website->production_domain)) {
			echo '<tr>';
			echo '<td>';
			echo '<a href="'.get_permalink($website->ID).'">';
			echo $website->post_title;
			echo '</a>';
			echo '</td>';
			echo '</tr>';
		}
	}

	// finish display
	echo '</tbody>';
	echo '</table>';

}

?>

<?php require('footer.php'); ?>
