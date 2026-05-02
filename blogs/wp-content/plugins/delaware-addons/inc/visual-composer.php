<?php
/**
 * Custom functions for Visual Composer
 *
 * @package    Delaware
 * @subpackage Visual Composer
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Class Delaware
 *
 * @since 1.0.0
 */
class Delaware_VC {
	public $icons;

	/**
	 * Construction
	 */
	function __construct() {
		// Stop if VC is not installed
		if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
			return false;
		}

		add_action( 'init', array( $this, 'map_shortcodes' ), 20 );

		$this->icons = self::vc_svg_icon();

		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'svg_icons', array( $this, 'icon_param' ), DELAWARE_ADDONS_URL . '/assets/js/vc/icon-field.js' );
		} elseif ( function_exists( 'add_shortcode_param' ) ) {
			add_shortcode_param( 'svg_icons', array( $this, 'icon_param' ), DELAWARE_ADDONS_URL . '/assets/js/vc/icon-field.js' );
		} else {
			return false;
		}

		add_filter('vc_autocomplete_delaware_portfolio_grid_list_id_callback', array(
			$this,
			'portfolioIdsAutocompleteSuggester',
		), 10, 1);
		add_filter('vc_autocomplete_delaware_portfolio_grid_list_id_render', array(
			$this,
			'portfolioIdsAutocompleteRender',
		), 10, 1);

		add_filter('vc_autocomplete_delaware_portfolio_grid_categories_callback', array($this, 'portfolioCatsAutocompleteSuggester',), 10, 1);
		add_filter('vc_autocomplete_delaware_portfolio_grid_categories_render', array($this, 'portfolioCatsAutocompleteRender',), 10, 1);
	}

	/**
	 * Suggester for autocomplete by slug
	 *
	 *
	 * @return array - id's from portfolio cat with title/slug.
	 */
	public function portfolioCatsAutocompleteSuggester($query)
	{
		global $wpdb;
		$cat_id = (int)$query;
		$query = trim($query);
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'portfolio_category' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : -1, stripslashes($query), stripslashes($query)
			), ARRAY_A
		);

		$result = array();
		if (is_array($post_meta_infos) && !empty($post_meta_infos)) {
			foreach ($post_meta_infos as $value) {
				$data = array();
				$data['value'] = $value['slug'];
				$data['label'] = esc_html__('Id', 'delaware') . ': ' . $value['id'] . ' - ' . esc_html__('Name', 'delaware') . ': ' . $value['name'];
				$result[] = $data;
			}
		}

		return $result;
	}

	/**
	 * Find portfolio cat by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function portfolioCatsAutocompleteRender($query)
	{
		$query = $query['value'];
		$query = trim($query);
		$term = get_term_by('slug', $query, 'portfolio_category');

		if (is_wp_error($term) || !$term) {
			return false;
		}

		$data = array();
		$data['value'] = $term->slug;
		$data['label'] = esc_html__('Id', 'delaware') . ': ' . $term->term_id . ' - ' . esc_html__('Name', 'delaware') . ': ' . $term->name;


		return $data;
	}

	/**
	 * Suggester for autocomplete by slug
	 *
	 *
	 * @return array - id's from portfolio with title/slug.
	 */
	public function portfolioIdsAutocompleteSuggester($query)
	{
		$args = array(
			'post_type' => 'portfolio',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts' => true,
			's' => $query
		);

		$query = new WP_Query($args);
		$results = array();
		while ($query->have_posts()) : $query->the_post();
			$data = array();
			$data['value'] = get_the_ID();
			$data['label'] = esc_html__('Id', 'delaware') . ': ' . get_the_ID() . ' - ' . esc_html__('Title', 'delaware') . ': ' . get_the_title();
			$results[] = $data;

		endwhile;
		wp_reset_postdata();

		return $results;
	}

	/**
	 * Find portfolio by id
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function portfolioIdsAutocompleteRender($query)
	{
		$query = trim($query['value']); // get value from requested

		if (empty($query)) {
			return false;
		}

		$args = array(
			'post_type' => 'portfolio',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts' => true,
			'p' => intval($query)
		);

		$query = new WP_Query($args);
		$data = array();
		while ($query->have_posts()) : $query->the_post();
			$data['value'] = get_the_ID();
			$data['label'] = esc_html__('Id', 'delaware') . ': ' . get_the_ID() . ' - ' . esc_html__('Title', 'delaware') . ': ' . get_the_title();
		endwhile;
		wp_reset_postdata();

		return $data;

	}

	public static function vc_svg_icon()
	{
		$icons = array(
			'back-arrow-circular-symbol',
			'calendar',
			'cash-money',
			'check-mark',
			'circular-graphic-for-business-stats',
			'comment',
			'creative',
			'creative-mind',
			'direction-sign',
			'exchange',
			'gear',
			'global',
			'global-advertisement',
			'growth',
			'heartbeat',
			'icon',
			'idea',
			'link',
			'mail',
			'marketing',
			'message',
			'mortarboard',
			'mountain',
			'next',
			'open-document',
			'partnership',
			'payment-method',
			'phone-call',
			'placeholder',
			'planning',
			'plus-zoom',
			'projection',
			'quote',
			'recycle',
			'right-arrow-circular-button',
			'right-arrow-circular-button1',
			'search',
			'share',
			'shopping-cart',
			'smartphone',
			'strategy',
			'tags',
			'tall-city-building',
			'user',
			'user2',
			'wall-clock',
			'wifi',
			'worker-loading-boxes',
			'world',
		);

		return apply_filters('delaware_svg_icons', $icons);
	}

	function icon_param($settings, $value)
	{
		// Generate dependencies if there are any
		$style= 'style="max-width: 100%; height: auto"';
		$icons = array();
		foreach ($this->icons as $icon) {
			$url = DELAWARE_ADDONS_URL . '/images/svg/' . $icon . '.svg';
			$url_val = DELAWARE_ADDONS_URL . '/images/svg/' . $value . '.svg';
			$icons[] = sprintf(
				'<span data-icon="%3$s" data-name="%1$s" class="vc_svg_icon %1$s %2$s" style="flex:none;width: 40px; margin: 5px"><img src="%3$s" alt="" %4$s /></span>',
				$icon,
				$icon == $value ? 'selected' : '',
				$url,
				$style
			);
		}

		return sprintf(
			'<div class="icon_block">
				<span class="preview-icon" style="width: 40px;display: block;"><img src="%s" alt="" %s/></use></svg></span>
				<input type="text" class="icon-search" placeholder="%s">
				<input type="hidden" name="%s" value="%s" class="wpb_vc_param_value wpb-textinput %s %s_field">
				<div class="icon-selector" style="display: flex;    flex-wrap: wrap;">%s</div>
			</div>',
			esc_attr($url_val),
			$style,
			esc_attr__('Quick Search', 'delaware'),
			esc_attr($settings['param_name']),
			esc_attr($value),
			esc_attr($settings['param_name']),
			esc_attr($settings['type']),
			implode('', $icons)
		);
	}
	/**
	 * Add new params or add new shortcode to VC
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function map_shortcodes() {
		$attributes = array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Svg Icon', 'delaware' ),
				'param_name'  => 'enable_svg',
				'group'       => esc_html__( 'Design Options', 'delaware' ),
				'value'       => array( esc_html__( 'Enable', 'delaware' ) => 'yes' ),
				'description' => esc_html__( 'Enable this option if you want to show svg icon `', 'delaware' ),
			),

			array(
				'type' => 'svg_icons',
				'heading' => esc_html__('Svg name', 'delaware'),
				'param_name' => 'svg_name',
				'value' => '',
				'group'       => esc_html__( 'Design Options', 'delaware' ),
				'dependency' => array(
					'element' => 'enable_svg',
					'value' => 'yes',
				),
			),
		);

		vc_add_params( 'vc_tta_section', $attributes );

		// Empty Space
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Empty Space', 'delaware' ),
				'base'     => 'delaware_empty_space',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Height(px)', 'delaware' ),
						'param_name'  => 'height',
						'admin_label' => true,
						'description' => esc_html__( 'Enter empty space height on Desktop.', 'delaware' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Height on Tablet(px)', 'delaware' ),
						'param_name'  => 'height_tablet',
						'admin_label' => true,
						'description' => esc_html__( 'Enter empty space height on Mobile. Leave empty to use the height of the desktop', 'delaware' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Height on Mobile(px)', 'delaware' ),
						'param_name'  => 'height_mobile',
						'admin_label' => true,
						'description' => esc_html__( 'Enter empty space height on Mobile. Leave empty to use the height of the tablet', 'delaware' )
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Color', 'delaware' ),
						'param_name' => 'bg_color',
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		// Button
		vc_map(
			array(
				'name'     => esc_html__( 'Call To Action', 'delaware' ),
				'base'     => 'delaware_cta',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'delaware' ),
						'param_name' => 'title',
						'group'      => esc_html__( 'General', 'delaware' )
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Title color', 'delaware' ),
						'param_name' => 'title_color',
						'group'      => esc_html__( 'General', 'delaware' ),
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Font size', 'delaware' ),
						'param_name'  => 'size',
						'value'       => '',
						'description' => esc_html__( 'Enter Font size of Title', 'delaware' ),
						'group'       => esc_html__( 'General', 'delaware' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Font weight', 'delaware' ),
						'param_name'  => 'weight',
						'value'       => '',
						'description' => esc_html__( 'Enter Font weight of Title', 'delaware' ),
						'group'       => esc_html__( 'General', 'delaware' ),
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Description ', 'delaware' ),
						'param_name' => 'desc',
						'group'      => esc_html__( 'General', 'delaware' )
					),

					array(
						'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
						'group'      => esc_html__( 'General', 'delaware' )
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show icon arrow', 'delaware' ),
						'param_name'  => 'show_icon',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable icon arrow', 'delaware' ),
						'group'       => esc_html__( 'General', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'Style', 'delaware' ),
						'type'       => 'dropdown',
						'param_name' => 'style_btn',
						'value'      => array(
							esc_html__( 'Style 1', 'delaware' ) => 'classic',
							esc_html__( 'Style 2', 'delaware' ) => 'btn-fix',
						),
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'Text color', 'delaware' ),
						'type'       => 'dropdown',
						'param_name' => 'text_color',
						'value'      => array(
							esc_html__( 'Dark', 'delaware' )  => 'dark',
							esc_html__( 'Light', 'delaware' ) => 'light',
						),
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'Button text color', 'delaware' ),
						'type'       => 'colorpicker',
						'param_name' => 'btn_color',
						'value'      => '',
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background button color', 'delaware' ),
						'param_name' => 'bg_color',
						'value'      => '',
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
						'group'       => esc_html__( 'General', 'delaware' )
					),
				),
			)
		);

		//section title
		vc_map(
			array(
				'name'     => esc_html__( 'Section title', 'delaware' ),
				'base'     => 'delaware_section_title',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Style title', 'delaware' ),
						'param_name'  => 'select_type',
						'value'       => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
							esc_html__( 'Style 3', 'delaware' ) => '3',
							esc_html__( 'Style 4', 'delaware' ) => '4',
							esc_html__( 'Style 5', 'delaware' ) => '5',
							esc_html__( 'Style 6', 'delaware' ) => '6',
							esc_html__( 'Style 7', 'delaware' ) => '7',
							esc_html__( 'Style 8', 'delaware' ) => '8',
						),
						'description' => esc_html__( 'Select style title you want show.', 'delaware' ),
						'admin_label' => true,
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'delaware' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Heading title', 'delaware' ),
						'param_name' => 'heading',
						'value'      => array(
							esc_html__( 'H2', 'delaware' ) => 'h2',
							esc_html__( 'H3', 'delaware' ) => 'h3',
							esc_html__( 'H4', 'delaware' ) => 'h4',
							esc_html__( 'H5', 'delaware' ) => 'h5',
							esc_html__( 'H6', 'delaware' ) => 'h6',
						),
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Font size', 'delaware' ),
						'param_name'  => 'size',
						'value'       => '',
						'admin_label' => true,
						'description' => esc_html__( 'Enter Font size of title', 'delaware' ),
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Font weight', 'delaware' ),
						'param_name'  => 'weight',
						'value'       => '',
						'description' => esc_html__( 'Enter Font weight of title', 'delaware' ),
					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Text Decoration', 'delaware' ),
						'param_name' => 'underline_check',
						'value'      => array(
							esc_html__( 'underline', 'delaware' ) => '2',
							esc_html__( 'nomal', 'delaware' )     => '1',
						),

					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Underline Text', 'delaware' ),
						'param_name' => 'underline_text',
						'value'      => '',
						'dependency' => array(
							'element' => 'underline_check',
							'value'   => '2',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Title Color', 'delaware' ),
						'param_name' => 'text_color',
						'value'      => '',
					),

					array(
						'heading'    => esc_html__( 'URL (Link) Button', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
						'dependency' => array(
							'element' => 'select_type',
							'value'   => array( '3', '7', '8' ),
						),

					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Description Button', 'delaware' ),
						'param_name' => 'descr_button',
						'value'      => '',
						'dependency' => array(
							'element' => 'select_type',
							'value'   => array( '3' ),
						),
					),

					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',
						'dependency' => array(
							'element' => 'select_type',
							'value'   => array( '1', '2', '3', '5', '6', '7', '8' ),
						),
					),

					array(
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
						'group'       => esc_html__( 'Class', 'delaware' )
					),
				),
			)
		);

		// Text Box
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Text Box', 'delaware' ),
				'base'     => 'delaware_text_box',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Text Align', 'delaware' ),
						'param_name' => 'align',
						'value'      => array(
							esc_html__( 'Left', 'delaware' )   => 'left',
							esc_html__( 'Center', 'delaware' ) => 'center',
							esc_html__( 'Right', 'delaware' )  => 'right',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'delaware' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'delaware' ),
						'param_name' => 'sub_title',
						'value'      => '',
					),

					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Underline', 'delaware' ),
						'param_name'  => 'underline',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" show title underline', 'delaware' ),
						'std'         => 'yes'
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Underline Text', 'delaware' ),
						'param_name' => 'underline_text',
						'value'      => '',
					),

					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Border', 'delaware' ),
						'param_name'       => 'border',
						'value'            => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description'      => esc_html__( 'If "YES" show box border', 'delaware' ),
						'std'              => 'yes',
						'edit_field_class' => 'vc_col-xs-6',
					),
					array(
						'type'             => 'attach_image',
						'heading'          => esc_html__( 'Background image', 'delaware' ),
						'param_name'       => 'background_image',
						'value'            => '',
						'edit_field_class' => 'vc_col-xs-6',
					),

					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Content', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
						'group'       => esc_html__( 'Class', 'delaware' )
					),
				),
			)
		);

		// Blog Section
		vc_map(
			array(
				'name'     => esc_html__( 'Blog Section', 'delaware' ),
				'base'     => 'delaware_blog_section',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'delaware' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Bordered', 'delaware' )   => 'border',
							esc_html__( 'Box Shadow', 'delaware' ) => 'shadow',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => esc_html__( 'Section Blog', 'delaware' ),
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Posts', 'delaware' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Posts you want to display. Set -1 to display all posts', 'delaware' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'delaware' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Grid', 'delaware' )     => 'grid',
							esc_html__( 'Carousel', 'delaware' ) => 'carousel',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Button Text', 'delaware' ),
						'param_name' => 'btn_text',
						'value'      => esc_html__( 'View More', 'delaware' ),
						'dependency' => array(
							'element' => 'type',
							'value'   => array( 'grid' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Autoplay', 'delaware' ),
						'param_name'  => 'autoplay',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable autoplay', 'delaware' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Autoplay speed', 'delaware' ),
						'param_name'  => 'autoplay_speed',
						'value'       => '800',
						'description' => esc_html__( 'Set auto play speed (in ms).', 'delaware' ),
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => array( 'yes' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Navigation', 'delaware' ),
						'param_name'  => 'nav',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable navigation', 'delaware' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Dots', 'delaware' ),
						'param_name'  => 'dot',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable dots', 'delaware' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Member
		vc_map(
			array(
				'name'     => esc_html__( 'Member', 'delaware' ),
				'base'     => 'delaware_member',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => esc_html__( 'Member', 'delaware' ),
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'delaware' ),
						'description' => esc_html__( 'Upload member image', 'delaware' ),
						'param_name'  => 'member_image',
						'value'       => '',
					),
					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Name', 'delaware' ),
						'param_name'  => 'member_name',
						'value'       => esc_html__( '', 'delaware' ),
						'description' => esc_html__( 'Enter name for this member', 'delaware' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Job', 'delaware' ),
						'param_name'  => 'member_job',
						'value'       => esc_html__( '', 'delaware' ),
						'description' => esc_html__( 'Enter job name for this member', 'delaware' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Description', 'delaware' ),
						'param_name'  => 'member_description',
						'value'       => esc_html__( '', 'delaware' ),
						'description' => esc_html__( 'Enter description for this member', 'delaware' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Socials', 'delaware' ),
						'value'      => '',
						'param_name' => 'socials',
						'params'     => array(
							array(
								'heading'     => esc_html__( 'Icon library', 'delaware' ),
								'description' => esc_html__( 'Select icon library.', 'delaware' ),
								'param_name'  => 'icon_type',
								'type'        => 'dropdown',
								'value'       => array(
									esc_html__( 'Font Awesome', 'delaware' ) => 'fontawesome',
									esc_html__( 'Custom Image', 'delaware' ) => 'image',
								),
							),
							array(
								'heading'     => esc_html__( 'Icon', 'delaware' ),
								'description' => esc_html__( 'Select icon from library.', 'delaware' ),
								'type'        => 'iconpicker',
								'param_name'  => 'icon_fontawesome',
								'value'       => 'fa fa-adjust',
								'settings'    => array(
									'emptyIcon'    => false,
									'iconsPerPage' => 4000,
								),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'fontawesome',
								),
							),
							array(
								'heading'     => esc_html__( 'Icon Image', 'delaware' ),
								'description' => esc_html__( 'Upload icon image', 'delaware' ),
								'type'        => 'attach_image',
								'param_name'  => 'image',
								'value'       => '',
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'image',
								),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Link Socials', 'delaware' ),
								'param_name'  => 'link',
								'value'       => '',
								'description' => esc_html__( 'Enter link for this social', 'delaware' ),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		// Add contact form 7 shortcode
		$mail_forms    = get_posts( 'post_type=wpcf7_contact_form&posts_per_page=-1' );
		$mail_form_ids = array(
			esc_html__( 'Select Form', 'delaware' ) => '',
		);
		foreach ( $mail_forms as $form ) {
			$mail_form_ids[$form->post_title] = $form->ID;
		}
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Contact Form 7', 'delaware' ),
				'base'     => 'delaware_contact_form_7',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Form Version', 'delaware' ),
						'param_name' => 'color',
						'value'      => array(
							esc_html__( 'Light', 'delaware' ) => 'light',
							esc_html__( 'Dark', 'delaware' )  => 'dark',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Contact Form 7', 'delaware' ),
						'param_name' => 'form',
						'value'      => $mail_form_ids,
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Form Background Color', 'delaware' ),
						'param_name' => 'form_bg',
						'value'      => '',
						'group'      => esc_html__( 'CSS', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Padding Top (px)', 'delaware' ),
						'param_name' => 'padding_top',
						'value'      => '',
						'group'      => esc_html__( 'CSS', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Padding Right (px)', 'delaware' ),
						'param_name' => 'padding_right',
						'value'      => '',
						'group'      => esc_html__( 'CSS', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Padding Bottom (px)', 'delaware' ),
						'param_name' => 'padding_bottom',
						'value'      => '',
						'group'      => esc_html__( 'CSS', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Padding Left (px)', 'delaware' ),
						'param_name' => 'padding_left',
						'value'      => '',
						'group'      => esc_html__( 'CSS', 'delaware' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Timeline
		vc_map(
			array(
				'name'     => esc_html__( 'Timeline', 'delaware' ),
				'base'     => 'delaware_timeline',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Year', 'delaware' ),
						'value'      => '',
						'param_name' => 'year',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Name', 'delaware' ),
								'param_name'  => 'name',
								'value'       => '',
								'admin_label' => true,
								'description' => esc_html__( 'Enter year for this section', 'delaware' ),
							),
							array(
								'type'       => 'param_group',
								'heading'    => esc_html__( 'Event', 'delaware' ),
								'value'      => '',
								'param_name' => 'event',
								'params'     => array(
									array(
										'type'        => 'textfield',
										'heading'     => esc_html__( 'Date', 'delaware' ),
										'param_name'  => 'date',
										'value'       => '',
										'description' => esc_html__( 'Enter date for this event', 'delaware' ),
									),
									array(
										'type'        => 'textfield',
										'heading'     => esc_html__( 'Title', 'delaware' ),
										'param_name'  => 'title',
										'value'       => '',
										'description' => esc_html__( 'Enter title for this event', 'delaware' ),
										'admin_label' => true,
									),
									array(
										'type'        => 'textfield',
										'heading'     => esc_html__( 'Description', 'delaware' ),
										'param_name'  => 'description',
										'value'       => '',
										'description' => esc_html__( 'Enter description for this event', 'delaware' ),
									),
								),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Tab
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Tabs', 'delaware' ),
				'base'     => 'delaware_tab',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Tab', 'delaware' ),
						'value'      => '',
						'param_name' => 'tab',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
								'description' => esc_html__( 'Enter title for this tab', 'delaware' ),
							),
							array(
								'heading'     => esc_html__( 'Icon library', 'delaware' ),
								'description' => esc_html__( 'Select icon library.', 'delaware' ),
								'param_name'  => 'icon_type',
								'type'        => 'dropdown',
								'value'       => array(
									esc_html__( 'Select type', 'delaware' ) => '',
									esc_html__( 'Font Awesome', 'delaware' ) => 'fa-font',
									esc_html__( 'Svg icon', 'delaware' )     => 'number',
								),

							),

							array(
								'heading'     => esc_html__( 'Icon', 'delaware' ),
								'description' => esc_html__( 'Pick an icon from library.', 'delaware' ),
								'type'        => 'iconpicker',
								'param_name'  => 'icon_fontawesome',
								'value'       => 'fa fa-adjust',
								'settings'    => array(
									'emptyIcon'    => false,
									'iconsPerPage' => 4000,
								),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'fa-font',
								),
							),

							array(
								'type' => 'svg_icons',
								'heading' => esc_html__('Svg name', 'delaware'),
								'param_name' => 'svg_name',
								'value' => '',
								'dependency' => array(
									'element' => 'icon_type',
									'value' => 'number',
								),
							),

							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Description', 'delaware' ),
								'param_name' => 'description',
								'value'      => '',
							),
							array(
								'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
								'type'       => 'vc_link',
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Portfolio Meta
		vc_map(
			array(
				'name'     => esc_html__( 'Portfolio Meta', 'delaware' ),
				'base'     => 'delaware_portfolio_meta',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		// GG maps
		vc_map(
			array(
				'name'     => esc_html__( 'Google Maps', 'delaware' ),
				'base'     => 'delaware_gmap',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Api Key', 'delaware' ),
						'param_name'  => 'api_key',
						'value'       => '',
						'description' => sprintf( __( 'Please go to <a href="%s">Google Maps APIs</a> to get a key', 'delaware' ), esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key' ) ),
					),

					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Address Information', 'delaware' ),
						'value'      => '',
						'param_name' => 'info',
						'params'     => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Location Image', 'delaware' ),
								'param_name'  => 'image',
								'value'       => '',
								'description' => esc_html__( 'Choose an image from media library', 'delaware' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Address', 'delaware' ),
								'param_name'  => 'address',
								'admin_label' => true,
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Details', 'delaware' ),
								'param_name' => 'details',
							),
						),
					),

					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Marker', 'delaware' ),
						'param_name'  => 'marker',
						'value'       => '',
						'description' => esc_html__( 'Choose an image from media library', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Width(px)', 'delaware' ),
						'param_name' => 'width',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Height(px)', 'delaware' ),
						'param_name' => 'height',
						'value'      => '500',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Zoom', 'delaware' ),
						'param_name' => 'zoom',
						'value'      => '13',
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'delaware' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
						),
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Map Colors', 'delaware' ),
						'param_name' => 'map_color',
						'value'      => '#efba2c',
						'dependency' => array(
							'element' => 'style',
							'value'   => array( '1' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'delaware' ),
					),
				),
			)
		);

		//Office Locations
		vc_map(
			array(
				'name'     => esc_html__( 'Office Box', 'delaware' ),
				'base'     => 'delaware_office_box',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Contact', 'delaware' ),
						'value'      => '',
						'param_name' => 'contacts',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
								'description' => esc_html__( 'Enter title for this section', 'delaware' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Content', 'delaware' ),
								'param_name'  => 'content',
								'value'       => '',
								'description' => esc_html__( 'Enter content number for this section', 'delaware' ),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Job box
		vc_map(
			array(
				'name'     => esc_html__( 'Job box', 'delaware' ),
				'base'     => 'delaware_job_box',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Job', 'delaware' ),
						'value'      => '',
						'param_name' => 'jobs',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Date', 'delaware' ),
								'param_name'  => 'date',
								'value'       => '',
								'description' => esc_html__( 'Enter date for this job', 'delaware' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
								'description' => esc_html__( 'Enter title for this job', 'delaware' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Address', 'delaware' ),
								'param_name'  => 'address',
								'value'       => '',
								'description' => esc_html__( 'Enter address for this job', 'delaware' ),
							),
							array(
								'heading'     => esc_html__( 'Link', 'delaware' ),
								'type'        => 'vc_link',
								'param_name'  => 'link',
								'description' => esc_html__( 'Enter link for this job', 'delaware' ),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Job box detail
		vc_map(
			array(
				'name'     => esc_html__( 'Job box detail', 'delaware' ),
				'base'     => 'delaware_job_box_detail',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Job', 'delaware' ),
						'value'      => '',
						'param_name' => 'jobs',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title',
								'value'       => '',
								'description' => esc_html__( 'Enter title for this job', 'delaware' ),
								'admin_label' => true,
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Content', 'delaware' ),
								'param_name'  => 'content',
								'value'       => '',
								'description' => esc_html__( 'Enter content for this job', 'delaware' ),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Contact box
		vc_map(
			array(
				'name'     => esc_html__( 'Contact box', 'delaware' ),
				'base'     => 'delaware_contact_box',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Section title', 'delaware' ),
						'param_name'  => 'section_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Heading title', 'delaware' ),
						'param_name' => 'heading',
						'value'      => array(
							esc_html__( 'H2', 'delaware' ) => 'h2',
							esc_html__( 'H3', 'delaware' ) => 'h3',
							esc_html__( 'H4', 'delaware' ) => 'h4',
							esc_html__( 'H5', 'delaware' ) => 'h5',
							esc_html__( 'H6', 'delaware' ) => 'h6',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Text Decoration', 'delaware' ),
						'param_name' => 'underline_check',

						'value'      => array(
							esc_html__( 'underline', 'delaware' ) => '2',
							esc_html__( 'nomal', 'delaware' )     => '1',
						),

					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Underline Text', 'delaware' ),
						'param_name' => 'underline_text',
						'value'      => '',
						'dependency' => array(
							'element' => 'underline_check',
							'value'   => '2',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Sub title', 'delaware' ),
						'param_name'  => 'sub_title',
						'value'       => '',
						'description' => esc_html__( 'Enter sub title for this section', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'description',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Description Highlight', 'delaware' ),
						'param_name' => 'des_highlight',
						'value'      => '',
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Addresses', 'delaware' ),
						'value'      => '',
						'param_name' => 'addresses',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title',
								'value'       => '',
								'description' => esc_html__( 'Enter title for this address', 'delaware' ),
								'admin_label' => true,
							),
							array(
								'type'        => 'textarea',
								'heading'     => esc_html__( 'Content', 'delaware' ),
								'param_name'  => 'content',
								'value'       => '',
								'description' => esc_html__( 'Enter content for this address', 'delaware' ),
							),
						),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Extra info', 'delaware' ),
						'value'      => '',
						'param_name' => 'extra',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Content', 'delaware' ),
								'param_name' => 'content',
								'value'      => '',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Form title', 'delaware' ),
						'param_name'  => 'form_title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this form', 'delaware' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Heading form title', 'delaware' ),
						'param_name' => 'heading_form',
						'value'      => array(
							esc_html__( 'H2', 'delaware' ) => 'h2',
							esc_html__( 'H3', 'delaware' ) => 'h3',
							esc_html__( 'H4', 'delaware' ) => 'h4',
							esc_html__( 'H5', 'delaware' ) => 'h5',
							esc_html__( 'H6', 'delaware' ) => 'h6',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Text Decoration', 'delaware' ),
						'param_name' => 'underline_check_form',

						'value'      => array(
							esc_html__( 'underline', 'delaware' ) => '2',
							esc_html__( 'nomal', 'delaware' )     => '1',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Underline Text', 'delaware' ),
						'param_name' => 'underline_text_form',
						'value'      => '',
						'dependency' => array(
							'element' => 'underline_check_form',
							'value'   => '2',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Contact Form 7', 'delaware' ),
						'param_name' => 'form',
						'value'      => $mail_form_ids,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Image box
		vc_map(
			array(
				'name'     => esc_html__( 'Image Box', 'delaware' ),
				'base'     => 'delaware_image_box',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(


					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Style', 'delaware' ),
						'param_name'  => 'style',
						'value'       => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
							esc_html__( 'Style 3', 'delaware' ) => '3',
							esc_html__( 'Style 4', 'delaware' ) => '4',
							esc_html__( 'Style 5', 'delaware' ) => '5',

						),
						'description' => esc_html__( 'Select style image box.', 'delaware' ),
						'admin_label' => true,
					),

					array(
						'heading'    => esc_html__( 'Title', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',

					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Heading title', 'delaware' ),
						'param_name' => 'heading',

						'value'      => array(
							esc_html__( 'H2', 'delaware' ) => 'h2',
							esc_html__( 'H1', 'delaware' ) => 'h1',
							esc_html__( 'H3', 'delaware' ) => 'h3',
							esc_html__( 'H4', 'delaware' ) => 'h4',
							esc_html__( 'H5', 'delaware' ) => 'h5',
							esc_html__( 'H6', 'delaware' ) => 'h6',
						),

					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Title Color', 'delaware' ),
						'param_name' => 'text_color',
						'value'      => '',
					),

					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',
					),

					array(
						'heading'     => esc_html__( 'Image', 'delaware' ),
						'description' => esc_html__( 'Upload image', 'delaware' ),
						'type'        => 'attach_image',
						'param_name'  => 'service_image',
						'value'       => '',

					),
					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',

					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Image position', 'delaware' ),
						'param_name' => 'img_pos',
						'value'      => array(
							esc_html__( 'Left', 'delaware' )  => '1',
							esc_html__( 'Right', 'delaware' ) => '2',
						),
						'dependency' => array(
							'element' => 'select_type',
							'value'   => array( '5' ),
						),

					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Color', 'delaware' ),
						'param_name' => 'background',
						'value'      => '',
						'dependency' => array(
							'element' => 'select_type',
							'value'   => array( '5' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Icon size', 'delaware' ),
						'param_name'  => 'icon_size',
						'description' => esc_html__( 'Enter Font size for icon.', 'delaware' ),
						'value'       => '',
					),
					array(
						'heading'    => esc_html__( 'Icon library', 'delaware' ),
						'param_name' => 'icon_type',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Font Awesome', 'delaware' )  => 'fontawesome',
							esc_html__( 'Delaware icon', 'delaware' ) => 'number',
							esc_html__( 'No icon', 'delaware' )  => '',
						),
					),

					array(
						'heading'     => esc_html__( 'Icon', 'meditex' ),
						'description' => esc_html__( 'Pick an icon from library.', 'meditex' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fontawesome',
						),
					),

					array(
						'type' => 'svg_icons',
						'heading' => esc_html__('Svg name', 'delaware'),
						'param_name' => 'svg_name',
						'value' => '',
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'number',
						),
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		// Image grid
		vc_map(
			array(
				'name'     => esc_html__( 'Image Grid', 'delaware' ),
				'base'     => 'delaware_image_grid',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(

					array(
						'heading'     => esc_html__( 'Image columns', 'delaware' ),
						'param_name'  => 'colum',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( '1 column', 'delaware' )  => '1',
							esc_html__( '2 columns', 'delaware' ) => '2',
							esc_html__( '3 columns', 'delaware' ) => '3',
							esc_html__( '4 columns', 'delaware' ) => '4',
							esc_html__( '5 columns', 'delaware' ) => '5',
							esc_html__( '6 columns', 'delaware' ) => '6',
						),
						'admin_label' => true,
						'std'         => '6',
					),

					array(
						"type"       => "attach_images",
						"heading"    => esc_html__( "Add images", "delaware" ),
						"param_name" => "images",
						"value"      => "",
					),

					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Effect when hover', 'delaware' ),
						'param_name' => 'hover',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),

				),
			)
		);

		//Portfolio grid
		vc_map(
			array(
				'name'     => esc_html__( 'Portfolio Grid', 'delaware' ),
				'base'     => 'delaware_portfolio_grid',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(

					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Style', 'delaware' ),
						'param_name'  => 'style',
						'value'       => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
						),
						'description' => esc_html__( 'Select style Portfolio Grid.', 'delaware' ),
						'admin_label' => true,
					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'delaware' ),
						'param_name' => 'colum',
						'value'      => array(
							esc_html__( '2 Columns', 'delaware' ) => '6',
							esc_html__( '3 Columns', 'delaware' ) => '4',
							esc_html__( '4 Columns', 'delaware' ) => '3',
						),
						'dependency' => array(
							'element' => 'style',
							'value'   => array( '1' ),
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order by', 'delaware' ),
						'param_name'  => 'orderby',
						'value'       => array(
							esc_html__( 'Default', 'delaware' ) => '',
							esc_html__( 'ID', 'delaware' )      => 'id',
							esc_html__( 'Title', 'delaware' )   => 'title',
							esc_html__( 'Date', 'delaware' )    => 'date',
							esc_html__( 'Random', 'delaware' )  => 'rand',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( '2' ),
						),
						'admin_label' => true,
						'edit_field_class' => 'vc_col-xs-6',
					),

					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'delaware' ),
						'param_name'  => 'order',
						'value'       => array(
							esc_html__( 'Descending', 'delaware' ) => 'descending',
							esc_html__( 'Ascending', 'delaware' )  => 'ascending',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( '2' ),
						),
						'admin_label' => true,
						'edit_field_class' => 'vc_col-xs-6',
					),

					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Filter', 'delaware' ),
						'param_name'  => 'filter',
						'value'       => array(
							esc_html__( 'Show', 'delaware' ) => 'show',
							esc_html__( 'Hide', 'delaware' ) => 'hide',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( '2' ),
						),
						'group'       => esc_html__( 'Filter Categories', 'bosoe' ),
						'admin_label' => true,
					),

					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Filter Color', 'delaware' ),
						'param_name'  => 'filter_color',
						'value'       => array(
							esc_html__( 'Dark', 'delaware' ) => 'dark',
							esc_html__( 'Light', 'delaware' ) => 'light',
						),
						'dependency'  => array(
							'element' => 'filter',
							'value'   => array( 'show' ),
						),
						'group'       => esc_html__( 'Filter Categories', 'bosoe' ),
					),

					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Filter Style', 'delaware' ),
						'param_name'  => 'filter_type',
						'value'       => array(
							esc_html__( 'Default', 'delaware' ) => 'default',
							esc_html__( 'Customs', 'delaware' ) => 'customs',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => array( '2' ),
						),
						'admin_label' => true,
						'group'       => esc_html__( 'Filter Categories', 'bosoe' ),
					),

					array(
						'type' => 'autocomplete',
						'heading' => esc_html__('Categories', 'delaware'),
						'param_name' => 'categories',
						'value' => '',
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
							'unique_values' => true,
						),
						'description' => esc_html__('Insert slug categories.', 'delaware'),
						'dependency' => array(
							'element' => 'filter_type',
							'value' => 'customs',
						),
						'group'       => esc_html__( 'Filter Categories', 'bosoe' ),
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Portfolio items show at most', 'delaware' ),
						'param_name' => 'value_item',
						'value'      => '',
					),

					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',
					),

				),
			)

		);

		//Portfolio Carousel
		vc_map(
			array(
				'name'     => esc_html__( 'Portfolio Carousel', 'delaware' ),
				'base'     => 'delaware_portfolio_carousel',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(


					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Items per row', 'delaware' ),
						'param_name'  => 'colum',
						'value'       => array(
							esc_html__( '2 items', 'delaware' ) => '2',
							esc_html__( '3 items', 'delaware' ) => '3',
							esc_html__( '4 items', 'delaware' ) => '4',
							esc_html__( '5 items', 'delaware' ) => '5',
							esc_html__( '6 items', 'delaware' ) => '6',
						),

						'admin_label' => true,
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Portfolio items show at most', 'delaware' ),
						'param_name' => 'value_item',
						'value'      => '',
					),


					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',

					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Excerpt content', 'delaware' ),
						'param_name' => 'excerpt_content',
						'value'      => '',
					),
				),
			)
		);

		//Icon box
		vc_map(
			array(
				'name'     => esc_html__( 'Icon Box', 'delaware' ),
				'base'     => 'delaware_icon_box',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Style', 'delaware' ),
						'param_name'  => 'select_type',
						'value'       => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
							esc_html__( 'Style 3', 'delaware' ) => '3',
							esc_html__( 'Style 4', 'delaware' ) => '4',
							esc_html__( 'Style 5', 'delaware' ) => '5',
							esc_html__( 'Style 6', 'delaware' ) => '6',
							esc_html__( 'Style 7', 'delaware' ) => '7',

						),
						'admin_label' => true,
					),

					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'No box shadow', 'delaware' ),
						'param_name' => 'shadow',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
						'group'      => esc_html__( 'Setting', 'delaware' )
					),

					array(
						'heading'    => esc_html__( 'Title', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',

					),

					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Hover hide title', 'delaware' ),
						'param_name' => 'hide_title',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
						'std'        => '1',
						'dependency' => array(
							'element' => 'select_type',
							'value'   => '2',
						),
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Title Color', 'delaware' ),
						'param_name' => 'text_color',
						'value'      => '',
						'group'      => esc_html__( 'Setting', 'delaware' )
					),

					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',

					),
					array(
						'heading'     => esc_html__( 'Icon library', 'delaware' ),
						'description' => esc_html__( 'Select icon library.', 'delaware' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Select type', 'delaware' ) => '',
							esc_html__( 'Font Awesome', 'delaware' ) => 'fa_font',
							esc_html__( 'Svg icon', 'delaware' )     => 'svg_icon',
							esc_html__( 'Custom image', 'delaware' ) => 'image',
						),
					),

					array(
						'heading'     => esc_html__( 'Icon', 'delaware' ),
						'description' => esc_html__( 'Pick an icon from library.', 'delaware' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fa_font',
						),
					),

					array(
						'type' => 'svg_icons',
						'heading' => esc_html__('Svg name', 'delaware'),
						'param_name' => 'svg_name',
						'value' => '',
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'svg_icon',
						),
					),

					array(
						'heading'     => esc_html__( 'Icon Image', 'delaware' ),
						'description' => esc_html__( 'Upload icon image', 'delaware' ),
						'type'        => 'attach_image',
						'param_name'  => 'image',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),

					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Icon Color', 'delaware' ),
						'param_name' => 'icon_color',
						'value'      => '',
						'edit_field_class' => 'vc_col-xs-6',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Icon size', 'delaware' ),
						'param_name'  => 'icon_size',
						'description' => esc_html__( 'Enter Font size for icon.', 'delaware' ),
						'value'       => '',
						'edit_field_class' => 'vc_col-xs-6',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show read more', 'delaware' ),
						'param_name' => 'checkbox',
						'value'      => '',
					),

					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show border bottom', 'delaware' ),
						'param_name' => 'border',
						'value'      => '',
						'dependency' => array(
							'element' => 'select_type',
							'value'   => '3',
						),
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Color', 'delaware' ),
						'param_name' => 'bg_color',
						'value'      => '',
						'group'      => esc_html__( 'Setting', 'delaware' )
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'group'       => esc_html__( 'Setting', 'delaware' )
					),
				),
			)

		);

		vc_map(
			array(
				'name'     => esc_html__( 'Icon Box 2', 'delaware' ),
				'base'     => 'delaware_icon_box_2',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'delaware' ),
						'param_name'  => 'title',
						'value'       => '',
						'description' => esc_html__( 'Enter title for this section', 'delaware' ),
					),

					array(
						'heading'     => esc_html__( 'Icon library', 'delaware' ),
						'description' => esc_html__( 'Select icon library.', 'delaware' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'delaware' ) => 'fa_font',
							esc_html__( 'Svg icon', 'delaware' )     => 'svg_icon',
							esc_html__( 'Custom image', 'delaware' ) => 'image',
							esc_html__( 'Select type', 'delaware' ) => '',
						),
					),

					array(
						'heading'     => esc_html__( 'Icon', 'delaware' ),
						'description' => esc_html__( 'Pick an icon from library.', 'delaware' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fa_font',
						),
					),

					array(
						'type' => 'svg_icons',
						'heading' => esc_html__('Svg name', 'delaware'),
						'param_name' => 'svg_name',
						'value' => '',
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'svg_icon',
						),
					),

					array(
						'type'       => 'attach_image',
						'heading'    => esc_html__( 'Video Background Image', 'delaware' ),
						'param_name' => 'image',
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'param_name'  => 'image_size',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'delaware' ),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',

					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)

		);

		//Portfolio Attribute
		vc_map(
			array(
				'name'     => esc_html__( 'Portfolio Attribute', 'delaware' ),
				'base'     => 'delaware_portfolio_attribute',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Client', 'delaware' ),
						'param_name' => 'client',
					),
					array(
						'heading'    => esc_html__( 'Link', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',

					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Rating', 'delaware' ),
						'param_name'  => 'rating',
						'value'       => array(
							esc_html__( '1 star', 'delaware' ) => '1',
							esc_html__( '2 star', 'delaware' ) => '2',
							esc_html__( '3 star', 'delaware' ) => '3',
							esc_html__( '4 star', 'delaware' ) => '4',
							esc_html__( '5 star', 'delaware' ) => '5',


						),
						'admin_label' => true,
					),
				)
			)
		);

		//Counter
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware counter', 'delaware' ),
				'base'     => 'delaware_counter',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'delaware' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
						),
						'std'        => '1',
					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Color', 'delaware' ),
						'param_name' => 'color',
						'value'      => array(
							esc_html__( 'Dark', 'delaware' )  => 'dark',
							esc_html__( 'Light', 'delaware' ) => 'light',
						),
					),
					array(
						'heading'    => esc_html__( 'Text Align', 'delaware' ),
						'param_name' => 'align',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left', 'delaware' )   => 'left',
							esc_html__( 'Center', 'delaware' ) => 'center',
							esc_html__( 'Right', 'delaware' )  => 'right',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Counter Title', 'delaware' ),
						'param_name' => 'title',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Number Counter', 'delaware' ),
						'param_name' => 'number',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Duaration', 'delaware' ),
						'param_name' => 'duaration',
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Units', 'delaware' ),
						'param_name' => 'units',
						'value'      => '',
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Text after counter', 'delaware' ),
						'param_name' => 'tx_after',
					),

					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Extra Class', 'delaware' ),
						'param_name' => 'el_class',
						'value'      => '',
					),
				)
			)
		);

		// Video Banner
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Video Banner', 'delaware' ),
				'base'     => 'delaware_video_banner',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Video file URL', 'delaware' ),
						'description' => esc_html__( 'Only support YouTube and Vimeo', 'delaware' ),
						'param_name'  => 'video',
						'value'       => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Min Height(px)', 'delaware' ),
						'param_name' => 'min_height',
						'value'      => '500',
					),
					array(
						'type'       => 'attach_image',
						'heading'    => esc_html__( 'Video Background Image', 'delaware' ),
						'param_name' => 'image',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'param_name'  => 'image_size',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'delaware' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Contact phone', 'delaware' ),
						'param_name' => 'number_phone',
						'value'      => '',

					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Extra Class', 'delaware' ),
						'param_name' => 'el_class',
						'value'      => '',

					),

				),
			)
		);

		//About
		vc_map(
			array(
				'name'     => esc_html__( 'About', 'delaware' ),
				'base'     => 'delaware_about',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(


					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Style', 'delaware' ),
						'param_name'  => 'style',
						'value'       => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',
						),
						'description' => esc_html__( 'Select style title you want show.', 'delaware' ),
						'admin_label' => true,
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'delaware' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
						'dependency'  => array(
							'element' => 'style',
							'value'   => '1',
						),
					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Heading title', 'delaware' ),
						'param_name' => 'heading',
						'value'      => array(
							esc_html__( 'H1', 'delaware' ) => 'h1',
							esc_html__( 'H2', 'delaware' ) => 'h2',
							esc_html__( 'H3', 'delaware' ) => 'h3',
							esc_html__( 'H4', 'delaware' ) => 'h4',
							esc_html__( 'H5', 'delaware' ) => 'h5',
							esc_html__( 'H6', 'delaware' ) => 'h6',
						),
						'dependency' => array(
							'element' => 'style',
							'value'   => '1',
						),
					),

					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Text Decoration', 'delaware' ),
						'param_name' => 'underline_check',

						'value'      => array(
							esc_html__( 'underline', 'delaware' ) => '2',
							esc_html__( 'nomal', 'delaware' )     => '1',
						),
						'dependency' => array(
							'element' => 'style',
							'value'   => '1',
						),

					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Underline Text', 'delaware' ),
						'param_name' => 'underline_text',
						'value'      => '',
						'dependency' => array(
							'element' => 'underline_check',
							'value'   => '2',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Title Color', 'delaware' ),
						'param_name' => 'text_color',
						'value'      => '',
						'dependency' => array(
							'element' => 'style',
							'value'   => '1',
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Font size', 'delaware' ),
						'param_name' => 'text_size',
						'value'      => '',
						'dependency' => array(
							'element' => 'style',
							'value'   => '1',
						),
					),

					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',
					),

					array(
						'heading'    => esc_html__( 'URL (Link) Button', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
					),

					array(
						'heading'    => esc_html__( 'Style button', 'delaware' ),
						'param_name' => 'style_button',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Style 1', 'delaware' ) => '1',
							esc_html__( 'Style 2', 'delaware' ) => '2',

						),
						'dependency' => array(
							'element' => 'style',
							'value'   => '1',
						),

					),

					array(
						'heading'     => esc_html__( 'Image', 'delaware' ),
						'description' => esc_html__( 'Upload image', 'delaware' ),
						'type'        => 'attach_image',
						'param_name'  => 'image',
						'value'       => '',

					),
					array(
						'heading'     => esc_html__( 'Image size', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size',
						'value'       => '',

					),

					array(
						'heading'     => esc_html__( 'Icon library', 'delaware' ),
						'description' => esc_html__( 'Select icon library.', 'delaware' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'delaware' ) => 'fa_font',
							esc_html__( 'Svg icon', 'delaware' )     => 'svg_icon',
							esc_html__( 'Custom image', 'delaware' ) => 'image',
						),
						'dependency'  => array(
							'element' => 'style',
							'value'   => '2',
						),

					),

					array(
						'heading'     => esc_html__( 'Icon', 'delaware' ),
						'description' => esc_html__( 'Pick an icon from library.', 'delaware' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fa_font',
						),
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Svg icon', 'delaware' ),
						'param_name'  => 'svg_icon',
						'value'       => '',
						'description' => esc_html__( 'Enter name svg icon from delaware theme' ),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'svg_icon',
						),
					),

					array(
						'heading'     => esc_html__( 'Icon Image', 'delaware' ),
						'description' => esc_html__( 'Upload icon image', 'delaware' ),
						'type'        => 'attach_image',
						'param_name'  => 'image_icon',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),

					array(
						'heading'     => esc_html__( 'Image size icon', 'delaware' ),
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'delaware' ),
						'type'        => 'textfield',
						'param_name'  => 'image_size_icon',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Icon Color', 'delaware' ),
						'param_name' => 'icon_color',
						'value'      => '',
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => array( 'fa_font', 'svg_icon' ),
						),
					),
				),
			)
		);

		// Contact Box
		vc_map(
			array(
				'name'     => esc_html__( 'Progress bar', 'delaware' ),
				'base'     => 'delaware_progress_bar',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number', 'delaware' ),
						'param_name'  => 'number',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Number color', 'delaware' ),
						'param_name' => 'number_color',
						'value'      => '',
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Progress bar color', 'delaware' ),
						'param_name' => 'progress_bar_color',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Progress bar stripes color', 'delaware' ),
						'param_name' => 'progress_bar_stripes_color',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Progress bacground bar color', 'delaware' ),
						'param_name' => 'progress_bar_bg_color',
						'value'      => '',
					),
					array(
						'type'        => 'textarea_html',
						'heading'     => esc_html__( 'Title', 'delaware' ),
						'param_name'  => 'content',
						'value'       => '',
						'admin_label' => true,
					),
				),
			)
		);

		// Testimonials
		vc_map(
			array(
				'name'     => esc_html__( 'Testimonials', 'delaware' ),
				'base'     => 'delaware_testimonials',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'delaware' ),
						'param_name' => 'title',
						'group'      => esc_html__( 'General', 'delaware' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub title', 'delaware' ),
						'param_name' => 'title_sub',
						'group'      => esc_html__( 'General', 'delaware' )
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Content', 'delaware' ),
						'param_name' => 'content_testi',
						'group'      => esc_html__( 'General', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
						'group'      => esc_html__( 'General', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'Background box', 'delaware' ),
						'type'       => 'dropdown',
						'param_name' => 'toggle_bk',
						'value'      => array(
							esc_html__( 'Display background', 'delaware' ) => '',
							esc_html__( 'No background', 'delaware' )      => 'no_bk',
						),
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'Text color', 'delaware' ),
						'type'       => 'dropdown',
						'param_name' => 'text_color',
						'value'      => array(
							esc_html__( 'Dark', 'delaware' )  => 'dark',
							esc_html__( 'Light', 'delaware' ) => 'light',
						),
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'heading'    => esc_html__( 'Button text color', 'delaware' ),
						'type'       => 'colorpicker',
						'param_name' => 'btn_color',
						'value'      => '',
						'group'      => esc_html__( 'Style', 'delaware' )
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
						'group'       => esc_html__( 'General', 'delaware' )
					),
				),
			)
		);

		// Testimonials Carousel
		vc_map(
			array(
				'name'     => esc_html__( 'Testimonials Carousel', 'delaware' ),
				'base'     => 'delaware_testimonials_carousel',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show Nav', 'delaware' ),
						'param_name' => 'nav',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show Dots', 'delaware' ),
						'param_name' => 'dots',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Autoplay', 'delaware' ),
						'param_name'  => 'autoplay',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable autoplay', 'delaware' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Autoplay speed', 'delaware' ),
						'param_name'  => 'autoplay_speed',
						'value'       => '2000',
						'description' => esc_html__( 'Set auto play speed (in ms).', 'delaware' ),
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => array( 'yes' ),
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'delaware' ),
						'param_name' => 'column',
						'value'      => array(
							esc_html__( '1', 'delaware' ) => '1',
							esc_html__( '2', 'delaware' ) => '2',
						),
					),
					array(
						'heading'    => esc_html__( 'Text color', 'delaware' ),
						'type'       => 'dropdown',
						'param_name' => 'text_color',
						'value'      => array(
							esc_html__( 'Dark', 'delaware' )  => 'dark',
							esc_html__( 'Light', 'delaware' ) => 'light',
						),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Testimonial Carousel', 'delaware' ),
						'value'      => '',
						'param_name' => 'setting',
						'params'     => array(
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__( 'Background image', 'delaware' ),
								'param_name' => 'image_gr',
							),
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__( 'Background box', 'delaware' ),
								'param_name' => 'image_box',
							),
							array(
								'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
								'type'       => 'vc_link',
								'param_name' => 'link',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__( 'Show icon arrow', 'delaware' ),
								'param_name' => 'show_icon',
								'value'      => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title_gr',
								'admin_label' => true
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Sub title', 'delaware' ),
								'param_name' => 'title_gr_sub',
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Content', 'delaware' ),
								'param_name' => 'content_gr_testi',
							),

						),
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
					),
				),
			)
		);

		// Testimonials Carousel 2
		vc_map(
			array(
				'name'     => esc_html__( 'Testimonials Carousel 2', 'delaware' ),
				'base'     => 'delaware_testimonials_carousel_2',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show Nav', 'delaware' ),
						'param_name' => 'nav',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show Dots', 'delaware' ),
						'param_name' => 'dots',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Autoplay', 'delaware' ),
						'param_name'  => 'autoplay',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable autoplay', 'delaware' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Autoplay speed', 'delaware' ),
						'param_name'  => 'autoplay_speed',
						'value'       => '2000',
						'description' => esc_html__( 'Set auto play speed (in ms).', 'delaware' ),
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => array( 'yes' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'delaware' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Underline Text', 'delaware' ),
						'param_name' => 'underline_text',
						'value'      => '',
						'dependency' => array(
							'element' => 'underline_check',
							'value'   => '2',
						),
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Description', 'delaware' ),
						'param_name' => 'content',
						'value'      => '',
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Testimonial Carousel', 'delaware' ),
						'value'      => '',
						'param_name' => 'setting',
						'params'     => array(
							array(
								'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
								'type'       => 'vc_link',
								'param_name' => 'link',
							),
							array(
								'heading'    => esc_html__( 'Show icon arrow', 'delaware' ),
								'type'       => 'checkbox',
								'param_name' => 'show_icon',
								'value'      => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
							),
							array(
								'heading'    => esc_html__( 'Text color', 'delaware' ),
								'type'       => 'dropdown',
								'param_name' => 'text_color',
								'value'      => array(
									esc_html__( 'Dark', 'delaware' )  => 'dark',
									esc_html__( 'Light', 'delaware' ) => 'light',
								),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'delaware' ),
								'param_name'  => 'title_gr',
								'admin_label' => true
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Sub title', 'delaware' ),
								'param_name' => 'title_gr_sub',
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Content', 'delaware' ),
								'param_name' => 'content_gr_testi',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		// button
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Button', 'delaware' ),
				'base'     => 'delaware_button',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Button background color', 'delaware' ),
						'param_name' => 'button_bg_color',
						'value'      => '',
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Button text color', 'delaware' ),
						'param_name' => 'button_text_color',
						'value'      => '',
					),
					array(
						'heading'    => esc_html__( 'Button align', 'delaware' ),
						'param_name' => 'button_align',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left', 'delaware' )   => 'left',
							esc_html__( 'Center', 'delaware' ) => 'center',
							esc_html__( 'Right', 'delaware' )  => 'right',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Font weight', 'delaware' ),
						'param_name'  => 'weight',
						'value'       => '',
						'description' => esc_html__( 'Enter Font weight.', 'delaware' ),
					),
					array(
						'heading'    => esc_html__( 'Button border', 'delaware' ),
						'param_name' => 'button_border',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'None', 'delaware' ) => 'none',
							esc_html__( 'Yes', 'delaware' )  => 'yes',
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Button border color', 'delaware' ),
						'param_name' => 'button_border_color',
						'value'      => '',
						'dependency' => array(
							'element' => 'button_border',
							'value'   => array( 'yes' ),
						),
					),
				),
			)
		);

		// link
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Link', 'delaware' ),
				'base'     => 'delaware_link',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'heading'    => esc_html__( 'URL (Link)', 'delaware' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
					),

					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Link color', 'delaware' ),
						'param_name' => 'color',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Font size', 'delaware' ),
						'param_name' => 'size',
					),
					array(
						'heading'    => esc_html__( 'Link align', 'delaware' ),
						'param_name' => 'link_align',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left', 'delaware' )   => 'left',
							esc_html__( 'Center', 'delaware' ) => 'center',
							esc_html__( 'Right', 'delaware' )  => 'right',
						),
					),
					array(
						'heading'     => esc_html__( 'Icon library', 'delaware' ),
						'description' => esc_html__( 'Select icon library.', 'delaware' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'delaware' ) => 'fa_font',
							esc_html__( 'Svg icon', 'delaware' )     => 'svg_icon',
						),

					),

					array(
						'heading'     => esc_html__( 'Icon', 'delaware' ),
						'description' => esc_html__( 'Pick an icon from library.', 'delaware' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fa_font',
						),
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Svg icon', 'delaware' ),
						'param_name'  => 'value_icon',
						'value'       => '',
						'description' => esc_html__( 'Enter name svg icon from delaware theme' ),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'svg_icon',
						),
					),
				),
			)
		);

		// Services
		vc_map(
			array(
				'name'     => esc_html__( 'Services', 'delaware' ),
				'base'     => 'delaware_services',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Services', 'delaware' ),
						'param_name'  => 'number',
						'value'       => 'All',
						'description' => esc_html__( 'Set numbers of Services you want to display. Set -1 to display all services', 'delaware' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Columns', 'delaware' ),
						'param_name'  => 'columns',
						'value'       => array(
							''                                    => '',
							esc_html__( '2 Columns', 'delaware' ) => '2',
							esc_html__( '3 Columns', 'delaware' ) => '3',
						),
						'description' => esc_html__( 'Set columns of Services you want to display.', 'delaware' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'delaware' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                 => '',
							esc_html__( 'Date', 'delaware' )   => 'date',
							esc_html__( 'Title', 'delaware' )  => 'title',
							esc_html__( 'Random', 'delaware' ) => 'rand',
						),
						'description' => esc_html__( 'Select to order Services. Leave empty to use the default order by of theme.', 'delaware' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'delaware' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'delaware' )  => 'asc',
							esc_html__( 'Descending ', 'delaware' ) => 'desc',
						),
						'description' => esc_html__( 'Select to sort Services. Leave empty to use the default sort of theme', 'delaware' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
					),
				),
			)
		);

		//Icon box carousel
		vc_map(
			array(
				'name'     => esc_html__( 'Delaware Icon Box Carousel', 'delaware' ),
				'base'     => 'delaware_icon_box_carousel',
				'class'    => '',
				'category' => esc_html__( 'Delaware', 'delaware' ),
				'params'   => array(
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show Dots', 'delaware' ),
						'param_name' => 'dots',
						'value'      => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Autoplay', 'delaware' ),
						'param_name'  => 'autoplay',
						'value'       => array( esc_html__( 'Yes', 'delaware' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable autoplay', 'delaware' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( esc_html__( 'Yes', 'delaware' ) => '1' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Autoplay speed', 'delaware' ),
						'param_name'  => 'autoplay_speed',
						'value'       => '2000',
						'description' => esc_html__( 'Set auto play speed (in ms).', 'delaware' ),
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => array( 'yes' ),
						),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Icon Box Carousel', 'delaware' ),
						'value'      => '',
						'param_name' => 'setting',
						'params'     => array(
							array(
								'heading'    => esc_html__( 'Title', 'delaware' ),
								'type'       => 'textfield',
								'param_name' => 'title',
								'admin_label'=> true,
							),
							array(
								'heading'    => esc_html__( 'Link (URL)', 'delaware' ),
								'type'       => 'vc_link',
								'param_name' => 'link',
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Description', 'delaware' ),
								'param_name' => 'desc',
								'value'      => '',
							),

							array(
								'heading'     => esc_html__( 'Icon library', 'delaware' ),
								'description' => esc_html__( 'Select icon library.', 'delaware' ),
								'param_name'  => 'icon_type',
								'type'        => 'dropdown',
								'value'       => array(
									esc_html__( 'Select type', 'delaware' )     => '',
									esc_html__( 'Font Awesome', 'delaware' ) => 'fa_font',
									esc_html__( 'Svg icon', 'delaware' )     => 'svg_icon',
								),
							),

							array(
								'heading'     => esc_html__( 'Icon', 'delaware' ),
								'description' => esc_html__( 'Pick an icon from library.', 'delaware' ),
								'type'        => 'iconpicker',
								'param_name'  => 'icon_fontawesome',
								'value'       => 'fa fa-adjust',
								'settings'    => array(
									'emptyIcon'    => false,
									'iconsPerPage' => 4000,
								),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'fa_font',
								),
							),

							array(
								'type' => 'svg_icons',
								'heading' => esc_html__('Svg name', 'delaware'),
								'param_name' => 'svg_name',
								'value' => '',
								'dependency' => array(
									'element' => 'icon_type',
									'value' => 'svg_icon',
								),
							),
						),
					),

					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'delaware' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'delaware' ),
						'group'       => esc_html__( 'Setting', 'delaware' )
					),
				),
			)

		);
	}
}