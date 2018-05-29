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

		$num_badges = $instance['num_badges'];
		$display_tooltips = $instance['display_tooltips'];

		$options = get_option('wpstitch_api_options');
		$stitch_widget_data = $options['wpstitch_feed_data'];

		require('inc/front-end.php');
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num_badges'] = strip_tags($new_instance['num_badges']);
		$instance['display_tooltips'] = strip_tags($new_instance['display_tooltips']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form

		$title = esc_attr( $instance['title'] );
		$num_badges = esc_attr( $instance['num_badges'] );
		$display_tooltips = esc_attr( $instance['display_tooltips'] );

		$options = get_option('wpstitch_api_options');
		$stitch_widget_data = $options['wpstitch_feed_data'];

		require( 'inc/widget-fields.php' );
	}
}

function wpstitch_api_feed__register_widgets() {
	register_widget( 'Wpstitch_Api_Feed_Widget' );
}

add_action( 'widgets_init', 'wpstitch_api_feed__register_widgets' );


//////////////////////////////////

function wpstitch_shortcode($atts, $content = null) {
	global $post;

	extract(shortcode_atts( array(
		'num_badges' => '8',
		'tooltip' => 'on'
	), $atts ) );


	if($tooltip == 'on') $tooltip = 1;
	if($tooltip == 'off') $tooltip = 0;

	$display_tooltips = $tooltip;

	$options = get_option('wpstitch_api_options');
	$stitch_widget_data = $options['wpstitch_feed_data'];

	ob_start();

	require( 'inc/front-end.php' );

	$content = ob_get_clean();

	return $content;
}

add_shortcode('wpstitch_data_shortcode', 'wpstitch_shortcode');

//////////////////////////////////

function wpstitch_api_get_feed( $wpstitch_username ){
	$json_feed_url = 'https://teamtreehouse.com/' . $wpstitch_username . '.json';

	$args = array('timeout' => 120);

	$json_feed = wp_remote_get($json_feed_url, $args);

	$wpstitch_decoded_data = json_decode($json_feed['body']);

	return $wpstitch_decoded_data;
}
//////////////////////////////////






//This function refreshes feed via ajax

function wpstitch_api_refresh_feed(){
	$options = get_option('wpstitch_api_options');
	$last_updated = $options['last_updated']; 

	$current_time = time();

	$update_difference = $current_time - $last_updated;

	if( $update_difference > 86400 ){

		$wpstitch_username = $options['wpstitch_username']; 

		$options['wpstitch_feed_data'] = wpstitch_api_get_feed($wpstitch_username);
		$options['last_updated'] = time();

		update_option( 'wpstitch_api_options', $options);

	}

	die();
}


//wp_ajax_ IS A COMMON TAG, THEN ADD FUNCTION NAME AFTER THAT wpstitch_api_refresh_feed
add_action('wp_ajax_wpstitch_api_refresh_feed', 'wpstitch_api_refresh_feed');



function wpstitch_enable_frontend_ajax() {
?>

<script>

	var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';

</script>


<?php	
}

add_action('wp_head', 'wpstitch_enable_frontend_ajax');


///////////////

function wpstitch_api_backend_styles(){
	wp_enqueue_style('wpstitch_api_backend_css', plugins_url('wpstitch-api/wpstitch-api-styles.css') );
}
add_action( 'admin_head', 'wpstitch_api_backend_styles' );

function wpstitch_api_frontend_scripts_styles(){
	wp_enqueue_style('wpstitch_api_backend_css', plugins_url('wpstitch-api/wpstitch-api-styles.css') );
	wp_enqueue_script('wpstitch_api_backend_js', plugins_url('wpstitch-api/wpstitch-api-styles.js'), array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'wpstitch_api_frontend_scripts_styles' );


?>