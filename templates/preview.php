<?php 

global $bit_cover_generator;

?>
<div id="preview-cover">
	<?php $attributes = wp_get_attachment_image_src( $bit_cover_generator->background_attachment_id, 'full'); ?>

	<div id="preview-background-box">
		<img id="preview-background" src="<?php echo $attributes[0] ?>" />
	</div>

	<div id="preview-overlay"></div>

	<div id="preview-title"></div>
	<div id="preview-eyecatcher">
		<div id="preview-eyecatcher-title"></div>
		<div id="preview-eyecatcher-text"></div>
	</div>
	<div id="preview-footer">
		<div id="preview-footer-title"></div>
		<div id="preview-footer-text"></div>
	</div>
</div>