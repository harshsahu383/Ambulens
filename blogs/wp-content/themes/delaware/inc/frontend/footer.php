<?php
/**
 *  Display top footer widget
 */
function delaware_top_footer_widgets() {
	if ( ! intval( delaware_get_option( 'footer_top' ) ) ) {
		return;
	}

	$style = delaware_get_option( 'footer_newsletter_styles' );

	?>
	<div class="footer-newsletter">
		<div class="container">
			<div class="row-flex">
				<div class="col-flex-md-5 col-flex-sm-6 col-flex-xs-12">
					<div class="message-newsletter">
						<span class="newsletter-icon svg-icon"><svg>
								<use xlink:href="#wifi"></use>
							</svg></span>

						<div class="message"><?php echo wp_kses( delaware_get_option( 'footer_top_left' ), wp_kses_allowed_html( 'post' ) ); ?></div>
					</div>
				</div>
				<div class="col-flex-md-5 col-flex-sm-6 col-flex-xs-12 col-md-offset-2 newsletter-style-<?php echo esc_attr( $style ); ?>">
					<?php echo do_shortcode( wp_kses( delaware_get_option( 'footer_top_right' ), wp_kses_allowed_html( 'post' ) ) ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'delaware_before_footer', 'delaware_top_footer_widgets', 20 );

function delaware_footer() {
	get_template_part( 'parts/footers/footer' );
}

add_action( 'delaware_footer', 'delaware_footer' );

/**
 * Display back to top
 *
 * @since 1.0.0
 */
function delaware_back_to_top() {
	if ( ! intval( delaware_get_option( 'back_to_top' ) ) ) {
		return;
	}
	?>
	<a id="scroll-top" class="backtotop" href="#page-top">
		<i class="fa fa-chevron-up"></i>
	</a>
	<?php
}

add_action( 'wp_footer', 'delaware_back_to_top' );

/**
 * Adds photoSwipe dialog element
 */
function delaware_gallery_images_lightbox() {

	if ( ! is_singular() ) {
		return;
	}

	?>
	<div id="pswp" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="pswp__bg"></div>

		<div class="pswp__scroll-wrap">

			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>

			<div class="pswp__ui pswp__ui--hidden">

				<div class="pswp__top-bar">


					<div class="pswp__counter"></div>

					<button class="pswp__button pswp__button--close"
							title="<?php esc_attr_e( 'Close (Esc)', 'delaware' ) ?>"></button>

					<button class="pswp__button pswp__button--share"
							title="<?php esc_attr_e( 'Share', 'delaware' ) ?>"></button>

					<button class="pswp__button pswp__button--fs"
							title="<?php esc_attr_e( 'Toggle fullscreen', 'delaware' ) ?>"></button>

					<button class="pswp__button pswp__button--zoom"
							title="<?php esc_attr_e( 'Zoom in/out', 'delaware' ) ?>"></button>

					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div>
				</div>

				<button class="pswp__button pswp__button--arrow--left"
						title="<?php esc_attr_e( 'Previous (arrow left)', 'delaware' ) ?>">
				</button>

				<button class="pswp__button pswp__button--arrow--right"
						title="<?php esc_attr_e( 'Next (arrow right)', 'delaware' ) ?>">
				</button>

				<div class="pswp__caption">
					<div class="pswp__caption__center"></div>
				</div>

			</div>

		</div>

	</div>
	<?php
}

add_action( 'wp_footer', 'delaware_gallery_images_lightbox' );

/**
 * Add off canvas shopping cart to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'delaware_off_canvas_menu_sidebar' ) ) :
	function delaware_off_canvas_menu_sidebar() {
		$header_layout = delaware_get_option( 'header_layout' );

		?>
		<div id="menu-sidebar-panel" class="menu-sidebar delaware-off-canvas-panel">
			<div class="widget-canvas-content">
				<div class="widget-panel-header">
					<a href="#" class="close-canvas-panel">&#10005;</a>
				</div>

				<div class="widget-panel-content">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
							)
						);
					}
					?>

					<ul class="menu-extra">
						<?php if ( $header_layout == 'v1' || $header_layout == 'v2' || $header_layout == 'v4' || $header_layout == 'v5' ) :
							delaware_socials_menu_item();
						endif ?>

						<?php if ( $header_layout == 'v3' ) :
							delaware_text_menu_item();
						endif ?>

						<?php if ( $header_layout == 'v6' || $header_layout == 'v5' ) :
							delaware_cart_menu_item();
						endif ?>

						<?php if ( $header_layout == 'v1' || $header_layout == 'v4' ) :
							delaware_search_menu_item();
						endif ?>
					</ul>
				</div>

				<div class="widget-panel-footer"></div>
			</div>
		</div>
		<?php
	}

endif;

add_action( 'wp_footer', 'delaware_off_canvas_menu_sidebar' );

/**
 * Display a layer to close canvas panel everywhere inside page
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'delaware_site_canvas_layer' ) ) :
	function delaware_site_canvas_layer() {
		?>
		<div id="off-canvas-layer" class="delaware-off-canvas-layer"></div>
		<?php
	}

endif;

add_action( 'wp_footer', 'delaware_site_canvas_layer' );