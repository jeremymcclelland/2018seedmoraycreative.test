<?php

/*
 *	Plugin Name: Stitch API JSON Feed Plugin
 *	Plugin URI: http://moraycreative.com
 *	Description: Boiler plate code for connecting to an api to receive a json response
 *	Version: 1.0
 *	Author: Jeremy McClelland - Stitch Co.
 *	Author URI: http://moraycreative.com
 *	License: GPL2
 *
*/

// Assign global variables

$plugin_url = WP_PLUGIN_URL . '/wpstitch-api';
$options = array();
$display_json = false;


// Add a link to our plugin in the admin menu under 'Settings > Stitch API'


function wpstitch_api_menu(){

	/*
	add_options_page function
	add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
	*/

	add_options_page(
		'Stitch API Plugin',
		'Stitch API',
		'manage_options',
		'wpstitch-api',
		'wpstitch_api_options_page'
	);
}

add_action('admin_menu', 'wpstitch_api_menu' );

///////////////////////////


function wpstitch_api_options_page(){

	if(!current_user_can( 'manage_options' ) ) {
		wp_die('You do not have sufficient permissions to access this page.' );
	}

	global $plugin_url;
	global $options;
	global $display_json;

	if(isset($_POST['wpstitch_form_submitted'])){
		$hidden_field = esc_html($_POST['wpstitch_form_submitted']);

		if($hidden_field == 'Y'){
			$wpstitch_username = esc_html( $_POST['wpstitch_username'] );

			$wpstitch_data = wpstitch_api_get_feed($wpstitch_username);

			$options['wpstitch_username']	= $wpstitch_username;
			$options['wpstitch_feed_data']	= $wpstitch_data;
			$options['last_updated']		= time();

			update_option( 'wpstitch_api_options', $options);

			//var_dump($wpstitch_username) ;
		}
	}

	$options = get_option('wpstitch_api_options');

	if($options != ''){
		$wpstitch_username = $options['wpstitch_username']; 
		$wpstitch_data = $options['wpstitch_feed_data']; 
	}

	//var_dump($wpstitch_data) ;
	//return $wpstitch_data;
	require('inc/options-page-wrapper.php');


}
//////////////////////////////////


///////////////////////////


function wpstitch_api_get_data(){

	if(!current_user_can( 'manage_options' ) ) {
		wp_die('You do not have sufficient permissions to access this page.' );
	}

	global $plugin_url;
	global $options;
	global $display_json;

	if(isset($_POST['wpstitch_form_submitted'])){
		$hidden_field = esc_html($_POST['wpstitch_form_submitted']);

		if($hidden_field == 'Y'){
			$wpstitch_username = esc_html( $_POST['wpstitch_username'] );

			$wpstitch_data = wpstitch_api_get_feed($wpstitch_username);

			$options['wpstitch_username']	= $wpstitch_username;
			$options['wpstitch_feed_data']	= $wpstitch_data;
			$options['last_updated']		= time();

			update_option( 'wpstitch_api_options', $options);

			//var_dump($wpstitch_username) ;
		}
	}

	$options = get_option('wpstitch_api_options');

	if($options != ''){
		$wpstitch_username = $options['wpstitch_username']; 
		$wpstitch_data = $options['wpstitch_feed_data']; 
	}

	//var_dump($wpstitch_data) ;
	return $wpstitch_data;



}
//////////////////////////////////


class Wpstitch_Api_Feed_Widget extends WP_Widget {

	function wpstitch_api_feed_widget() {
		// Instantiate the parent object
		parent::__construct( false, 'JSON API Feed Widget' );
	}

	function widget( $args, $instance ) {
		//widget output
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );

		$options = get_option('wpstitch_api_options');
		$stitch_widget_data = $options['wpstitch_feed_data'];

		require('inc/front-end.php');
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form

		$title = esc_attr( $instance['title'] );

		require( 'inc/widget-fields.php' );
	}
}

function wpstitch_api_feed__register_widgets() {
	register_widget( 'Wpstitch_Api_Feed_Widget' );
}

add_action( 'widgets_init', 'wpstitch_api_feed__register_widgets' );





//////////////////////////////////

function wpstitch_api_get_feed( $wpstitch_username ){
	$json_feed_url = 'https://teamtreehouse.com/' . $wpstitch_username . '.json';

	$args = array('timeout' => 120);

	$json_feed = wp_remote_get($json_feed_url, $args);

	$wpstitch_decoded_data = json_decode($json_feed['body']);

	return $wpstitch_decoded_data;
}
//////////////////////////////////

function wpstitch_api_styles(){
	wp_enqueue_style('wpstitch_api_styles', plugins_url('wpstitch-api/wpstitch-api-styles.css') );
}
add_action( 'admin_head', 'wpstitch_api_styles' );


?>