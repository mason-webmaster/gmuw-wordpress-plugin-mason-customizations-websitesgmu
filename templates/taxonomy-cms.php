<?php require('header.php'); ?>

<p></p>

<h3>Live/Production Website(s)</h3>

<?php
echo gmuw_websitesgmu_production_website_listing_by_taxonomy($taxonomy,$taxonomy_term_slug);
?>

<?php require('footer.php'); ?>
