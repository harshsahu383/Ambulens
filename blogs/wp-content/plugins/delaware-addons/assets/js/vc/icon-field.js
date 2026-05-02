!function($) {
	$('body').on( 'click', '.icon-selector span.vc_svg_icon', function(e) {
		e.preventDefault();
		var $el = $( this ),
			icon = $el.data( 'icon' ),
			name = $el.data( 'name' );

		$el.closest( 'div' ).prev( 'input.svg_icons_field' ).val( name ).siblings( '.preview-icon' ).children( 'img' ).attr( 'src', icon );
		$el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
	} );

	$( 'body' ).on( 'keyup','.icon-search', function() {
		var search = $( this ).val(),
			$icons = $( this ).siblings( '.icon-selector' ).children();

			if ( !search ) {
				$icons.show();
				return;
			}

			$icons.hide().filter( function() {
				return $( this ).data( 'icon' ).indexOf( search ) >= 0;
			} ).show();
	} );
}(window.jQuery);
