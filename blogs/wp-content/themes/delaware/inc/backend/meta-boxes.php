<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


/**
 * Enqueue script for handling actions with meta boxes
 *
 * @since 1.0
 *
 * @param string $hook
 */
function delaware_meta_box_scripts( $hook ) {
	// Detect to load un-minify scripts when WP_DEBUG is enable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'delaware-meta-boxes', get_template_directory_uri() . "/js/backend/meta-boxes$min.js", array( 'jquery' ), '', true );
	}
}

add_action( 'admin_enqueue_scripts', 'delaware_meta_box_scripts' );

/**
 * Registering meta boxes
 *
 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
 *
 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 *
 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
 *
 * @return array All registered meta boxes
 */
function delaware_register_meta_boxes( $meta_boxes ) {
	// Post format's meta box
	$meta_boxes[] = array(
		'id'       => 'post-format-settings',
		'title'    => esc_html__( 'Format Details', 'delaware' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'             => esc_html__( 'Image', 'delaware' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'  => esc_html__( 'Gallery', 'delaware' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => esc_html__( 'Video', 'delaware' ),
				'id'    => 'video',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'video',
			),
		),
	);

	//Page Header Settings
	$meta_boxes[] = array(
		'id'       => 'page-header-settings',
		'title'    => esc_html__( 'Page Header Settings', 'delaware' ),
		'pages'    => array( 'post', 'page', 'portfolio', 'service', 'product' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name'  => esc_html__( 'Hide Page Header', 'delaware' ),
				'id'    => 'hide_page_header',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Breadcrumb', 'delaware' ),
				'id'    => 'hide_breadcrumb',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Title', 'delaware' ),
				'id'    => 'hide_title',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Disable Parallax', 'delaware' ),
				'id'    => 'hide_parallax',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'    => esc_html__( 'Text Color', 'delaware' ),
				'id'      => 'text_color',
				'type'    => 'select',
				'std'     => 'dark',
				'options' => array(
					'dark'  => esc_html__( 'Dark', 'delaware' ),
					'light' => esc_html__( 'Light', 'delaware' ),
				),
				'class'   => 'hide-homepage',
			),
			array(
				'name'             => esc_html__( 'Background Image', 'delaware' ),
				'id'               => 'page_bg',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'std'              => false,
				'class'            => 'hide-homepage',
			),
		),
	);

	// Portfolio
	$meta_boxes[] = array(
		'id'       => 'portfolio-info',
		'title'    => esc_html__( 'Portfolio Info', 'delaware' ),
		'pages'    => array( 'portfolio' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'  => esc_html__( 'Client', 'delaware' ),
				'id'    => 'client',
				'type'  => 'text',
				'class' => 'client',
			),
			array(
				'name'  => esc_html__( 'Website', 'delaware' ),
				'id'    => 'website',
				'type'  => 'text',
				'class' => 'website',
			),
			array(
				'name'       => esc_html__( 'Rating', 'delaware' ),
				'id'         => 'rating',
				'type'       => 'slider',
				'js_options' => array(
					'min'  => 0,
					'max'  => 10,
					'step' => 1,
				),
			),
		),
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'delaware_register_meta_boxes' );

function delaware_notice__success() {

	if ( ! function_exists('delaware_vc_addons_init') ) {
		return;
	}

	$versions = get_plugin_data( WP_PLUGIN_DIR . '/delaware-addons/delaware-addons.php' );
	if ( version_compare( $versions['Version'], '1.1.0', '>=' ) ) {
		return;
	}

	?>
	<div class="notice notice-info is-dismissible">
		<p><strong><?php esc_html_e( 'The Delaware Addons plugin needs to be updated to 1.1 to ensure maximum compatibility with this theme. If you do not update it, your widgets will be lost.', 'delaware' ); ?></strong></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'delaware_notice__success' );