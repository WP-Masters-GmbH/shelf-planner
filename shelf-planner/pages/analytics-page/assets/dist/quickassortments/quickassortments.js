( function( $ ) {
	'use strict';
	$( function() {
		$( '#the-list' ).on( 'click', '.editinline', function() {
			var cp = $( this )
				.closest( 'tr' )
				.find( '.cost_price.column-cost_price' )
				.text()
				.replace(/\D/,'');

			$( 'input[name="_qa_cog_cost"]', '.inline-edit-row' ).val( cp );
		} );
	} );

	// QA Retail Insights multiple select
	$( '.qa-wrap #qa_cog_ri_settings_excluded_order_status' ).select2( {
		multiselect: true,
		placeholder: 'Please select and option...'
	} );

} )( jQuery );

// var post_id = $( this ).closest( 'tr' ).attr( 'id' );
// post_id = post_id.replace( 'post-', '' );
