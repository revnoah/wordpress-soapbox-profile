<?php
/**
 * @package SoaboxProfile
 * @version 1.0.0
 */

/*
Plugin Name: Soapbox Profile
Plugin URI: https://github.com/revnoah/wordpress-soapbox-profile#readme
Description: Plugin to display profile for online individuals
Author: Noah Stewart
Version: 1.0.0
Author URI: http://noahjstewart.com/
*/

//define constants for plugin
define("PLUGIN_DIR", __DIR__);
define("PAGENAME", 'soapbox-profile');

//load required includes
require_once realpath(__DIR__) . '/includes/createdb.inc.php';
require_once realpath(__DIR__) . '/includes/helpers.inc.php';
require_once realpath(__DIR__) . '/includes/form.inc.php';
require_once realpath(__DIR__) . '/includes/admin.inc.php';
require_once realpath(__DIR__) . '/includes/soapbox.inc.php';

//register rewrite hook
register_activation_hook(__FILE__, 'soapbox_profile_create_db');
register_activation_hook(__FILE__, 'soapbox_profile_rewrite_activation');
register_deactivation_hook(__FILE__, 'soapbox_profile_rewrite_activation');

/**
 * Handle rewrite rules
 *
 * @return void
 */
function soapbox_profile_rewrite_activation(){
	flush_rewrite_rules();
}

//filter for query vars passed to index.php
add_filter('query_vars', 'soapbox_profile_query_vars');

/**
 * Handle query params
 *
 * @param array $vars Query vars
 * @return array
 */
function soapbox_profile_query_vars(array $vars): array {
  $vars[] = 'slug';

  return $vars;
}

// load custom template, generate image and redirect based on query vars
add_action('template_redirect', 'soapbox_profile_catch_vars');

/**
 * Core page functionality
 *
 * @return void
 */
function soapbox_profile_catch_vars(): void {
	global $wpdb, $wp_query, $errors;
	$current_user = wp_get_current_user();
	$template_file = '';
	session_start();

	$pagename = get_query_var('pagename');
	$slug = get_query_var('slug');

	if ($pagename !== PAGENAME) {
		return;
	}

	//TODO: handle multiple routes, actions or pagenames
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//load variables to pass into template
		global $settings, $errors;

		//load setting with defaults
		$settings = soapbox_profile_settings_saved();

		//handle action and get any errors
		$errors = soapbox_profile_handle_action($_POST);

		//handle errors and redirect back to page
		if (count($errors) > 0) {
			$template_name = 'page-' . PAGENAME . '.php';
			$template_path = soapbox_profile_locate_template([$template_name], true);
		}
	} else {
		//load variables to pass into template
		global $settings;
		$settings = soapbox_profile_settings_saved();

		//$template_path = soapbox_profile_redirect_page_template($template_file);		
		$template_name = 'page-' . PAGENAME . '.php';
		$template_path = soapbox_profile_locate_template([$template_name], true);
	}
}
