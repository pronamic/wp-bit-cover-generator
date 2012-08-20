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

require_once 'classes/Bit_Cover_Generator.php';

function bit_cover_generator() {
	global $bit_cover_generator;

	$bit_cover_generator = new Bit_Cover_Generator();

	if( isset( $_POST['upload'] ) ) {
		$ok = $bit_cover_generator->check_background_upload();

		var_dump( $ok );
	}

	include 'templates/input-background.php';
}


