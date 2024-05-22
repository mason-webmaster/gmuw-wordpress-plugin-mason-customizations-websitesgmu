<?php require('header.php'); ?>

<p></p>

<h3>Websites</h3>

<?php
echo gmuw_websitesgmu_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug);
?>

<h3>Other Website Instances</h3>

<?php
if (is_user_logged_in()) {
	echo gmuw_websitesgmu_non_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug);
}
?>

<?php require('footer.php'); ?>
