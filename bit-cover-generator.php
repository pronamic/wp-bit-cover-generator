<?php
/*
Plugin Name: Bit Cover Generator
Plugin URI: http://pronamic.eu/wp-plugins/bit-cover-generator/
Description: Creates an Bit Cover from visitor input.

Version: 0.1
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: bit_cover_generator
Domain Path: /languages/

License: GPL
*/

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

require_once 'classes/Bit_Cover.php';
require_once 'classes/Bit_Cover_Generator.php';
require_once 'classes/Bit_Cover_Plugin.php';

function bit_cover_generator_init() {
	if ( is_user_logged_in() ) {
		global $bit_cover_generator, $bit_cover;
	
		$bit_cover_generator = new Bit_Cover_Generator();
		$bit_cover_generator->overlay_file = plugin_dir_path( __FILE__ ) . 'includes/images/overlay.png';
		$bit_cover_generator->fonts_dir = plugin_dir_path( __FILE__ ) . 'includes/fonts/';

		$bit_cover = $bit_cover_generator->cover;
	
		$bit_cover_generator->collectInput();
	
		if( isset( $_POST['upload'] ) ) {
			$ok = true;
		
			if($ok) $ok = $bit_cover_generator->is_background_uploaded();
			if($ok) $ok = $bit_cover_generator->is_background_displayable();
			if($ok) $ok = $bit_cover_generator->upload_background_bits();
			if($ok) $ok = $bit_cover_generator->create_background_attachment();
			if($ok) $ok = $bit_cover_generator->determine_crop();
		}
	
		if(isset($_POST['submit'])) {
			$bit_cover_generator->generate();
		}
	}
}

add_action( 'init', 'bit_cover_generator_init' );

function bit_cover_generator_preview() {
	if ( is_user_logged_in() ) {
		global $bit_cover_generator;

		if ( $bit_cover_generator->has_background() ) {
			include 'templates/preview.php';
		}
	}
}

function bit_cover_generator_has_background() {
	global $bit_cover_generator;

	return $bit_cover_generator->has_background();
}

function bit_cover_generator() {
	if ( is_user_logged_in() ) {
		global $bit_cover_generator, $bit_cover;
	
		if ( $bit_cover_generator->has_background() ) {
			include 'templates/input-other.php';
		} else {
			include 'templates/input-background.php';
		}
	}
}

function bit_cover_generator_scripts() {
	wp_enqueue_script(
		'jquery.imgareaselect',
		plugins_url( 'includes/jquery.imgareaselect/scripts/jquery.imgareaselect.js' , __FILE__ ) ,
		array('jquery')
	);

	wp_enqueue_script(
		'really-simple-color-picker',
		plugins_url( 'includes/really-simple-color-picker/jquery.colorPicker.js' , __FILE__ ) ,
		array('jquery')
	);

	wp_enqueue_script(
		'bit-cover-generator',
		plugins_url( 'includes/js/generator.js' , __FILE__ ) ,
		array('jquery', 'jquery.imgareaselect', 'really-simple-color-picker')
	);

	wp_enqueue_style(
		'jquery.imgareaselect',
		plugins_url( 'includes/jquery.imgareaselect/css/imgareaselect-default.css' , __FILE__ ) 
	);

	wp_enqueue_style(
		'bit-cover-generator' ,
		plugins_url( 'includes/css/generator.css' , __FILE__ ) 
	);

	wp_enqueue_style(
		'really-simple-color-picker',
		plugins_url( 'includes/really-simple-color-picker/colorPicker.css' , __FILE__ ) 
	);
}

add_action( 'wp_enqueue_scripts', 'bit_cover_generator_scripts' );

function bit_cover_generator_setup() {
	add_image_size( 'bit-cover-select', 376, 9999 );
}

add_action( 'after_setup_theme', 'bit_cover_generator_setup' );

Bit_Cover_Plugin::bootstrap( __FILE__ );
