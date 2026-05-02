<?php
/**
 * Martfury theme customizer
 *
 * @package Martfury
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Delaware_Customize {
	/**
	 * Customize settings
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * The class constructor
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->config = $config;

		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		$this->register();
	}

	/**
	 * Register settings
	 */
	public function register() {

		/**
		 * Add the theme configuration
		 */
		if ( ! empty( $this->config['theme'] ) ) {
			Kirki::add_config(
				$this->config['theme'], array(
					'capability'  => 'edit_theme_options',
					'option_type' => 'theme_mod',
				)
			);
		}

		/**
		 * Add panels
		 */
		if ( ! empty( $this->config['panels'] ) ) {
			foreach ( $this->config['panels'] as $panel => $settings ) {
				Kirki::add_panel( $panel, $settings );
			}
		}

		/**
		 * Add sections
		 */
		if ( ! empty( $this->config['sections'] ) ) {
			foreach ( $this->config['sections'] as $section => $settings ) {
				Kirki::add_section( $section, $settings );
			}
		}

		/**
		 * Add fields
		 */
		if ( ! empty( $this->config['theme'] ) && ! empty( $this->config['fields'] ) ) {
			foreach ( $this->config['fields'] as $name => $settings ) {
				if ( ! isset( $settings['settings'] ) ) {
					$settings['settings'] = $name;
				}

				Kirki::add_field( $this->config['theme'], $settings );
			}
		}
	}

	/**
	 * Get config ID
	 *
	 * @return string
	 */
	public function get_theme() {
		return $this->config['theme'];
	}

	/**
	 * Get customize setting value
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option( $name ) {

		$default = $this->get_option_default( $name );

		return get_theme_mod( $name, $default );
	}

	/**
	 * Get default option values
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_option_default( $name ) {
		if ( ! isset( $this->config['fields'][ $name ] ) ) {
			return false;
		}

		return isset( $this->config['fields'][ $name ]['default'] ) ? $this->config['fields'][ $name ]['default'] : false;
	}
}

/**
 * This is a short hand function for getting setting value from customizer
 *
 * @param string $name
 *
 * @return bool|string
 */
function delaware_get_option( $name ) {
	global $delaware_customize;

	$value = false;

	if ( class_exists( 'Kirki' ) ) {
		$value = Kirki::get_option( 'delaware', $name );
	} elseif ( ! empty( $delaware_customize ) ) {
		$value = $delaware_customize->get_option( $name );
	}

	return apply_filters( 'delaware_get_option', $value, $name );
}


/**
 * Get default option values
 *
 * @param $name
 *
 * @return mixed
 */
function delaware_get_option_default( $name ) {
	global $delaware_customize;

	if ( empty( $delaware_customize ) ) {
		return false;
	}

	return $delaware_customize->get_option_default( $name );
}

/**
 * Move some default sections to `general` panel that registered by theme
 *
 * @param object $wp_customize
 */
function delaware_customize_modify( $wp_customize ) {
	$wp_customize->get_section( 'title_tagline' )->panel     = 'general';
	$wp_customize->get_section( 'static_front_page' )->panel = 'general';
}

add_action( 'customize_register', 'delaware_customize_modify' );


/**
 * Get customize settings
 *
 * @return array
 */
function delaware_customize_settings() {
	/**
	 * Customizer configuration
	 */

	$settings = array(
		'theme' => 'delaware',
	);

	$panels = array(
		'general'    => array(
			'priority' => 5,
			'title'    => esc_html__( 'General', 'delaware' ),
		),
		'typography' => array(
			'priority' => 10,
			'title'    => esc_html__( 'Typography', 'delaware' ),
		),
		'styling'    => array(
			'priority' => 10,
			'title'    => esc_html__( 'Styling', 'delaware' ),
		),
		'header'     => array(
			'priority' => 10,
			'title'    => esc_html__( 'Header', 'delaware' ),
		),
		'blog'       => array(
			'title'      => esc_html__( 'Blog', 'delaware' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'footer'     => array(
			'title'      => esc_html__( 'Footer', 'delaware' ),
			'priority'   => 350,
			'capability' => 'edit_theme_options',
		),
		'service'    => array(
			'title'      => esc_html__( 'Service', 'delaware' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'page'       => array(
			'priority' => 10,
			'title'    => esc_html__( 'Page', 'delaware' ),
		),
		'portfolio'  => array(
			'title'      => esc_html__( 'Portfolio', 'delaware' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
	);

	$sections = array(
		'topbar'                       => array(
			'title'       => esc_html__( 'Topbar', 'delaware' ),
			'description' => '',
			'priority'    => 5,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header'                       => array(
			'title'       => esc_html__( 'Header', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'logo'                         => array(
			'title'       => esc_html__( 'Logo', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'blog_page_header'             => array(
			'title'       => esc_html__( 'Page Header on Blog Page', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'blog_page'                    => array(
			'title'       => esc_html__( 'Blog Page', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'single_post'                  => array(
			'title'       => esc_html__( 'Single Post', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'footer_setting'               => array(
			'title'       => esc_html__( 'Footer Setting', 'delaware' ),
			'description' => '',
			'priority'    => 350,
			'panel'       => 'footer',
		),
		'footer_newsletter'            => array(
			'title'       => esc_html__( 'Footer Newsletter', 'delaware' ),
			'description' => '',
			'priority'    => 350,
			'panel'       => 'footer',
		),
		'footer_widgets'               => array(
			'title'       => esc_html__( 'Footer Widgets', 'delaware' ),
			'description' => '',
			'priority'    => 350,
			'panel'       => 'footer',
		),
		'footer_copyright'             => array(
			'title'       => esc_html__( 'Footer Copyright', 'delaware' ),
			'description' => '',
			'priority'    => 350,
			'panel'       => 'footer',
		),
		'service_page'                 => array(
			'title'      => esc_html__( 'Service Page', 'delaware' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
			'panel'      => 'service',
		),
		'service_page_header'          => array(
			'title'       => esc_html__( 'Page Header on Service Page', 'delaware' ),
			'description' => '',
			'priority'    => 9,
			'capability'  => 'edit_theme_options',
			'panel'       => 'service',
		),
		'single_service_page_header'   => array(
			'title'       => esc_html__( 'Page Header on Single Service', 'delaware' ),
			'description' => '',
			'priority'    => 11,
			'capability'  => 'edit_theme_options',
			'panel'       => 'service',
		),
		'portfolio_page_header'        => array(
			'title'       => esc_html__( 'Page Header on Portfolio Page', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'portfolio',
		),
		'portfolio_page'               => array(
			'title'       => esc_html__( 'Portfolio Page', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'portfolio',
		),
		'single_portfolio_page_header' => array(
			'title'       => esc_html__( 'Page Header on Single Portfolio', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'portfolio',
		),
		'single_portfolio'             => array(
			'title'       => esc_html__( 'Single Portfolio', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'portfolio',
		),
		'color_scheme'                 => array(
			'title'       => esc_html__( 'Color Scheme', 'delaware' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),

		// Shop Page
		'shop_page_header'             => array(
			'title'       => esc_html__( 'Page Header on Shop Page', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'woocommerce',
		),
		'woocommerce_product_catalog'  => array(
			'title'       => esc_html__( 'Product Catalog', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'woocommerce',
		),
		'single_product_page_header'   => array(
			'title'       => esc_html__( 'Page Header on Single Product', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'woocommerce',
		),
		'single_product'               => array(
			'title'       => esc_html__( 'Single Product', 'delaware' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'woocommerce',
		),

		'page_layout'  => array(
			'title'      => esc_html__( 'Page Layout', 'delaware' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
			'panel'      => 'page',
		),
		'page_header'  => array(
			'title'       => esc_html__( 'Page Header', 'delaware' ),
			'description' => esc_html__( 'Work on page default.', 'delaware' ),
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'page',
		),
		'body_typo'    => array(
			'title'       => esc_html__( 'Body', 'delaware' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'heading_typo' => array(
			'title'       => esc_html__( 'Heading', 'delaware' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'header_typo'  => array(
			'title'       => esc_html__( 'Header', 'delaware' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'footer_typo'  => array(
			'title'       => esc_html__( 'Footer', 'delaware' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
	);

	$fields = array(
		// Topbar
		'topbar_enable'     => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show topbar', 'delaware' ),
			'section'  => 'topbar',
			'default'  => 1,
			'priority' => 10,
		),
		'topbar_background' => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Topbar Background Color', 'delaware' ),
			'default'  => '',
			'section'  => 'topbar',
			'priority' => 10,
		),

		'topbar_custom_field_1' => array(
			'type'    => 'custom',
			'section' => 'topbar',
			'default' => '<hr/>',
		),

		'topbar_mobile_content' => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Topbar Mobile justify content', 'delaware' ),
			'section'  => 'topbar',
			'default'  => 'flex-start',
			'priority' => 10,
			'choices'  => array(
				'flex-start'    => esc_html__( 'Flex Start', 'delaware' ),
				'flex-end'      => esc_html__( 'Flex End', 'delaware' ),
				'center'        => esc_html__( 'Center', 'delaware' ),
				'space-between' => esc_html__( 'Space Between', 'delaware' ),
			),
		),

		// Typography
		'body_typo'             => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Body', 'delaware' ),
			'section'  => 'body_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Rubik',
				'variant'        => '400',
				'font-size'      => '16px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#9b9ea8',
				'text-transform' => 'none',
			),
		),
		'heading1_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 1', 'delaware' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'font-size'      => '36px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading2_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 2', 'delaware' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'font-size'      => '30px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading3_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 3', 'delaware' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'font-size'      => '24px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading4_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 4', 'delaware' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'font-size'      => '18px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading5_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 5', 'delaware' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'font-size'      => '16px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading6_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 6', 'delaware' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'font-size'      => '12px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'menu_typo'             => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Menu', 'delaware' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '15px',
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'sub_menu_typo'         => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Sub Menu', 'delaware' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Poppins',
				'variant'        => '600',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '14px',
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'footer_text_typo'      => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Footer Text', 'delaware' ),
			'section'  => 'footer_typo',
			'priority' => 10,
			'default'  => array(
				'font-family' => 'Rubik',
				'variant'     => '400',
				'subsets'     => array( 'latin-ext' ),
				'font-size'   => '16px',
			),
		),

		// Color Scheme
		'color_scheme'          => array(
			'type'     => 'palette',
			'label'    => esc_html__( 'Base Color Scheme', 'delaware' ),
			'default'  => '',
			'section'  => 'color_scheme',
			'priority' => 10,
			'choices'  => array(
				''        => array( '#2685f9' ),
				'#ee4300' => array( '#ee4300' ),
				'#642020' => array( '#642020' ),
				'#11166d' => array( '#11166d' ),
				'#dede0b' => array( '#dede0b' ),
			),
		),
		'custom_color_scheme'   => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Color Scheme', 'delaware' ),
			'default'  => 0,
			'section'  => 'color_scheme',
			'priority' => 10,
		),
		'custom_color'          => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'delaware' ),
			'default'         => '',
			'section'         => 'color_scheme',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'custom_color_scheme',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		// Header layout
		'header_layout'         => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Header Layout', 'delaware' ),
			'section'  => 'header',
			'default'  => 'v1',
			'priority' => 10,
			'choices'  => array(
				'v1' => esc_html__( 'Header v1', 'delaware' ),
				'v2' => esc_html__( 'Header v2', 'delaware' ),
				'v3' => esc_html__( 'Header v3', 'delaware' ),
				'v4' => esc_html__( 'Header v4', 'delaware' ),
				'v5' => esc_html__( 'Header v5', 'delaware' ),
				'v6' => esc_html__( 'Header v6', 'delaware' ),
			),
		),

		'header_bg' => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Header Background', 'delaware' ),
			'section'         => 'header',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v3',
				),
			),
		),

		'header_transparent' => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Header Transparent', 'delaware' ),
			'default'  => 0,
			'section'  => 'header',
			'priority' => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v5',
				),
			),
		),

		'header_sticky' => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Header Sticky', 'delaware' ),
			'default'  => 0,
			'section'  => 'header',
			'priority' => 10,
		),

		'header_custom_field_1' => array(
			'type'    => 'custom',
			'section' => 'header',
			'default' => '<hr/>',
		),
		'search_extra_item'     => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Search Extra Item', 'delaware' ),
			'default'         => 1,
			'section'         => 'header',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( 'v1', 'v4' ),
				),
			),
		),

		'socials_extra_item' => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Socials Extra Item', 'delaware' ),
			'default'         => 1,
			'section'         => 'header',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( 'v1', 'v2', 'v4', 'v5' ),
				),
			),
		),

		'menu_socials' => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Menu Socials', 'delaware' ),
			'section'         => 'header',
			'priority'        => 10,
			'default'         => array(),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'delaware' ),
					'description' => esc_html__( 'Enter the URL for this social', 'delaware' ),
					'default'     => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( 'v1', 'v2', 'v4', 'v5' ),
				),
				array(
					'setting'  => 'socials_extra_item',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		'cart_extra_item' => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Cart Extra Item', 'delaware' ),
			'default'         => 1,
			'section'         => 'header',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( 'v5', 'v6' ),
				),
			),
		),

		'text_extra_item'             => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Text Extra Item', 'delaware' ),
			'default'         => '',
			'section'         => 'header',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => '==',
					'value'    => 'v3',
				),
			),
		),

		// Logo
		'logo_dark'                   => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo', 'delaware' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_light'                  => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo Light', 'delaware' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_width'                  => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Logo Width', 'delaware' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_height'                 => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Logo Height', 'delaware' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_position'               => array(
			'type'     => 'spacing',
			'label'    => esc_html__( 'Logo Margin', 'delaware' ),
			'section'  => 'logo',
			'priority' => 10,
			'default'  => array(
				'top'    => '0',
				'bottom' => '0',
				'left'   => '0',
				'right'  => '0',
			),
		),

		// Blog Page Header
		'blog_page_header'            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'blog_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'blog_page_header_els'        => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'blog_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_page_header_bg'         => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'blog_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_page_header_text_color' => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'blog_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for blog page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'blog_page_header_parallax'   => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'blog_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Blog
		'blog_view'                   => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Blog View', 'delaware' ),
			'section'  => 'blog_page',
			'default'  => 'classic',
			'priority' => 10,
			'choices'  => array(
				'grid'    => esc_html__( 'Grid', 'delaware' ),
				'classic' => esc_html__( 'Classic', 'delaware' ),
				'masonry' => esc_html__( 'Masonry', 'delaware' ),
			),
		),

		'blog_layout' => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Blog Layout', 'delaware' ),
			'section'         => 'blog_page',
			'default'         => 'full-content',
			'priority'        => 10,
			'description'     => esc_html__( 'Select default sidebar for blog page.', 'delaware' ),
			'choices'         => array(
				'content-sidebar' => esc_html__( 'Right Sidebar', 'delaware' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'delaware' ),
				'full-content'    => esc_html__( 'Full Content', 'delaware' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_view',
					'operator' => '==',
					'value'    => 'classic',
				),
			),
		),

		'blog_grid_columns'   => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Blog Grid Columns', 'delaware' ),
			'section'         => 'blog_page',
			'default'         => '3',
			'priority'        => 10,
			'choices'         => array(
				'3' => esc_html__( '3 Columns', 'delaware' ),
				'2' => esc_html__( '2 Columns', 'delaware' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_view',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
		'blog_entry_meta'     => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Entry Meta', 'delaware' ),
			'section'  => 'blog_page',
			'default'  => array( 'author', 'comment', 'cat' ),
			'choices'  => array(
				'author'  => esc_html__( 'Author', 'delaware' ),
				'comment' => esc_html__( 'Comment', 'delaware' ),
				'cat'     => esc_html__( 'Category', 'delaware' ),
			),
			'priority' => 10,
		),
		'excerpt_length_blog' => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Limit content blog', 'delaware' ),
			'section'  => 'blog_page',
			'default'  => 50,
			'priority' => 10,
		),

		'blog_nav_type'      => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Type of Navigation', 'delaware' ),
			'section'  => 'blog_page',
			'default'  => 'numbers',
			'priority' => 90,
			'choices'  => array(
				'numbers' => esc_html__( 'Page Numbers', 'delaware' ),
				'ajax'    => esc_html__( 'Load Ajax', 'delaware' ),
			),
		),


		// Single Posts
		'single_post_layout' => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Single Post Layout', 'delaware' ),
			'section'     => 'single_post',
			'default'     => 'full-content',
			'priority'    => 5,
			'description' => esc_html__( 'Select default sidebar for the single post page.', 'delaware' ),
			'choices'     => array(
				'content-sidebar' => esc_html__( 'Right Sidebar', 'delaware' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'delaware' ),
				'full-content'    => esc_html__( 'Full Content', 'delaware' ),
			),
		),

		'post_entry_meta'         => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Entry Meta', 'delaware' ),
			'section'  => 'single_post',
			'default'  => array( 'author', 'date', 'cat' ),
			'choices'  => array(
				'author' => esc_html__( 'Author', 'delaware' ),
				'date'   => esc_html__( 'Date', 'delaware' ),
				'cat'    => esc_html__( 'Categories', 'delaware' ),
			),
			'priority' => 10,
		),
		'show_post_author_header' => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Author Top', 'delaware' ),
			'description' => esc_html__( 'Check this option to show author on entry header in the single post page.', 'delaware' ),
			'section'     => 'single_post',
			'default'     => 1,
			'priority'    => 10,
		),
		'show_post_social_share'  => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Socials Share', 'delaware' ),
			'description' => esc_html__( 'Check this option to show socials share in the single post page.', 'delaware' ),
			'section'     => 'single_post',
			'default'     => 1,
			'priority'    => 10,
		),
		'post_socials_share'      => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Socials Share', 'delaware' ),
			'section'         => 'single_post',
			'default'         => array( 'facebook', 'twitter', 'linkedin' ),
			'choices'         => array(
				'twitter'   => esc_html__( 'Twitter', 'delaware' ),
				'facebook'  => esc_html__( 'Facebook', 'delaware' ),
				'google'    => esc_html__( 'Google Plus', 'delaware' ),
				'pinterest' => esc_html__( 'Pinterest', 'delaware' ),
				'linkedin'  => esc_html__( 'Linkedin', 'delaware' ),
				'vkontakte' => esc_html__( 'Vkontakte', 'delaware' ),
			),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'show_post_social_share',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		'show_author_box' => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Author Box', 'delaware' ),
			'section'  => 'single_post',
			'default'  => 1,
			'priority' => 30,
		),

		// Footer
		'back_to_top'     => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Back to Top', 'delaware' ),
			'section'  => 'footer_setting',
			'default'  => 1,
			'priority' => 10,
		),

		'footer_border_color' => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Border Color', 'delaware' ),
			'default'  => '',
			'section'  => 'footer_setting',
			'priority' => 10,
		),

		'footer_background_color'      => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'delaware' ),
			'default'  => '',
			'section'  => 'footer_setting',
			'priority' => 10,
		),
		'footer_background_image'      => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Background Image', 'delaware' ),
			'default'  => '',
			'section'  => 'footer_setting',
			'priority' => 10,
		),
		'footer_background_horizontal' => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Horizontal', 'delaware' ),
			'section'  => 'footer_setting',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''       => esc_html__( 'None', 'delaware' ),
				'left'   => esc_html__( 'Left', 'delaware' ),
				'center' => esc_html__( 'Center', 'delaware' ),
				'right'  => esc_html__( 'Right', 'delaware' ),
			),
		),
		'footer_background_vertical'   => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Vertical', 'delaware' ),
			'section'  => 'footer_setting',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''       => esc_html__( 'None', 'delaware' ),
				'top'    => esc_html__( 'Top', 'delaware' ),
				'center' => esc_html__( 'Center', 'delaware' ),
				'bottom' => esc_html__( 'Bottom', 'delaware' ),
			),
		),
		'footer_background_repeat'     => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Repeat', 'delaware' ),
			'section'  => 'footer_setting',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''          => esc_html__( 'None', 'delaware' ),
				'no-repeat' => esc_html__( 'No Repeat', 'delaware' ),
				'repeat'    => esc_html__( 'Repeat', 'delaware' ),
				'repeat-y'  => esc_html__( 'Repeat Vertical', 'delaware' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontal', 'delaware' ),
			),
		),
		'footer_background_attachment' => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Attachment', 'delaware' ),
			'section'  => 'footer_setting',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''       => esc_html__( 'None', 'delaware' ),
				'scroll' => esc_html__( 'Scroll', 'delaware' ),
				'fixed'  => esc_html__( 'Fixed', 'delaware' ),
			),
		),
		'footer_background_size'       => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Size', 'delaware' ),
			'section'  => 'footer_setting',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''        => esc_html__( 'None', 'delaware' ),
				'auto'    => esc_html__( 'Auto', 'delaware' ),
				'cover'   => esc_html__( 'Cover', 'delaware' ),
				'contain' => esc_html__( 'Contain', 'delaware' ),
			),
		),

		// Newsletter
		'footer_top'                   => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Newsletter', 'delaware' ),
			'section'  => 'footer_newsletter',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_newsletter_styles'     => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Newsletter Styles', 'delaware' ),
			'section'         => 'footer_newsletter',
			'default'         => '2',
			'priority'        => 10,
			'choices'         => array(
				'1' => esc_html__( 'Style 1', 'delaware' ),
				'2' => esc_html__( 'Style 2', 'delaware' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_top',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		'footer_top_left' => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Description', 'delaware' ),
			'section'         => 'footer_newsletter',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'footer_top',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		'footer_top_right'                  => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Shortcodes', 'delaware' ),
			'section'         => 'footer_newsletter',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'footer_top',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Footer Widget
		'footer_widget_columns'             => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Footer Columns', 'delaware' ),
			'section'     => 'footer_widgets',
			'default'     => '3',
			'priority'    => 10,
			'choices'     => array(
				'3' => esc_html__( '3 Columns', 'delaware' ),
				'4' => esc_html__( '4 Columns', 'delaware' ),
				'2' => esc_html__( '2 Columns', 'delaware' ),
			),
			'description' => esc_html__( 'Go to Appearance > Widgets > Footer 1, 2, 3, 4 to add widgets content.', 'delaware' ),
		),
		'footer_widget_text_color'          => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'footer_widgets',
			'default'     => 'light',
			'priority'    => 10,
			'choices'     => array(
				'light' => esc_html__( 'Light', 'delaware' ),
				'dark'  => esc_html__( 'Dark', 'delaware' ),
			),
			'description' => esc_html__( 'Footer Widget text color', 'delaware' ),
		),
		'footer_widget_title_style'         => array(
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Widget Title Style', 'delaware' ),
			'section'  => 'footer_widgets',
			'default'  => false,
			'priority' => 10,
		),

		// Footer Copyright
		'footer_copyright_background_color' => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'delaware' ),
			'default'  => '',
			'section'  => 'footer_copyright',
			'priority' => 10,
		),
		'footer_copyright_border_top'       => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Border Top', 'delaware' ),
			'section'  => 'footer_copyright',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_bottom_show_logo'           => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Logo Footer', 'delaware' ),
			'section'  => 'footer_copyright',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_bottom_logo_image'          => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Footer Logo Bottom', 'delaware' ),
			'section'         => 'footer_copyright',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'footer_bottom_show_logo',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		'footer_copyright' => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Footer Copyright', 'delaware' ),
			'description' => esc_html__( 'Shortcodes are allowed', 'delaware' ),
			'section'     => 'footer_copyright',
			'default'     => esc_html__( 'Copyright &copy; 2018', 'delaware' ),
			'priority'    => 10,
		),
		'service_columns'  => array(
			'type'     => 'radio',
			'label'    => esc_html__( 'Select Service Columns', 'delaware' ),
			'priority' => 10,
			'default'  => 2,
			'section'  => 'service_page',
			'choices'  => array(
				2 => esc_attr__( '2 Columns', 'delaware' ),
				3 => esc_attr__( '3 Columns', 'delaware' ),
				4 => esc_attr__( '4 Columns', 'delaware' ),
			),
		),

		'service_excerpt_descr'                   => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Excerpt length', 'delaware' ),
			'priority' => 10,
			'default'  => '10',
			'section'  => 'service_page',

		),

		// Service Page Header
		'service_page_header'                     => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'service_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'service_page_header_els'                 => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'service_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'service_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'service_page_header_text_color'          => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'service_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for service page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'service_page_header_bg'                  => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'service_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'service_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'service_page_header_parallax'            => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'service_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'service_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Page Header Single Service
		'single_service_page_header'              => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'single_service_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'single_service_page_header_els'          => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'single_service_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'single_service_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_service_page_header_bg'           => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'single_service_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'single_service_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_service_page_header_text_color'   => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'single_service_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for single service page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'single_service_page_header_parallax'     => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'single_service_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'single_service_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Page Header Single Portfolio
		'single_portfolio_page_header'            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'single_portfolio_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'single_portfolio_page_header_els'        => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'single_portfolio_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'single_portfolio_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_portfolio_page_header_bg'         => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'single_portfolio_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'single_portfolio_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_portfolio_page_header_text_color' => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'single_portfolio_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for single portfolio page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'single_portfolio_page_header_parallax'   => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'single_portfolio_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'single_portfolio_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Portfolio Page Header
		'portfolio_page_header'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'portfolio_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'portfolio_page_header_els'               => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'portfolio_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'portfolio_page_header_bg'                => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'portfolio_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'portfolio_page_header_text_color'        => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'portfolio_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for portfolio page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'portfolio_page_header_parallax'          => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'portfolio_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Portfolio

		'delaware_portfolio_layout' => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Portfolio layout', 'delaware' ),
			'priority' => 10,
			'default'  => 'grid',
			'section'  => 'portfolio_page',
			'choices'  => array(
				'grid'    => esc_attr__( 'Grid', 'delaware' ),
				'masonry' => esc_attr__( 'Masonry', 'delaware' ),
			),
		),


		'delaware_portfolio_columns' => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Portfolio Columns', 'delaware' ),
			'priority'        => 10,
			'default'         => 2,
			'section'         => 'portfolio_page',
			'choices'         => array(
				2 => esc_attr__( '2 columns', 'delaware' ),
				3 => esc_attr__( '3 columns', 'delaware' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'delaware_portfolio_layout',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),

		'portfolio_cats_filters' => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Portfolio Categories Filters', 'delaware' ),
			'priority' => 10,
			'default'  => '1',
			'section'  => 'portfolio_page',

		),
		'portfolio_cat_number'   => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Categorize  number', 'delaware' ),
			'priority'        => 10,
			'default'         => 5,
			'section'         => 'portfolio_page',
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_cats_filters',
					'operator' => '==',
					'value'    => '1',
				),
			),

		),

		'portfolio_excerpt_descr' => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Excerpt number', 'delaware' ),
			'priority' => 10,
			'default'  => 10,
			'section'  => 'portfolio_page',
		),

		'portfolio_pagination_style' => array(
			'type'     => 'radio',
			'label'    => esc_html__( 'Pagination style', 'delaware' ),
			'priority' => 10,
			'section'  => 'portfolio_page',
			'default'  => 'numbers',
			'choices'  => array(
				'numbers' => esc_html__( 'Page Numbers', 'delaware' ),
				'ajax'    => esc_html__( 'Load Ajax', 'delaware' ),
			),
		),

		'portfolio_related'                     => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Related Portfolio', 'delaware' ),
			'priority' => 10,
			'default'  => 1,
			'section'  => 'single_portfolio',

		),
		'portfolio_related_item'                => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Related Portfolio number', 'delaware' ),
			'priority' => 10,
			'default'  => 3,
			'section'  => 'single_portfolio',

		),
		'portfolio_related_title'               => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Related title', 'delaware' ),
			'priority' => 10,
			'default'  => esc_html__( 'Latest Case Studies', 'delaware' ),
			'section'  => 'single_portfolio',

		),
		// Page Header for Shop
		'page_header_shop'                      => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'shop_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'page_header_shop_els'                  => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'shop_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_shop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_shop_bg'                   => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'shop_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'page_header_shop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_shop_text_color'           => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'shop_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for catalog page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'page_header_shop_parallax'             => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'shop_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_shop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Shop Page
		'shop_layout'                           => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Shop Layout', 'delaware' ),
			'default'     => 'full-content',
			'section'     => 'woocommerce_product_catalog',
			'priority'    => 20,
			'description' => esc_html__( 'Select default sidebar for shop page.', 'delaware' ),
			'choices'     => array(
				'sidebar-content' => esc_html__( 'Left Sidebar', 'delaware' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'delaware' ),
				'full-content'    => esc_html__( 'Full Content', 'delaware' ),
			),
		),

		// Page
		'page_layout'                           => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Page Layout', 'delaware' ),
			'section'     => 'page_layout',
			'default'     => 'full-content',
			'priority'    => 10,
			'description' => esc_html__( 'Select default sidebar for page default.', 'delaware' ),
			'choices'     => array(
				'full-content'    => esc_html__( 'Full Content', 'delaware' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'delaware' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'delaware' ),
			),
		),
		'page_page_header'                      => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'page_page_header_els'                  => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_page_header_bg'                   => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'page_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_page_header_text_color'           => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'page_header',
			'default'     => 'dark',
			'priority'    => 30,
			'description' => esc_html__( 'Select default color for default page page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'page_page_header_parallax'             => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Single Product Page Header
		'single_product_page_header'            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'delaware' ),
			'default'     => 1,
			'section'     => 'single_product_page_header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show the page header below the header.', 'delaware' ),
		),
		'single_product_page_header_els'        => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'delaware' ),
			'section'         => 'single_product_page_header',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'delaware' ),
				'title'      => esc_html__( 'Title', 'delaware' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_product_page_header_bg'         => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'delaware' ),
			'section'         => 'single_product_page_header',
			'default'         => '',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_product_page_header_text_color' => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Text Color', 'delaware' ),
			'section'     => 'single_product_page_header',
			'default'     => 'dark',
			'priority'    => 10,
			'description' => esc_html__( 'Select default color for single product page header.', 'delaware' ),
			'choices'     => array(
				'dark'  => esc_html__( 'Dark', 'delaware' ),
				'light' => esc_html__( 'Light', 'delaware' ),
			),
		),
		'single_product_page_header_parallax'   => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Parallax', 'delaware' ),
			'default'         => 1,
			'section'         => 'single_product_page_header',
			'priority'        => 30,
			'description'     => esc_html__( 'Check this option to enable parallax for the page header.', 'delaware' ),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Single Product
		'single_product_layout'                 => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Single Product Layout', 'delaware' ),
			'default'     => 'full-content',
			'section'     => 'single_product',
			'priority'    => 10,
			'description' => esc_html__( 'Select default sidebar for the single post page.', 'delaware' ),
			'choices'     => array(
				'sidebar-content' => esc_html__( 'Left Sidebar', 'delaware' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'delaware' ),
				'full-content'    => esc_html__( 'Full Content', 'delaware' ),
			),
		),
		'single_product_custom_field_1'         => array(
			'type'    => 'custom',
			'section' => 'single_product',
			'default' => '<hr/>',
		),
		'related_product'                       => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Related Products', 'delaware' ),
			'section'     => 'single_product',
			'description' => esc_html__( 'Check this option to show related posts in the single post page.', 'delaware' ),
			'default'     => 1,
			'priority'    => 20,
		),
		'related_product_title'                 => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Related Products Title', 'delaware' ),
			'section'  => 'single_product',
			'default'  => esc_html__( 'Related Products', 'delaware' ),
			'priority' => 20,

			'active_callback' => array(
				array(
					'setting'  => 'related_product',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		'related_product_numbers'               => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Related Products Numbers', 'delaware' ),
			'section'         => 'single_product',
			'default'         => '3',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'related_product',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		'related_product_columns'               => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Related Products Columns', 'delaware' ),
			'default'     => '4',
			'section'     => 'single_product',
			'priority'    => 20,
			'description' => esc_html__( 'Select product columns for related product.', 'delaware' ),
			'choices'     => array(
				'4' => esc_html__( '4 Columns', 'delaware' ),
				'3' => esc_html__( '3 Columns', 'delaware' ),
			),
		),
	);

	$settings['panels']   = apply_filters( 'delaware_customize_panels', $panels );
	$settings['sections'] = apply_filters( 'delaware_customize_sections', $sections );
	$settings['fields']   = apply_filters( 'delaware_customize_fields', $fields );

	return $settings;
}

$delaware_customize = new Delaware_Customize( delaware_customize_settings() );


