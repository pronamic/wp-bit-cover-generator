<?php 

global $bit_cover_generator, $bit_cover;

?>
<form method="post" action="">
	<div class="field">
		<span class="label"><?php _e( 'Background crop:', 'bit_cover_generator' ); ?></span>
	
		<?php $attributes = wp_get_attachment_image_src( $bit_cover_generator->background_attachment_id, 'bit-cover-select' ); ?>
	
		<input id="background-attachment-id" name="background-attachment-id" value="<?php echo esc_attr( $bit_cover_generator->background_attachment_id ); ?>" type="hidden" />
	
		<input id="background-width" name="background-width" type="hidden" value="<?php echo esc_attr( $bit_cover->width ); ?>" />
		<input id="background-height" name="background-height" type="hidden" value="<?php echo esc_attr( $bit_cover->height ); ?>" />
		<input id="background-crop-x1" name="background-crop-x1" type="hidden" value="<?php echo esc_attr( $bit_cover->crop_x1 ); ?>" />
		<input id="background-crop-y1" name="background-crop-y1" type="hidden" value="<?php echo esc_attr( $bit_cover->crop_y1 ); ?>" />
		<input id="background-crop-x2" name="background-crop-x2" type="hidden" value="<?php echo esc_attr( $bit_cover->crop_x2 ); ?>" />
		<input id="background-crop-y2" name="background-crop-y2" type="hidden" value="<?php echo esc_attr( $bit_cover->crop_y2 ); ?>" />
	
		<div id="background-area-select-wrap" style="position: relative;">
	 		<img id="background-area-select" src="<?php echo $attributes[0] ?>" />
	 	</div>
	</div>
	
	<div class="field">
		<label for="font-id"><?php _e( 'Font:', 'bit_cover_generator' ); ?></label>
	
		<?php 
		
		$fonts = array(
			'arial' => 'Arial' ,
			'georgia' => 'Georgia' , 
			'goudsabi' => 'Goudsabi' , 
			'impact' => 'Impact' , 
			'times' => 'Times New Roman' , 
			'verdana' => 'Verdana'
		);
		
		?>
		<select id="font-id" name="font-id">
			<?php foreach ( $fonts as $id => $label ): ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $bit_cover_generator->fontId, $id ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	
	<div class="field">
		<label for="title"><?php _e( 'Title:', 'bit_cover_generator' ); ?></label>
		<input id="title" name="title" type="text" maxlength="45" value="<?php echo esc_attr( $bit_cover->title ); ?>" />
	</div>

	<div class="color-field">
		<label for="title-color"><?php _e( 'Color:', 'bit_cover_generator' ); ?></label> 
		<input id="title-color" name="title-color" type="text" value="<?php echo esc_attr( $bit_cover->title_color ); ?>" />
	</div>
	
	<div class="field">
		<label for="eyecatcher-title"><?php _e( 'Eyecatcher title:', 'bit_cover_generator' ); ?></label>
		<input id="eyecatcher-title" name="eyecatcher-title" type="text" maxlength="20" value="<?php echo esc_attr( $bit_cover->eyecatcher_title ); ?>" /> 
	</div>
	
	<div class="field">
		<label for="eyecatcher-text"><?php _e( 'Eyecatcher text:', 'bit_cover_generator' ); ?></label>
		<input id="eyecatcher-text" name="eyecatcher-text" type="text" maxlength="60" value="<?php echo esc_attr( $bit_cover->eyecatcher_text ); ?>" />
	</div>
	
	<div class="color-field">
		<label for="eyecatcher-color"><?php _e( 'Color:', 'bit_cover_generator' ); ?></label>
		<input id="eyecatcher-color" name="eyecatcher-color" type="text" value="<?php echo esc_attr( $bit_cover->eyecatcher_color ); ?>" />
	</div>
	
	<div class="field">
		<label for="footer-title"><?php _e( 'Footer title:', 'bit_cover_generator' ); ?></label>
		<input id="footer-title" name="footer-title" type="text" maxlength="38" value="<?php echo esc_attr( $bit_cover->footer_title ); ?>" /> 
	</div>

	<div class="color-field">
		<label for="footer-title-color"><?php _e( 'Color:', 'bit_cover_generator' ); ?></label>
		<input id="footer-title-color" name="footer-title-color" type="text" value="<?php echo esc_attr( $bit_cover->footer_title_color ); ?>" />
	</div>
	
	<div class="field">
		<label for="footer-text"><?php _e( 'Footer text:', 'bit_cover_generator' ); ?></label>
		<input id="footer-text" name="footer-text" type="text" maxlength="200" value="<?php echo esc_attr( $bit_cover->footer_text); ?>" /> 
	</div>

	<div class="color-field">
		<label for="footer-text-color"><?php _e( 'Color:', 'bit_cover_generator' ); ?></label>	
		<input id="footer-text-color" name="footer-text-color" type="text" value="<?php echo esc_attr( $bit_cover->footer_text_color); ?>" />
	</div>		
	
	<?php if ( $bit_cover_generator->has_errors() ): ?>
	
		<div class="error">
			<ul>
				<?php foreach ( $bit_cover_generator->get_errors() as $error ): ?>
					<li>
						<?php echo $error; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	
	<?php endif; ?>
	
	<p class="action">
		<button type="submit" name="submit"><?php _e( 'Generate cover', 'bit_cover_generator' ); ?></button>
	</p>
</form>