<?php require('taxonomy-header.php'); ?>

<p></p>

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
