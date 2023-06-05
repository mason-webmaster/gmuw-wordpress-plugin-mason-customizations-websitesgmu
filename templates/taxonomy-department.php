<?php require('header.php'); ?>

<p></p>

<h3>Google Analytics Account(s)</h3>

<?php

// Get posts
$ga_accounts = gmuw_websitesgmu_get_custom_posts('ga_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $ga_accounts as $ga_account ) {
	echo '<p>';
	//echo '<a href="'.gmuw_websitesgmu_ga_account_admin_link_url($ga_account->ID).'">';
	echo $ga_account->post_title;
	//echo '</a>';
	echo ' ('. $ga_account->ga_account_id .')';
	echo gmuw_websitesgmu_ga_account_admin_link($ga_account->ID);
	echo '</p>';

}
?>

<h3>Google Tag Manager Account(s)</h3>

<?php

// Get posts
$gtm_accounts = gmuw_websitesgmu_get_custom_posts('gtm_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $gtm_accounts as $gtm_account ) {
	echo '<p><a href="'.get_permalink($gtm_account->ID).'">'. $gtm_account->post_title .'</a> ('. $gtm_account->gtm_account_id .')</p>';
}
?>

<h3>Live/Production Website(s)</h3>

<?php

// Get posts
$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','production_domain','',$taxonomy,$taxonomy_term_slug);

// setup display
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<td>Website</td>';
echo '<td>GTM Container</td>';
echo '<td>GA4 Property</td>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Loop through posts
foreach ( $websites as $website ) {
	echo '<tr>';
	//echo '<a href="'.get_permalink($website->ID).'">'. $website->post_title .'</a>';
	echo '<td>';
	echo '<a href="https://'.$website->production_domain.'/">'. $website->production_domain .'</a>';
	if ($website->post_title!=$website->production_domain) {
		echo ' ('.$website->post_title.')';
	}
	echo '</td>';

	echo '<td>';
	if (!empty(get_post_meta($website->ID, 'website_gtm_container_post_id', true))) {
        echo get_the_title(get_post_meta($website->ID, 'website_gtm_container_post_id', true));
        echo '<br />';
        echo gmuw_websitesgmu_gtm_container_admin_link(get_post_meta($website->ID, 'website_gtm_container_post_id', true));
	}
	echo '</td>';
	echo '<td>';
	if (!empty(get_post_meta($website->ID, 'website_ga_property_post_id', true))) {
        echo get_the_title(get_post_meta($website->ID, 'website_ga_property_post_id', true));
        echo '<br />';
        echo gmuw_websitesgmu_ga_property_admin_link(get_post_meta($website->ID, 'website_ga_property_post_id', true));
	}
	echo '</td>';
	echo '</tr>';
}

// finish display
echo '</tbody>';
echo '</table>';


?>

<h3>Other Website Instances</h3>

<?php

// Get posts
$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $websites as $website ) {
	if (empty($website->production_domain)) {
		echo '<p>';
		echo '<a href="'.get_permalink($website->ID).'">'. $website->post_title .'</a>';
		echo '</p>';
	}
}

?>

<?php require('footer.php'); ?>
