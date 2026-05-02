<?php
/**
 * Steel Themes Core functions and definitions
 *
 * @package Delaware
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function delaware_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'delaware_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'delaware', get_template_directory() . '/lang' );

	// Theme supports
	// Supports WooCommerce plugin.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'gallery', 'video' ) );
	add_theme_support(
		'html5', array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
		)
	);
	add_editor_style( 'css/editor-style.css' );

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	add_theme_support( 'align-wide' );

	add_theme_support( 'align-full' );

	add_image_size( 'delaware-masonry-thumb', 410, 230, false );
	add_image_size( 'delaware-portfolio-grid', 510, 200, true );
	add_image_size( 'delaware-portfolio-grid-2', 570, 354, true );
	add_image_size( 'delaware-portfolio-grid-3', 570, 300, true );
	add_image_size( 'delaware-service-grid-thumb', 570, 335, true );
	add_image_size( 'delaware-service-widget-thumb', 90, 90, true );
	add_image_size( 'delaware-portfolio-single', 1170, 500, true );
	add_image_size( 'delaware-service-single', 870, 410, true );
	add_image_size( 'delaware-portfolio-widget-thumb', 120, 120, true );

	add_image_size( 'delaware-blog-thumb', 1170, 608, true );
	add_image_size( 'delaware-blog-grid-thumb', 570, 278, true );
	add_image_size( 'delaware-blog-masonry-thumb', 570, 740, false );

	add_image_size( 'delaware-nav-post-thumb', 370, 200, true );


	$menus = array(
		'primary'         => esc_html__( 'Primary Menu', 'delaware' ),
		'footer-bottom'   => esc_html__( 'Footer Bottom', 'delaware' ),
		'sidebar-service' => esc_html__( 'Service Menu', 'delaware' ),
	);

	// Register theme nav menu
	foreach ( $menus as $id => $name ) {
		register_nav_menus(
			array(
				$id => $name,
			)
		);
	}

	new Delaware_WooCommerce;

}

add_action( 'after_setup_theme', 'delaware_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function delaware_register_sidebar() {
	$sidebars = array(
		'topbar-left'     => esc_html__( 'Topbar Left', 'delaware' ),
		'topbar-right'    => esc_html__( 'Topbar Right', 'delaware' ),
		'topbar-mobile'   => esc_html__( 'Topbar Mobile', 'delaware' ),
		'header-contact'  => esc_html__( 'Header Contact', 'delaware' ),
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'delaware' ),
		'service-sidebar' => esc_html__( 'Service Sidebar', 'delaware' ),
		'shop-sidebar'    => esc_html__( 'Shop Sidebar', 'delaware' ),
		'page-sidebar'    => esc_html__( 'Page Sidebar', 'delaware' ),
	);

	// Register sidebar
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => esc_html__( 'Add widgets here in order to display on pages', 'delaware' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 4; $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'delaware' ) . " $i",
				'id'            => "footer-sidebar-$i",
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}

add_action( 'widgets_init', 'delaware_register_sidebar' );

/**
 * Load theme
 */


// customizer hooks
require get_template_directory() . '/inc/backend/customizer.php';

// Woocommerce hooks
require get_template_directory() . '/inc/frontend/woocommerce.php';

require get_template_directory() . '/inc/backend/editor.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
}

// Frontend functions and shortcodes
require get_template_directory() . '/inc/functions/media.php';
require get_template_directory() . '/inc/functions/nav.php';
require get_template_directory() . '/inc/functions/entry.php';
require get_template_directory() . '/inc/functions/header.php';
require get_template_directory() . '/inc/functions/comments.php';
require get_template_directory() . '/inc/functions/options.php';
require get_template_directory() . '/inc/functions/breadcrumbs.php';
require get_template_directory() . '/inc/functions/footer.php';

// Frontend hooks
require get_template_directory() . '/inc/frontend/layout.php';
require get_template_directory() . '/inc/frontend/header.php';
require get_template_directory() . '/inc/frontend/footer.php';
require get_template_directory() . '/inc/frontend/nav.php';
require get_template_directory() . '/inc/frontend/entry.php';