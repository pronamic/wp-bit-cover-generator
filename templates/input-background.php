<?php 

global $bit_cover_generator;

?>

<h2>
	<?php _e( 'Step 1 - Choose your background', 'bit_cover_generator' ); ?>
</h2>

<div class="gform_wrapper">
	<form method="post" action="" enctype="multipart/form-data">
		<p>
			<label for="background">
				<?php _e( 'Background image:', 'bit_cover_generator' ); ?>
			</label>
		
			<input id="background" name="background" type="file" />
		</p>
	
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
			<button class="btn important" type="submit" name="upload"><?php _e( 'Upload background', 'bit_cover_generator' ); ?></button>
		</p>
		
		<p class="note"><?php _e( '* It may take time before your background is uploaded.', 'bit_cover_generator' ); ?></p>
	</form>
</div>