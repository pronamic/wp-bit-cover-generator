<?php

/**
 * Title: Bit Cover Plugin
 * Description:
 * Copyright: Copyright (c) 2010 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Bit_Cover_Plugin {
	/**
	 * File
	 * 
	 * @var string
	 */
	public static $file;

	////////////////////////////////////////////////////////////

	/**
	 * Bootstrap
	 * 
	 * @param string $file
	 */
	public static function bootstrap( $file ) {
		self::$file = $file;

		add_action( 'init',           array( __CLASS__, 'init' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		// Text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'bit_cover_generator', false, $rel_path );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'bit_cover_generator', // $id
			__( 'Bit Cover Generator', 'bit_cover_generator' ), // $title
			array( __CLASS__, 'meta_box' ), // $callback
			'entry' // $post_type
		);
	}

	/**
	 * Meta box
	 * 
	 * @param array $post
	 */
	public static function meta_box( $post ) {
		include plugin_dir_path( self::$file ) . 'admin/meta-box.php';
	}
}
