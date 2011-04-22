<?php
/*
Plugin Name: WP Simple Survey
Plugin URI: http://www.steele-agency.com/2010/08/wordpress-simple-survey/
Description: A jQuery-based plugin that displays basic weighted survey, and then routes user to location based on score. Survey displays one question at a time, and uses jQuery to reload the subsequent question without reloading the page. Scores, Names, and Results can be recorded, emailed, and displayed in the WordPress backend.
Version: 2.0.2
Author: Richard Royal
Author URI: http://www.steele-agency.com/author/rroyal/
License: GPL2
*/


global $wpdb;
define('WPSS_PATH',ABSPATH.PLUGINDIR."/wordpress-simple-survey/");
define('WPSS_URL',WP_PLUGIN_URL."/wordpress-simple-survey/");
define('WPSS_SUBMIT_RESULTS',get_bloginfo('url')."/?wpss-routing=results");
define('WPSS_QUIZZES_DB',$wpdb->prefix.'wpss_Quizzes');
define('WPSS_QUESTIONS_DB',$wpdb->prefix.'wpss_Questions');
define('WPSS_ANSWERS_DB',$wpdb->prefix.'wpss_Answers');
define('WPSS_RESULTS_DB',$wpdb->prefix.'wpss_Results');
define('WPSS_ROUTES_DB',$wpdb->prefix.'wpss_Routes');
define('WPSS_FIELDS_DB',$wpdb->prefix.'wpss_Fields');
define('WPSS_EXTENDED_DB_VERSION','1.0');
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
require_once("functions.php");
require_once("submit_functions.php");
require_once("db_setup.php");
require_once("quiz_js.php");
require_once("output_quiz.php");



// run setup scripts on activation
register_activation_hook(__FILE__,'wpss_plugin_install');



/**
 *  Create admin pages in WP backend
 *	Connect Each Admin page with its function
 *	which imports php script page
 */
function simpsurv_admin(){require_once("admin_quizzes.php");}
function simpsurv_tracking(){require_once("view_results.php");}
function simpsurv_help(){require_once("admin_help.php");}
function simpsurv_global(){require_once("admin_global_options.php");}
function simpsurv_admin_actions() {
	if (current_user_can('manage_options')) {
		add_menu_page("WP Simple Survey - Setup Quizzes", "WPSS - Setup", "publish_posts", "wpss-setup","simpsurv_admin");
		add_submenu_page( "wpss-setup", "WP Simple Survey - Results / Export","Results/Export" ,"publish_posts", "wpss-results", "simpsurv_tracking");
		add_submenu_page( "wpss-setup", "WP Simple Survey - Help","WPSS Help" ,"publish_posts", "wpss-help", "simpsurv_help");
		add_submenu_page( "wpss-setup", "WP Simple Survey - Global","WPSS Global Options" ,"publish_posts", "wpss-global", "simpsurv_global");		
	}
}add_action('admin_menu', 'simpsurv_admin_actions');









/**
 *  Include JS Library in HTML <head>
 *  Allowing user ability to toggle off jquery import
 *
 *  NOTE: See js/README.txt	for good time
 */
function wpss_includeScripts(){
  $jquery = get_option('wpss_queue_jquery');
  $jqueryui = get_option('wpss_queue_jqueryui');
  if(!$jquery) update_option('wpss_queue_jquery','checked');;
  if(!$jqueryui) update_option('wpss_queue_jqueryui','checked');;  
  $jquery = get_option('wpss_queue_jquery');
  $jqueryui = get_option('wpss_queue_jqueryui');
  
	if (!is_admin()){ 
    if($jquery == 'checked' && $jqueryui == 'checked'){
      wp_deregister_script('jquery-ui-core');
      wp_deregister_script('jquery-ui-tabs');
      wp_deregister_script('jquery-ui-sortable');
      wp_deregister_script('jquery-ui-draggable');
      wp_deregister_script('jquery-ui-droppable');
      wp_deregister_script('jquery-ui-selectable');
      wp_deregister_script('jquery-ui-resizable');
      wp_deregister_script('jquery-ui-dialog');          
      wp_register_script('jquery-ui',WPSS_URL.'js/jquery-ui-1.8.10.full.min.js',array('jquery'),'1.8.10');
      wp_enqueue_script('jquery-ui');		
		  wp_enqueue_script('wpss_custom', WPSS_URL.'js/custom.js',array('jquery','jquery-ui'), '1.0' );        
    }
    elseif($jquery == 'checked' && $jqueryui == 'unchecked'){	
		  wp_enqueue_script('wpss_custom', WPSS_URL.'js/custom.js',array('jquery'), '1.0' );         
    }
    elseif($jquery == 'unchecked' && $jqueryui == 'checked'){	
      wp_deregister_script('jquery-ui-core');
      wp_deregister_script('jquery-ui-tabs');
      wp_deregister_script('jquery-ui-sortable');
      wp_deregister_script('jquery-ui-draggable');
      wp_deregister_script('jquery-ui-droppable');
      wp_deregister_script('jquery-ui-selectable');
      wp_deregister_script('jquery-ui-resizable');
      wp_deregister_script('jquery-ui-dialog');          
      wp_register_script('jquery-ui',WPSS_URL.'js/jquery-ui-1.8.10.full.min.js','1.8.10');
      wp_enqueue_script('jquery-ui');		
		  wp_enqueue_script('wpss_custom', WPSS_URL.'js/custom.js',array('jquery-ui'), '1.0' );        
    }	
    elseif($jquery == 'unchecked' && $jqueryui == 'unchecked'){	    
		  wp_enqueue_script('wpss_custom', WPSS_URL.'js/custom.js',array('jquery-ui'), '1.0' );            
    }
	}
}add_action('wp_print_scripts', 'wpss_includeScripts');







/**
 *  Register CSS's for plugin
 */
function wpss_stylesheets() {
  if(!is_admin()){
	  wp_enqueue_style('wpss_style', WPSS_URL.'style.css');  
	  wp_enqueue_style('wpss_uicore', WPSS_URL.'css/ui.core.css');
	  wp_enqueue_style('wpss_uitheme', WPSS_URL.'css/ui.theme.css');
	  wp_enqueue_style('wpss_probar', WPSS_URL.'css/ui.progressbar.css');
  } 
}add_action('wp_print_styles', 'wpss_stylesheets');





/**
 *  Register CSS for Admin Pages
 */
function wpss_admin_register_init(){
	wp_enqueue_style('wpss_style', WPSS_URL.'style.css');
  wp_enqueue_style('wpss_jquery_ui', WPSS_URL.'css/jquery-ui.css');
}add_action('admin_init', 'wpss_admin_register_init');







/**
 *  Output JS for Admin Pages, admin_enqueue_scripts buggy 
 */
function wpss_admin_register_head(){
  // NOTE:  wp_register_script doesnt want to work for admin pages
  //        admin_enqueue_scripts doesnt exist on admin_init or admin_head
  //        #wp_register_script('wpss_tip',WPSS_URL.'js/jquery.tools.min.js');		
  //        #wp_enqueue_scripts('wpss_tip');

	// only import tooltip when needed to avoid conflict with widget dragging js
  $wpss_pages = array('wpss-results','wpss-setup');
  if (in_array($_GET['page'],$wpss_pages)){ 
    echo '<script type="text/javascript" src="'.WPSS_URL.'js/jquery.tools.min.js"></script>';
    echo '<script type="text/javascript" src="'.WPSS_URL.'js/jquery-ui-full.min.js"></script>';  
    wp_tiny_mce(true, array("editor_selector" => "wpss_tinyedit"));      
    echo '<script type="text/javascript" src="'.WPSS_URL.'js/custom_backend.js"></script>';        
  }      
}add_action('admin_head', 'wpss_admin_register_head');






/**
 *	Setup custom URL for plugin to POST quiz results to,
 *	Allows for proper access to 'global worpress' scope
 *	including database settings needed for tracking
 */
function wpss_parse_request($wp) {
    // only process requests POST'ed to "/?wpss-routing=results"
    if (array_key_exists('wpss-routing', $wp->query_vars) && $wp->query_vars['wpss-routing'] == 'results') {
  		include('submit_quiz.php');	
    }
}add_action('parse_request', 'wpss_parse_request');

function wpss_parse_query_vars($vars) {
    $vars[] = 'wpss-routing';
    return $vars;
}add_filter('query_vars', 'wpss_parse_query_vars');




?>
