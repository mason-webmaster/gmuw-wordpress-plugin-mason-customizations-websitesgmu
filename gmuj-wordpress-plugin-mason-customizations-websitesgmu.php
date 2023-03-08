<?php

/**
 * Main plugin file for the Mason WordPress customizations plugin for the instance: websitesgmu
 */

/**
 * Plugin Name:       Mason WordPress: Customizations Plugin: websitesgmu
 * Author:            Mason Web Administration
 * Plugin URI:        https://github.com/mason-webmaster/gmuw-wordpress-plugin-mason-customizations-websitesgmu
 * Description:       Mason WordPress Plugin to implement custom functionality for this website
 * Version:           0.9
 */


// Exit if this file is not called directly.
if (!defined('WPINC')) {
	die;
}

// Set up auto-updates
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
'https://github.com/mason-webmaster/gmuw-wordpress-plugin-mason-customizations-websitesgmu/',
__FILE__,
'gmuw-wordpress-plugin-mason-customizations-websitesgmu'
);

// Load custom code modules. Comment lines here to turn on or off individual features

// styles
require('php/styles.php');

// scripts
require('php/scripts.php');

// post types
require('php/post-types.php');

// taxonomies
require('php/taxonomies.php');

// shortcodes
//require('php/shortcodes.php');

// Branding
  include('php/fnsBranding.php');

// Admin menu
  include('php/admin-menu.php');

// Admin page
  include('php/admin-page.php');







// Add additional columns to website post list (https://pluginrepublic.com/add-acf-fields-to-admin-columns)
add_filter ('manage_website_posts_columns', 'gmuw_websitesgmu_add_columns_website');
function gmuw_websitesgmu_add_columns_website ($columns) {
 return array_merge ( $columns, array ( 
   //post id
   'post_id' => 'Post ID',
   //ACF fields
   'web_host' => 'Web Host',
   'environment_name'   => 'Environment Name', 
   'hosting_domain' => 'Hosting Domain',
   'production_domain'   => 'Production Domain', 
   'wordpress_theme'   => 'Theme', 
   'website_status'   => 'Status', 
   //Other fields
   'wordpress_theme_live' => 'Live Theme',
   'wordpress_login' => 'WP Login',
   'web_host_admin' => 'Web Host Admin',
 ) );
}

// Generate field output for additional columns in the website post list
add_action ('manage_website_posts_custom_column', 'gmuw_websitesgmu_website_custom_column', 10, 2);
function gmuw_websitesgmu_website_custom_column ($column, $post_id) {
 switch ($column) {
   case 'post_id':
    echo $post_id;
    break;
   case 'web_host':
    echo get_post_meta($post_id, 'web_host', true);
    break;
   case 'environment_name':
    echo get_post_meta($post_id, 'environment_name', true);
    break;
   case 'hosting_domain':
    if (has_term('wpengine', 'web_host')) {
      echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . 'wpengine.com</a>';
    }
    if (has_term('materiell', 'web_host')) {
      echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com" target="_blank">' . get_post_meta($post_id, 'environment_name', true) . 'materiellcloud.com</a>';
    }
    break;
   case 'production_domain':
     echo '<a href="https://'.get_post_meta($post_id, 'production_domain', true).'" target="_blank">' . get_post_meta ($post_id, 'production_domain', true) . '</a>';
     break;
   case 'wordpress_theme':
     echo get_post_meta($post_id, 'wordpress_theme', true);
     break;
   case 'website_status':
     echo get_post_meta($post_id, 'website_status', true);
     break;

   case 'wordpress_theme_live':
      if (get_post_meta($post_id, 'website_status', true)!='deleted') {
        echo gmuj_websitesgmu_get_live_website_theme($post_id);
      }
     break;

   case 'wordpress_login':

      if (get_post_meta($post_id, 'website_status', true)!='deleted') {

        if (get_post_meta($post_id, 'web_host', true)=='WPE') {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.wpengine.com/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __FILE__ ).'images/logo-wordpress.png'.'" /></a>';
        } elseif (get_post_meta($post_id, 'web_host', true)=='Materiell') {
            echo '<a href="https://'.get_post_meta($post_id, 'environment_name', true).'.materiellcloud.com/wp-admin/" target="_blank" title="WordPress login"><img style="width:25px;" src="'.plugin_dir_url( __FILE__ ).'images/logo-wordpress.png'.'" /></a>';
        } else {

        }

      }
      break;

   case 'web_host_admin':
      if (get_post_meta($post_id, 'website_status', true)!='deleted') {
        if (get_post_meta($post_id, 'web_host', true)=='WPE') {
        //Store web host admin URL
        $web_host_admin_url='https://my.wpengine.com/installs/'.get_post_meta($post_id, 'environment_name', true).'/';
        // Output web host admin links
        echo '<a href="'.$web_host_admin_url.'" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __FILE__ ).'images/logo-wpengine.png'.'" /> overview</a><br />';
        echo '<a href="'.$web_host_admin_url.'cache_dashboard" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __FILE__ ).'images/logo-wpengine.png'.'" /> cache</a><br />';
        echo '<a href="'.$web_host_admin_url.'advanced" target="_blank"><img style="width:25px; vertical-align: middle; margin-bottom:1px;" src="'.plugin_dir_url( __FILE__ ).'images/logo-wpengine.png'.'" /> advanced</a><br />';
        }
      }
      break;
 }
}

function gmuj_websitesgmu_get_live_website_theme($post_id){
  //Get the real-time theme info from the WP REST API on the live site

      // Get post object
      $post = get_post($post_id);
      //$return_value.='Post ID: '.$post_id.'<br />';

      // Get post hosting domain from post object
      $domain = get_post_meta($post_id, 'environment_name', true).'.wpengine.com';
      //$return_value.='Domain: '.$domain.'<br />';

      // Set URL for REST endpoint
      $mason_site_check_in_theme_info_endpoint_url='https://' . $domain . '/wp-json/gmuj-sci/theme-info';
      //$return_value.='URL: '.$mason_site_check_in_theme_info_endpoint_url.'<br />';

      // Try to get the info
      $mason_site_check_in_theme_info_response = gmuw_msc_get_url_content($mason_site_check_in_theme_info_endpoint_url);
        //$return_value.='<p><textarea>'.$mason_site_check_in_theme_info_response.'</textarea></p>';
      // Try to parse it as JSON
      $mason_site_check_in_theme_info_response_json = json_decode($mason_site_check_in_theme_info_response);
        //$return_value.=var_dump($mason_site_check_in_theme_info_response_json);

        // Did we get a json response?
        if (gettype($mason_site_check_in_theme_info_response_json)!='object'){
          $return_value.='<p><a href="'.$mason_site_check_in_theme_info_endpoint_url.'" target="_blank">Not JSON object.</a> &#128533;</p>';
        } else {
          if ($mason_site_check_in_theme_info_response_json->data->status==404){
              $return_value.='<p><a href="'.$mason_site_check_in_theme_info_endpoint_url.'" target="_blank">JSON 404.</a> &#128533;</p>';
          } else {
            $return_value.='<p>';
            $return_value.='<a href="'.$mason_site_check_in_theme_info_endpoint_url.'" target="_blank">'.$mason_site_check_in_theme_info_response_json->theme_name.' ('.$mason_site_check_in_theme_info_response_json->theme_version.')</a> &#128515;';
            $return_value.='</p>';
              
            // Is the post meta theme name different from the live theme name?
            if (get_post_meta($post_id, 'wordpress_theme', true ) != $mason_site_check_in_theme_info_response_json->theme_name.' ('.$mason_site_check_in_theme_info_response_json->theme_version.')') {
              $return_value.='<p>';
              $return_value.=gmuj_websitesgmu_update_theme_link($post_id,$mason_site_check_in_theme_info_response_json->theme_name,$mason_site_check_in_theme_info_response_json->theme_version);
              $return_value.='</p>';
            }

          }
        }

      // Return value
      return $return_value;

}

function gmuj_websitesgmu_update_theme_link($post_id,$theme_name,$theme_version){
  //Display theme update link

  //Return value
  return "<a class='button button-small' href='admin.php?page=gmuj_websitesgmu&action=update_theme&post_id=". $post_id ."&theme_name=". urlencode($theme_name) ."&theme_version=". $theme_version ."'>Update Post</a>";

}
