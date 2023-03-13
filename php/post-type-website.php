<?php
/**
 * Handle custom post type
 */

/**
 * Register a custom post type
 */
add_action('init', 'gmuw_register_custom_post_type_websites');
function gmuw_register_custom_post_type_websites() {

    $labels = array(
        'name'                  => 'Websites',
        'singular_name'         => 'Website',
        'menu_name'             => 'Websites',
        'name_admin_bar'        => 'Website',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Website',
        'new_item'              => 'New Website',
        'edit_item'             => 'Edit Website',
        'view_item'             => 'View Website',
        'all_items'             => 'All Websites',
        'search_items'          => 'Search Websites',
        'parent_item_colon'     => 'Parent Website:',
        'not_found'             => 'No Websites found.',
        'not_found_in_trash'    => 'No Websites found in Trash.',
        'featured_image'        => 'Website Image',
        'set_featured_image'    => 'Set website image',
        'remove_featured_image' => 'Remove website image',
        'use_featured_image'    => 'Use as website image',
        'archives'              => 'Websites archives',
        'insert_into_item'      => 'Insert into website',
        'uploaded_to_this_item' => 'Uploaded to this website',
        'filter_items_list'     => 'Filter website list',
        'items_list_navigation' => 'Website list navigation',
        'items_list'            => 'Website list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'website'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => gmuw_websitesgmu_get_cpt_icon('website'),
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register website custom post type
    register_post_type('website', $args);

}

// Add additional columns to the admin list
add_filter ('manage_website_posts_columns', 'gmuw_websitesgmu_add_columns_website');
function gmuw_websitesgmu_add_columns_website ($columns) {

    return array_merge ( $columns, array ( 
        //ACF fields
        'environment_name'   => 'Env. Name',
        'hosting_domain' => 'Hosting Domain',
        'production_domain'   => 'Prod Domain',
        'wordpress_theme'   => 'WP Theme',
        'website_status'   => 'Status',
        //Other fields
        'website_ga_property_post_id' => 'GA Account',
        'website_gtm_container_post_id' => 'GTM Container',
        'wordpress_data_feeds' => 'WP Data Feeds',
        'wordpress_theme_live' => 'Live WP Theme',
        'admin_login' => 'Admin Login',
        'web_host_admin' => 'Web Host Admin',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_website_posts_custom_column', 'gmuw_websitesgmu_website_custom_column', 10, 2);
function gmuw_websitesgmu_website_custom_column ($column, $post_id) {

    switch ($column) {
        case 'environment_name':
            echo get_post_meta($post_id, 'environment_name', true);
            break;
        case 'hosting_domain':
            if (has_term('wpengine', 'web_host')) {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . '.wpengine.com</a>';
            }
            if (has_term('materiell', 'web_host')) {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . '.materiellcloud.com</a>';
            }
            if (has_term('acquia-cloud-site-factory', 'web_host')) {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.sitemasonry.gmu.edu" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . '.sitemasonry.gmu.edu</a>';
            }
            break;
        case 'production_domain':
            echo '<a href="https://'.get_post_meta($post_id, 'production_domain', true).'" target="_blank">' . get_post_meta ($post_id, 'production_domain', true) . '</a>';
            break;
        case 'wordpress_theme':
            if (has_term('wordpress', 'cms')) {
                echo get_post_meta($post_id, 'wordpress_theme', true);
            }
            break;
        case 'website_status':
            echo get_post_meta($post_id, 'website_status', true);
            break;
        case 'website_ga_property_post_id':
            if (get_post_meta($post_id, 'website_ga_property_post_id', true)){
            echo get_the_title(get_post_meta($post_id, 'website_ga_property_post_id', true)).'<br />';
            echo '<a href="'.get_edit_post_link(get_post_meta($post_id, 'website_ga_property_post_id', true)).'">edit</a> | ';
            echo '<a href="'.get_post_permalink(get_post_meta($post_id, 'website_ga_property_post_id', true)).'">view</a> ';
            }
            break;
        case 'website_gtm_container_post_id':
            if (get_post_meta($post_id, 'website_gtm_container_post_id', true)){
            echo get_the_title(get_post_meta($post_id, 'website_gtm_container_post_id', true)).'<br />';
            echo '<a href="'.get_edit_post_link(get_post_meta($post_id, 'website_gtm_container_post_id', true)).'">edit</a> | ';
            echo '<a href="'.get_post_permalink(get_post_meta($post_id, 'website_gtm_container_post_id', true)).'">view</a> ';
            }
            break;
        case 'wordpress_data_feeds':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wordpress', 'cms')) {
                    if (has_term('materiell','web_host')) {
                        $hosting_domain='https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com';
                    }
                    if (has_term('wpengine','web_host')) {
                        $hosting_domain='https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com';
                }
                echo '<a href="'.$hosting_domain.'/wp-json/gmuj-sci/theme-info">theme info</a><br /><a href="'.$hosting_domain.'/wp-json/gmuj-sci/most-recent-modifications">modifications</a><br /><a href="'.$hosting_domain.'/wp-json/gmuj-mmi/mason-site-info">site info</a>';
                }
            }
            break;
        case 'wordpress_theme_live':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wordpress', 'cms')) {
                    if ($_GET['live_theme_check']=='1') {
                        echo gmuj_websitesgmu_get_live_website_theme($post_id);
                    } else {
                        echo '<a href="'.$_SERVER['REQUEST_URI'].'&live_theme_check=1">enable</a>';
                    }
                }
            }
            break;
        case 'admin_login':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wpengine', 'web_host')) {
                    echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wordpress.png'.'" /></a>';
                }
                if (has_term('materiell', 'web_host')) {
                    echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wordpress.png'.'" /></a>';
                }
                if (has_term('acquia-cloud-site-factory', 'web_host')) {
                    echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.sitemasonry.gmu.edu/user/login/" target="_blank" title="Drupal login"><img style="width:25px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-drupal.png'.'" /></a>';
                }
            }
            break;
        case 'web_host_admin':
            if (get_post_meta($post_id, 'website_status', true)!='deleted') {
                if (has_term('wpengine', 'web_host')) {
                    //Store web host admin URL
                    $web_host_admin_url='https://my.wpengine.com/installs/'.get_post_meta($post_id, 'environment_name', true).'/';
                    // Output web host admin links
                    echo '<a href="'.$web_host_admin_url.'" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> overview</a><br />';
                    echo '<a href="'.$web_host_admin_url.'cache_dashboard" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> cache</a><br />';
                    echo '<a href="'.$web_host_admin_url.'advanced" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-wpengine.png'.'" /> advanced</a><br />';
                }
                if (has_term('acquia-cloud-site-factory', 'web_host')) {
                    //Store web host admin URL
                      $web_host_admin_url='https://www.georgemasonusf.acsitefactory.com/sites-by-group/list?field_domain_contains='.get_post_meta($post_id, 'environment_name', true).'';
                    // Output web host admin links
                    echo '<a href="'.$web_host_admin_url.'" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __DIR__ ).'images/logo-acsf.png'.'" /></a><br />';
                }
            }
            break;
    }

}


/**
 * Adds meta box to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_websitesgmu_custom_dashboard_meta_box_websites');
function gmuw_websitesgmu_custom_dashboard_meta_box_websites() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add meta box */
  add_meta_box("gmuw_websitesgmu_custom_dashboard_meta_box_websites", "Websites", "gmuw_websitesgmu_custom_dashboard_meta_box_websites_content", "dashboard","normal");

}

/**
 * Provides content for the dashboard meta box
 */
function gmuw_websitesgmu_custom_dashboard_meta_box_websites_content() {

  //Initialize variables
  $cpt_slug='website';
  $content='';

  //basic totals
  $content.='<p>'.gmuw_websitesgmu_get_cpt_totals($cpt_slug).'</p>';

  //follow-up records
  $content.=gmuw_websitesgmu_meta_box_display_follow_up_records($cpt_slug);

  //Display meta box
  gmuw_websitesgmu_custom_dashboard_meta_box_cpt_summary($cpt_slug,$content);

}
