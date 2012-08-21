<?php 

global $bit_cover_generator;

?>
<form method="post" action="" enctype="multipart/form-data">
	<p>
		<label for="background">
			<?php _e( 'Choose your background:', 'bit_cover_generator' ); ?>
		</label>
	
		<input id="background" name="background" type="file" />
	</p>
	
	<p>Het kan even duren voordat je achtergrond geupload is.</p>
	
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
		<button type="submit" name="upload"><?php _e( 'Upload background', 'bit_cover_generator' ); ?></button>
	</p>
</form>