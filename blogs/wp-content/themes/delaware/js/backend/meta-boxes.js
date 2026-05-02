jQuery( document ).ready( function( $ ) {
	"use strict";

	// Show/hide settings for post format when choose post format
	var $format = $( '#post-formats-select' ).find( 'input.post-format' ),
		$formatBox = $( '#post-format-settings' );

    $format.on( 'change', function () {
        var type = $(this).filter(':checked').val();
        postFormatSettings(type);
    } );
    $format.filter( ':checked' ).trigger( 'change' );

    $(document.body).on('change', '.editor-post-format .components-select-control__input', function () {
        var type = $(this).val();
        postFormatSettings(type);
    });

    $(window)ccfunction () {
        var $el = $(document.body).find('.editor-post-format .components-select-control__input'),
            type = $el.val();
        postFormatSettings(type);
    });

    function postFormatSettings(type) {
        $formatBox.hide();
        if ($formatBox.find('.rwmb-field').hasClass(type)) {
            $formatBox.show();
        }

        $formatBox.find('.rwmb-field').slideUp();
        $formatBox.find('.' + type).slideDown();
    }

    // Show/hide settings for template settings
    $( '#page_template' ).on( 'change', function () {

        pageHeaderSettings($(this));

    } ).trigger( 'change' );

    $(document.body).on('change', '.editor-page-attributes__template .components-select-control__input', function () {
        pageHeaderSettings($(this));
    });

    $(window).on('load', function () {
        var $el = $(document.body).find('.editor-page-attributes__template .components-select-control__input');
        pageHeaderSettings($el);
    });

    function pageHeaderSettings($el) {

        if ($el.val() == 'template-homepage.php') {
            $('#page-header-settings').find('.hide-homepage').slideUp();
        } else {
            $('#page-header-settings').find('.hide-homepage').slideDown();
        }
    }
	// Show/hide settings for custom layout settings
	$( '#custom_layout' ).on( 'change', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.rwmb-field.custom-layout' ).slideDown();
		}
		else {
			$( '.rwmb-field.custom-layout' ).slideUp();
		}
	} ).trigger( 'change' );
} );
