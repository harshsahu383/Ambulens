<?php
/**
 * Hooks for frontend display
 *
 * @package Delaware
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 * @param array $classes Classes for the body element.
 * @return array
 */
function delaware_body_classes( $classes ) {
	$header_layout = delaware_get_option( 'header_layout' );
	$blog_view = delaware_get_option( 'blog_view' );
	$type_nav  = delaware_get_option( 'blog_nav_type' );

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	if ( intval( delaware_get_option( 'header_sticky' ) ) ) {
		$classes[] = 'header-sticky';
	}

    if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
		if ( delaware_get_option( 'blog_view' ) == 'masonry' )
            $classes[] = 'blog-masonry';
    }

	if ( is_singular( 'post' ) ) {
		$classes[] = delaware_get_option( 'single_post_layout' );

	} elseif ( delaware_is_blog() ) {

		$classes[] = 'dl-blog-page';
		$classes[] = 'blog-' . $blog_view;

		if ( 'classic' == $blog_view ) {
			$classes[] = delaware_get_option( 'blog_layout' );
		}

		if ( $type_nav == 'ajax' ) {
			$classes[] = 'dl-blog-ajax-enable';
		}

	} elseif ( delaware_is_portfolio() ) {
		$classes[] = 'delaware-portfolio';
			$classes[] = 'portfolio-layout-' . delaware_get_option( 'delaware_portfolio_layout' );
			$classes[] = 'dl-portfolio-page';

			if( delaware_get_option('portfolio_cats_filters') == 1 || delaware_get_option( 'delaware_portfolio_layout' ) == 'masonry'){
				$classes[] = 'delaware-portfolio-isotope';
			}

	} else {
		$classes[] = delaware_get_layout();
	}

	if ( is_page_template( 'template-homepage.php' ) ) {
		if ( intval( delaware_get_option( 'header_transparent' ) ) && delaware_get_option( 'header_layout' ) == 'v5' ) {
			$classes[] = 'header-transparent';
		}
	}
	if ( intval( delaware_get_option( 'topbar_enable' )  ) ){
		$classes[] = 'show-topbar';

	}

		$classes[] = 'header-' . $header_layout;

	return $classes;
}

add_filter( 'body_class', 'delaware_body_classes' );

if ( ! function_exists( 'delaware_content_columns' ) ) :

	/**
	 * Display CSS classes for content columns
	 *
	 * @param string $layout
	 */
	function delaware_content_columns( $layout = null ) {
		echo implode( ' ', delaware_get_content_columns( $layout ) );
	}

endif;

if ( ! function_exists( 'delaware_get_content_columns' ) ) :
	/**
	 * Get CSS classes for content columns
	 *
	 * @param string $layout
	 *
	 * @return array
	 */
	function delaware_get_content_columns( $layout = null ) {
		$layout = $layout ? $layout : delaware_get_layout();

		if ( 'full-content' == $layout ) {
			return array( 'col-md-12', 'col-sm-12', 'col-xs-12' );
		}

		if ( delaware_is_catalog() || ( function_exists( 'is_product' ) && is_product() ) ) {
			return array( 'col-md-9', 'col-sm-12', 'col-xs-12' );
		} else if( delaware_is_service() || is_singular( 'service' ) ) {
			return array( 'col-md-9', 'col-sm-12', 'col-xs-12' );
		} else {


			if( $layout == 'sidebar-content' )
				return array( 'sidebar-content','col-md-8', 'col-sm-12', 'col-xs-12' );
			else
				return array('col-md-8', 'col-sm-12', 'col-xs-12' );
		}
	}

endif;

if ( ! function_exists( 'delaware_get_layout' ) ) :
	/**
	 * Get layout base on current page
	 *
	 * @return string
	 */
	function delaware_get_layout() {
		$layout = 'full-content';

		if ( is_404() ) {
			$layout = 'full-content';

		} elseif ( is_singular( 'post' ) ) {
			if ( get_post_meta( get_the_ID(), 'custom_page_layout', true ) ) {
				$layout = get_post_meta( get_the_ID(), 'layout', true );
			} else {
				$layout = delaware_get_option( 'single_post_layout' );
			}

		} elseif ( is_page_template( array( 'template-fullwidth.php', 'template-homepage.php' ) ) ) {
			$layout = 'full-content';
		} elseif ( is_page() ) {
			if ( get_post_meta( get_the_ID(), 'custom_page_layout', true ) ) {
				$layout = get_post_meta( get_the_ID(), 'layout', true );
			} else {
				$layout = delaware_get_option( 'page_layout' );
			}
		} elseif ( is_singular( 'portfolio' ) || delaware_is_portfolio() ) {
			$layout = 'full-content';

		} elseif ( delaware_is_service() ) {
			$layout = 'full-content';

		} elseif ( is_singular( 'service' ) ) {
			$layout = delaware_get_option( 'single_service_layout' );

		} elseif ( delaware_is_catalog() ) {
			$layout = delaware_get_option( 'shop_layout' );

		} elseif ( function_exists( 'is_product' ) && is_product() ) {
			$layout = delaware_get_option( 'single_product_layout' );

		} elseif ( delaware_is_blog() && delaware_get_option( 'blog_view' ) == 'classic' ) {
			$layout = delaware_get_option( 'blog_layout' );
		}


		return apply_filters( 'delaware_site_layout', $layout );
	}

endif;
