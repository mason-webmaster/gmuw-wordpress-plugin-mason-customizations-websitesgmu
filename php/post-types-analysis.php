<?php
/**
 * Summary: php file which contains functions to perform analysis on custom post types
 */


/**
 * Get totals summaries for a custom post type
 */
function gmuw_websitesgmu_get_cpt_totals($post_type) {

  // Initialize variables
  $return_value='';

  //Get numbers of deleted/non-deleted
  $count_non_deleted=gmuw_websitesgmu_get_cpt_total($post_type,'not-deleted');
  $count_deleted=gmuw_websitesgmu_get_cpt_total($post_type,'deleted');

  //Start building return value
  $return_value.='<p>';
  $return_value.='<strong>'.$count_non_deleted.' Records</strong><br />';
  
  //Do we have deleted?
  if ($count_deleted>1) {
    $return_value.='('.$count_deleted.' deleted; '.gmuw_websitesgmu_get_cpt_total($post_type,'all').' total)';
  }

  //Finish building return value
  $return_value.='</p>';

  //Return value
  return $return_value;

}

/**
 * Get the total number of posts for a custom post type
 */
function gmuw_websitesgmu_get_cpt_total($post_type,$count_mode) {

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );  

  // get meta query args based on mode
  switch ($count_mode) {
    case 'all':
      $args_meta = array();
      break;
    case 'deleted':
      $args_meta = array(
        'meta_query' => array(
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '=',
          ),
        )
      );
      break;
    case 'not-deleted':
      $args_meta = array(
        'meta_query' => array(
          array(
            'relation' => 'OR',
            array(
              'key'   => 'deleted',
              'compare' => 'NOT EXISTS',
            ),
            array(
              'key'   => 'deleted',
              'value' => '1',
              'compare' => '!=',
            ),
          )
        )
      );
      break;
  }

  // merge arg arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

  // Get count
  $posts_count = count($posts);

  return $posts_count;

}

/**
 * Get the total number of posts non-deleted with meta key value
 */
function gmuw_websitesgmu_get_total_non_delete_with_meta($post_type,$meta_key,$meta_value) {

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );  

  // set meta query args
  $args_meta = array(
    'meta_query' => array(
      array(
        'relation' => 'AND',
        array(
          'key'   => $meta_key,
          'value' => $meta_value,
          'compare' => '=',
        ),
        array(
          'relation' => 'OR',
          array(
            'key'   => 'deleted',
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '!=',
          ),
        )
      )
    )
  );

  // merge arg arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

  // Get count
  $posts_count = count($posts);

  return $posts_count;

}

/**
 * Get the total number of posts non-deleted where some meta key value exists
 */
function gmuw_websitesgmu_get_total_non_delete_with_meta_exists($post_type,$meta_key) {

  //Set basic arguments for the get posts function
  $args = array(
      'post_type'  => $post_type,
      'post_status' => 'publish',
      'nopaging' => true,
      'order' => 'ASC',
      'orderby' => 'name'
  );

  // set meta query args
  $args_meta = array(
    'meta_query' => array(
      array(
        'relation' => 'AND',
        array(
          'relation' => 'AND',
          array(
            'key'   => $meta_key,
            'compare' => 'EXISTS',
          ),
          array(
            'key'   => $meta_key,
            'value' => '',
            'compare' => '!=',
          ),
        ),
        array(
          'relation' => 'OR',
          array(
            'key'   => 'deleted',
            'compare' => 'NOT EXISTS',
          ),
          array(
            'key'   => 'deleted',
            'value' => '1',
            'compare' => '!=',
          ),
        )
      )
    )
  );

  // merge arg arrays
  $args_full = array_merge($args, $args_meta);

  // Get posts
  $posts = get_posts($args_full);

  // Get count
  $posts_count = count($posts);

  return $posts_count;

}

function gmuj_websitesgmu_total_websites() {

	// Get total number of websites

	// Get count of websites that are not explicitly deleted. (They could either not have a status at all or have a status of 'deleted'.)
	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT LIKE',
						    'value' => 'deleted',
				        ),
				    )
			    )
			)
		)
	);

}

/**
 * Gets count of website environments using wordpress
 */
function gmuj_websitesgmu_websites_wordpress() {

	// Get count of website environments using wordpress

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'cms',
						'field'    => 'slug',
						'terms'    => array('wordpress')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments using drupal
 */
function gmuj_websitesgmu_websites_drupal() {

	// Get count of website environments using drupal

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'cms',
						'field'    => 'slug',
						'terms'    => array('drupal')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments hosted on Materiell
 */
function gmuj_websitesgmu_websites_materiell() {

	// Get count of website environments hosted on Materiell

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'web_host',
						'field'    => 'slug',
						'terms'    => array('materiell')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments hosted on WPEngine
 */
function gmuj_websitesgmu_websites_wpengine() {

	// Get count of website environments hosted on WPEngine

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'web_host',
						'field'    => 'slug',
						'terms'    => array('wpengine')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments hosted on ACSF
 */
function gmuj_websitesgmu_websites_acsf() {

	// Get count of website environments hosted on ACSF

	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    ),
				'tax_query' => array(
					array(
						'taxonomy' => 'web_host',
						'field'    => 'slug',
						'terms'    => array('acquia-cloud-site-factory')
					)
				)
			)
		)
	);

}

/**
 * Gets count of website environments using the official theme
 */
function gmuj_websitesgmu_websites_using_theme() {

	// Get total number of websites using the official theme

	// Get count of websites that are explicitly known to use the official theme.
	return count(
		get_posts(
			array(
			    'post_type'  => 'website',
			    'post_status' => 'publish',
			    'nopaging' => true,
			    'meta_query' => array(
			        array(
					    'key'   => 'wordpress_theme',
					    'value' => 'gmuj|Mason Twenty Twenty Theme',
			            'compare' => 'REGEXP',
			        ),
				    array(
				        'relation' => 'OR',
				        array(
						    'key'   => 'website_status',
				            'compare' => 'NOT EXISTS',
				        ),
				        array(
						    'key'   => 'website_status',
						    'value' => 'deleted',
				            'compare' => 'NOT LIKE',
				        ),
				    )
			    )
			)
		)
	);

}
