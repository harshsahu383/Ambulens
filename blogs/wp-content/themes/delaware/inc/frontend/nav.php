<?php
/**
 * Hooks for template nav menus
 *
 * @package Delaware
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since 1.0
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function delaware_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}

add_filter( 'wp_page_menu_args', 'delaware_page_menu_args' );

function delaware_nav_menu_extra_items() {
	echo sprintf(
		'<a href="#" class="toggle-search"><i class="fa fa-search" aria-hidden="true"></i></a>
			<form method="get" class="search-form" action="%s">
				<input type="search" class="search-field" placeholder="%s..." value="%s" name="s">
				<input type="submit" class="search-submit" value="%s">
			</form>',
		esc_url( home_url( '/' ) ),
		esc_attr__( 'Search', 'delaware' ),
		esc_attr( get_search_query() ),
		esc_attr__( 'Search', 'delaware' )
	);
}