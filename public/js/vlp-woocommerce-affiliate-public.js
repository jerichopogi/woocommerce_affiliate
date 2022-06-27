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


	jQuery(document).ready(function($){
	
		$('.single-product .elementor-button-link').on('click', function(e){
			var nonce = $('input[name="_nonce_count"]').val();
			var count = $('#_count').val();
			var id = $('#_postid').val();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {_nonce_count: nonce,
					action: 'my_action',
					_count: count,
					postid: id 
				},
				success: function( data ) {
					if (data != '' && data != '0'){
						$('#_count').val( data );
						$('#counter').html('Current Count: ' + data);
					}
					else{
						console.log("Error");
					}
				}
			})
		});
	});

})( jQuery );
