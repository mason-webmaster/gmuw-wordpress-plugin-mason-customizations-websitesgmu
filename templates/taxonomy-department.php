<?php

/**
 * The custom template file for taxonomy/departments
 */

get_header();

// Get info
$term = get_queried_object();
$taxonomy = $term->taxonomy;
$taxonomy_term_slug = $term->slug;
$taxonomy_term_name = $term->name;

?>

<main id="site-content" role="main">

<?php

$archive_title    = get_the_archive_title();
$archive_subtitle = get_the_archive_description();

if ( $archive_title || $archive_subtitle ) {
	?>

	<header class="archive-header has-text-align-center header-footer-group">

		<div class="archive-header-inner section-inner medium">

			<?php if ( $archive_title ) { ?>
				<h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
			<?php } ?>

			<?php if ( $archive_subtitle ) { ?>
				<div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
			<?php } ?>

		</div><!-- .archive-header-inner -->

	</header><!-- .archive-header -->

	<?php
}

?>

<article>

	<div class="post-inner">

		<div class="gmuj-sidebar-wrapper">
			<div class="gmuj-sidebar"></div>
			<div class="entry-content">

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

			</div>

		</div>

	</div>

<article>

</main><!-- #site-content -->

<?php
get_footer();