<?php require('header.php'); ?>

<?php $post = get_post()?>

<p></p>

<h3>Google Tag Manager Account:<br /><?php the_title(); ?></h3>

<?php

// Is this record deleted?
if ($post->deleted==1) {

	// Display deleted message
	echo '<p>This GTM account has been deleted.</p>';

} else {

	// Display GTM account info

	// account ID
	echo '<p>Account ID: '.$post->gtm_account_id.'</p>';

	// GTM containers in this account
	if (is_user_logged_in()) {

		echo '<h4>GTM Containers in This Account</h4>';
		echo '<p>';
		
		//get gtm_container posts
		$gtm_containers=gmuw_websitesgmu_get_custom_posts('gtm_container','not-deleted','gtm_container_account_post_id',$post->ID);
		//loop through posts
		foreach ($gtm_containers as $gtm_container) {
			echo $gtm_container->post_title . ' ('.$gtm_container->gtm_container_id_public.')<br />';
		}

		echo '</p>';

	}

}

?>

<?php require('footer.php'); ?>
