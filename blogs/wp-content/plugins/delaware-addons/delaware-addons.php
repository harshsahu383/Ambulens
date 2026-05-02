<?php
/**
 * Plugin Name: Delaware Addons
 * Plugin URI: http://steelthemes.com/plugins/delaware-addons.zip
 * Description: Extra elements for Visual Composer. It was built for Delaware theme.
 * Version: 1.1.2
 * Author: SteelThemes
 * Author URI: http://steelthemes.com
 * License: GPL2+
 * Text Domain: delaware
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'DELAWARE_ADDONS_DIR' ) ) {
	define( 'DELAWARE_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'DELAWARE_ADDONS_URL' ) ) {
	define( 'DELAWARE_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once DELAWARE_ADDONS_DIR . '/inc/visual-composer.php';
require_once DELAWARE_ADDONS_DIR . '/inc/shortcodes.php';
require_once DELAWARE_ADDONS_DIR . '/inc/portfolio.php';
require_once DELAWARE_ADDONS_DIR . '/inc/services.php';
require_once DELAWARE_ADDONS_DIR . '/inc/socials.php';
require_once DELAWARE_ADDONS_DIR . '/inc/widgets/widgets.php';

if ( is_admin() ) {
	require_once DELAWARE_ADDONS_DIR . '/inc/importer.php';
}

/**
 * Init
 */
function delaware_vc_addons_init() {
	load_plugin_textdomain( 'delaware', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	new Delaware_VC;
	new Delaware_Shortcodes;
	new Delaware_Portfolio;
	new Delaware_Services;
}

add_action( 'after_setup_theme', 'delaware_vc_addons_init', 20 );