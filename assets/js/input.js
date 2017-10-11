(function($){
	
	
	function initialize_field( $el ) {
		var permittedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ";
		$el.on('keypress', '.geocoded-postcode__field', function(e) {
			return permittedChars.indexOf(String.fromCharCode(e.charCode)) !== -1;
		});

		$el.on('blur', '.geocoded-postcode__field', function(e) {
			var $input = $(this),
					postcode = $input.val();

			if ( postcode.length > 0 ) {
				$.getJSON('https://api.postcodes.io/postcodes/' + postcode + '/validate', function(response) {
					if (response.result) {
						$input.removeClass('geocoded-postcode__field--invalid', );
					} else {
						$input.addClass('geocoded-postcode__field--invalid', );
					}
				});
			}
		});
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'geocoded-postcode'
			acf.get_fields({ type : 'geocoded-postcode'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			$(postbox).find('.field[data-field_type="geocoded-postcode"]').each(function(){
				initialize_field( $(this) );
			});
		
		});
	
	
	}


})(jQuery);
