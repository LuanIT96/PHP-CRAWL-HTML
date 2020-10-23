<?php
/**
 * Plugin Name: Show Data Table
 * Plugin URI: https://wordpress.org
 * Description: Simple show data table plugin.
 * Version: 1.0.0
 * Author: Nguyen Thanh Luan
 * Author URI: https://thanhluan.tk
 * license: GPLv2 or Later
 * Text Domain: show_data_table
 */

if(!function_exists('add_action')) {
	exit;
}

require_once(plugin_dir_path(__FILE__) . './config.php');
require_once(plugin_dir_path(__FILE__) . './models/Crawler.php');
require_once(plugin_dir_path(__FILE__) . './includes/class.show-data-table.php');
define('SHOW_TABLE_VERSION', '1.0.0');
define('SHOW_TABLE_MINIMUM_WP_VERSION', '5.2.2');

//Class Crawler
new Crawler();

//activation
function showdata_table_activation() {
	if ( ! wp_next_scheduled( 'example_event' ) ) {
		wp_schedule_event( time(), 'one_minute', 'example_event' );
	}
}
register_activation_hook(__FILE__,'showdata_table_activation');

// deactivation
function showdata_table_deactivation() {
	global $wpdb;
	$table_4d = $wpdb->prefix . "4d";
	$table_schedule_4d = $wpdb->prefix . "schedule_4d";
	$table_toto = $wpdb->prefix . "toto";
	$table_schedule_toto = $wpdb->prefix . "schedule_toto";
	//delete table
	$wpdb->get_results("DROP table IF Exists $table_4d");
	$wpdb->get_results("DROP table IF Exists $table_schedule_4d");
	$wpdb->get_results("DROP table IF Exists $table_toto");
	$wpdb->get_results("DROP table IF Exists $table_schedule_toto");
}
register_deactivation_hook( __FILE__, 'showdata_table_deactivation');

//uninstall
function showdata_table_uninstall()
{
	wp_clear_scheduled_hook('example_event');
	global $wpdb;
	$table_4d = $wpdb->prefix . "4d";
	$table_schedule_4d = $wpdb->prefix . "schedule_4d";
	$table_toto = $wpdb->prefix . "toto";
	$table_schedule_toto = $wpdb->prefix . "schedule_toto";
	//delete table
	$wpdb->get_results("DROP table IF Exists $table_4d");
	$wpdb->get_results("DROP table IF Exists $table_schedule_4d");
	$wpdb->get_results("DROP table IF Exists $table_toto");
	$wpdb->get_results("DROP table IF Exists $table_schedule_toto");
}
register_uninstall_hook(__FILE__, 'showdata_table_uninstall');

//add menu plugin
function showdata_table_menu() {
	add_options_page( 'Welcome plugin crawl html', 'Show Data Table', 'manage_options', 'my-unique-identifier', 'showdata_plugin_options' );
}
add_action( 'admin_menu', 'showdata_table_menu' );

function showdata_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require_once(plugin_dir_path(__FILE__).'views/view-index.php');
}

//list option 4d
function list_option_4d() 
{
	global $wpdb;
	$table_4d = $wpdb->prefix . "4d";
	$result = $wpdb->get_results("SELECT date FROM $table_4d GROUP BY date ORDER BY date ASC");
	return $result;
}

//list option toto
function list_option_toto()
{
	global $wpdb;
	$table_toto = $wpdb->prefix . "toto";
	$result = $wpdb->get_results("SELECT date FROM $table_toto GROUP BY date ORDER BY date ASC");
	return $result;
}

//view 4d
function view_4d($date)
{
	global $wpdb;
	$table_4d = $wpdb->prefix . "4d";
	$date_default = date('Y:m:d');
	$date = $date ? $date : $date_default;
	$result = $wpdb->get_results("SELECT * FROM $table_4d WHERE DATE(date) = '". $date ."' ORDER BY prizes ASC , id ASC LIMIT 23");
	return $result;
}

//view toto
function view_toto($date)
{
	global $wpdb;
	$table_toto = $wpdb->prefix . "toto";
	$date_default = date('Y:m:d');
	$date = $date ? $date : $date_default;
	$result = $wpdb->get_results("SELECT * FROM $table_toto WHERE DATE(date) = '". $date ."' ORDER BY toto_name ASC , id ASC LIMIT 7");
	return $result;
}

//shortcode 4d
function shortcode_4d(){
	ob_start();
	require_once( plugin_dir_path(__FILE__) . 'views/view-4d.php');
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'show-table-4d', 'shortcode_4d' );

//shortcode toto
function shortcode_toto(){
	ob_start();
	require_once( plugin_dir_path(__FILE__) . 'views/view-toto.php');
	$content = ob_get_clean();
	return $content;
}
add_shortcode('show-table-toto', 'shortcode_toto');