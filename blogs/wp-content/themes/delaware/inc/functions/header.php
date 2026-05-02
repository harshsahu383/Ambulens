<?php
/**
 * Custom function for header
 * 
 * @package Delaware
 */


/**
 * Search Item
 */
function delaware_search_menu_item() {
	if ( ! intval( delaware_get_option( 'search_extra_item' ) ) ) {
		return;
	}

	$icon_html = sprintf( '<a href="#" class="search-toggle dl-search-toggle"><i class="fa fa-search"></i></a>' );

	$icon_html = delaware_get_option( 'header_layout' ) == 'v1' ? $icon_html : '';

	$item = sprintf(
		'<div class="search-item-wrapper">
			<form method="get" class="search-form" action="%s">
				<i class="fa fa-search"></i>
				<input type="search" class="search-field" placeholder="%s" value="%s" name="s">
				<input type="submit" class="search-submit" value="%s">
			</form>
		</div>',
		esc_url( home_url( '/' ) ),
		esc_attr__( 'Search', 'delaware' ),
		esc_attr( get_search_query() ),
		esc_attr__( 'Search', 'delaware' )
	);

	printf( '<li class="menu-extra-item menu-search-item">%s%s</li>', $icon_html, $item );
}

/**
 * Search Item
 */
function delaware_cart_menu_item() {
	if ( ! intval( delaware_get_option( 'cart_extra_item' ) ) ) {
		return;
	}

	if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
		return;
	}
	global $woocommerce;

	$icon_cart = '<span class="shopping-cart-icon hidden-md hidden-xs hidden-sm"><svg viewBox="0 0 20 20"><use xlink:href="#shopping-cart"></use></svg></span>';
	$icon_cart = apply_filters( 'dl_icon_cart', $icon_cart );

	$text_cart = apply_filters( 'dl_text_cart', sprintf( '<span class="text-cart hidden-lg">%s</span>', esc_html__( 'Shopping Cart', 'delaware' ) ) );

	printf(
		'<li class="menu-extra-item menu-cart-item">
			<a class="cart-contents icon-cart-contents" href="%s">
				%s
				%s
				<span class="mini-cart-counter">%s</span>
			</a>
		</li>',
		esc_url( wc_get_cart_url() ),
		$icon_cart,
		$text_cart,
		intval( $woocommerce->cart->cart_contents_count )
	);
}

/**
 * Menu Socials Item
 */
function delaware_socials_menu_item() {
	if ( ! intval( delaware_get_option( 'socials_extra_item' ) ) ) {
		return;
	}

	$menu_socials = (array)delaware_get_option('menu_socials');

	if ( empty( $menu_socials )) {
		return;
	}

	$socials_html = array();

	if ($menu_socials) {

		$socials = (array) delaware_get_socials();

		foreach ($menu_socials as $social) {
			foreach ($socials as $name => $label) {
				$link_url = $social['link_url'];

				if (preg_match('/' . $name . '/', $link_url)) {

					if ($name == 'google') {
						$name = 'google-plus';
					}

					$socials_html[] = sprintf('<a href="%s" target="_blank"><i class="fa fa-%s"></i></a>', esc_url($link_url), esc_attr($name));
					break;
				}
			}
		}
	}

	printf( '<li class="menu-extra-item menu-socials-item">%s</li>', implode( '', $socials_html ) );
}

/**
 * Text Item
 */
function delaware_text_menu_item() {
	$text = delaware_get_option( 'text_extra_item' );

	if ( ! $text ) {
		return;
	}

	$item = wp_kses( $text, wp_kses_allowed_html( 'post' ) );

	printf( '<li class="menu-extra-item menu-text-item">%s</li>', $item );
}