(function($) {

	$(document).ready( function() {

		$('body').on( 'update_price', function() {
			$('.wceb_custom_fields').slideDown();
			$('.single_add_to_cart_button').show();
		});

		$('body').on('reset_image found_variation', '.variations_form', function( e, variation ) {
			$('.wceb_custom_fields').slideUp();
			$('.single_add_to_cart_button').hide();
		});

				
	});

})(jQuery);