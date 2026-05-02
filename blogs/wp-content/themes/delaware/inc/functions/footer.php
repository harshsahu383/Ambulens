<?php

/**
 *  Display footer widget
 */
function delaware_footer_widgets() {
	$footer_title_style = delaware_get_option( 'footer_widget_title_style' );
	$text_color         = delaware_get_option( 'footer_widget_text_color' );
	$class_footer       = '';

	if ( $footer_title_style ) {
		$class_footer .= ' widget-title-style';
	}

	$class_footer .= ' text-' . $text_color;

	if ( is_active_sidebar( 'footer-sidebar-1' ) == false &&
	     is_active_sidebar( 'footer-sidebar-2' ) == false &&
	     is_active_sidebar( 'footer-sidebar-3' ) == false &&
	     is_active_sidebar( 'footer-sidebar-4' ) == false
	) {
		return '';
	}

	?>

    <div id="footer-widgets" class="footer-widgets widgets-area <?php echo esc_attr( $class_footer ); ?>">
        <div class="container">
            <div class="row-flex">

				<?php
				$columns = max( 1, absint( delaware_get_option( 'footer_widget_columns' ) ) );

				$col_class = 'col-flex-xs-12 col-flex-sm-6 col-flex-md-6 col-flex-lg-' . floor( 12 / $columns );
				for ( $i = 1; $i <= $columns; $i ++ ) :
					?>
                    <div class="footer-sidebar footer-<?php echo esc_attr( $i ) ?> <?php echo esc_attr( $col_class ) ?>">
						<?php ob_start();
						dynamic_sidebar( "footer-sidebar-$i" );
						$output = ob_get_clean();
						echo apply_filters( 'delaware_footer_widget_content', $output, $i ); ?>
                    </div>
				<?php endfor; ?>

            </div>
        </div>
    </div>
	<?php
}

/**
 *  Display site footer
 */
function delaware_footer_copyright() {
	$bg    = delaware_get_option( 'footer_widget_background_color' );
	$style = '';

	if ( $bg ) {
		$style = 'background-color:' . $bg . ';';
	}

	?>
    <div class="footer-copyright" style="<?php echo esc_attr( $style ) ?>">
        <div class="container footer-info">
            <div class="row-flex">
				<?php
				if ( intval( delaware_get_option( 'footer_bottom_show_logo' ) ) ) {
					$class_col = 'col-flex-md-5 col-flex-xs-12';
				} else {
					$class_col = 'col-flex-md-6 col-flex-xs-12';
				}
				?>
                <div class="<?php echo esc_attr( $class_col ); ?>">
                    <div class="footer-copyright">
						<?php echo do_shortcode( wp_kses( delaware_get_option( 'footer_copyright' ), wp_kses_allowed_html( 'post' ) ) ); ?>
                    </div>
                </div>
				<?php
				if ( intval( delaware_get_option( 'footer_bottom_show_logo' ) ) ) :
					?>
                    <div class="col-flex-md-2 col-flex-xs-12">
                        <img src="<?php echo esc_attr( delaware_get_option( 'footer_bottom_logo_image' ) ); ?>"
                             alt="<?php esc_attr_e( 'footer logo', 'delaware' ); ?>">
                    </div>
				<?php endif; ?>
                <div class="<?php echo esc_attr( $class_col ); ?>">
                    <div class="text-right menu-bottom">
						<?php
						if ( has_nav_menu( 'footer-bottom' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'footer-bottom',
									'depth'          => 1
								)
							);
						}
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function delaware_get_footer_custom_css() {
	$border_color     = delaware_get_option( 'footer_border_color' );
	$copyright_bg     = delaware_get_option( 'footer_copyright_background_color' );
	$copyright_border = intval( delaware_get_option( 'footer_copyright_border_top' ) );

	$css = '';

	if ( $border_color ) {
		$css .= '.footer-widgets .newsletter-style-2 .mc4wp-form-fields input[type="email"],
		 		.footer-widgets .mc4wp-form-fields input[type="email"],
		 		.footer-widgets .footer-sidebar article,
		 		.footer-widgets .footer-sidebar .wpcf7-form input[type="text"],
		 		.footer-widgets .footer-sidebar .wpcf7-form input[type="email"],
		 		.footer-widgets .footer-sidebar .wpcf7-form select,
		 		.footer-widgets .footer-sidebar .wpcf7-form textarea,
		 		.footer-widgets.widget-title-style .footer-sidebar .widget-title
		 		{ border-color: ' . $border_color . '; }';

		$css .= '.site-footer .footer-info ul li:before { background-color: ' . $border_color . '; }';
	}

	if ( $copyright_border && $border_color ) {
		$css .= '.site-footer .footer-info { border-top: 1px solid ' . $border_color . '; }';
	}

	if ( $copyright_bg ) {
		$css .= '.site-footer .footer-copyright { background-color: ' . $copyright_bg . '; }';
	}

	return $css;
}