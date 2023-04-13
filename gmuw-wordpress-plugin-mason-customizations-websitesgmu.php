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

// Dashboard
  include('php/admin-dashboard.php');

// Editor
  include('php/editor.php');

// JSON
  include('php/json.php');

// WPE API
  include('php/wpe-api.php');

// ACSF API
  include('php/acsf-api.php');
