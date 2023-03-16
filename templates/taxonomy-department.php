<?php require('taxonomy-header.php'); ?>

<p></p>

<h3>Google Analytics Account(s)</h3>

<?php

// Get posts
$ga_accounts = gmuw_websitesgmu_get_custom_posts('ga_account','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $ga_accounts as $ga_account ) {
	echo '<p><a href="'.get_permalink($ga_account->ID).'">'. $ga_account->post_title .'</a> ('. $ga_account->ga_account_id .')</p>';
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

<h3>Website(s)</h3>

<?php

// Get posts
$websites = gmuw_websitesgmu_get_custom_posts('website','not-deleted','','',$taxonomy,$taxonomy_term_slug);

// Loop through posts
foreach ( $websites as $website ) {
	echo '<p>';
	echo '<a href="'.get_permalink($website->ID).'">'. $website->post_title .'</a>';
	echo '</a>';
	if (!empty($website->production_domain)) {
		echo ' ('. $website->production_domain .')';
	}
	echo '</p>';
}
?>

<?php require('taxonomy-footer.php'); ?>
