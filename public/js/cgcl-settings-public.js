(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	//console.log(cgcl_ajax_object);

	loadURLToInputField(cgcl_ajax_object.coc_image);

	function loadURLToInputField(url) {
		getImgURL(url, (imgBlob) => {
			// Load img blob to input
			// WIP: UTF8 character error
			let fileName = 'hasFilename.jpg';
			let file = new File(
				[imgBlob],
				fileName,
				{ type: 'image/jpeg', lastModified: new Date().getTime() },
				'utf-8'
			);
			let container = new DataTransfer();
			container.items.add(file);
			document.querySelector('#file_input').files = container.files;
		});
	}
	// xmlHTTP return blob respond
	function getImgURL(url, callback) {
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			callback(xhr.response);
		};
		xhr.open('GET', url);
		xhr.responseType = 'blob';
		xhr.send();
	}
})( jQuery );