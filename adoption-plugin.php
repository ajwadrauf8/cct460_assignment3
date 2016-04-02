<?php
/* 
 * Plugin Name: Group Plugin Assignment
 * Plugin URI: https://phoenix.sheridanc.on.ca/~ccit3427/
 * Description: Showing how  our cool  widget work.
 * Author: Yahya Al-Mashni, Ajwad Rauf, Benjamin Sin
 * Assignment 2: Custom Plugin
 * Author URI: https://phoenix.sheridanc.on.ca/~ccit3427/
 * Version: 1.0 
 */

// Launch the plugin.
function custom_post_adoption_init() {
	add_action( 'widgets_init', 'custom_adoption_widget_load_widgets' );
}
add_action( 'plugins_loaded', 'custom_post_adoption_init' );

// Load plugin textdomain.
function custom_adoption_textdomain() {
	load_plugin_textdomain( 'adoption-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'custom_adoption_textdomain' );

// Loads the widgets packaged with the plugin.
function custom_adoption_widget_load_widgets() {
	require_once( 'adoption-post-widget.php' );
	register_widget( 'adoption_post_widget' );
}

function enqueue_scripts() {

	wp_enqueue_script( 'adoption-slider', plugins_url( '/js/lider.js', __FILE__ ));
	wp_enqueue_script( 'JQ', plugins_url( '/js/jquery.min.js', __FILE__ ));
	wp_enqueue_script( 'JQ2', plugins_url( '/js/jquery-ui.min.js', __FILE__ ));
	wp_enqueue_style( 'styles', get_stylesheet_uri() );

}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );


require_once( 'adoption-box.php' );
require_once( 'pop-adoption.php' );
require_once( 'notice-adoption.php' );