<?php
if(!function_exists('add_action')) {
	exit;
}
//load admin css
function load_admin_style() {
	wp_enqueue_style( 'admin_css', plugins_url(). '/show-data-table/scripts/css/admin-style.css', false, '1.0.0' );
} 
add_action( 'admin_enqueue_scripts', 'load_admin_style'); 

//load plugin css
function load_plugin_style() {
	wp_enqueue_style('plugin_css', plugins_url(). '/show-data-table/scripts/css/plugin-style.css', false, '1.0.0');
}

add_action('wp_enqueue_scripts', 'load_plugin_style');