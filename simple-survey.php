<?php
/*
Plugin Name: WP Simple Survey
Plugin URI: http://www.steele-agency.com/2010/08/wordpress-simple-survey/
Description: A jQuery-based plugin that displays basic weighted survey, and then routes user to location based on score. Survey displays one question at a time, and uses jQuery to reload the subsequent question without reloading the page. Scores, Names, and Results can be recorded, emailed, and displayed in the Wordpress backend.
Version: 1.5.3
Author: Richard Royal
Author URI: http://www.steele-agency.com/author/rroyal/
License: GPL2
*/
/*  Copyright 2010 Richard Royal (email: richard at steele-agency.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
?>
<?php

// Import for quiz producing function
include("quiz_output.php");

// Register plugin activation table creation
global $my_plugin_table;
global $my_plugin_db_version;
global $wpdb;
$my_plugin_table = $wpdb->prefix . 'wpss_quizTracking';
$my_plugin_version = '1.0';

register_activation_hook( __FILE__,  'wpss_plugin_install' );

function wpss_plugin_install() {
	global $wpdb;
	global $my_plugin_table;
	global $my_plugin_db_version;

	if ( $wpdb->get_var( "show tables like '$my_plugin_table'" ) != $my_plugin_table ) {
		$sql = "CREATE TABLE $my_plugin_table (".
		     "id int NOT NULL AUTO_INCREMENT, ".
		     "results text NOT NULL, ".
		     "time VARCHAR(30) DEFAULT '0' NOT NULL, ".
		     "UNIQUE KEY id (id) ".
				 ")";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	    	add_option( "my_plugin_db_version", $my_plugin_db_version );
	}
}

// Filter Content for quiz string: [wp-simple-survey]
function simpsurv_filter($content) {
	return str_replace('[wp-simple-survey]',getQuiz(),$content);
}

// Route Setting link menu to admin page
function simpsurv_admin(){
	include("admin_page.php");
}

// Route Results link menu to results page
function simpsurv_tracking(){
	include("tracking.php");
}

// Add Wordpress Custom Menu, Settings & Results
function simpsurv_admin_actions() {
	if (current_user_can('manage_options')) {
		// Add Menus with functions
		add_menu_page( "WP Simple Survey - Options", "WPSS Options", "publish_posts", "wpss-options", "simpsurv_admin");
		add_submenu_page( "wpss-options", "WP Simple Survey - Results","WPSS Results" ,"publish_posts", "wpss-results", "simpsurv_tracking");
	}
}

# Action hooks for admin menu, string filter, and javascripts
add_action('admin_menu', 'simpsurv_admin_actions');
add_filter('the_content', 'simpsurv_filter');
add_action('wp_print_scripts', 'WPSS_ScriptsAction');


# Includes JS in HTML header
function WPSS_ScriptsAction(){
	$wpss_url = WP_PLUGIN_URL."/wordpress-simple-survey/";
	if (!is_admin()){
		// Register WP's version of jQuery $ jQueryUI, NOTE: These are queued in noConflict() Mode
		wp_enqueue_script('wpss_jqueryuiprogressbar', $wpss_url.'jqueryui1.7/development-bundle/ui/ui.progressbar.js',array( 'jquery', 'jquery-ui-core' ), '1.7' );
	}
	// Ensure jQuery is registered for admin Results Page Toggle
	else{ 
		if ($_GET['page']== "wpss-options"){ // only call when needed to avoid conflict
			wp_enqueue_script('wpss_tip', $wpss_url.'jqueryui1.7/jquery.tools.min.js',array( 'jquery', 'jquery-ui-core' ), '1.0' );
		}
		else wp_enqueue_script('jquery');
	}
}

// Register CSS's for plugin
add_action('wp_print_styles', 'add_my_stylesheets');
function add_my_stylesheets() {
	$wpss_url = WP_PLUGIN_URL . "/wordpress-simple-survey/";

	// main plugin css
	wp_register_style('wpss_style', $wpss_url.'style.css');
	wp_enqueue_style( 'wpss_style');

	// ui core css
	wp_register_style('wpss_uicore', $wpss_url.'jqueryui1.7/development-bundle/themes/smoothness/ui.core.css');
	wp_enqueue_style( 'wpss_uicore');

	// ui theme css
	wp_register_style('wpss_uitheme', $wpss_url.'jqueryui1.7/development-bundle/themes/smoothness/ui.theme.css');
	wp_enqueue_style( 'wpss_uitheme');

	// ui progressbar css
	wp_register_style('wpss_probar', $wpss_url.'jqueryui1.7/development-bundle/themes/smoothness/ui.progressbar.css');
	wp_enqueue_style( 'wpss_probar');
}


// Register CSS for Admin Pages
function wpss_admin_register_head() { 
	$wpss_url = WP_PLUGIN_URL . "/wordpress-simple-survey/";
	$admin_css_url = $wpss_url . 'style.css'; 
	echo '<link rel="stylesheet" type="text/css" href="'.$admin_css_url.'" />';
}
add_action('admin_head', 'wpss_admin_register_head');



/*
 *	Setup custom URL for plugin to POST quiz results to,
 *	Allows for proper access to 'global worpress' scope
 *	including database settings needed for tracking
 */
function wpss_parse_request($wp) {
    // only process requests POST'ed to "/?wpss-routing=results"
    if (array_key_exists('wpss-routing', $wp->query_vars) 
            && $wp->query_vars['wpss-routing'] == 'results') {
		include('quiz_submit.php');	
    }
}
add_action('parse_request', 'wpss_parse_request');

function wpss_parse_query_vars($vars) {
    $vars[] = 'wpss-routing';
    return $vars;
}
add_filter('query_vars', 'wpss_parse_query_vars');


?>
