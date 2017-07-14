<?php
/**
* Plugin Name: Find Tweets
* Plugin URI: http://lancehadleydesign.com/
* Description: Find tweets within the content of your website.
* Version: 0.11
* Author: Lance Hadley
* Author URI: http://lancehadleydesign.com/
*/

// Set constant URI to the plugin URL.
    if( !defined( 'FT_URI' ) )
        define( 'FT_URI', plugin_dir_url( __FILE__ ) );
// Set the constant path to the plugin's javascript directory.
    if( !defined( 'FT_JS' ) )
        define( 'FT_JS', FT_URI . trailingslashit( 'js' ), true );

// Set the constant path to the plugin's CSS directory.
    if( !defined( 'FT_CSS' ) )
        define( 'FT_CSS', FT_URI . trailingslashit( 'css' ), true );

// Add ZeroClipboard Scripts
		wp_register_script('ZeroClipboard', FT_JS .'ZeroClipboard/ZeroClipboard.js');
		wp_register_script('Zeromain', FT_JS .'ZeroClipboard/main.js');
		
/** Step 1. */
function find_tweet_menu() {
	add_submenu_page('tools.php','Find Tweets', 'Find Tweets', 'manage_options', 'find_tweets', 'find_tweet_options' );
}
 /** Step 2 (from text above). */
add_action( 'admin_menu', 'find_tweet_menu' );


/** Step 3. */
function find_tweet_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include('googl.php');
	include('processor.php');
}
 ?>