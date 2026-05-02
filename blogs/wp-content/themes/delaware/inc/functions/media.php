<?php
/**
 * Custom functions for images, audio, videos.
 *
 * @package Delaware
 */

/**
 * Register fonts
 *
 * @since  1.0.0
 *
 * @return string
 */
function delaware_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by Montserrat, translate this to 'off'. Do not translate
    * into your own language.
    */
    if ( 'off' !== _x( 'on', 'Rubik font: on or off', 'delaware' ) ) {
        $font_families[] = 'Rubik:300,400,500,600,700';
    }

    if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'delaware' ) ) {
        $font_families[] = 'Poppins:300,400,500,600,700';
    }

    if ( ! empty( $font_families ) ) {
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );

        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url_raw( $fonts_url );
}
