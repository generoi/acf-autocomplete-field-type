(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field( $field ) {
	
		$(':text:visible',$field).autocomplete({
			source: function( request, response  ){
				
				if(!request.term.trim().length)
					response( [] );
				console.log($field);
				$.getJSON( ajaxurl, { 
						'action' : 'autocomplete_ajax',
						'field_key' : $field.data('key'),
						'request' : request.term.trim()
					}, function( data ){
					
					response( data );
					
				});
			}
		});
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		// acf.add_action('ready append', function( $field ){
			
		// 	// search $el for fields of type 'autocomplete'
		// 	acf.get_fields({ type : 'autocomplete'}, $field).each(function(){
				
		// 		initialize_field( $(this) );
				
		// 	});
			
		// });
		acf.add_action('ready_field/type=autocomplete', initialize_field);
		acf.add_action('append_field/type=autocomplete', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="autocomplete"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}

})(jQuery);
