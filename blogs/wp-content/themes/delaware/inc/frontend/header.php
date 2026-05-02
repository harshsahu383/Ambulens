<?php
/**
 * Hooks for template header
 *
 * @package Delaware
 */

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function delaware_enqueue_scripts() {
	/**
	 * Register and enqueue styles
	 */
	wp_register_style( 'delaware-fonts', delaware_fonts_url(), array(), '' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.7' );
	wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.6.3' );
	wp_register_style( 'fontawesome-5', get_template_directory_uri() . '/css/font-awesome-5.min.css', array(), '5.15.3' );
	wp_register_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '20171020' );
	wp_register_style( 'photoswipe', get_template_directory_uri() . '/css/photoswipe.css', array(), '4.1.1' );
	wp_register_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.8.1' );
	wp_register_style( 'photoswipe', get_template_directory_uri() . '/css/photoswipe.css', array(), '4.1.1' );

	wp_enqueue_style(
		'delaware', get_template_directory_uri() . '/style.css', array(
		'delaware-fonts',
		'bootstrap',
		'fontawesome',
		'fontawesome-5',
		'flaticon',
		'photoswipe',
		'slick',

	), '20180406'
	);

	wp_add_inline_style( 'delaware', delaware_customize_css() );

	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/plugins/html5shiv.min.js', array(), '3.7.2' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/plugins/respond.min.js', array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	/**
	 * Register and enqueue scripts
	 */
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'sticky', get_template_directory_uri() . '/js/plugins/jquery.sticky.js', array(), '1.0', true );
	wp_register_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.min.js', array(), '2.2.2', true );
	wp_register_script( 'tabs', get_template_directory_uri() . '/js/plugins/jquery.tabs.js', array(), '1.0.0', true );
	wp_register_script( 'slick', get_template_directory_uri() . '/js/plugins/slick.min.js', array(), '1.0', true );
	wp_register_script( 'counterup', get_template_directory_uri() . '/js/plugins/jquery.counterup.min.js', array(), '1.0', true );
	wp_register_script( 'waypoints', get_template_directory_uri() . '/js/plugins/waypoints.min.js', array(), '1.0', true );
	wp_register_script( 'photoswipe', get_template_directory_uri() . '/js/plugins/photoswipe.min.js', array(), '4.1.1', true );
	wp_register_script( 'photoswipe-ui', get_template_directory_uri() . '/js/plugins/photoswipe-ui.min.js', array( 'photoswipe' ), '4.1.1', true );
	wp_register_script( 'parallax', get_template_directory_uri() . '/js/plugins/jquery.parallax.min.js', array(), '1.0', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script(
		'delaware', get_template_directory_uri() . "/js/scripts$min.js", array(
		'jquery',
		'imagesloaded',
		'sticky',
		'isotope',
		'tabs',
		'slick',
		'counterup',
		'waypoints',
		'parallax',
	), '20171013', true
	);

	if ( is_singular() ) {

		wp_enqueue_style( 'photoswipe' );
		wp_enqueue_script( 'photoswipe-ui' );

		$photoswipe_skin = 'photoswipe-default-skin';
		if ( wp_style_is( $photoswipe_skin, 'registered' ) && ! wp_style_is( $photoswipe_skin, 'enqueued' ) ) {
			wp_enqueue_style( $photoswipe_skin );
		}
	}


}

add_action( 'wp_enqueue_scripts', 'delaware_enqueue_scripts' );

/**
 * Enqueues front-end CSS for theme customization
 */
function delaware_customize_css() {
	$css = '';

	$css .= delaware_get_footer_custom_css();

	// Logo
	$logo_size_width = delaware_get_option( 'logo_width' );
	$logo_css        = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

	$logo_size_height = intval( delaware_get_option( 'logo_height' ) );
	$logo_css         .= $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

	$logo_margin = delaware_get_option( 'logo_position' );
	$logo_css    .= $logo_margin['top'] ? 'margin-top:' . $logo_margin['top'] . '; ' : '';
	$logo_css    .= $logo_margin['right'] ? 'margin-right:' . $logo_margin['right'] . '; ' : '';
	$logo_css    .= $logo_margin['bottom'] ? 'margin-bottom:' . $logo_margin['bottom'] . '; ' : '';
	$logo_css    .= $logo_margin['left'] ? 'margin-left:' . $logo_margin['left'] . '; ' : '';

	if ( ! empty( $logo_css ) ) {
		$css .= '.site-header .logo img ' . ' {' . $logo_css . '}';
	}

	$topbar_bg = delaware_get_option( 'topbar_background' );

	if ( $topbar_bg ) {
		$css .= '.topbar { background-color:' . $topbar_bg . ';}';
		$css .= '.header-v3 .topbar { background-color:' . $topbar_bg . ';}';
	}

	$header_bg = delaware_get_option( 'header_bg' );

	if ( $header_bg ) {
		$css .= '.header-v3 .header-main { background-image: url(' . esc_url( $header_bg ) . ');}';
	}

	/* Color Scheme */
	$color_scheme_option = delaware_get_option( 'color_scheme' );

	if ( intval( delaware_get_option( 'custom_color_scheme' ) ) ) {
		$color_scheme_option = delaware_get_option( 'custom_color' );
	}

	// Don't do anything if the default color scheme is selected.
	if ( $color_scheme_option ) {
		$css .= delaware_get_color_scheme_css( $color_scheme_option );
	}
	$css .= delaware_typography_css();

	$css .= delaware_get_heading_typography_css();

	return $css;
}

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function delaware_show_topbar() {
	if ( ! intval( delaware_get_option( 'topbar_enable' ) ) ) {
		return;
	}

	if ( is_active_sidebar( 'topbar-left' ) == false &&
	     is_active_sidebar( 'topbar-right' ) == false
	) {
		return;
	}

	?>
    <div id="topbar" class="topbar hidden-md hidden-sm hidden-xs">
        <div class="container">
            <div class="row-flex">
				<?php if ( is_active_sidebar( 'topbar-left' ) ) : ?>

                    <div class="topbar-left topbar-widgets text-left row-flex">
						<?php
						ob_start();
						dynamic_sidebar( 'topbar-left' );
						$output = ob_get_clean();

						echo apply_filters( 'delaware_topbar_left', $output );
						?>
                    </div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'topbar-right' ) ) : ?>
                    <div class="topbar-right topbar-widgets text-right row-flex">
						<?php
						ob_start();
						dynamic_sidebar( 'topbar-right' );
						$output = ob_get_clean();

						echo apply_filters( 'delaware_topbar_right', $output );
						?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
	<?php
}

add_action( 'delaware_before_header', 'delaware_show_topbar' );

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function delaware_show_topbar_mobile() {
	if ( ! intval( delaware_get_option( 'topbar_enable' ) ) ) {
		return;
	}

	if ( is_active_sidebar( 'topbar-mobile' ) == false ) {
		return;
	}

	$style = '';

	$topbar_bg   = delaware_get_option( 'topbar_background' );
	$topbar_flex = delaware_get_option( 'topbar_mobile_content' );

	$style_wrapper = 'justify-content:' . $topbar_flex . ';';

	if ( $topbar_bg ) {
		$style = ' background-color: ' . esc_attr( $topbar_bg ) . '';
	}

	?>
    <div class="topbar topbar-mobile hidden-lg" style="<?php echo esc_attr( $style ) ?>">
        <div class="container">
            <div class="topbar-widgets row-flex" style="<?php echo esc_attr( $style_wrapper ) ?>">
				<?php
				ob_start();
				dynamic_sidebar( 'topbar-mobile' );
				$output = ob_get_clean();

				echo apply_filters( 'delaware_topbar_mobile', $output );
				?>
            </div>
        </div>
    </div>
	<?php
}

add_action( 'delaware_before_header', 'delaware_show_topbar_mobile' );

/**
 * Display header
 */
function delaware_show_header() {
	get_template_part( 'parts/headers/header', delaware_get_option( 'header_layout' ) );
}

add_action( 'delaware_header', 'delaware_show_header' );

/**
 * Display page header
 */
function delaware_page_header() {
	if ( is_page_template( 'template-homepage.php' ) ) {
		return;
	}

	if ( is_singular( 'post' ) ) {
		return;
	}
	get_template_part( 'parts/page-headers/page-header' );
}

add_action( 'delaware_after_header', 'delaware_page_header' );

/**
 * Get breadcrumbs
 *
 * @since  1.0.0
 *
 * @return string
 */

if ( ! function_exists( 'delaware_get_breadcrumbs' ) ) :
	function delaware_get_breadcrumbs() {

		ob_start();
		?>
        <nav class="breadcrumbs">
			<?php
			delaware_breadcrumbs(
				array(
					'before'   => '',
					'taxonomy' => function_exists( 'is_woocommerce' ) && is_woocommerce() ? 'product_cat' : 'category',
				)
			);
			?>
        </nav>
		<?php
		echo ob_get_clean();
	}

endif;

/**
 * Filter to archive title and add page title for singular pages
 *
 * @param string $title
 *
 * @return string
 */
function delaware_the_archive_title( $title ) {
	if ( is_search() ) {
		$title = esc_html__( 'Search Results', 'delaware' );

	} elseif ( is_404() ) {
		$title = esc_html__( 'Page Not Found', 'delaware' );

	} elseif ( is_page() ) {
		$title = get_the_title();

	} elseif ( is_home() && is_front_page() ) {
		$title = esc_html__( 'The Latest Posts', 'delaware' );

	} elseif ( is_home() && ! is_front_page() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );

	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );

	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$cats = get_the_terms( get_the_ID(), 'product_cat' );
		if ( ! is_wp_error( $cats ) && $cats ) {
			$title = $cats[0]->name;
		} else {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}

	} elseif ( is_post_type_archive( 'portfolio' ) ) {

		if ( get_option( 'delaware_portfolio_page_id' ) ) {
			$title = get_the_title( get_option( 'delaware_portfolio_page_id' ) );
		} else {
			$title = esc_html__( 'Portfolio', 'delaware' );
		}

	} elseif ( is_post_type_archive( 'service' ) ) {
		if ( get_option( 'delaware_service_page_id' ) ) {
			$title = get_the_title( get_option( 'delaware_service_page_id' ) );
		} else {
			$title = esc_html__( 'Services', 'delaware' );
		}

	} elseif ( is_tax() || is_category() ) {
		$title = single_term_title( '', false );

	} elseif ( is_singular() ) {
		$title = get_the_title( get_the_ID() );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'delaware_the_archive_title' );

function delaware_wpb_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;

	return $fields;
}

add_filter( 'comment_form_fields', 'delaware_wpb_move_comment_field_to_bottom' );

/**
 * Returns CSS for the color schemes.
 *
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function delaware_get_color_scheme_css( $colors ) {
	return <<<CSS

	/* Background Color */

	.primary-background-color,
	.delaware-section-title .delaware-button .btn:hover,
    .delaware_title-type-7 .delaware-button .btn,
    .delaware_title-type-8 .delaware-button .btn,
    .delaware-about-type-2 .col-left .delaware-button .btn,
    .dl-accordion.vc_tta-accordion .vc_tta-panel-body:before,
    .dl-contact-form-7 .wpcf7-form .dl-form-1 input[type="submit"],
    .dl-contact-form-7 .wpcf7-form .dl-form-2 input[type="submit"],
    .map-form input[type="submit"],
    .tab-block-overview .tab-nav .active,
    .tab-block-overview .tab-content .tab-panel a,
    .tab-block-overview .tab-content .tab-panel a:hover,.tab-block-overview .tab-content .tab-panel a:focus,.tab-block-overview .tab-content .tab-panel a:active,
    .tab-block-overview .tab-content .tab-panel a:hover,.tab-block-overview .tab-content .tab-panel a:focus,.tab-block-overview .tab-content .tab-panel a:active,
    .site-contact .widget_search .search-form:after,
    ul.menu-extra .search-form:after,
    ul.menu-extra li.menu-cart-item .mini-cart-counter,
    .dl-btn-primary,.dl-btn-primary:hover,.dl-btn-primary:focus,.dl-btn-primary:active,
    .dl-btn-secondary:hover,.dl-btn-secondary:focus,.dl-btn-secondary:active,
    .dl-btn-secondary:hover,.dl-btn-secondary:focus,.dl-btn-secondary:active,
    .paging-navigation.blog-nav-ajax .page-numbers.next,.paging-navigation.portfolio-nav-ajax .page-numbers.next,
    .blog-wrapper .entry-thumbnail .meta,
    .comment-form .form-submit input,
    .widget .widget-title:after,
    .widget_tag_cloud a:hover,
    .portfolio-wrapper .category:before,
    /** * 7.1 Woocommerce */.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,
    .woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce a.button:focus,.woocommerce button.button:focus,.woocommerce input.button:focus,.woocommerce #respond input#submit:focus,.woocommerce a.button:active,.woocommerce button.button:active,.woocommerce input.button:active,.woocommerce #respond input#submit:active,
    .woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,.woocommerce a.button:focus,.woocommerce button.button:focus,.woocommerce input.button:focus,.woocommerce #respond input#submit:focus,.woocommerce a.button:active,.woocommerce button.button:active,.woocommerce input.button:active,.woocommerce #respond input#submit:active,
    .woocommerce a.button:disabled,.woocommerce button.button:disabled,.woocommerce input.button:disabled,.woocommerce #respond input#submit:disabled,.woocommerce a.button:disabled[disabled],.woocommerce button.button:disabled[disabled],.woocommerce input.button:disabled[disabled],.woocommerce #respond input#submit:disabled[disabled],.woocommerce a.button .disabled,.woocommerce button.button .disabled,.woocommerce input.button .disabled,.woocommerce #respond input#submit .disabled,
    .woocommerce a.button:disabled:hover,.woocommerce button.button:disabled:hover,.woocommerce input.button:disabled:hover,.woocommerce #respond input#submit:disabled:hover,.woocommerce a.button:disabled[disabled]:hover,.woocommerce button.button:disabled[disabled]:hover,.woocommerce input.button:disabled[disabled]:hover,.woocommerce #respond input#submit:disabled[disabled]:hover,.woocommerce a.button .disabled:hover,.woocommerce button.button .disabled:hover,.woocommerce input.button .disabled:hover,.woocommerce #respond input#submit .disabled:hover,.woocommerce a.button:disabled:focus,.woocommerce button.button:disabled:focus,.woocommerce input.button:disabled:focus,.woocommerce #respond input#submit:disabled:focus,.woocommerce a.button:disabled[disabled]:focus,.woocommerce button.button:disabled[disabled]:focus,.woocommerce input.button:disabled[disabled]:focus,.woocommerce #respond input#submit:disabled[disabled]:focus,.woocommerce a.button .disabled:focus,.woocommerce button.button .disabled:focus,.woocommerce input.button .disabled:focus,.woocommerce #respond input#submit .disabled:focus,.woocommerce a.button:disabled:active,.woocommerce button.button:disabled:active,.woocommerce input.button:disabled:active,.woocommerce #respond input#submit:disabled:active,.woocommerce a.button:disabled[disabled]:active,.woocommerce button.button:disabled[disabled]:active,.woocommerce input.button:disabled[disabled]:active,.woocommerce #respond input#submit:disabled[disabled]:active,.woocommerce a.button .disabled:active,.woocommerce button.button .disabled:active,.woocommerce input.button .disabled:active,.woocommerce #respond input#submit .disabled:active,
    .woocommerce a.button:disabled:hover,.woocommerce button.button:disabled:hover,.woocommerce input.button:disabled:hover,.woocommerce #respond input#submit:disabled:hover,.woocommerce a.button:disabled[disabled]:hover,.woocommerce button.button:disabled[disabled]:hover,.woocommerce input.button:disabled[disabled]:hover,.woocommerce #respond input#submit:disabled[disabled]:hover,.woocommerce a.button .disabled:hover,.woocommerce button.button .disabled:hover,.woocommerce input.button .disabled:hover,.woocommerce #respond input#submit .disabled:hover,.woocommerce a.button:disabled:focus,.woocommerce button.button:disabled:focus,.woocommerce input.button:disabled:focus,.woocommerce #respond input#submit:disabled:focus,.woocommerce a.button:disabled[disabled]:focus,.woocommerce button.button:disabled[disabled]:focus,.woocommerce input.button:disabled[disabled]:focus,.woocommerce #respond input#submit:disabled[disabled]:focus,.woocommerce a.button .disabled:focus,.woocommerce button.button .disabled:focus,.woocommerce input.button .disabled:focus,.woocommerce #respond input#submit .disabled:focus,.woocommerce a.button:disabled:active,.woocommerce button.button:disabled:active,.woocommerce input.button:disabled:active,.woocommerce #respond input#submit:disabled:active,.woocommerce a.button:disabled[disabled]:active,.woocommerce button.button:disabled[disabled]:active,.woocommerce input.button:disabled[disabled]:active,.woocommerce #respond input#submit:disabled[disabled]:active,.woocommerce a.button .disabled:active,.woocommerce button.button .disabled:active,.woocommerce input.button .disabled:active,.woocommerce #respond input#submit .disabled:active,
    .woocommerce a.button:disabled:hover,.woocommerce button.button:disabled:hover,.woocommerce input.button:disabled:hover,.woocommerce #respond input#submit:disabled:hover,.woocommerce a.button:disabled[disabled]:hover,.woocommerce button.button:disabled[disabled]:hover,.woocommerce input.button:disabled[disabled]:hover,.woocommerce #respond input#submit:disabled[disabled]:hover,.woocommerce a.button .disabled:hover,.woocommerce button.button .disabled:hover,.woocommerce input.button .disabled:hover,.woocommerce #respond input#submit .disabled:hover,.woocommerce a.button:disabled:focus,.woocommerce button.button:disabled:focus,.woocommerce input.button:disabled:focus,.woocommerce #respond input#submit:disabled:focus,.woocommerce a.button:disabled[disabled]:focus,.woocommerce button.button:disabled[disabled]:focus,.woocommerce input.button:disabled[disabled]:focus,.woocommerce #respond input#submit:disabled[disabled]:focus,.woocommerce a.button .disabled:focus,.woocommerce button.button .disabled:focus,.woocommerce input.button .disabled:focus,.woocommerce #respond input#submit .disabled:focus,.woocommerce a.button:disabled:active,.woocommerce button.button:disabled:active,.woocommerce input.button:disabled:active,.woocommerce #respond input#submit:disabled:active,.woocommerce a.button:disabled[disabled]:active,.woocommerce button.button:disabled[disabled]:active,.woocommerce input.button:disabled[disabled]:active,.woocommerce #respond input#submit:disabled[disabled]:active,.woocommerce a.button .disabled:active,.woocommerce button.button .disabled:active,.woocommerce input.button .disabled:active,.woocommerce #respond input#submit .disabled:active,
    .woocommerce a.button:disabled:hover,.woocommerce button.button:disabled:hover,.woocommerce input.button:disabled:hover,.woocommerce #respond input#submit:disabled:hover,.woocommerce a.button:disabled[disabled]:hover,.woocommerce button.button:disabled[disabled]:hover,.woocommerce input.button:disabled[disabled]:hover,.woocommerce #respond input#submit:disabled[disabled]:hover,.woocommerce a.button .disabled:hover,.woocommerce button.button .disabled:hover,.woocommerce input.button .disabled:hover,.woocommerce #respond input#submit .disabled:hover,.woocommerce a.button:disabled:focus,.woocommerce button.button:disabled:focus,.woocommerce input.button:disabled:focus,.woocommerce #respond input#submit:disabled:focus,.woocommerce a.button:disabled[disabled]:focus,.woocommerce button.button:disabled[disabled]:focus,.woocommerce input.button:disabled[disabled]:focus,.woocommerce #respond input#submit:disabled[disabled]:focus,.woocommerce a.button .disabled:focus,.woocommerce button.button .disabled:focus,.woocommerce input.button .disabled:focus,.woocommerce #respond input#submit .disabled:focus,.woocommerce a.button:disabled:active,.woocommerce button.button:disabled:active,.woocommerce input.button:disabled:active,.woocommerce #respond input#submit:disabled:active,.woocommerce a.button:disabled[disabled]:active,.woocommerce button.button:disabled[disabled]:active,.woocommerce input.button:disabled[disabled]:active,.woocommerce #respond input#submit:disabled[disabled]:active,.woocommerce a.button .disabled:active,.woocommerce button.button .disabled:active,.woocommerce input.button .disabled:active,.woocommerce #respond input#submit .disabled:active,
    .woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,
    .woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce a.button.alt:focus,.woocommerce button.button.alt:focus,.woocommerce input.button.alt:focus,.woocommerce #respond input#submit.alt:focus,.woocommerce a.button.alt:active,.woocommerce button.button.alt:active,.woocommerce input.button.alt:active,.woocommerce #respond input#submit.alt:active,
    .woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce a.button.alt:focus,.woocommerce button.button.alt:focus,.woocommerce input.button.alt:focus,.woocommerce #respond input#submit.alt:focus,.woocommerce a.button.alt:active,.woocommerce button.button.alt:active,.woocommerce input.button.alt:active,.woocommerce #respond input#submit.alt:active,
    .woocommerce ul.products li.product a.button:hover,
    .woocommerce-cart td.actions .update-cart,
    .woocommerce-cart td.actions .update-cart:hover,.woocommerce-cart td.actions .update-cart:focus,.woocommerce-cart td.actions .update-cart:active,
    .woocommerce-cart td.actions .update-cart:hover,.woocommerce-cart td.actions .update-cart:focus,.woocommerce-cart td.actions .update-cart:active,
    .woocommerce-cart td.actions .update-cart:hover,.woocommerce-cart td.actions .update-cart:focus,.woocommerce-cart td.actions .update-cart:active,
    .woocommerce-cart td.actions .update-cart:hover,.woocommerce-cart td.actions .update-cart:focus,.woocommerce-cart td.actions .update-cart:active,
    .footer-widgets .footer-sidebar article .category-post
	{background-color: $colors}

	/*Background important*/
	.delaware-icon-box-4:hover
    {background-color: $colors ! important;}

	/* Border Color */

	.delaware-about-type-1 .delaware-button .bt-1:hover,
    .delaware-about-type-1 .delaware-button .bt-2:hover,
    .dl_testi_carousel .style-1 .dl-icon-quote,
    .widget_tag_cloud a:hover
	{border-color: $colors}

	/* Color */
	.primary-color,
	blockquote cite,
    .main-color,
    .delaware-section-title .delaware-button .btn,
    .delaware_title-type-7 .desc ul li:before,
    .delaware_title-type-8 .desc ul li:before,
    .delaware-image-box-1:hover .emtry-content .readmore,
    .delaware-image-box-2:hover .emtry-content .readmore,
    .delaware-image-box-3:hover .emtry-content .readmore,
    .delaware-wrap-item-type-2 .button-group .button:hover,.delaware-wrap-item-type-2 .button-group .button.active,
    .delaware-icon-box-1:hover .emtry-header .icon-content .svg-icon,
    .delaware-icon-box-1:hover .emtry-content .readmore,
    .delaware-icon-box-7 .emtry-content .readmore:hover,
    .delaware_portfolio_info ul .rating span i,.dl-portfolio-meta ul .rating span i,
    .dl-latest-post .meta.date a,
    .dl-latest-post .dl-latest-post-header a,
    .dl-latest-post .dl-latest-post-header a:hover,
    .dl_testi:hover .dl-button a,
    .timeline-item .timeline-content span,
    .job-box .job-date,
    .job-box .job-box-item:hover a,
    .list-style-job ul li::before,
    .primary-color,
    .dl_testi_carousel .style-1 svg,
    .dl-contact-box .hightlight,
    .dl-contact-box .form-ct-box .wpcf7-form .select-arrow::after,
    .site-contact .delaware-social-links-widget a:hover,
    ul.menu-extra .menu-socials-item a:hover,
    ul.menu-extra .menu-text-item i,
    .site-extra-text .header-contact i,.site-extra-text .header-contact span,
    .main-nav li:hover > a,
    .main-nav .menu > li.menu-item:hover > a,.main-nav .menu > li.menu-item.current-menu-item > a,.main-nav .menu > li.menu-item.current_page_item > a,.main-nav .menu > li.menu-item.current-menu-ancestor > a,.main-nav .menu > li.menu-item.current-menu-parent > a,.main-nav .menu > li.menu-item.active > a,
    .post-author .post-author-info .post-author-social ul li a:hover,
    .entry-meta .meta:hover,
    .entry-meta .meta:hover a,
    .entry-header .entry-author h3 span,
    .entry-header .mf-single-post-socials-share .share-icon,
    .entry-header .mf-single-post-socials-share h4 i,
    .entry-footer .tag-list a,
    .blog-wrapper .entry-footer a,
    .single-post .entry-footer .count-cmt:before,
    .widget_categories li > a:hover,.widget_archive li > a:hover,.widget_pages li > a:hover,.widget_meta li > a:hover,.widget_nav_menu li > a:hover,.widget_dl-custom-menu li > a:hover,
    .recent-post .post-text a:hover,.popular-post .post-text a:hover,
    .delaware-newsletter-widget .mc4wp-form-fields input[type="submit"],
    .topbar .delaware-social-links-widget a:hover,
    .delaware-office-location-widget .svg-icon,
    .delaware-office-location-widget .topbar-office li i,
    .service-wrapper .entry-content .read-more a,
    .service-sidebar .widget .menu li:after,
    .service-sidebar .widget .menu li.current-menu-item a,.service-sidebar .widget .menu li:hover a,.service-sidebar .widget .menu li.current-menu-item:after,.service-sidebar .widget .menu li:hover:after,
    .service-sidebar .broucher .light i,
    .categories-filter .button:hover,.categories-filter .button.active,
    .portfolio-wrapper.columns-2 .portfolio-inner:hover .entry-title a,
    .woocommerce .woocommerce-result-count,
    .woocommerce .star-rating,
    .woocommerce .star-rating::before,
    .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
    .woocommerce .quantity .increase:hover,.woocommerce .quantity .decrease:hover,
    .woocommerce .comment-form-rating .stars a,
    .woocommerce .comment-form-rating .stars a:hover,
    .woocommerce table.shop_table td.product-subtotal,.woocommerce table.shop_table td.product-total,
    .woocommerce .woocommerce-message a,.woocommerce .woocommerce-error a,.woocommerce .woocommerce-info a,
    .woocommerce .widget_product_categories .product-categories li:hover,
    .woocommerce .widget_product_categories .product-categories:hover a,
    .woocommerce .widget_product_categories .product-categories .current-cat a,
    .woocommerce-checkout form.checkout .create-account label,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li:hover a,.woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
    .footer-newsletter .message-newsletter .newsletter-icon,
    .footer-widgets .footer-widget-contact span,
    .footer-widgets .footer-sidebar .footer-contact i,
    .newsletter-style-2 .mc4wp-form-fields span
	{color: $colors}


CSS;
}

if ( ! function_exists( 'delaware_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function delaware_typography_css() {
		$css        = '';
		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
			'text-align'     => 'text-align',
		);

		$settings = array(
			'body_typo'        => 'body',
			'heading1_typo'    => 'h1',
			'heading2_typo'    => 'h2',
			'heading3_typo'    => 'h3',
			'heading4_typo'    => 'h4',
			'heading5_typo'    => 'h5',
			'heading6_typo'    => 'h6',
			'menu_typo'        => '.main-nav a, .mf-header-item-button a, .primary-mobile-nav ul.menu > li > a',
			'sub_menu_typo'    => '.main-nav li li a, .primary-mobile-nav ul.menu ul li a',
			'footer_text_typo' => '.site-footer',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = delaware_get_option( $setting );
			$default    = (array) delaware_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[ $key ] ) && ! empty( $typography[ $key ] ) ) {
					if ( isset( $default[ $key ] ) && strtoupper( $default[ $key ] ) == strtoupper( $typography[ $key ] ) ) {
						continue;
					}

					$value = 'font-family' == $key ? '"' . rtrim( trim( $typography[ $key ] ), ',' ) . '"' : $typography[ $key ];
					$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

					if ( $value ) {
						$style .= $property . ': ' . $value . ';';
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		return $css;
	}
endif;

/**
 * Returns CSS for the typography.
 *
 * @return string typography CSS.
 */
function delaware_get_heading_typography_css() {

	$headings   = array(
		'h1' => 'heading1_typo',
		'h2' => 'heading2_typo',
		'h3' => 'heading3_typo',
		'h4' => 'heading4_typo',
		'h5' => 'heading5_typo',
		'h6' => 'heading6_typo',
	);
	$inline_css = '';
	foreach ( $headings as $heading ) {
		$keys = array_keys( $headings, $heading );
		if ( $keys ) {
			$inline_css .= delaware_get_heading_font( $keys[0], $heading );
		}
	}


	return $inline_css;

}

/**
 * Returns CSS for the typography.
 *
 *
 * @param array $body_typo Color scheme body typography.
 *
 * @return string typography CSS.
 */
function delaware_get_heading_font( $key, $heading ) {

	$inline_css   = '';
	$heading_typo = delaware_get_option( $heading );

	if ( $heading_typo ) {
		if ( isset( $heading_typo['font-family'] ) && strtolower( $heading_typo['font-family'] ) !== 'hind' ) {
			$inline_css .= $key . '{font-family:' . rtrim( trim( $heading_typo['font-family'] ), ',' ) . ', Arial, sans-serif}';
		}
	}

	if ( empty( $inline_css ) ) {
		return;
	}

	return <<<CSS
	{$inline_css}
CSS;
}
