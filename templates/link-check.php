<?php

//get query string parameters
$taxonomy = ( isset( $_GET['taxonomy'] ) ) ? sanitize_text_field( $_GET['taxonomy'] ) : '';
$term = ( isset( $_GET['term'] ) ) ? sanitize_text_field( $_GET['term'] ) : '';

?>
<!DOCTYPE html>
<html>

<head>
	<title><?php echo get_bloginfo('name') ?>: Link Check</title>
</head>

<body>

	<?php echo '<h1>' . get_bloginfo('name') . '</h1>'; ?>
	<h2>Link Check</h2>
	<?php echo gmuw_websitesgmu_websites_display_homepage_links_by_taxonomy($taxonomy,$term); ?>

</body>

</html>