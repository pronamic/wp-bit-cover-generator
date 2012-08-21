jQuery(document).ready(function($) {
	function update() {
		$("#preview-cover")
			.removeClass().addClass("font-" + $("#font-id").val());
		$("#preview-title")
			.html($("#title").val())
			.css("color", $("#title-color").val());
		$("#preview-eyecatcher-title")
			.html($("#eyecatcher-title").val())
			.css("color", $("#eyecatcher-color").val());
		$("#preview-eyecatcher-text")
			.html($("#eyecatcher-text").val())
			.css("color", $("#eyecatcher-color").val());
		$("#preview-footer-title")
			.html($("#footer-title").val())
			.css("color", $("#footer-title-color").val());
		$("#preview-footer-text")
			.html($("#footer-text").val())
			.css("color", $("#footer-text-color").val());
	}

	if($("#preview-cover").length > 0) {
		$("#font-id").change(update);
		$("#title").keyup(update);
		$("#title-color").change(update);
		$("#eyecatcher-title").keyup(update);
		$("#eyecatcher-text").keyup(update);
		$("#eyecatcher-color").change(update);
		$("#footer-title").keyup(update);
		$("#footer-title-color").change(update);
		$("#footer-text").keyup(update);
		$("#footer-text-color").change(update);
	
		update();

		$(".color-field input").colorPicker();

		var coverWidth = 575;
		var coverHeight = 733;
	
		var backgroundAreaSelect = $("#background-area-select");
		var previewBackground = $("#preview-background");
		
		var originalWidth = $("#background-width").val();
		var originalHeight = $("#background-height").val();

		function updatePreview(img, selection) { 
			var scaleX = coverWidth / (selection.width || 1); 
			var scaleY = coverHeight / (selection.height || 1); 

			previewBackground.css({
				width: Math.round(scaleX * originalWidth) + "px" , 
				height: Math.round(scaleY * originalHeight) + "px", 
				marginLeft: '-' + Math.round(scaleX * selection.x1) + "px", 
				marginTop: '-' + Math.round(scaleY * selection.y1) + "px"
			}); 
		}
		
		function updateFields(img, selection) {
			$("#background-crop-x1").val(selection.x1);
			$("#background-crop-y1").val(selection.y1);
			$("#background-crop-x2").val(selection.x2);
			$("#background-crop-y2").val(selection.y2);
		}
		
		backgroundAreaSelect.imgAreaSelect({
			aspectRatio: "" + coverWidth + ":" + coverHeight , 
			handles: true , 
			show: true , 
			parent: $("#background-area-select-wrap") , 
			x1: $("#background-crop-x1").val() , y1: $("#background-crop-y1").val() , 
			x2: $("#background-crop-x2").val() , y2: $("#background-crop-y2").val() , 
			imageWidth: originalWidth , 
			imageHeight: originalHeight , 
			onSelectChange: updatePreview , 
			onSelectEnd: updateFields , 
			onInit: updatePreview 
		});
	}
});