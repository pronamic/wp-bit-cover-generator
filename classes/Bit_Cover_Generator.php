<?php

/**
 * Title: Bit cover generator
 * Description:
 * Copyright: Copyright (c) 2010 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Bit_Cover_Generator {
	/**
	 * Errors
	 * 
	 * @var array
	 */
	private $errors;

	////////////////////////////////////////////////////////////

	/**
	 * Constructs and initailizes an Bit cover generator
	 */
	public function __construct() {
		$this->errors = array();
	}

	////////////////////////////////////////////////////////////

	/**
	 * Check if there have errors occuried
	 * 
	 * @return boolean true if errors, false otherwise
	 */
	public function has_errors() {
		return ! empty( $this->errors );
	}

	/**
	 * Get errors
	 * 
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Add an error
	 * 
	 * @param string $error
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Check background upload
	 * 
	 * @return boolean true if no errors, false otherwise
	 */
	public function check_background_upload() {
		$result = false;

		if ( isset( $_FILES['background'] ) ) {
			$this->background_file = $_FILES['background'];

			if ( $this->background_file['error'] == UPLOAD_ERR_OK ) {
				$result = true;
			} else {
				switch ( $this->background_file['error'] ) {
					case UPLOAD_ERR_INI_SIZE :
						$this->add_error( __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'bit_cover_generator' ) );
						break;
					case UPLOAD_ERR_FORM_SIZE : 
						$this->add_error( __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.', 'bit_cover_generator' ) );
						break;
					case UPLOAD_ERR_PARTIAL :
						$this->add_error( __( 'The uploaded file was only partially uploaded.', 'bit_cover_generator' ) );
						break;
					case UPLOAD_ERR_NO_FILE : 
						$this->add_error( __( 'No file was uploaded.', 'bit_cover_generator' ) );
						break;
					case UPLOAD_ERR_NO_TMP_DIR : 
						$this->add_error( __( 'Missing a temporary folder.', 'bit_cover_generator' ) );
						break;
					case UPLOAD_ERR_CANT_WRITE : 
						$this->addError( __( 'Failed to write file to disk.', 'bit_cover_generator' ) );
						break;
					case UPLOAD_ERR_CANT_WRITE : 
						$this->add_error( __( 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.', 'bit_cover_generator' ) );
						break;
					default : 
						$this->add_error( __( 'No file was uploaded.', 'bit_cover_generator' ) );
						break;
				}				
			}
		} else {
			$this->add_error(__('The background image is not specified.', 'bit_cover_generator' ) );
		}

		return $result;
	}
}
