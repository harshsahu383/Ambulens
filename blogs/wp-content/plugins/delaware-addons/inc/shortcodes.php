<?php

/**
 * Define theme shortcodes
 *
 * @package Delaware
 */
class Delaware_Shortcodes {

	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	public $api_key = '';

	/**
	 * Store variables for maps
	 *
	 * @var array
	 */
	public $maps = array();

	/**
	 * Check if WooCommerce plugin is actived or not
	 *
	 * @var bool
	 */
	private $wc_actived = false;

	/**
	 * Construction
	 *
	 * @return Delaware_Shortcodes
	 */
	function __construct() {
		$this->wc_actived = function_exists( 'is_woocommerce' );

		$shortcodes = array(
			'delaware_empty_space',
			'delaware_cta',
			'delaware_section_title',
			'delaware_text_box',
			'delaware_testimonials',
			'delaware_image_box',
			'delaware_testi',
			'delaware_image_grid',
			'delaware_portfolio_grid',
			'delaware_portfolio_carousel',
			'delaware_icon_box',
			'delaware_icon_box_2',
			'delaware_portfolio_attribute',
			'delaware_counter',
			'delaware_video_banner',
			'delaware_testimonials_carousel',
			'delaware_blog_section',
			'delaware_member',
			'delaware_about',
			'delaware_contact_form_7',
			'delaware_progress_bar',
			'delaware_timeline',
			'delaware_gmap',
			'delaware_testimonials_carousel_2',
			'delaware_button',
			'delaware_office_box',
			'delaware_job_box_detail',
			'delaware_job_box',
			'delaware_contact_box',
			'delaware_link',
			'delaware_services',
			'delaware_tab',
			'delaware_portfolio_meta',
			'delaware_icon_box_carousel'
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}

		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	/**
	 * Check is portfolio
	 *
	 * @since  1.0
	 */
	function delaware_load_image( $img_id, $size ) {
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image       = wpb_getImageBySize(
				array(
					'attach_id'  => $img_id,
					'thumb_size' => $size,
				)
			);
			$image_thumb = $image['thumbnail'];
		} else {
			$image = wp_get_attachment_image_src( $img_id, $size );
			if ( $image ) {
				$img_url     = $image[0];
				$img_size    = $size;
				$image_thumb = "<img alt='$img_size'  src='$img_url'/>";
			}
		}

		return $image_thumb;
	}

	public function footer() {
		// Load Google maps only when needed
		if ( isset( $this->l10n['map'] ) ) {
			echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false&key=' . $this->api_key . '"><\/script>\')</script>';
		}

		wp_enqueue_script(
			'shortcodes', DELAWARE_ADDONS_URL . '/assets/js/frontend.js', array(
			'jquery',
		), '20171018', true
		);

		$this->l10n['isRTL'] = is_rtl();

		wp_localize_script( 'shortcodes', 'delawareShortCode', $this->l10n );
	}

	/**
	 * Get empty space
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_empty_space( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'height'        => '',
				'height_mobile' => '',
				'height_tablet' => '',
				'bg_color'      => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'ba-empty-space',
			$atts['el_class'],
		);

		$style = '';

		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		$height        = $atts['height'] ? (float) $atts['height'] : 0.0;
		$height_tablet = $atts['height_tablet'] ? (float) $atts['height_tablet'] : $height;
		$height_mobile = $atts['height_mobile'] ? (float) $atts['height_mobile'] : $height_tablet;

		$inline_css        = $height >= 0.0 ? ' style="height: ' . esc_attr( $height ) . 'px"' : '';
		$inline_css_mobile = $height_mobile >= 0.0 ? ' style="height: ' . esc_attr( $height_mobile ) . 'px"' : '';
		$inline_css_tablet = $height_tablet >= 0.0 ? ' style="height: ' . esc_attr( $height_tablet ) . 'px"' : '';

		return sprintf(
			'<div class="%s" style="%s">' .
			'<div class="ba_empty_space_lg" %s></div>' .
			'<div class="ba_empty_space_md" %s></div>' .
			'<div class="ba_empty_space_xs" %s></div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			$inline_css,
			$inline_css_tablet,
			$inline_css_mobile
		);
	}

	/**
	 * Get CTA
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_cta( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'       => '',
				'title_color' => '',
				'size'        => '',
				'weight'      => '',
				'desc'        => '',
				'link'        => '',
				'show_icon'   => '',
				'style_btn'   => 'classic',
				'text_color'  => 'dark',
				'btn_color'   => '',
				'bg_color'    => '',
				'el_class'    => '',
			), $atts
		);

		$border = 'border-display';
		if ( ! empty( $atts['bg_color'] ) ) {
			$border = '';
		}

		$fix_top = '';
		if ( ! empty( $atts['title'] ) && ! empty( $atts['desc'] ) ) {
			$fix_top = 'fix-top';
		}

		$css_class = array(
			'dl-cta',
			'text-' . $atts['text_color'],
			$border,
			$atts['el_class'],
		);

		$css_style = $atts['style_btn'];

		$text_color = $atts['title_color'];

		$style_text = '';
		$style_text .= "style='";
		if ( ! empty( $text_color ) ) : $style_text .= "color:$text_color;"; endif;
		if ( $atts['size'] ) {
			$size = $this->dl_font_size_handle( $atts['size'] );
			$style_text .= "font-size:$size;";
		}

		if ( $atts['weight'] ) {
			$weight = $atts['weight'];
			$style_text .= "font-weight:$weight;";
		}

		$style_text .= "'";

		$output   = array();
		$output[] = '<div class="cta-content">';
		if ( $atts['title'] ) {
			$output[] = sprintf( '<h2 %s>%s</h2>', $style_text, $atts['title'] );
		}

		if ( $atts['desc'] ) {
			$output[] = sprintf( '<p>%s</p>', $atts['desc'] );
		}

		$output[] = '</div>';

		$output[] = $this->delaware_addons_btn( $atts );

		return sprintf(
			'<div class="%s">' .
			'<div class="container">' .
			'<div class="cta-wrapper %s %s">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$css_style,
			$fix_top,
			implode( ' ', $output )
		);
	}


	/**
	 * Get section title
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_section_title( $atts, $content ) {
		$atts      = shortcode_atts(
			array(
				'title'           => '',
				'underline_check' => '',
				'underline_text'  => '',
				'heading'         => 'h2',
				'size'            => '',
				'weight'          => '',
				'select_type'     => '1',
				'link'            => '',
				'text_color'      => '',
				'descr_button'    => '',
				'el_class'        => '',
			), $atts
		);
		$css_class = array(
			'delaware-section-title',
			$atts['el_class']
		);

		$heading         = $atts['heading'];
		$underline_text  = $atts['underline_text'];
		$title           = $atts['title'];
		$select_type     = $atts['select_type'];
		$text_color      = $atts['text_color'];
		$underline_check = $atts['underline_check'];
		$link            = vc_build_link( $atts['link'] );
		$btn_wrapper_css = '';

		switch ( $select_type ) {
			case '1':
				$class_layout_t = 5;
				$class_layout_d = 7;
				$section_type   = 1;
				break;
			case '2':
				$class_layout_d = $class_layout_t = '12 text-center';
				$section_type   = 2;
				break;
			case '3':
				$class_layout_t  = 3;
				$class_layout_d  = 6;
				$section_type    = 3;
				$btn_wrapper_css = 'col-sm-3 col-xs-12';
				break;
			case '4':
				$class_layout_d = $class_layout_t = '12 text-center';
				$section_type   = 4;
				break;
			case '5':
				$class_layout_d = $class_layout_t = '12 text-left';
				$section_type   = 5;
				break;
			case '6':
				$class_layout_d = $class_layout_t = '12 text-center';
				$section_type   = 6;
				break;
			case '7':
				$class_layout_d = $class_layout_t = '12 text-left';
				$section_type   = 7;
				break;
			case '8':
				$class_layout_d = $class_layout_t = '12 text-left';
				$section_type   = 8;
				break;
			default:
				$class_layout_t = 5;
				$class_layout_d = 7;
		}

		$style_text = '';
		$style_text .= "style='";

		if ( ! empty( $text_color ) ) : $style_text .= "color:$text_color;"; endif;

		if ( $atts['size'] ) {
			$size = $this->dl_font_size_handle( $atts['size'] );
			$style_text .= "font-size:$size;";
		}

		if ( $atts['weight'] ) {
			$weight = $atts['weight'];
			$style_text .= "font-weight:$weight;";
		}

		$style_text .= "'";

		$class_check = 'no-underline';
		if ( $underline_check == 2 ) {
			$class_check = 'underline';
		}

		if ( ! empty( $underline_text ) ) {
			$title = str_replace( $underline_text, ' <span class="underline">' . $underline_text . '</span> ', $title );
		}

		if ( ! empty( $link['url'] ) && ( $select_type == 3 || $select_type == 7 || $select_type == 8 ) ) {
			$value_button = $link['title'];
			$link         = $link['url'];
			$descr_button = $atts['descr_button'];
			$btn_css      = 'dl-btn-primary';

			if ( $select_type == 3 ) {
				$btn_css = 'btn';
			}

			$layout_bt = '<div class="delaware-button ' . esc_attr( $btn_wrapper_css ) . '">
				<a class="' . esc_attr( $btn_css ) . '" href="' . $link . '">' . $value_button . '<span>' . $descr_button . '</span></a>
			</div>';
		} else {
			$layout_bt = '';
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$layout_descr = '';
		if ( $select_type != 4 ) {
			$layout_descr = '<div class="col-xs-12 col-sm-' . $class_layout_d . ' desc">' . $content . '</div>';
		}

		return sprintf(
			"<div class='%s delaware_title-type-%s row' >
				<%s class='col-xs-12 col-sm-%s title %s' %s>%s</%s>
				%s %s
			</div>",
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $section_type ),
			$heading,
			$class_layout_t,
			esc_attr( $class_check ),
			$style_text,
			$title,
			$heading,
			$layout_descr,
			$layout_bt
		);
	}

	/**
	 * Text box
	 */
	function delaware_text_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'align'            => 'left',
				'title'            => '',
				'sub_title'        => '',
				'underline'        => 'yes',
				'underline_text'   => '',
				'border'           => 'yes',
				'background_image' => '',
				'el_class'         => '',
			), $atts
		);

		$css_class = array(
			'dl-text-box',
			'text-' . $atts['align'],
			$atts['underline'] == 'yes' ? 'show-underline' : '',
			$atts['border'] == 'yes' ? 'show-border' : '',
			$atts['el_class']
		);

		$title          = $atts['title'];
		$sub_title      = $atts['sub_title'];
		$underline_text = $atts['underline_text'];
		$output         = array();
		$style          = '';

		if ( ! empty( $underline_text ) ) {
			$title = str_replace( $underline_text, '<span class="underline">' . $underline_text . '</span>', $title );
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		if ( $title ) {
			$output[] = sprintf( '<h2 class="box-title">%s</h2>', $title );
		}

		if ( $sub_title ) {
			$output[] = sprintf( '<h4 class="sub-title">%s</h4>', $sub_title );
		}

		if ( $content ) {
			$output[] = sprintf( '<div class="box-content">%s</div>', $content );
		}

		if ( $atts['background_image'] ) {
			$image_src = wp_get_attachment_image_src( $atts['background_image'], 'full' );
			$style     = 'style="background-image: url( ' . esc_url( $image_src[0] ) . ' );"';
		}

		return sprintf(
			'<div class="%s" %s>%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			implode( '', $output )
		);
	}

	/**
	 * Get image box
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_image_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'         => '1',
				'link'          => '',
				'heading'       => 'h3',
				'text_color'    => '',
				'service_image' => '',
				'image_size'    => '',
				'icon_type'     => 'fontawesome',
				'svg_name'    => '',
				'icon_fontawesome'    => '',
				'img_pos'       => '1',
				'background'    => '',
				'icon_size'     => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'col-xs-12 no-padding delaware-image-box',
			$atts['el_class']
		);

		$select_type = $atts['style'];
		$heading     = $atts['heading'];
		$link        = vc_build_link( $atts['link'] );
		$text_color  = $atts['text_color'];
		$background  = $atts['background'];
		$icon_type   = $atts['icon_type'];
		$value_icon  = $atts['svg_name'];
		$text_color  = ! empty( $text_color ) ? "style='color:$text_color;'" : '';
		$background  = ! empty( $background ) ? "style='background-color:$background;'" : '';

		if ( $link != null ) :
			$title      = $link['title'];
			$title_link = $link['url'];
		else :
			$title      = '';
			$title_link = '#';
		endif;

		$style_ic_size = '';

		if ( $atts['icon_size'] ) {
			$icon_size     = $this->dl_font_size_handle( $atts['icon_size'] );
			$style_ic_size = "font-size:$icon_size;";
		}

		$style_ic = $style_ic_size == '' ? '' : "style='$style_ic_size'";

		if ( $icon_type == 'fontawesome' ) {
			$icon = sprintf( "<span class='%s' %s></span>", $atts['icon_fontawesome'],$style_ic );
		} elseif ( $icon_type == 'number' ){
			$icon = "<span class='svg-icon' $style_ic><svg viewBox='0 0 20 20'><use xlink:href='#$value_icon'></use></svg></span>";
		} else{
			$icon = '';
		}

		if ( $atts['service_image'] ) {
			$image_thumb = delaware_load_image( $atts['service_image'], $atts['image_size'] );
		} else {
			$image_thumb = '';
		}
		$title_1 = "<$heading class='title' title='$title' $text_color>$title</$heading>";

		$read_more = esc_html__( 'Read more', 'delaware' );

		$emtry_header = sprintf(
			"<a href='%s' class='emtry-header'><div class='emtry-thumbnail'>%s</div><div class='icon-content'><div class='overlay-border'>%s</div></div></a>",
			$title_link, $image_thumb, $icon
		);

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$emtry_content = sprintf(
			"<div class='emtry-content' %s>
					<a href='%s' class='emtry-title'>%s</a>
					<div class='descreption'>%s</div>
					<a href='%s' class='readmore'>%s <span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#next'></use></svg></span></a>
					<div class='icon-content'>%s</div>
				</div>", $background, $title_link, $title_1, $content, $title_link, $read_more, $icon
		);

		$wrap_content = $emtry_content . $emtry_header;
		if ( $atts['img_pos'] == 1 ) : $wrap_content = $emtry_header . $emtry_content;endif;

		return sprintf( "<div class='%s delaware-image-box-%s'>%s</div>", esc_attr( implode( ' ', $css_class ) ), $select_type, $wrap_content );
	}

	/**
	 * Get image grid
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_image_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'colum'      => '6',
				'images'     => '',
				'image_size' => '',
				'hover'      => '',
				'el_class'   => '',
			), $atts
		);

		$css_class = array(
			'col-xs-12 no-padding delaware-image-grid',
			$atts['hover'] == 'yes' ? 'effect-enable' : '',
			$atts['el_class']
		);

		$number_colum = intval( $atts['colum'] );
		$image_ids    = explode( ',', $atts['images'] );
		$class_col    = 'image-item text-center col-flex-xs-6 col-flex-sm-6';

		if ( $number_colum == 5 ) {
			$class_col .= " col-flex-md-1-5";
		} else {
			$number_col = 12 / $number_colum;
			$class_col .= " col-flex-md-" . $number_col;
		}

		$images = array();
		foreach ( $image_ids as $image_id ) {

			if ( $atts['images'] ) {
				$image_thumb = delaware_load_image( $image_id, $atts['image_size'] );
			} else {
				$image_thumb = '';
			}

			$images[] = "<div class='$class_col'> $image_thumb </div>";
		}
		$images = implode( '', $images );

		return sprintf( "<div class='%s'><div class='row-flex'>%s</div></div>", esc_attr( implode( ' ', $css_class ) ), $images );
	}

	/**
	 * Get portfolio grid
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_portfolio_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'         => '1',
				'colum'         => '4',
				'value_item'    => '6',
				'image_size'    => '',
				'orderby'       => '',
				'order'         => 'descending',
				'filter'        => 'show',
				'filter_type'   => 'default',
				'filter_color'  => 'dark',
				'categories'    => '',
				'el_class'      => '',
			), $atts
		);

		$select_type  = $atts['style'];
		$select_colum = $atts['colum'];
		$filter       = $atts['filter'];
		$filter_type  = $atts['filter_type'];
		$oder         = $atts['order'];
		$value_item   = $atts['value_item'];

		if ( $oder == 'ascending' ): $oder = 'ASC';
		else:$oder = 'DESC';endif;
		if ( $select_type == 2 ): $select_colum = '5-2';endif;
		$cats = array();

		$args      = array(
			'post_type'      => 'portfolio',
			'posts_per_page' => $value_item,
			'orderby'        => $atts['orderby'],
			'order'          => $oder
		);
		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) :
			$delaware_value = array();

			while ( $the_query->have_posts() ) : $the_query->the_post();

				$permalink = get_permalink();
				$the_title = get_the_title();
				$excerpt   = get_the_excerpt();
				$terms     = get_the_terms( get_the_ID(), 'portfolio_category' );

				if ( $terms && ! is_wp_error( $terms ) ) :
					$category_link = get_term_link( $terms[0]->term_id, 'portfolio_category' );
					$category_name = $terms[0]->name;
					$category_slug = $terms[0]->slug;
					$category      = "<a class='category' href='$category_link'>$category_name</a>";
					foreach ( $terms as $cat ) {
						if ( empty( $cats[$cat->term_id] ) ) {
							$cats[$cat->term_id] = array( 'name' => $cat->name, 'slug' => $cat->slug, );
						}
					}

				endif;

				$css = 'col-sm-' . $select_colum . ' element-item ' . $category_slug . ' delaware-portfolio-grid-wrapter';

				if ( get_post_thumbnail_id( get_the_ID() ) ) {
					$image_thumb = delaware_load_image( get_post_thumbnail_id( get_the_ID() ), $atts['image_size'] );
				} else {
					$image_thumb = '';
					$css .= ' no-thumb';
				}

				$delaware_value[] = sprintf(
					"<div class='%s' data-category='%s'>
						<div class='emtry-wrapter'>
							<a href='%s' class='emtry-thumbnail'>%s</a>
							<div class='hover'>
								<div class='emtry-category'>%s</div>
								<a href='%s' class='emtry-title'>%s</a>
							</div>
						</div>
						<div class='emtry-content'>%s</div>
					</div>", esc_attr( $css ), $category_slug, $permalink, $image_thumb, $category, $permalink, $the_title, $excerpt
				);
			endwhile;
			wp_reset_postdata();
		endif;

		if ( $select_type == 2 ):
			if ( $filter == 'show' ) :

				if ($filter_type == 'customs') {
					$cats_slug = explode(',', $atts['categories']);

					foreach ($cats_slug as $slug) {
						$cat = get_term_by('slug', $slug, 'portfolio_category');

						if ($cat) {
							$list_category[] = "<span class='button' data-filter='." . esc_attr( $cat->slug ) . "'>" . esc_html( $cat->name ) . "</span>";
						}
					}
				}
				else{
					foreach ( $cats as $category ) {
						$slug            = esc_attr( $category['slug'] );
						$cate_name       = esc_attr( $category['name'] );
						$list_category[] = "<span class='button' data-filter='." . esc_attr( $slug ) . "'>" . esc_html( $cate_name ) . "</span>";
					}
				}

				$str_viewall   = esc_html__( 'View all', 'delaware' );
				$list_category = implode( ' ', $list_category );
				$out_put       = sprintf(
					"<div id='filters' class='button-group text-center %s'><span class='button active' data-filter='*'>%s</span>%s</div>",esc_attr($atts['filter_color']) ,$str_viewall, $list_category
				);

			else: $out_put = '';
			endif;
		else: $out_put = '';
		endif;


		$delaware_post = implode( ' ', $delaware_value );
		if ( $select_type == 2 ) :
			$list_post = "<div class='grid row' id='delaware_portfolio_vs_grid'>$delaware_post</div>";
		else :
			$list_post = "<div class='row'>$delaware_post</div>";
		endif;


		return sprintf( '<div class="delaware-wrap-item delaware-wrap-item-type-%s">%s%s</div>', $select_type, $out_put, $list_post );
	}

	/**
	 * Get carousel
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_portfolio_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(

				'colum'           => '4',
				'value_item'      => '',
				'image_size'      => '',
				'excerpt_content' => '10',

			), $atts
		);

		$item_per_row   = $atts['colum'];
		$posts_per_page = $atts['value_item'];
		$class_of_item  = 12 / $item_per_row;
		if ( $item_per_row == 5 ): $class_of_item = '5-2';endif;

		$args      = array( 'post_type' => 'portfolio', 'posts_per_page' => $posts_per_page );
		$the_query = new WP_Query( $args );
		$html      = array();

		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$permalink = get_permalink();
				$the_title = get_the_title();
				$excerpt   = get_the_excerpt();
				$id        = get_the_ID();
				$excerpt   = delaware_content_limit( $excerpt, $atts['excerpt_content'], false );
				$category  = delaware_portfolio_category();

				$image_thumb = '';
				$id_thumb    = get_post_thumbnail_id( $id );
				if ( $id_thumb ) {
					$image_thumb = delaware_load_image( $id_thumb, $atts['image_size'] );
				}

				$readmore = esc_html__( 'Read more', 'delaware' );
				$html[]   = sprintf(
					"<div class='col-sm-%s delaware-portfolio-grid-wrapter'>
						<div class='emtry-wrapter'>
							<a href='%s' class='emtry-thumbnail'>%s</a>
							<div class='hover'>
								<div class='emtry-category'>%s</div>
								<h4 class='emtry-title'><a href='%s'>%s</a></h4>
								<div class='emtry-content'>%s</div>
								<a href='%s' class='readmore'>%s<span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#next'></use></svg></span></a>
							</div>
							<div class='entry-header'>
								<div class='emtry-category'>%s</div>
								<h4 class='emtry-title'><a href='%s'>%s</a></h4>
							</div>
						</div>
					</div>", $class_of_item, $permalink, $image_thumb, $category, $permalink, $the_title, $excerpt, $permalink, $readmore,
					$category, $permalink, $the_title
				);
			endwhile;
		endif;

		$out_put = implode( ' ', $html );

		return sprintf( '<div class="delaware-portfolio-carousel" data-number="%s">%s</div>', $item_per_row, $out_put );
	}

	/**
	 * Shortcode to display latest post
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function delaware_blog_section( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'          => 'border',
				'section_title'  => esc_html__( 'Section Blog', 'delaware' ),
				'number'         => '3',
				'type'           => 'grid',
				'btn_text'       => esc_html__( 'View More', 'delaware' ),
				'nav'            => false,
				'dot'            => false,
				'autoplay'       => false,
				'autoplay_speed' => '800',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'dl-latest-post blog-grid',
			$atts['style'] . '-style',
			$atts['type'],
			$atts['el_class'],
		);

		$section_title = $btn = '';

		if ( $atts['section_title'] ) {
			$atts['title']     = $atts['section_title'];
			$atts['position']  = 'left';
			$atts['color']     = 'dark';
			$atts['font_size'] = 'large';
			$section_title     = $atts['section_title'];
		}

		if ( $atts['type'] == 'grid' && $atts['btn_text'] ) {
			$btn = sprintf(
				'<a href="%s" class="mf-btn-2 view-more">%s<span class="dl-icon-next svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#next"></use></svg></span></a>',
				esc_url( get_permalink( get_option( 'page_for_posts' ) ) ),
				$atts['btn_text']
			);
		}

		$autoplay_speed = intval( $atts['autoplay_speed'] );

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['nav'] ) {
			$nav = true;
		} else {
			$nav = false;
		}

		if ( $atts['dot'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		$is_carousel = 1;
		if ( $atts['type'] == 'grid' ) {
			$is_carousel = 0;
		}

		$id                      = uniqid( 'post-slider-' );
		$this->l10n['post'][$id] = array(
			'nav'            => $nav,
			'dot'            => $dot,
			'autoplay'       => $autoplay,
			'autoplay_speed' => $autoplay_speed,
			'is_carousel'    => $is_carousel,
		);

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();
			global $mf_post;
			$mf_post['css']  = ' blog-wrapper-col-3 col-flex-md-4 col-flex-sm-6 col-flex-xs-6';
			$mf_post['size'] = 'delaware-blog-grid-thumb';

			ob_start();
			get_template_part( 'parts/content', get_post_format() );
			$output[] = ob_get_clean();

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="%s">
				<div class="dl-latest-post-header">%s%s</div>
                <div class="post-list row-flex" id="%s">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$section_title,
			$btn,
			esc_attr( $id ),
			implode( '', $output )
		);
	}

	function delaware_member( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'section_title'      => esc_html__( 'Member', 'delaware' ),
				'member_image'       => '',
				'member_name'        => 'grid',
				'member_job'         => esc_html__( 'View More', 'delaware' ),
				'member_description' => false,
				'image_size'         => '',
				'socials'            => '',
				'el_class'           => '',
			), $atts
		);

		$css_class = array(
			'member-box',
			$atts['el_class'],
		);

		$socials       = vc_param_group_parse_atts( $atts['socials'] );
		$social_output = array();
		if ( ! empty( $socials ) ) {
			foreach ( $socials as $name => $value ) {
				$icon      = $image = $icon_html = '';
				$icon_type = $value['icon_type'];

				if ( isset( $value['icon_fontawesome'] ) && $value['icon_fontawesome'] ) {
					$icon = '<i class="' . esc_attr( $value['icon_fontawesome'] ) . '"></i>';
				}

				if ( isset( $value['image'] ) && $value['image'] ) {
					$image = wp_get_attachment_image( $value['image'], 'full' );
				}

				if ( $icon_type == 'fontawesome' ) {
					$icon_html = $icon;
				} else {
					$icon_html = $image;
				}

				if ( isset( $value['link'] ) && $value['link'] ) {
					$link = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $value['link'] ), $icon_html );
				} else {
					$link = $icon_html;
				}

				$social_output[] = sprintf( '<li>%s</li>', $link );

			}
		}

		$image_size = $atts['image_size'];
		$image_src  = '';

		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image = wpb_getImageBySize(
				array(
					'attach_id'  => $atts['member_image'],
					'thumb_size' => $image_size,
				)
			);

			if ( $image['thumbnail'] ) {
				$image_src = $image['thumbnail'];
			} elseif ( $image['p_img_large'] ) {
				$image_src = $image['p_img_large'][0];
			}

		} else {
			$image_src = wp_get_attachment_image( $atts['member_image'], $image_size );
		}

		return sprintf(
			'<div class="%s"><div class="member-header">%s</div>
                <div class="member-item">
                	<div class="member-image">
                		%s
                		<ul class="team-social">%s</ul>
					</div>
					
					<div class="member-entry">
						<h2>%s</h2>
						<span>%s</span>
						<div class="member-content">
							%s
						</div>
					</div>
				</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$atts['section_title'],
			$image_src,
			implode( '', $social_output ),
			$atts['member_name'],
			$atts['member_job'],
			$atts['member_description']
		);
	}

	/**
	 * Get icon box
	 *
	 * @since  1.0
	 *
	 * @return string
	 *
	 * Shortcode to display contact form 7
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function delaware_contact_form_7( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'section_title'  => '',
				'color'          => 'light',
				'form'           => '',
				'form_bg'        => '',
				'padding_top'    => '',
				'padding_right'  => '',
				'padding_bottom' => '',
				'padding_left'   => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'dl-contact-form-7',
			'form-' . $atts['color'],
			$atts['el_class']
		);

		$style = array();

		$section_title = '';
		$p_top         = intval( $atts['padding_top'] );
		$p_right       = intval( $atts['padding_right'] );
		$p_bottom      = intval( $atts['padding_bottom'] );
		$p_left        = intval( $atts['padding_left'] );

		if ( $atts['section_title'] ) {
			$atts['title']     = $atts['section_title'];
			$atts['position']  = 'left';
			$atts['color']     = 'dark';
			$atts['font_size'] = 'medium';
			//$section_title     = $this->delaware_addons_title( $atts );
			$section_title = $atts['section_title'];
		}

		if ( $atts['form_bg'] ) {
			$style[] = 'background-color:' . $atts['form_bg'] . ';';
		}

		if ( $atts['padding_top'] ) {
			$style[] = 'padding-top: ' . $p_top . 'px;';
		}

		if ( $atts['padding_right'] ) {
			$style[] = 'padding-right: ' . $p_right . 'px;';
		}

		if ( $atts['padding_bottom'] ) {
			$style[] = 'padding-bottom: ' . $p_bottom . 'px;';
		}

		if ( $atts['padding_left'] ) {
			$style[] = 'padding-left: ' . $p_left . 'px;';
		}

		return sprintf(
			'<div class="%s" style="%s">%s%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $style ),
			$section_title,
			do_shortcode( '[contact-form-7 id="' . esc_attr( $atts['form'] ) . '" title=" ' . get_the_title( $atts['form'] ) . ' "]' )
		);
	}

	function delaware_timeline( $atts, $content ) {
		$atts = shortcode_atts(
			array(

				'year'     => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'timeline-box',
			$atts['el_class'],
		);

		$years           = vc_param_group_parse_atts( $atts['year'] );
		$timeline_output = '';
		if ( ! empty( $years ) ) {

			foreach ( $years as $name => $value ) {
				$year_html = '<li class="timeline-item period">
                    <div class="timeline-info"></div>
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h2 class="timeline-title">' . $value['name'] . '</h2>
                    </div>
                	</li>';

				$events      = vc_param_group_parse_atts( $value['event'] );
				$events_html = '';

				if ( ! empty( $events ) ) {
					foreach ( $events as $name => $event ) {
						$events_html = $events_html . sprintf(
								'<li class="timeline-item">
	                    <div class="timeline-marker"></div>
	                    <div class="timeline-content">
	                    	<div>
		                        <span>%s</span>
		                        <h3 class="timeline-title">%s</h3>
		                        <p>%s</p>
	                        </div>
	                    </div>
	                    </li>', $event['date'], $event['title'], $event['description']
							);
					}
				}

				$timeline = '<ul class="year-item">' . $year_html . $events_html . '</ul>';
				$timeline_output .= $timeline;
			}
		}

		return sprintf(
			'<div class="%s"><div class="timeline timeline-centered">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			$timeline_output
		);
	}

	function delaware_tab( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'tab'      => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'dl-tabs',
			$atts['el_class'],
		);

		$tab              = vc_param_group_parse_atts( $atts['tab'] );
		$tab_nav_output   = array();
		$tab_panel_output = array();
		if ( ! empty( $tab ) ) {
			$i = 0;
			foreach ( $tab as $name => $value ) {
				$i ++;
				if ( $value['icon_type'] == 'fa-font' ) {
					$icon = "<span class='svg-icon'><i class='" . $value['icon_fontawesome'] . "' aria-hidden='true'></i></span>";
				} elseif ($value['icon_type'] == 'number')  {
					$icon = "<span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#" . $value['svg_name'] . "'></use></svg></span>";
				} else {
					$icon = '';
				}

				if ( $i == 1 ) {
					$class_index = 'active';
				} else {
					$class_index = '';
				}

				$tab_nav_output[] = sprintf( '<li class="%s">%s<span>%s</span></li>', $class_index, $icon, $value['title'] );
			}

			foreach ( $tab as $name => $value ) {
				$link       = vc_build_link( $value['link'] );
				$title      = $link['title'];
				$title_link = $link['url'];

				$tab_panel_output[] = sprintf( '<div class="tab-panel"><div>%s</div> <a href="%s">%s</a></div>', $value['description'], $title_link, $title );
			}
		}

		return sprintf(
			'<div class="%s"><div class="tab-block-overview">
				<ul class="tab-nav">%s</ul>
				<div class="tab-content">%s</div>
				</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $tab_nav_output ),
			implode( ' ', $tab_panel_output )

		);
	}

	/*
	 * GG Maps shortcode
	 */
	function delaware_gmap( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'api_key'     => '',
				'info'        => '',
				'marker'      => '',
				'width'       => '',
				'height'      => '500',
				'zoom'        => '13',
				'el_class'    => '',
				'style'       => '1',
				'map_color'   => '#2685f9',
			), $atts
		);

		$class = array(
			'dl-map-shortcode',
			$atts['el_class'],
		);

		$style = '';
		if ( $atts['width'] ) {
			$unit = 'px;';
			if ( strpos( $atts['width'], '%' ) ) {
				$unit = '%;';
			}

			$atts['width'] = intval( $atts['width'] );
			$style .= 'width: ' . $atts['width'] . $unit;
		}
		if ( $atts['height'] ) {
			$unit = 'px;';
			if ( strpos( $atts['height'], '%' ) ) {
				$unit = '%;';
			}

			$atts['height'] = intval( $atts['height'] );
			$style .= 'height: ' . $atts['height'] . $unit;
		}
		if ( $atts['zoom'] ) {
			$atts['zoom'] = intval( $atts['zoom'] );
		}

		if ( $atts['style'] ) {
			$atts['style'] = intval( $atts['style'] );
		}

		$id   = uniqid( 'dl_map_' );
		$html = sprintf(
			'<div class="%s"><div id="%s" class="dl-map" style="%s"></div></div>',
			implode( ' ', $class ),
			$id,
			$style
		);

		$lats            = array();
		$lng             = array();
		$info            = array();
		$i               = 0;
		$fh_info         = vc_param_group_parse_atts( $atts['info'] );

		if ( ! empty( $fh_info ) ) {
			foreach ( $fh_info as $key => $value ) {

				$map_img = $map_info = $map_html = '';

				if ( isset( $value['image'] ) && $value['image'] ) {
					$map_img = wp_get_attachment_image( $value['image'], 'thumbnail' );
				}

				if ( isset( $value['details'] ) && $value['details'] ) {
					$map_info = sprintf( '<div class="mf-map-info">%s</div>', $value['details'] );
				}

				$map_html = sprintf(
					'<div class="box-wrapper" style="width:150px">%s<h4>%s</h4>%s</div>',
					$map_img,
					esc_html__( 'Location', 'cargohub' ),
					$map_info
				);

				$coordinates = $this->get_coordinates( $value['address'], $atts['api_key'] );
				$lats[]      = $coordinates['lat'];
				$lng[]       = $coordinates['lng'];
				$info[]      = $map_html;

				if ( isset( $coordinates['error'] ) ) {
					return $coordinates['error'];
				}

				$i ++;
			}
		}

		$marker = '';
		if ( $atts['marker'] ) {

			if ( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker           = $attachment_image ? $attachment_image[0] : '';
			}
		}

		$this->api_key = $atts['api_key'];

		$this->l10n['map'][$id] = array(
			'type'      => 'normal',
			'lat'       => $lats,
			'lng'       => $lng,
			'zoom'      => $atts['zoom'],
			'marker'    => $marker,
			'height'    => $atts['height'],
			'info'      => $info,
			'number'    => $i,
			'style'     => $atts['style'],
			'map_color' => $atts['map_color'],
		);

		return $html;

	}

	/**
	 * Helper function to get coordinates for map
	 *
	 * @since 1.0.0
	 *
	 * @param string $address
	 * @param bool   $refresh
	 *
	 * @return array
	 */
	function get_coordinates( $address,$api_key, $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false', 'key' => $api_key );
			$url      = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'delaware' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'delaware' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'delaware' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'delaware' );
				} else {
					$results['error'] = esc_html__( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'delaware' );
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'delaware' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	function delaware_office_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'section_title' => esc_html__( 'Office Box', 'delaware' ),
				'contacts'      => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'office-box',
			$atts['el_class'],
		);

		$contacts        = vc_param_group_parse_atts( $atts['contacts'] );
		$contacts_output = array();
		if ( ! empty( $contacts ) ) {
			foreach ( $contacts as $name => $value ) {

				$contacts_output[] = sprintf(
					'
					<li><span>%s:</span>%s</li>',
					$value['title'], $value['content']
				);

			}
		}


		return sprintf(
			'<div class="%s"><ul>%s</ul></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $contacts_output )

		);
	}

	function delaware_job_box_detail( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'section_title' => esc_html__( 'Job Box Detail', 'delaware' ),
				'jobs'          => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'job-box-detail',
			$atts['el_class'],
		);

		$jobs        = vc_param_group_parse_atts( $atts['jobs'] );
		$jobs_output = array();
		if ( ! empty( $jobs ) ) {
			foreach ( $jobs as $name => $value ) {
				$jobs_output[] = sprintf( '<li><span>%s: </span>%s</li>', $value['title'], $value['content'] );
			}
		}


		return sprintf(
			'<div class="%s"><ul>%s</ul></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $jobs_output )

		);
	}

	function delaware_job_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'section_title' => esc_html__( 'Job Box', 'delaware' ),
				'jobs'          => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'job-box',
			$atts['el_class'],
		);

		$jobs        = vc_param_group_parse_atts( $atts['jobs'] );
		$jobs_output = array();
		if ( ! empty( $jobs ) ) {
			foreach ( $jobs as $name => $value ) {

				$attributes = array();

				$link = vc_build_link( $value['link'] );

				if ( ! empty( $link['url'] ) ) {
					$attributes['href'] = $link['url'];
				}

				$label = $link['title'];

				if ( ! $label ) {
					$attributes['title'] = $label;
				}

				if ( ! empty( $link['target'] ) ) {
					$attributes['target'] = $link['target'];
				}

				if ( ! empty( $link['rel'] ) ) {
					$attributes['rel'] = $link['rel'];
				}

				$attr = array();

				foreach ( $attributes as $name => $v ) {
					$attr[] = $name . '="' . esc_attr( $v ) . '"';
				}

				$link_icon = '<span class="dl-icon-next svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#next"></use></svg></span>';

				if ( $attributes['href'] || $attributes['title'] ) {
					$button = sprintf(
						'<%1$s %2$s>%3$s %4$s</%1$s>',
						empty( $attributes['href'] ) ? 'span' : 'a',
						implode( ' ', $attr ),
						$label,
						$link_icon
					);
				} else {
					$button = '';
				}

				$jobs_output[] = sprintf(
					'<div class="job-wrapper col-md-6 col-sm-6 col-xs-6">
						<div class="job-box-item">
						<span class="job-date">%s </span>
						<h5>%s</h5>
						<span class="address">%s</span>
						%s
						</div>
					</div>',
					$value['date'], $value['title'], $value['address'], $button
				);
			}
		}

		return sprintf(
			'<div class="%s"><div class="row">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $jobs_output )

		);
	}

	function delaware_contact_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'section_title'        => '',
				'heading'              => 'h2',
				'underline_check'      => '',
				'underline_text'       => '',
				'sub_title'            => '',
				'description'          => '',
				'des_highlight'        => '',
				'addresses'            => '',
				'extra'                => '',
				'form_title'           => '',
				'heading_form'         => '',
				'underline_check_form' => '',
				'underline_text_form'  => '',
				'form'                 => '',
				'el_class'             => '',
			), $atts
		);

		$css_class = array(
			'dl-contact-box',
			$atts['el_class'],
		);

		$addresses        = vc_param_group_parse_atts( $atts['addresses'] );
		$addresses_output = array();
		if ( ! empty( $addresses ) ) {
			foreach ( $addresses as $name => $value ) {

				$addresses_output[] = sprintf(
					'<li><span>%s</span>%s</li>',
					$value['title'], $value['content']
				);
			}
		}

		$extra        = vc_param_group_parse_atts( $atts['extra'] );
		$extra_output = array();
		if ( ! empty( $extra ) ) {
			foreach ( $extra as $name => $value ) {

				$extra_output[] = sprintf(
					'<li><span>%s: </span>%s</li>',
					$value['title'], $value['content']
				);
			}
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$title_atts = array(
			'heading'         => $atts['heading'],
			'underline_text'  => $atts['underline_text'],
			'title'           => $atts['section_title'],
			'underline_check' => $atts['underline_check'],
			'select_type'     => '4',
		);

		$title = $this->delaware_section_title( $title_atts, $content );

		$des    = $atts['description'];
		$des_hl = $atts['des_highlight'];
		if ( ! empty( $des_hl ) ) {
			$des = str_replace( $des_hl, '<span class="hightlight">' . $des_hl . '</span>', $des );
		}

		$title_form_atts = array(
			'heading'         => $atts['heading_form'],
			'underline_text'  => $atts['underline_text_form'],
			'title'           => $atts['form_title'],
			'underline_check' => $atts['underline_check_form'],
			'select_type'     => '4',
		);

		$title_form = $this->delaware_section_title( $title_form_atts, $content );

		$form_atts = array(
			'form' => $atts['form'],
		);

		$form = $this->delaware_contact_form_7( $form_atts, $content );

		return sprintf(
			'<div class="%s">
				<div class="row">
					<div class="col-md-7 col-sm-12 col-xs-12 contact-box-content">
						<div class="left-box-ct">
							%s
							<div class="sub-title">%s</div><div class="des-contact-box">%s</div>
							<div><ul class="address-ct-box">%s</ul><ul class="extra-ct-box">%s</ul></div>
						</div>
					</div>
					<div class="col-md-5 col-sm-12 col-xs-12 contact-box-form">
						<div class="form-ct-box">%s %s</div>
					</div>
				</div>
 			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$title,
			$atts['sub_title'],
			$des,
			implode( '', $addresses_output ),
			implode( '', $extra_output ),
			$title_form,
			$form
		);
	}

	/**
	 * Icon Box
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */

	function delaware_icon_box( $atts, $content ) {

		$atts = shortcode_atts(
			array(
				'select_type'      => '1',
				'shadow'           => '',
				'hide_title'       => '',
				'link'             => '',
				'text_color'       => '',
				'font_size'        => '',
				'icon_type'        => 'fa_font',
				'svg_name'         => '',
				'icon_fontawesome' => 'fa fa-adjust',
				'image_size'       => '',
				'image'            => '',
				'icon_color'       => '',
				'bg_color'         => '',
				'checkbox'         => '0',
				'border'           => '',
				'icon_size'        => '',
				'el_class'         => '',

			), $atts
		);

		$select_type = $atts['select_type'];
		$hide_title  = $atts['hide_title'];
		$shadow      = $atts['shadow'];
		$link        = vc_build_link( $atts['link'] );
		$text_color  = $atts['text_color'];
		$icon_type   = $atts['icon_type'];
		$value_icon  = $atts['svg_name'];
		$icon_color  = $atts['icon_color'];
		$bg_color    = $atts['bg_color'];
		$checkbox    = $atts['checkbox'];
		$border      = $atts['border'];
		$el_class    = $atts['el_class'];
		$title       = $link['title'];
		$title_link  = $link['url'];
		$image_ids   = explode( ',', $atts['image'] );

		$text_color = $text_color ? "style='color:$text_color;'" : '';
		$bg_color   = $bg_color ? "style='background-color:$bg_color;'" : '';

		$style_ic_color = "";
		$style_ic_size  = "";

		if ( $icon_color ) {
			$style_ic_color = "color:$icon_color;";
		}

		if ( $atts['icon_size'] ) {
			$icon_size     = $this->dl_font_size_handle( $atts['icon_size'] );
			$style_ic_size = "font-size:$icon_size;";
		}

		$style_ic = "style='$style_ic_color $style_ic_size'";

		$class_extra = "";
		if ( ! empty( $shadow ) ) {
			$class_extra = 'no-box-shadow ';
		}
		if ( ! empty( $shadow ) ) {
			$class_extra .= 'hover-hide-title';
		}

		switch ( $icon_type ) {
			case 'fa_font':
				$value = $atts['icon_fontawesome'];
				$icon  = "<span class='$value svg-icon' $style_ic></span>";
				if ( $select_type == 4 ): $icon .= $icon;endif;
				break;
			case 'svg_icon':

				$icon = "<span class='svg-icon' $style_ic><svg viewBox='0 0 20 20'><use xlink:href='#$value_icon'></use></svg></span>";
				if ( $select_type == 4 ): $icon .= $icon;endif;
				break;
			case 'image':
				$image_thumb = delaware_load_image( $atts['image'], $atts['image_size'] );
				$icon        = "<span class='img'>$image_thumb</span>";
				break;
			default:
				$icon = "";
		}


		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}


		$read_more      = esc_html__( 'Read more', 'delaware' );
		$show_read_more = '';
		if ( $checkbox == true ): $show_read_more = "<a href='" . esc_url( $title_link ) . "' class='readmore'>$read_more<span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#next'></use></svg></span></a>";endif;

		$show_border = '';
		if ( $border == true ) : $show_border = 'show-border-bottom'; endif;

		return sprintf(
			"
			<div class='col-xs-12 delaware-icon-box delaware-icon-box-%s %s %s %s ' %s>
				<a href='%s' class='emtry-header'><span class='icon-content'>%s</span></a>
				<div class='emtry-content'>
					<h4 class='emtry-title'><a class='title' href='%s' %s>%s</a></h4>
					<div class='descreption'>%s</div>
					%s
				</div>
			</div>
			
		",
			esc_attr( $select_type ),
			esc_attr( $el_class ),
			esc_attr( $show_border ),
			esc_attr( $class_extra ),
			$bg_color,
			esc_url( $title_link ),
			$icon,
			esc_url( $title_link ),
			$text_color,
			$title,
			$content,
			$show_read_more
		);
	}

	/**
	 * Icon Box
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */

	function delaware_icon_box_2( $atts, $content ) {

		$atts = shortcode_atts(
			array(
				'title'            => '',
				'icon_type'        => 'fa_font',
				'svg_name'         => '',
				'icon_fontawesome' => 'fa fa-adjust',
				'image'            => '',
				'image_size'       => '',
				'el_class'         => '',
			), $atts
		);

		$css_class = array(
			'dl-icon-box-2',
			$atts['el_class']
		);

		$icon       = $title = '';
		$icon_type  = $atts['icon_type'];
		$value_icon = $atts['svg_name'];

		if ( $icon_type == 'fa_font' ) {
			$value = $atts['icon_fontawesome'];
			$icon  = '<span class="' . esc_attr( $value ) . ' svg-icon" ></span>';

		} elseif ( $icon_type == 'svg_icon' ) {
			$icon = '<span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#' . esc_attr( $value_icon ) . '"></use></svg></span>';

		} elseif (  $icon_type == 'image' && $atts['image'] ) {
				$image_thumb = delaware_load_image( $atts['image'], $atts['image_size'] );
				$icon        = "<span class='img'>$image_thumb</span>";
		} else {
			$icon = '';
		}


		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		if ( $atts['title'] ) {
			$title = sprintf( '<h4 class="box-title">%s</h4>', $atts['title'] );
		}

		return sprintf(
			'<div class="%s">
				<div class="box-header">%s%s</div>
				<div class="box-content">%s</div>
			</div>'
			,
			esc_attr( implode( ' ', $css_class ) ),
			$icon,
			$title,
			$content
		);
	}

	/**
	 * Get portfolio attribute
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_portfolio_attribute( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'client' => '',
				'link'   => '',
				'rating' => '',
			), $atts
		);

		$client     = $atts['client'];
		$link       = vc_build_link( $atts['link'] );
		$title      = $link['title'];
		$title_link = $link['url'];
		$rating     = $atts['rating'];

		$terms    = get_the_terms( get_the_ID(), 'portfolio_category' );
		$category = '';


		if ( $terms && ! is_wp_error( $terms ) ) :
			$category_link = get_term_link( $terms[0]->term_id, 'portfolio_category' );
			$category_name = $terms[0]->name;

			$category = "<a class='category' href='$category_link'>$category_name</a>";
		endif;


		$category = ! empty( $category ) ? "<li><p>Category :</p> <span> $category</span></li>" : '';
		$client   = ! empty( $client ) ? "<li><p>Client :</p> <span> $client</span></li>" : '';
		$link     = ! empty( $link ) ? "<li><p>Link :</p> <a href='$title_link'> $title</a></li>" : '';
		$star     = "<i class='fa fa-star'></i>";
		$star_o   = "<i class='fa fa-star-o'></i>";

		$rating = intval( $rating );

		$rating_star   = array();
		$rating_star_0 = array();

		$count = $rating;
		for ( $count; $count >= 1; $count -- ) {
			$rating_star[] = $star;
		}
		for ( $count; $count < 5; $count ++ ) {
			if ( $count == 5 - $rating ): break;endif;
			$rating_star_0[] = $star_o;
		}

		$star_count = implode( '', $rating_star );
		$star_count .= implode( '', $rating_star_0 );
		$date = get_the_date( 'jS M, Y ' );

		return sprintf(
			"<div class='col-xs-12 delaware_portfolio_info'><ul>%s %s<li><p>%s :</p> <span>%s</span></li>%s<li class='rating'><p>%s :</p><span>%s</span></li></ul></div>",
			$category, $client, esc_html__( 'Date', 'delaware' ), $date, $link, esc_html__( 'Rating', 'delaware' ), $star_count
		);
	}

	/**
	 * Get counter
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_counter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'     => '1',
				'color'     => 'dark',
				'align'     => 'left',
				'title'     => '',
				'number'    => '',
				'duaration' => '',
				'tx_after'  => '',
				'units'     => '',
				'el_class'  => '',
			), $atts
		);

		$css_class = array(
			'col-xs-12 delaware-counter',
			'text-' . $atts['color'],
			'text-' . $atts['align'],
			'style-' . $atts['style'],
			$atts['el_class']
		);

		$counter_number = $atts['number'];
		$duaration      = $atts['duaration'];
		$tx_after       = $atts['tx_after'];

		$units = $atts['units'];

		return sprintf(
			"<div class='%s'>
				<div class='col-xs-12 counter-value'>
					<span class='counter-number' data-duaration='%s'>%s</span>
					<span class='unit'>%s</span>
					<span class='after'>%s</span>
				</div>
				<div class='counter-content'>%s</div>
			</div>",
			esc_attr( implode( ' ', $css_class ) ),
			$duaration,
			$counter_number,
			$units,
			$tx_after,
			$atts['title']
		);
	}

	function delaware_addons_btn( $atts ) {
		$css_class = array(
			'dl-button'
		);

		if ( isset( $atts['el_class'] ) ) {
			$css_class[] = $atts['el_class'];
		}

		$style_css = array();
		if ( isset( $atts['btn_color'] ) && ! empty( $atts['btn_color'] ) ) {
			$style_css[] = sprintf( 'color:%s', $atts['btn_color'] );
		}

		if ( isset( $atts['bg_color'] ) && ! empty( $atts['bg_color'] ) ) {
			$style_css[] = sprintf( ';background-color:%s', $atts['bg_color'] );
		}

		if ( ! empty( $style_css ) ) {
			$style = sprintf( 'style ="%s"', implode( ' ', $style_css ) );
		} else {
			$style = '';
		}

		$attributes = array();

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		$label = $link['title'];

		if ( ! $label ) {
			$attributes['title'] = $label;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		if ( isset( $atts['show_icon'] ) && $atts['show_icon'] ) {
			$show_icon = '<span class="dl-icon-next svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#next"></use></svg></span>';
		} else {
			$show_icon = '';
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$button = sprintf(
			'<%1$s %2$s %3$s>%4$s %5$s</%1$s>',
			empty( $attributes['href'] ) ? 'span' : 'a',
			implode( ' ', $attr ),
			$style,
			$label,
			$show_icon
		);

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$button
		);
	}

	/**
	 * Get video banner
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_video_banner( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'video'        => '',
				'min_height'   => '500',
				'image'        => '',
				'image_size'   => '',
				'number_phone' => '',
				'btn_text'     => esc_html__( 'Watch Project Video', 'delaware' ),
				'el_class'     => '',

			), $atts
		);

		if ( empty( $atts['video'] ) ) {
			return '';
		}

		$css_class = array(
			'mf-video-banner',
			$atts['el_class'],
		);

		$min_height   = intval( $atts['min_height'] );
		$video_html   = $src = $btn = '';
		$style        = array();
		$video_url    = $atts['video'];
		$number_phone = $atts['number_phone'];
		$video_w      = '1024';
		$video_h      = '768';

		if ( $min_height ) {
			$style[] = 'min-height:' . $min_height . 'px;';
		}

		if ( $atts['image'] ) {
			$image = wp_get_attachment_image_src( $atts['image'], 'full' );
			if ( $image ) {
				$src = $image[0];
			}
			$style[] = 'background-image:url(' . $src . ');';
		}

		if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
			$atts = array(
				'width'  => $video_w,
				'height' => $video_h
			);
			if ( $oembed = @wp_oembed_get( $video_url, $atts ) ) {
				$video_html = $oembed;
			}
			if ( $video_html ) {
				$video_html = sprintf( '<div class="mf-wrapper"><div class="mf-video-wrapper">%s</div></div>', $video_html );
			}
		}

		return sprintf(
			'<div class="delaware-video-banner %s" style="%s">
				<div class="mf-video-content"><a href="#" data-href="%s" class="photoswipe"><span class="video-play"></span></a></div>
				<div class="content-video"><a class="btn btn-default telephone">
				<span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#smartphone"></use></svg></span>%s</a></div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( implode( ' ', $style ) ),
			esc_attr( $video_html ),
			$number_phone
		);
	}


	/**
	 * Get about
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function delaware_about( $atts, $content ) {
		$atts            = shortcode_atts(
			array(
				'style'            => '1',
				'title'            => '',
				'underline_check'  => '',
				'underline_text'   => '',
				'heading'          => 'h1',
				'link'             => '',
				'el_class'         => '',
				'text_color'       => '',
				'descr_button'     => '',
				'text_size'        => '',
				'image'            => '',
				'image_size'       => '',
				'icon_type'        => 'fa_font',
				'svg_icon'         => '',
				'icon_fontawesome' => 'fa fa-adjust',
				'image_size_icon'  => '',
				'image_icon'       => '',
				'icon_color'       => '',
				'style_button'     => '1',

			), $atts
		);
		$heading         = $atts['heading'];
		$underline_text  = $atts['underline_text'];
		$title           = $atts['title'];
		$style           = $atts['style'];
		$text_color      = $atts['text_color'];
		$underline_check = $atts['underline_check'];
		$icon_type       = $atts['icon_type'];
		$svg_icon        = $atts['svg_icon'];
		$icon_color      = $atts['icon_color'];
		$style_button    = $atts['style_button'];

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$link = vc_build_link( $atts['link'] );

		$icon_color = ! empty( $icon_color ) ? "style='color:$icon_color;'" : '';

		$style_text = "style='";
		if ( ! empty( $text_color ) ):$style_text .= "color:$text_color;";endif;

		if ( $atts['text_size'] ) {
			$text_size = $this->dl_font_size_handle( $atts['text_size'] );
			$style_text .= "font-size:$text_size;";
		}

		$style_text .= "'";

		$class_check = 'no-underline';
		if ( $underline_check == 2 ) {
			$class_check = 'underline';
		}

		if ( ! empty( $underline_text ) ) {
			$title = str_replace( $underline_text, '<span class="underline">' . $underline_text . '</span>', $title );
		}

		$value_button = $link['title'];
		$link         = $link['url'];

		$layout_bt = '<div class="no-padding delaware-button"><a class="btn bt-' . $style_button . '" href="' . $link . '">' . $value_button . '</a></div>';

		$layout_descr = '<div class="no-padding col-xs-12 col-sm-12 desc">' . $content . '</div>';
		$img          = '';


		if ( $atts['image'] ) {
			$image_thumb = delaware_load_image( $atts['image'], $atts['image_size'] );
		} else {
			$image_thumb = '';
		}
		$img .= "<div class='about-image'>";
		$img .= $image_thumb;
		$img .= "</div>";

		switch ( $icon_type ) {
			case 'fa_font':
				$value = $atts['icon_fontawesome'];
				$icon  = "<span class='" . esc_attr( $value ) . " svg-icon'></span>";
				break;
			case 'svg_icon':
				$icon = "<span class='svg-icon' $icon_color><svg viewBox='0 0 20 20'><use xlink:href='#$svg_icon'></use></svg></span>";
				break;
			case 'image':
				$image_thumb = delaware_load_image( $atts['image_icon'], $atts['image_size_icon'] );
				$icon        = "<span class='img'>$image_thumb</span>";
				break;
			default:
				$icon = "";
		}
		if ( $style == 2 ):
			$layout_bt = "<div class='no-padding delaware-button'>
				<a class='btn' href='" . esc_url( $link ) . "'>$icon<p>$value_button</p></a>
			</div>";
		endif;

		if ( $style == 1 ) {
			$out_put = sprintf(
				"<%s class='no-padding col-xs-12 col-sm-12 title %s' %s>%s</%s>%s %s %s",
				$heading, $class_check, $style_text, $title, $heading, $layout_descr, $layout_bt, $img
			);
		} else {
			$out_put = sprintf(
				"<div class='col-left'>%s %s </div><div class='col-right'>%s</div>",
				$img, $layout_bt, $layout_descr
			);
		}


		return sprintf(
			"<div class='col-xs-12 col-sm-12 no-padding delaware-section-title delaware-about delaware-about-type-%s' >%s</div>",
			$style, $out_put
		);
	}

	function delaware_addons_get_socials() {
		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'delaware' ),
			'twitter'    => esc_html__( 'Twitter', 'delaware' ),
			'google'     => esc_html__( 'Google', 'delaware' ),
			'tumblr'     => esc_html__( 'Tumblr', 'delaware' ),
			'flickr'     => esc_html__( 'Flickr', 'delaware' ),
			'vimeo'      => esc_html__( 'Vimeo', 'delaware' ),
			'youtube'    => esc_html__( 'Youtube', 'delaware' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'delaware' ),
			'pinterest'  => esc_html__( 'Pinterest', 'delaware' ),
			'dribbble'   => esc_html__( 'Dribbble', 'delaware' ),
			'spotify'    => esc_html__( 'Spotify', 'delaware' ),
			'instagram'  => esc_html__( 'Instagram', 'delaware' ),
			'tumbleupon' => esc_html__( 'Tumbleupon', 'delaware' ),
			'wordpress'  => esc_html__( 'WordPress', 'delaware' ),
			'rss'        => esc_html__( 'Rss', 'delaware' ),
			'deviantart' => esc_html__( 'Deviantart', 'delaware' ),
			'share'      => esc_html__( 'Share', 'delaware' ),
			'skype'      => esc_html__( 'Skype', 'delaware' ),
			'behance'    => esc_html__( 'Behance', 'delaware' ),
			'apple'      => esc_html__( 'Apple', 'delaware' ),
			'yelp'       => esc_html__( 'Yelp', 'delaware' ),
		);

		return apply_filters( 'delaware_addons_get_socials', $socials );
	}

	/**
	 * Get progress bar
	 *
	 * @since  1.0
	 *
	 * @return string
	 */

	function delaware_progress_bar( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'                     => '',
				'number_color'               => '',
				'progress_bar_color'         => '',
				'progress_bar_bg_color'      => '',
				'progress_bar_stripes_color' => '',
			), $atts
		);

		$number                = $atts['number'];
		$number                = intval( $number );
		$number_color          = $atts['number_color'];
		$progress_bar_color    = $atts['progress_bar_color'];
		$progress_bar_bg_color = $atts['progress_bar_bg_color'];
		$stripes               = $atts['progress_bar_stripes_color'];

		$number_color          = ! empty( $icon_color ) ? "color:$number_color;" : '';
		$progress_bar_bg_color = ! empty( $progress_bar_bg_color ) ? "style='background:$progress_bar_bg_color'" : '';

		if ( $progress_bar_color ) {
			$stripes            = ! empty( $stripes ) ? $stripes : '#fff';
			$progress_bar_color = "background-image: linear-gradient(135deg, $progress_bar_color 25%, $stripes 5%,
			$stripes 30%, $progress_bar_color 50%, $progress_bar_color 75%, $stripes 45%, $stripes);";
		}
		$style = "style='width:$number%;$progress_bar_color'";

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}


		return sprintf(
			"<div class='col-xs-12 delaware-progressbar'><div class='progress-number' %s>%s</div>
		<div class='progress-bar' %s><span class='line-progress' %s></span></div><div class='content'>%s</div></div>",
			$number_color, $number . '%', $progress_bar_bg_color, $style, $content
		);
	}


	/**
	 * Get button
	 *
	 * @since  1.0
	 *
	 * @return string
	 */

	function delaware_button( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'link'                => '',
				'button_bg_color'     => '',
				'button_text_color'   => '',
				'button_align'        => 'left',
				'button_border'       => 'none',
				'weight'              => '',
				'button_border_color' => '',
			), $atts
		);

		$link                = vc_build_link( $atts['link'] );
		$value               = $link['title'];
		$bt_link             = $link['url'];
		$button_bg_color     = $atts['button_bg_color'];
		$button_text_color   = $atts['button_text_color'];
		$button_align        = $atts['button_align'];
		$button_border       = $atts['button_border'];
		$weight              = $atts['weight'];
		$button_border_color = $atts['button_border_color'];

		$style = '';
		$style .= "style='";
		if ( $button_bg_color ) : $style .= "background:$button_bg_color;"; endif;
		if ( $button_text_color ) : $style .= "color:$button_text_color;"; endif;
		if ( $button_border != 'none' ) :
			$style .= "border: 2px solid $button_border_color;";
		endif;
		if ( $weight ) :
			$style .= "font-weight: $weight;";
		endif;
		$style .= "'";

		return sprintf(
			"<div class='delaware-button text-%s'><a class='btn' href='%s' %s>%s</a></div>",
			$button_align, $bt_link, $style, $value
		);

	}

	function delaware_testimonials_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'nav'            => '',
				'dots'           => '',
				'autoplay'       => false,
				'autoplay_speed' => '2000',
				'column'         => '1',
				'text_color'     => 'dark',
				'setting'        => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'dl_testi',
			'text-' . $atts['text_color'],
			$atts['el_class']
		);

		$fix_dot = 'dot-' . $atts['column'];
		$fix_columns = 'fix-' . $atts['column'];
		$style = 'testi-carousel__style-' . $atts['column'];

		if ( $atts['nav'] ) {
			$nav = true;
		} else {
			$nav = false;
		}

		if ( $atts['dots'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		$id    = uniqid( 'delaware-box-slider-' );
		$slide = intval( $atts['column'] );
		$speed = intval( $atts['autoplay_speed'] );

		$this->l10n['testimonialsCarousel'][$id] = array(
			'slide'    => $slide,
			'nav'      => $nav,
			'dot'      => $dot,
			'autoplay' => $autoplay,
			'speed'    => $speed,

		);

		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();

		$css_bk = $style_img_b = '';

		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {
				$style_img = $container = '';
				if ( isset( $value['image_gr'] ) ) {
					$image = wp_get_attachment_image_src( $value['image_gr'], 'full' );
					if ( $image ) {
						$src = $image[0];
					}
					$style_img = 'background-image:url(' . $src . ');background-size: cover; background-position:center;';
				} else {
					$css_bk = 'no-background-image';
				}

				if ( isset( $value['image_box'] ) ) {
					$image_b = wp_get_attachment_image_src( $value['image_box'], 'full' );
					if ( $image_b ) {
						$src_b = $image_b[0];
					}
					$style_img_b = 'background-image:url(' . $src_b . ');background-size: cover; background-position:center;';
				} else {
					$css_bk_b = 'no-background-image';
				}

				if ( $atts['column'] == 1 ) {
					$container = 'container';
				} else {
					$container = 'no-container';
				}
				if ( isset ( $value['title_gr'] ) ) {
					$titles = sprintf( '<h2>%s</h2><div class="border"></div>', $value['title_gr'] );
				}
				if ( isset ( $value['title_gr_sub'] ) ) {
					$titles_sub = sprintf( '<p class="sub-title">%s</p>', $value['title_gr_sub'] );
				}
				if ( isset ( $value['content_gr_testi'] ) ) {
					$content_box = sprintf( '<p>%s</p>', $value['content_gr_testi'] );
				}

				$btn = $this->delaware_addons_btn( $value );

				$check_link = vc_build_link( $value['link'] );
				$dl_btn     = '';
				if ( isset( $value['link'] ) && ! empty( $value['link'] ) && ! empty( $check_link['url'] ) ) {
					$dl_btn = sprintf(
						'<div class="btn-testi">' .
						'%s' .
						'</div>',
						$btn
					);
				}

				if ( $atts['column'] == "1" ) {
					$fix_main_box = sprintf(
						'<div class="dl-icon-quote svg-icon">
						<svg viewBox="0 0 20 20">
							<use xlink:href="#quote"></use>
						</svg>
						</div>' .
						'<div class ="box-title">' .
						'%s' .
						'%s' .
						'</div>' .
						'%s',
						$titles,
						$titles_sub,
						$content_box
					);

					$outputs[] = sprintf(
						'<div class=" %s">' .
						'<div class="box-img" style="%s">' .
						'<div class="%s %s">' .
						'<div class="%s">' .
						'<div class="box-testi" style="%s" >' .
						'%s' .
						'%s' .
						'</div>' .
						'</div>' .
						'</div>' .
						'</div>' .
						'</div>',
						$style,
						$style_img,
						$css_bk,
						$container,
						esc_attr( implode( ' ', $css_class ) ),
						$style_img_b,
						$fix_main_box,
						$dl_btn
					);
				} else {
					$avatar = isset( $value['image_gr'] ) && ! empty($value['image_gr']) ? sprintf('<div class="testi-avatar"><img src="%s"></div>',$src) : '';
					$fix_main_box = sprintf(
						'<div class ="box-title">' .
						'%s' .
						'%s' .
						'<span class="dl-icon-quote svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#quote"></use></svg></span>' .
						'</div>' .
						'%s',
						$titles,
						$titles_sub,
						$content_box
					);

					$outputs[] = sprintf(
						'<div class="%s columns-2">' .
						'<div class="%s">' .
						'%s' .
						'<div class="box-testi" style="%s" >' .
						'%s' .
						'%s' .
						'</div>' .
						'</div>' .
						'</div>',
						esc_attr( implode( ' ', $css_class ) ),
						$style,
						$avatar,
						$style_img_b,
						$fix_main_box,
						$dl_btn
					);
				}

			}
		}

		return sprintf(
			'<div id="%s" class="dl_testi_carousel stick-testi %s %s">%s</div>',
			$id,
			$fix_dot,
			$fix_columns,
			implode( '', $outputs )
		);
	}

	function delaware_testimonials_carousel_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'nav'            => '',
				'dots'           => '',
				'autoplay'       => false,
				'autoplay_speed' => '2000',
				'title'          => '',
				'underline_text' => '',
				'title_sub_1'    => '',
				'title_sub_2'    => '',
				'setting'        => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'dl_testi_carousel_2',
			$atts['el_class']
		);

		if ( $atts['nav'] ) {
			$nav = true;
		} else {
			$nav = false;
		}

		if ( $atts['dots'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		$id    = uniqid( 'delaware-box-single-' );
		$speed = intval( $atts['autoplay_speed'] );

		$this->l10n['testimonialsCarousel2'][$id] = array(
			'autoplay' => $autoplay,
			'nav'      => $nav,
			'dot'      => $dot,
			'speed'    => $speed,

		);

		$title          = '<h2 class="testi-section-title">' . $atts['title'] . '</h2>';
		$underline_text = $atts['underline_text'];

		if ( ! empty( $underline_text ) ) {
			$title = str_replace( $underline_text, '<span class="underline">' . $underline_text . '</span>', $title );
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		if ( $content ) {
			$content = sprintf( '<div class="desc">%s</div>', $content );
		}

		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();

		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {
				$css = 'text-' . $value['text_color'];

				if ( isset ( $value['title_gr'] ) ) {
					$titles = sprintf( '<h2>%s</h2><div class="border"></div>', $value['title_gr'] );
				}

				if ( isset ( $value['title_gr_sub'] ) ) {
					$titles_sub = sprintf( '<p class="sub-title">%s</p>', $value['title_gr_sub'] );
				}

				if ( isset ( $value['content_gr_testi'] ) ) {
					$content_box = sprintf( '<p>%s</p>', $value['content_gr_testi'] );
				}

				$btn = $this->delaware_addons_btn( $value );

				$dl_btn = '';
				if ( isset( $value['link'] ) && $value['link'] ) {
					$dl_btn = sprintf(
						'<div class="btn-testi">' .
						'%s' .
						'</div>',
						$btn
					);
				}

				$main_box = sprintf(
					'<div class ="box-title">' .
					'%s' .
					'%s' .
					'<span class="dl-icon-quote svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#quote"></use></svg></span>' .
					'</div>' .
					'%s',
					$titles,
					$titles_sub,
					$content_box
				);

				$outputs[] = sprintf(
					'<div class="dl_testi %s">' .
					'<div class="box-testi" >' .
					'%s' .
					'%s' .
					'</div>' .
					'</div>',
					esc_attr( $css ),
					$main_box,
					$dl_btn
				);
			}
		}

		return sprintf(
			'<div class="%s">' .
			'<div class="row">' .
			'<div class="col-md-6 col-sm-6 col-xs-6">' .
			'%s %s' .
			'<div class="testi-arrows hidden-md hidden-sm hidden-xs">
				<span class="testi-2-prev"><span class="dl-icon-prev svg-icon"><i class="fa fa-angle-left"></i></span></span>
				<span class="testi-2-next"><span class="dl-icon-next svg-icon"><i class="fa fa-angle-right"></i></span></span>
			</div>' .
			'</div>' .
			'<div class="col-md-6 col-sm-6 col-xs-6">' .
			'<div id="%s" class="stick-testi">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$title,
			$content,
			$id,
			implode( '', $outputs )
		);
	}

	function delaware_testimonials( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'         => '',
				'title_sub'     => '',
				'content_testi' => '',
				'link'          => '1',
				'image'         => '',
				'toggle_bk'     => '',
				'text_color'    => 'dark',
				'btn_color'     => '',
				'el_class'      => ''
			), $atts
		);

		$bk = '';

		if ( $atts['toggle_bk'] == 'no_bk' ) {
			$bk = "hidden-bk";
		}

		$css_class = array(
			'dl_testi',
			'text-' . $atts['text_color'],
			$bk,
			$atts['el_class']

		);

		$title = array();
		if ( $atts['title'] ) {
			$title[] = sprintf( '<h2>%s</h2><div class="border"></div>', $atts['title'] );
		}
		if ( $atts['title_sub'] ) {
			$title[] = sprintf( '<p class="sub-title">%s</p>', $atts['title_sub'] );
		}
		if ( $atts['content_testi'] ) {
			$content = sprintf( '<p class="content">%s</p>', $atts['content_testi'] );

		};

		$atts['show_icon'] = true;

		$output   = array();
		$output[] = $this->delaware_addons_btn( $atts );

		return sprintf(
			'<div class="%s">' .
			'<div class="box-testi">' .
			'<div class ="box-title">' .
			'%s' .
			'<span class="dl-icon-quote svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#quote"></use></svg></span>' .
			'</div>' .
			'%s' .
			'<div class="btn-testi">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $title ),
			$content,
			implode( ' ', $output )
		);

	}

	function delaware_link( $atts, $content ) {

		$atts = shortcode_atts(
			array(
				'link'             => '',
				'color'            => '',
				'size'             => '',
				'link_align'       => 'left',
				'icon_type'        => 'fa_font',
				'value_icon'       => '',
				'icon_fontawesome' => 'fa fa-adjust',
			), $atts
		);

		$link       = vc_build_link( $atts['link'] );
		$color      = $atts['color'];
		$icon_type  = $atts['icon_type'];
		$size       = $this->dl_font_size_handle( $atts['size'] );
		$align      = $atts['link_align'];
		$value_icon = $atts['value_icon'];
		$title      = $link['title'];
		$title_link = $link['url'];

		$style_link = '';
		$style_link .= "style='";

		$style_link .= $color ? "color:$color;" : '';

		if ( $atts['size'] ) {
			$size = $this->dl_font_size_handle( $atts['size'] );
			$style_link .= "font-size:$size;";
		}

		$style_link .= "text-align:$align;";

		$style_link .= "'";

		switch ( $icon_type ) {
			case 'fa_font':
				$value = $atts['icon_fontawesome'];
				$icon  = "<i class='$value' aria-hidden='true'></i>";
				break;
			case 'svg_icon':
				$icon = "<span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#$value_icon'></use></svg></span>";
				break;
			default:
				$icon = "";
		}

		return sprintf(
			"
			<div class='delaware-link'>
				<a href='%s' %s>%s %s</a>
			</div>
			
		", $title_link, $style_link, $title, $icon
		);
	}

	/**
	 * Shortcode to display services
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function delaware_services( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'number'   => '6',
				'columns'  => '3',
				'orderby'  => 'date',
				'order'    => 'desc',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'dl-services',
			$atts['el_class'],
		);

		global $columns_service_vc;
		$columns_service_vc = $atts['columns'];

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'service',
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();
			ob_start();
			get_template_part( 'parts/content', 'service' );
			$output[] = ob_get_clean();

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="%s">
                <div class="services-list row">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	function delaware_portfolio_meta( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'dl-portfolio-meta',
			$atts['el_class'],
		);

		$terms    = get_the_terms( get_the_ID(), 'portfolio_category' );
		$category = '';


		if ( $terms && ! is_wp_error( $terms ) ) :
			$category_link = get_term_link( $terms[0]->term_id, 'portfolio_category' );
			$category_name = $terms[0]->name;

			$category = "<a class='category' href='" . esc_url( $category_link ) . "'>" . esc_html( $category_name ) . "</a>";
		endif;


		$category = ! empty( $category ) ? "<li><p>Category :</p> <span> $category</span></li>" : '';
		$client   = get_post_meta( get_the_ID(), 'client', true );
		$website  = get_post_meta( get_the_ID(), 'website', true );
		$rating   = get_post_meta( get_the_ID(), 'rating', true );

		$client  = ! empty( $client ) ? "<li><p>" . esc_html__( 'Client :', 'delaware' ) . "</p> <span> $client</span></li>" : '';
		$website = ! empty( $website ) ? "<li><p>" . esc_html__( 'Website :', 'delaware' ) . "</p> <span> <a href='" . esc_url( $website ) . "'>$website</a> </span></li>" : '';

		$rating = intval( $rating );

		$score     = min( 10, abs( $rating ) );
		$full_star = $score / 2;
		$half_star = $score % 2;
		$stars     = array();

		for ( $i = 1; $i <= 5; $i ++ ) {
			if ( $i <= $full_star ) {
				$stars[] = '<i class="fa fa-star"></i>';
			} elseif ( $half_star ) {
				$stars[]   = '<i class="fa fa-star-half-o"></i>';
				$half_star = false;
			} else {
				$stars[] = '<i class="fa fa-star-o"></i>';
			}
		}

		$date = get_the_date();


		return sprintf(
			'<div class="%s">
                <div class="portfolio-meta">
                	<ul>
                		%s
                		%s
                		<li><p>%s</p> <span>%s</span></li>
                		%s
                		<li class="rating"><p>%s</p><span>%s</span></li>
                	</ul>
                </div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$category,
			$client,
			esc_html__( 'Date :', 'delaware' ),
			$date,
			$website,
			esc_html__( 'Rating :', 'delaware' ),
			join( "\n", $stars )
		);
	}

	/**
	 * @param string $atts
	 *
	 * @return string
	 */
	protected function dl_font_size_handle( $atts ) {
		$atts = preg_replace( '/\s+/', '', $atts );

		$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
		// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
		$regexr   = preg_match( $pattern, $atts, $matches );
		$value    = isset( $matches[1] ) ? (float) $matches[1] : (float) $atts;
		$unit     = isset( $matches[2] ) ? $matches[2] : 'px';
		$fontSize = $value . $unit;

		return $fontSize;
	}

	/**
	 * Icon Box Carousel
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */

	function delaware_icon_box_carousel( $atts, $content ) {

		$atts = shortcode_atts(
			array(
				'dots'           => '',
				'autoplay'       => '',
				'autoplay_speed' => '',
				'setting'        => '',
				'el_class'         => '',
			), $atts
		);

		// add class
		$css_class = array(
			'delaware-icon-box-carousel',
			$atts['el_class']
		);

		if ( $atts['dots'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		$id     = uniqid( 'dalaware-icon-box-carousel-' );
		$speed  = intval( $atts['autoplay_speed'] );

		$this->l10n['iconboxCarousel'][ $id ] = array(
			'dot'      => $dot,
			'autoplay' => $autoplay,
			'speed'    => $speed,
		);

		// param content
		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();

		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {

				$icon_type = isset($value['icon_type']) && ! empty($value['icon_type'] ) ? $value['icon_type'] : '';

				switch ( $icon_type ) {
					case 'fa_font':
						$values =  $value['icon_fontawesome'];
						$icon  = isset($values) && ! empty($values) ? "<span class='$values svg-icon'></span>" : '';
						break;
					case 'svg_icon':
						$value_icon  = $value['svg_name'];
						$icon = isset($value_icon) && ! empty($value_icon) ? sprintf("<span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#%s'></use></svg></span>",$value_icon) : '';
						break;
					default:
						$icon = "";
				}

				// set link
				$link = isset( $value['link'] ) && ! empty( $value['link'] ) ? vc_build_link( $value['link'] ) : '';

				// set text
				$href = isset( $link['url'] ) && ! empty( $link['url'] ) ? $link['url'] : '#';
				$title =  isset($value['title']) && ! empty($value['title']) ? sprintf('<h3><a href="%s">%s</a></h3>',$href ,$value['title']) : '';
				$desc  =  isset($value['desc']) && ! empty($value['desc'])  ? sprintf('<div class="description">%s</div>', $value['desc']) : '' ;
				$box_text = sprintf('<div class="entry-text">%s %s</div>', $title, $desc);

				// set btn
				$text_btn = sprintf("<a href='%s' class='readmore'>%s<span class='svg-icon'><svg viewBox='0 0 20 20'><use xlink:href='#next'></use></svg></span></a>",$href, esc_html__( 'Read More', 'delaware' ));
				$btn = isset( $link['url'] ) && ! empty( $link['url'] ) ? $text_btn : '';

				$outputs[] = sprintf(
					'<div class="box-icon">' .
					'%s' .
					'%s' .
					'%s' .
					'</div>',
					$icon,
					$box_text,
					$btn
				);

			}
		}

		return sprintf(
			'<div class="%s">' .
			'<div class="row">' .
			'<div id="%s">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>',
			implode( '', $css_class ),
			$id,
			implode( '', $outputs )
		);
	}
}


