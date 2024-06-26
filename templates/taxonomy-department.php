<?php require('header.php'); ?>

<p></p>

<h3>Google Analytics Account(s)</h3>

<?php

// Get posts
$ga_accounts = gmuw_websitesgmu_get_custom_posts('ga_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// do we have posts?
if (empty($ga_accounts)) {
	echo '<p>There are no GA accounts associated with this department.</p>';
} else {
	// setup display
	echo '<table>';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Account Name</th>';
	echo '<th>Account ID</th>';
	echo '<th>Admin Link</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

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

}

?>

<h3>Google Tag Manager Account</h3>

<?php

// Get posts
$gtm_accounts = gmuw_websitesgmu_get_custom_posts('gtm_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Do we have posts?
if (empty($gtm_accounts)) {

	echo '<p>There are no GTM accounts associated with this department.</p>';

} else {
	// setup display
	echo '<table>';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Account Name</th>';
	echo '<th>Account ID</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

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

}

?>

<h3>Websites</h3>

<?php
echo gmuw_websitesgmu_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug);
?>

<?php
if (is_user_logged_in()) {

	echo '<h3>Other Website Instances</h3>';
	
	echo gmuw_websitesgmu_non_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug);
}
?>

<?php require('footer.php'); ?>
