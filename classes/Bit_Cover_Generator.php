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
	const GENERATE_WIDTH = 1200;
	
	const GENERATE_HEIGHT = 1564;

	/**
	 * Errors
	 * 
	 * @var array
	 */
	private $errors;

	////////////////////////////////////////////////////////////

	/**
	 * The cover to generate
	 * 
	 * @var Bit_Cover
	 */
	public $cover;

	////////////////////////////////////////////////////////////

	/**
	 * Constructs and initailizes an Bit cover generator
	 */
	public function __construct() {
		$this->cover = new Bit_Cover();

		$this->user = wp_get_current_user();

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
	public function is_background_uploaded() {
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
						$this->add_error( __( 'Failed to write file to disk.', 'bit_cover_generator' ) );
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
	
	public function is_background_displayable() {
		$result = false;

		$path = $this->background_file['tmp_name'];

		$result = file_is_displayable_image( $path );

		if ( ! $result ) {
			$this->add_error( __('We can not work with the background you uploaded.', 'bit_cover_generator' ) );
		}

		return $result;
	}

	public function upload_background_bits() {
		$result = false;

		$bits = file_get_contents( $this->background_file['tmp_name'] );

		$this->background_name = $this->background_file['name'];
		
		$upload_info = wp_upload_bits( $this->background_name, null, $bits );
				
		if ( $upload_info['error'] === false ) {
			$this->background_info = $upload_info;

			$result = true;
		} else {
			$this->add_error( __('Somehting went wrong while uploading the background image.', 'bitcoveractie' ) );
			$this->add_error( $upload_info['error'] );
		}

		return $result;
	}
	
	public function create_background_attachment() {
		$path = $this->background_info['file'];

		$file_type = wp_check_filetype( $path );

		$data = array(
			'post_title' => $this->background_name ,
			'post_mime_type' => $file_type['type'] , 
			'guid' => $path ,
			'post_content' => ''
		);

		$this->background_attachment_id = wp_insert_attachment( $data, $path );

		$meta_data = wp_generate_attachment_metadata( $this->background_attachment_id, $path );

		$updated = wp_update_attachment_metadata( $this->background_attachment_id,  $meta_data );
		
		return true;
	}

	public function determine_crop() {
		$path = $this->background_info['file'];

		$size = getimagesize( $path );

		if($size !== false) {
			$this->cover->width = $size[0];
			$this->cover->height = $size[1];

			$ratio = self::GENERATE_WIDTH / self::GENERATE_HEIGHT;
			$upload_ratio = $this->cover->width / $this->cover->height;

			$this->cover->crop_x1 = 0;
			$this->cover->crop_y1 = 0;

			if ( $this->cover->width > $this->cover->height || $upload_ratio > $ratio ) {
				$this->cover->crop_x2 = round( $this->cover->height * $ratio );
				$this->cover->crop_y2 = round( $this->cover->height ); 
			} else {
				$this->cover->crop_x2 = round( $this->cover->width );
				$this->cover->crop_y2 = round( $this->cover->width / $ratio );
			}
		}
	}

	public function has_background() {
		return isset( $this->background_attachment_id );
	}

	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////

	public function generate() {
		$ok = true;

		if($ok) { $ok = $this->checkInput(); }
		if($ok) { $ok = $this->createCoverPost(); }
		if($ok) { $ok = $this->bindBackgroundToCover(); }
		if($ok) { $ok = $this->addCoverMeta(); }
		if($ok) { $ok = $this->createCanvas(); }
		if($ok) { $ok = $this->addBackgroundToCanvas(); }
		if($ok) { $ok = $this->addOverlayToCanvas(); }
		if($ok) { $ok = $this->setFont(); }
		if($ok) { $ok = $this->addTitleToCanvas(); }
		if($ok) { $ok = $this->addEyecatcherToCanvas(); }
		if($ok) { $ok = $this->addFooterToCanvas(); }
		if($ok) { $ok = $this->uploadCoverBits(); }
		if($ok) { $ok = $this->createCoverAttachment(); }
		if($ok) { $ok = $this->setCoverThumbnail(); }
		if($ok) { $ok = $this->destroyCanvas(); }
		if($ok) { $ok = $this->redirect(); }
	}

	public function collectInput() {
		$this->cover->font_id = filter_input(INPUT_POST, 'font-id', FILTER_SANITIZE_STRING);
		$this->cover->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		$this->cover->title_color = filter_input(INPUT_POST, 'title-color', FILTER_SANITIZE_STRING);
		if ( empty( $this->cover->title_color ) ) {
			$this->cover->title_color = '#000000';
		}
		$this->cover->eyecatcher_title = filter_input(INPUT_POST, 'eyecatcher-title', FILTER_SANITIZE_STRING);
		$this->cover->eyecatcher_text = filter_input(INPUT_POST, 'eyecatcher-text', FILTER_SANITIZE_STRING);
		$this->cover->eyecatcher_color = filter_input(INPUT_POST, 'eyecatcher-color', FILTER_SANITIZE_STRING);
		if ( empty( $this->cover->eyecatcher_color ) ) {
			$this->cover->eyecatcher_color = '#FFFFFF';
		}
		$this->cover->footer_title = filter_input(INPUT_POST, 'footer-title', FILTER_SANITIZE_STRING);
		$this->cover->footer_title_color = filter_input(INPUT_POST, 'footer-title-color', FILTER_SANITIZE_STRING);
		if( empty( $this->footer_title_color ) ) {
			$this->footer_title_color = '#FFFFFF';
		}
		$this->cover->footer_text = filter_input(INPUT_POST, 'footer-text', FILTER_SANITIZE_STRING);
		$this->cover->footer_text_color = filter_input(INPUT_POST, 'footer-text-color', FILTER_SANITIZE_STRING);
		if ( empty( $this->cover->footer_text_color ) ) {
			$this->footer_text_color = '#FFFFFF';
		}
		$this->background_attachment_id = filter_input(INPUT_POST, 'background-attachment-id', FILTER_SANITIZE_STRING);
		$this->cover->width = filter_input(INPUT_POST, 'background-width', FILTER_SANITIZE_NUMBER_INT);
		$this->cover->height = filter_input(INPUT_POST, 'background-height', FILTER_SANITIZE_NUMBER_INT);
		$this->cover->crop_x1 = filter_input(INPUT_POST, 'background-crop-x1', FILTER_SANITIZE_NUMBER_INT);
		$this->cover->crop_y1 = filter_input(INPUT_POST, 'background-crop-y1', FILTER_SANITIZE_NUMBER_INT);
		$this->cover->crop_x2 = filter_input(INPUT_POST, 'background-crop-x2', FILTER_SANITIZE_NUMBER_INT);
		$this->cover->crop_y2 = filter_input(INPUT_POST, 'background-crop-y2', FILTER_SANITIZE_NUMBER_INT);

		return true;
	}

	public function checkInput() {
		$result = true;

		if(empty($this->cover->title)) {
			$result = false;

			$this->add_error( __('The title can not be empty', 'bitcoveractie') );
		}

		return $result;
	}

	private function createCoverPost() {
		$result = false;

 		$coverData = array(
 			'post_title' => $this->cover->title ,
 			'post_content' => '' , 
 			'post_status' => 'publish' , 
 			'post_author' => $this->user->ID,
 			'post_type' => 'entry' , 
 			'comment_status' => 'closed'
		);

		$result = wp_insert_post($coverData, true);
		
		if(is_wp_error($result)) {
			$result = false;
		} else {
			$this->coverId = $result;

			$result = true;
		}

		return $result;
	}
	
	private function bindBackgroundToCover() {
		$attachment = array(
			'ID' => $this->background_attachment_id ,
			'post_parent' => $this->coverId
		);

		$result = wp_update_post($attachment);

		return $result !== 0;
	}

	private function addCoverMeta() {
		$id = $this->coverId;

		update_post_meta($id, '_bit_cover_font_id', $this->cover->font_id );

		update_post_meta($id, '_bit_cover_title', $this->cover->title );
		update_post_meta($id, '_bit_cover_title_color', $this->cover->title_color );
						
		update_post_meta($id, '_bit_cover_eyecatcher_title', $this->cover->eyecatcher_title );
		update_post_meta($id, '_bit_cover_eyecatcher_text', $this->cover->eyecatcher_text);
		update_post_meta($id, '_bit_cover_eyecatcher_color', $this->cover->eyecatcher_color);

		update_post_meta($id, '_bit_cover_footer_title', $this->cover->footer_title);
		update_post_meta($id, '_bit_cover_footer_title_color', $this->cover->footer_title_color);	
		update_post_meta($id, '_bit_cover_footer_text', $this->cover->footer_text);
		update_post_meta($id, '_bit_cover_footer_text_color', $this->cover->footer_text_color);

		update_post_meta($id, '_bit_cover_crop_x1', $this->cover->crop_x1);
		update_post_meta($id, '_bit_cover_crop_y1', $this->cover->crop_y1);
		update_post_meta($id, '_bit_cover_crop_x2', $this->cover->crop_x2);
		update_post_meta($id, '_bit_cover_crop_y2', $this->cover->crop_y2);
		
		update_post_meta($id, '_bit_cover_votes', 0);

		return true;
	}

	private function createCanvas() {
		$this->canvas = imagecreatetruecolor(self::GENERATE_WIDTH, self::GENERATE_HEIGHT);

		return true;
	}

	private function destroyCanvas() {
		imagedestroy($this->canvas);

		return true;
	}

	private function addBackgroundToCanvas() {
		$path = get_attached_file($this->background_attachment_id);

		$background = imagecreatefromjpeg($path);

		$cropX = $this->cover->crop_x1;
		$cropY = $this->cover->crop_y1;
		$cropWidth = $this->cover->crop_x2 - $this->cover->crop_x1;
		$cropHeight = $this->cover->crop_y2 - $this->cover->crop_y1;
		
		imagecopyresampled(
			$this->canvas , 
			$background , 
			0 , 0 , // Destination X and Y
			$cropX , $cropY , // Source X and Y
			self::GENERATE_WIDTH , self::GENERATE_HEIGHT , // Destination width and height 
			$cropWidth , $cropHeight // Source width and height
		);

		imagedestroy($background);

		return true;
	}

	private function addOverlayToCanvas() {
		$path = $this->overlay_file;

		$overlay = imagecreatefrompng($path);

		$width = imagesx($overlay);
		$height = imagesy($overlay);
									
		imagecopyresampled(
			$this->canvas , 
			$overlay , 
			0 , 0 , 0 , 0 , 
			self::GENERATE_WIDTH , self::GENERATE_HEIGHT , 
			$width , $height
		);
									
		imagedestroy($overlay);

		return true;
	}
	
	private function getColor($hex) {
		if(substr($hex, 0, 1) == '#') {
			$hex = substr($hex, 1);
		}

		$red = hexdec(substr($hex, 0, 2));
		$green = hexdec(substr($hex, 2, 2));
		$blue = hexdec(substr($hex, 4, 2));

		return imagecolorallocate($this->canvas, $red, $green, $blue);
	}

	private function setFont() {
		$path = $this->fonts_dir . $this->cover->font_id . '.ttf';

		if(!is_readable($path)) {
			$path = $this->fonts_dir . 'arial.ttf';
		}

		$this->font = realpath($path);

		return true;
	}

	private function addTitleToCanvas() {
		$color = $this->getColor($this->cover->title_color);
		$size = 30;
		$angle = 0;

		$box = imagettfbbox($size, $angle, $this->font, $this->cover->title);

		$x = $box[0] + (self::GENERATE_WIDTH / 2) - ($box[4] / 2);
		$y = 50;

		imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $this->cover->title);
		
		return true;
	}

	private function addEyecatcherToCanvas() {
		// Title
		$shadowColor = $this->getColor('#000000');
		$color = $this->getColor($this->cover->eyecatcher_color);
		$size = 40;
		$angle = 0;
		$x = 500;
		$y = 650;

		imagettftext($this->canvas, $size, $angle, $x - 1, $y - 1, $shadowColor, $this->font, $this->cover->eyecatcher_title);
		imagettftext($this->canvas, $size, $angle, $x,     $y    , $color, $this->font, $this->cover->eyecatcher_title);

		// Text
		$size = 30;
		$x = $x;
		$y = $y + 50;

		$rows = str_split($this->cover->eyecatcher_text, 30);

		if(isset($rows[0])) {
			imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $rows[0]);
		}
		if(isset($rows[1])) {
			$y += 40;
			imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $rows[1]);
		}

		return true;
	}

	private function addFooterToCanvas() {
		// Title
		$shadowColor = $this->getColor('#000000');
		$color = $this->getColor($this->cover->footer_title_color);
		$size = 35;
		$angle = 0;
		$x = 60;
		$y = 1285;
				
		imagettftext($this->canvas, $size, $angle, $x - 1, $y - 1, $shadowColor, $this->font, $this->cover->footer_title);
		imagettftext($this->canvas, $size, $angle, $x    , $y    , $color, $this->font, $this->cover->footer_title);

		// Text
		$color = $this->getColor($this->cover->footer_text_color);
		$size = 25;
		$angle = 0;
		$x = 60;
		$y = 1325;

		$rows = str_split($this->cover->footer_text, 50);

		if(isset($rows[0])) {
			imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $rows[0]);
		}
		if(isset($rows[1])) {
			$y += 40;
			imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $rows[1]);
		}
		if(isset($rows[2])) {
			$y += 40;
			imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $rows[2]);
		}
		if(isset($rows[3])) {
			$y += 40;  
			imagettftext($this->canvas, $size, $angle, $x, $y, $color, $this->font, $rows[3]);
		}

		return true;
	}

	private function uploadCoverBits() {
		$result = false;

		ob_start();
		imagepng($this->canvas);
		$bits = ob_get_clean();

		$this->canvasInfo = wp_upload_bits('cover.png', null, $bits);

		$result = $this->canvasInfo['error'] === false;

		return $result;
	}
	
	private function createCoverAttachment() {
		$path = $this->canvasInfo['file'];

		$fileType = wp_check_filetype($path);

		$attachmentData = array(
			'post_title' => $this->background_name ,
			'post_mime_type' => $fileType['type'] , 
			'guid' => $path ,
			'post_parent' => $this->coverId , 
			'post_content' => ''
		);

		$this->coverAttachmentId = wp_insert_attachment($attachmentData, $path , $this->coverId);
		$metaData = wp_generate_attachment_metadata($this->coverAttachmentId, $path);
		$updated = wp_update_attachment_metadata($this->coverAttachmentId,  $metaData);
		
		return true;
	}

	private function setCoverThumbnail() {
		set_post_thumbnail($this->coverId, $this->coverAttachmentId);
		
		return true;
	}

	private function redirect() {
		if(empty($this->errors)) {
			$url = get_permalink($this->coverId);

			// See other
			wp_redirect($url, 303);
		
			exit;
		}

		return true;
	}
	
}
