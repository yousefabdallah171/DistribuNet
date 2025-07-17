jQuery(document).ready(function($){
	"use strict";
	var guido_upload;
	var guido_selector;

	function guido_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		guido_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( guido_upload ) {
			guido_upload.open();
			return;
		} else {
			// Create the media frame.
			guido_upload = wp.media.frames.guido_upload =  wp.media({
				// Set the title of the modal.
				title: "Select Image",

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: "Selected",
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			guido_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = guido_upload.state().get('selection').first();

				guido_upload.close();
				guido_selector.find('.upload_image').val(attachment.attributes.url).change();
				if ( attachment.attributes.type == 'image' ) {
					guido_selector.find('.guido_screenshot').empty().hide().prepend('<img src="' + attachment.attributes.url + '">').slideDown('fast');
				}
			});

		}
		// Finally, open the modal.
		guido_upload.open();
	}

	function guido_remove_file(selector) {
		selector.find('.guido_screenshot').slideUp('fast').next().val('').trigger('change');
	}
	
	$('body').on('click', '.guido_upload_image_action .remove-image', function(event) {
		guido_remove_file( $(this).parent().parent() );
	});

	$('body').on('click', '.guido_upload_image_action .add-image', function(event) {
		guido_add_file(event, $(this).parent().parent());
	});

});