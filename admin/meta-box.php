<dl>
	<dt><?php _e( 'Font ID', 'bit_cover_generator' ); ?></dt>
	<dd><?php echo get_post_meta( $post->ID, '_bit_cover_font_id', true ); ?></dd>

	<dt><?php _e( 'Title', 'bit_cover_generator' ); ?></dt>
	<dd><?php echo get_post_meta( $post->ID, '_bit_cover_title', true ); ?></dd>

	<dt><?php _e( 'Title Color', 'bit_cover_generator' ); ?></dt>
	<dd><?php echo get_post_meta( $post->ID, '_bit_cover_title_color', true ); ?></dd>

	<dt><?php _e( 'Footer', 'bit_cover_generator' ); ?></dt>
	<dd>
		<dl>
		<dt><?php _e( 'Title', 'bit_cover_generator' ); ?></dt>
		<dd><?php echo get_post_meta( $post->ID, '_bit_cover_eyecatcher_title', true ); ?></dd>
	
		<dt><?php _e( 'Text', 'bit_cover_generator' ); ?></dt>
		<dd><?php echo get_post_meta( $post->ID, '_bit_cover_eyecatcher_text', true ); ?></dd>
	
		<dt><?php _e( 'Color', 'bit_cover_generator' ); ?></dt>
		<dd><?php echo get_post_meta( $post->ID, '_bit_cover_eyecatcher_color', true ); ?></dd>
		</dl>
	</dd>

	<dt><?php _e( 'Footer', 'bit_cover_generator' ); ?></dt>
	<dd>
		<dl>
			<dt><?php _e( 'Title', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_footer_title', true ); ?></dd>
		
			<dt><?php _e( 'Title Color', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_footer_title_color', true ); ?></dd>
		
			<dt><?php _e( 'Text', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_footer_text', true ); ?></dd>
		
			<dt><?php _e( 'Text Color', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_footer_text_color', true ); ?></dd>
		</dl>
	</dd>

	<dt><?php _e( 'Crop', 'bit_cover_generator' ); ?></dt>
	<dd>
		<dl>
			<dt><?php _e( 'X1', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_crop_x1', true ); ?></dd>

			<dt><?php _e( 'Y1', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_crop_y1', true ); ?></dd>

			<dt><?php _e( 'X2', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_crop_x2', true ); ?></dd>

			<dt><?php _e( 'Y2', 'bit_cover_generator' ); ?></dt>
			<dd><?php echo get_post_meta( $post->ID, '_bit_cover_crop_y2', true ); ?></dd>
		</dl>
	</dd>
</dl>