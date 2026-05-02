<?php
/**
 * Load and register widgets
 *
 * @package Delaware
 */

require_once DELAWARE_ADDONS_DIR . '/inc/widgets/recent-posts.php';
require_once DELAWARE_ADDONS_DIR . '/inc/widgets/socials.php';
require_once DELAWARE_ADDONS_DIR . '/inc/widgets/newsletter.php';
require_once DELAWARE_ADDONS_DIR . '/inc/widgets/custom-menu.php';
require_once DELAWARE_ADDONS_DIR . '/inc/widgets/office-location.php';
require_once DELAWARE_ADDONS_DIR . '/inc/widgets/portfolio.php';

if ( ! function_exists( 'delaware_register_widgets' ) ) {

	/**
	 * Register widgets
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	function delaware_register_widgets() {
		register_widget( 'Delaware_Recent_Posts_Widget' );
		register_widget( 'Delaware_Social_Links_Widget' );
		register_widget( 'Delaware_Newsletter_Widget' );
		register_widget( 'Delaware_Custom_Menu_Widget' );
		register_widget( 'Delaware_Office_Location_Widget' );
		register_widget( 'Delaware_Portfolio_Widget' );
	}

	add_action( 'widgets_init', 'delaware_register_widgets' );

}