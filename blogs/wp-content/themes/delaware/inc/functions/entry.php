<?php
/**
 * Custom functions for entry.
 *
 * @package Delaware
 */

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0.0
 */
function delaware_posted_on( $echo = true ) {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$posted_on = sprintf(
		_x( '%s', 'post date', 'delaware' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$output = '<span class="meta posted-on">' . $posted_on . '</span>';

	if ( $echo != true ) {
		return $output;
	} else {
		echo '' . $output;
	}
}

/**
 * Prints HTML with meta information for the current post category
 *
 * @since 1.0.0
 */

function delaware_post_category() {
	$category = get_the_terms( get_the_ID(), 'category' );

	$cat_html = '';

	if ( ! is_wp_error( $category ) && $category ) {
		$cat_html = sprintf( '<a href="%s" class="cat-links">%s</a>', esc_url( get_term_link( $category[0], 'category' ) ), esc_html( $category[0]->name ) );
	}

	if ( $cat_html ) {
		echo '<span class="meta cat">' . $cat_html . '</span>';
	}
}


/**
 * Prints HTML with meta information for the social share and tags.
 *
 * @since 1.0.0
 */
function delaware_entry_footer() {
	if ( ! has_tag() && ! have_comments() ) {
		return;
	}

	echo '<footer class="entry-footer">';
	echo '<div class="count-cmt">' . esc_html( get_comments_number() ) . '</div>';
	if ( has_tag() ) :
		the_tags( '<div class="tag-list"><h4>' . esc_html__( 'Tags: ', 'delaware' ) . '</h4>', ' , ', '</div>' );
	endif;

	echo '</footer>';
}

/**
 * Get blog meta
 *
 * @since  1.0
 *
 * @return string
 */
if ( ! function_exists( 'delaware_get_post_meta' ) ) :
	function delaware_get_post_meta( $meta ) {

		$post_id = 0;
		if ( is_home() && ! is_front_page() ) {
			$post_id = get_queried_object_id();
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			$post_id = intval( get_option( 'woocommerce_shop_page_id' ) );
		} elseif ( is_post_type_archive( 'portfolio' ) ) {
			$post_id = intval( get_option( 'delaware_portfolio_page_id' ) );
		} elseif ( is_post_type_archive( 'service' ) ) {
			$post_id = intval( get_option( 'delaware_service_page_id' ) );
		} elseif ( is_singular() ) {
			$post_id = get_the_ID();
		}

		return $post_id ? get_post_meta( $post_id, $meta, true ) : false;

	}
endif;


/**
 * Show entry thumbnail base on its format
 *
 * @since  1.0
 */
function delaware_entry_thumbnail( $size = 'thumbnail' ) {
	$html = '';

	$css_post = '';

	if ( $post_format = get_post_format() ) {
		$css_post = 'format-' . $post_format;
	}

	switch ( get_post_format() ) {
		case 'gallery':
			$images   = get_post_meta( get_the_ID(), 'images' );
			$css_post .= ' owl-carousel';

			$gallery = array();
			if ( empty( $images ) ) {
				$thumb = get_the_post_thumbnail( get_the_ID(), $size );

				$html .= '<div class="single-image">' . $thumb . '</div>';
			} else {
				foreach ( $images as $image ) {
					$thumb = wp_get_attachment_image( $image, $size );
					if ( $thumb ) {
						$gallery[] = sprintf( '<div class="item-gallery">%s</div>', $thumb );
					}
				}

				$html .= implode( '', $gallery );
			}

			break;

		case 'video':
			$video = get_post_meta( get_the_ID(), 'video', true );
			if ( is_singular( 'post' ) ) {
				if ( ! $video ) {
					break;
				}

				// If URL: show oEmbed HTML
				if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
					if ( $oembed = @wp_oembed_get( $video, array( 'width' => 1170 ) ) ) {
						$html .= $oembed;
					} else {
						$atts = array(
							'src'   => $video,
							'width' => 1170,
						);

						if ( has_post_thumbnail() ) {
							$atts['poster'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
						}
						$html .= wp_video_shortcode( $atts );
					}
				} // If embed code: just display
				else {
					$html .= $video;
				}
			} else {
				$image_src = get_the_post_thumbnail( get_the_ID(), $size );
				if ( $video ) {
					$html = sprintf( '<a href="%s">%s</a>', esc_url( $video ), $image_src );
				} else {
					$html = $image_src;
				}
			}

			break;

		default:
			$html = get_the_post_thumbnail( get_the_ID(), $size );
			if ( ! is_singular( 'post' ) ) {
				$html = sprintf( '<a href="%s">%s</a>', esc_url( get_the_permalink() ), $html );
			}

			break;
	}

	if ( $html ) {
		$html = sprintf( '<div  class="entry-format %s">%s</div>', esc_attr( $css_post ), $html );
	}

	echo apply_filters( __FUNCTION__, $html, get_post_format() );
}

/**
 * Get author meta
 *
 * @since  1.0
 *
 */
function delaware_author_box() {
	if ( delaware_get_option( 'show_author_box' ) == 0 ) {
		return;
	}

	if ( ! get_the_author_meta( 'description' ) ) {
		return;
	}

	?>
    <div class="post-author">
        <h2 class="box-title"><?php esc_html_e( 'About Author', 'delaware' ) ?></h2>

        <div class="post-author-box clearfix">
            <div class="post-author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 85 ); ?>
            </div>
            <div class="post-author-info">
                <h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>

                <div class="author-desc"><?php the_author_meta( 'description' ); ?></div>
				<?php

				$socials = array(
					'facebook' => esc_html__( 'facebook', 'delaware' ),
					'twitter'  => esc_html__( 'twitter', 'delaware' ),
					'google'   => esc_html__( 'google-plus', 'delaware' ),
					'youtube'  => esc_html__( 'youtube', 'delaware' ),
				);

				$links = array();
				foreach ( $socials as $social => $name ) {
					$link = get_the_author_meta( $social, get_the_author_meta( 'ID' ) );
					if ( empty( $link ) ) {
						continue;
					}
					$links[] = sprintf(
						'<li><a href="%s" target="_blank"><i class="fa fa-%s"></i></a></li>',
						esc_url( $link ),
						esc_html( $social )
					);
				}

				if ( ! empty( $links ) ) {
					echo sprintf( '<div class="post-author-social"><ul class="socials-link">%s</ul></div>', implode( '', $links ) );
				}
				?>

            </div>
        </div>
    </div>
	<?php
}

/**
 * Get single entry meta
 *
 * @since  1.0
 *
 */
function delaware_entry_meta( $single_post = false ) {
	$fields = (array) delaware_get_option( 'blog_entry_meta' );

	if ( $single_post ) {
		$fields = (array) delaware_get_option( 'post_entry_meta' );
	}

	if ( empty ( $fields ) ) {
		return;
	}

	foreach ( $fields as $field ) {
		switch ( $field ) {

			case 'author':
				echo '<span class="meta author vcard"><span class="svg-icon" style=""><svg viewBox="0 0 20 20"><use xlink:href="#user2"></use></svg></span><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
				     . '">' . esc_html__( 'By', 'delaware' ) . ' ' . esc_html( get_the_author() ) . '</a></span>';
				break;

			case 'date':
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
				);

				$archive_year  = get_the_time( 'Y' );
				$archive_month = get_the_time( 'm' );
				$archive_day   = get_the_time( 'd' );

				echo sprintf(
					'<span class="meta date"><span class="svg-icon" style=""><svg viewBox="0 0 20 20"><use xlink:href="#calendar"></use></svg></span>' .
					'<a href="%s">%s</a>
					</span>',
					esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
					$time_string
				);

				break;


			case 'cat':
				$category = get_the_terms( get_the_ID(), 'category' );

				$cat_html = '';

				if ( ! is_wp_error( $category ) && $category ) {
					$cat_html = sprintf( '<a href="%s" class="cat-links">%s</a>', esc_url( get_term_link( $category[0], 'category' ) ), esc_html( $category[0]->name ) );
				}

				if ( $cat_html ) {
					echo '<span class="meta cat"><span class="svg-icon" style=""><svg viewBox="0 0 20 20"><use xlink:href="#tags"></use></svg></span>' . $cat_html . '</span>';
				}

				break;

			case 'comment':
				$number_comment = get_comments_number();
				$text           = $number_comment == 1 ? esc_html__( 'Comment', 'delaware' ) : esc_html__( 'Comments', 'delaware' );

				echo '<span class="meta number-comment"><span class="svg-icon" style=""><svg viewBox="0 0 20 20"><use xlink:href="#comment"></use></svg></span>' . $number_comment . ' ' . $text . '</span>';
				break;
		}
	}
}

if ( ! function_exists( 'delaware_get_page_header_image' ) ) :

	/**
	 * Get page header image URL
	 *
	 * @return string
	 */
	function delaware_get_page_header_image() {
		if ( delaware_get_post_meta( 'hide_page_header' ) ) {
			return false;
		}

		if ( is_404() || is_page_template( 'template-coming-soon.php' ) ) {
			return false;
		}

		if ( is_page_template( array( 'template-homepage.php' ) ) ) {
			return false;
		}
		$elements = array( 'title', 'breadcrumb' );

		$page_header = array(
			'bg_image'   => '',
			'text_color' => 'dark',
			'parallax'   => false,
			'elements'   => $elements,
		);


		if ( delaware_is_portfolio() ) {
			if ( ! intval( delaware_get_option( 'portfolio_page_header' ) ) ) {
				return false;
			}

			$elements                  = delaware_get_option( 'portfolio_page_header_els' );
			$page_header['bg_image']   = delaware_get_option( 'portfolio_page_header_bg' );
			$page_header['text_color'] = delaware_get_option( 'portfolio_page_header_text_color' );
			$page_header['parallax']   = intval( delaware_get_option( 'portfolio_page_header_parallax' ) );

		} elseif ( delaware_is_service() ) {
			if ( ! intval( delaware_get_option( 'service_page_header' ) ) ) {
				return false;
			}

			$elements                  = delaware_get_option( 'service_page_header_els' );
			$page_header['bg_image']   = delaware_get_option( 'service_page_header_bg' );
			$page_header['text_color'] = delaware_get_option( 'service_page_header_text_color' );
			$page_header['parallax']   = intval( delaware_get_option( 'service_page_header_parallax' ) );


		} elseif ( delaware_is_catalog() ) {
			if ( ! intval( delaware_get_option( 'page_header_shop' ) ) ) {
				return false;
			}

			$elements                  = delaware_get_option( 'page_header_shop_els' );
			$page_header['bg_image']   = delaware_get_option( 'page_header_shop_bg' );
			$page_header['text_color'] = delaware_get_option( 'page_header_shop_text_color' );
			$page_header['parallax']   = intval( delaware_get_option( 'page_header_shop_parallax' ) );

		} elseif ( function_exists( 'is_product' ) && is_product() ) {
			if ( ! intval( delaware_get_option( 'single_product_page_header' ) ) ) {
				return false;
			}

			$elements                  = delaware_get_option( 'single_product_page_header_els' );
			$page_header['bg_image']   = delaware_get_option( 'single_product_page_header_bg' );
			$page_header['text_color'] = delaware_get_option( 'single_product_page_header_text_color' );
			$page_header['parallax']   = intval( delaware_get_option( 'service_page_header_parallax' ) );

		} elseif ( is_singular( 'post' )
		           || is_singular( 'portfolio' )
		           || is_singular( 'service' )
		           || is_page()
		           || is_page_template( 'template-fullwidth.php' )
		) {
			if ( is_singular( 'post' ) ) {
				if ( ! intval( delaware_get_option( 'single_page_header' ) ) ) {
					return false;
				}

				$elements   = delaware_get_option( 'single_page_header_els' );
				$bg_image   = delaware_get_option( 'single_page_header_bg' );
				$text_color = delaware_get_option( 'single_page_header_text_color' );
				$parallax   = intval( delaware_get_option( 'single_page_header_parallax' ) );

			} elseif ( is_singular( 'portfolio' ) ) {
				if ( ! intval( delaware_get_option( 'single_portfolio_page_header' ) ) ) {
					return false;
				}

				$elements   = delaware_get_option( 'single_portfolio_page_header_els' );
				$bg_image   = delaware_get_option( 'single_portfolio_page_header_bg' );
				$text_color = delaware_get_option( 'single_portfolio_page_header_text_color' );
				$parallax   = intval( delaware_get_option( 'single_portfolio_page_header_parallax' ) );


			} elseif ( is_singular( 'service' ) ) {
				if ( ! intval( delaware_get_option( 'single_service_page_header' ) ) ) {
					return false;
				}

				$elements   = delaware_get_option( 'single_service_page_header_els' );
				$bg_image   = delaware_get_option( 'single_service_page_header_bg' );
				$parallax   = intval( delaware_get_option( 'single_service_page_header_parallax' ) );
				$text_color = delaware_get_option( 'service_page_header_text_color' );

			} elseif ( is_page() || is_page_template( 'template-fullwidth.php' ) ) {
				if ( ! intval( delaware_get_option( 'page_page_header' ) ) ) {
					return false;
				}

				$elements   = delaware_get_option( 'page_page_header_els' );
				$bg_image   = delaware_get_option( 'page_page_header_bg' );
				$text_color = delaware_get_option( 'page_page_header_text_color' );
				$parallax   = intval( delaware_get_option( 'page_page_header_parallax' ) );
			}

			$page_header['bg_image']   = $bg_image;
			$page_header['text_color'] = $text_color;
			$page_header['parallax']   = $parallax;

		} else {
			if ( ! intval( delaware_get_option( 'blog_page_header' ) ) ) {
				return false;
			}

			$elements                  = delaware_get_option( 'blog_page_header_els' );
			$page_header['bg_image']   = delaware_get_option( 'blog_page_header_bg' );
			$page_header['text_color'] = delaware_get_option( 'blog_page_header_text_color' );
			$page_header['parallax']   = intval( delaware_get_option( 'blog_page_header_parallax' ) );
		}

		if ( delaware_get_post_meta( 'hide_title' ) ) {
			$key = array_search( 'title', $elements );
			if ( $key !== false ) {
				unset( $elements[ $key ] );
			}
		}

		if ( delaware_get_post_meta( 'hide_breadcrumb' ) ) {
			$key = array_search( 'breadcrumb', $elements );
			if ( $key !== false ) {
				unset( $elements[ $key ] );
			}
		}

		if ( delaware_get_post_meta( 'hide_parallax' ) ) {
			unset( $page_header['parallax'] );
		}

		if ( delaware_get_post_meta( 'page_bg' ) ) {
			$meta_page_bg            = delaware_get_post_meta( 'page_bg' );
			$page_header['bg_image'] = wp_get_attachment_url( $meta_page_bg );
		}

		if ( delaware_get_post_meta( 'text_color' ) ) {
			$text_color                = delaware_get_post_meta( 'text_color' );
			$page_header['text_color'] = $text_color;
		}

		$page_header['elements'] = $elements;

		return $page_header;
	}
endif;

if ( ! function_exists( 'delaware_menu_icon' ) ) :
	/**
	 * Get menu icon
	 */
	function delaware_menu_icon() {
		printf(
			'<a id="icon-menu-mobile" class="navbar-icon" href="#">
				<span class="navbars-line"></span>
			</a>'
		);
	}
endif;

/**
 * Get socials
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
function delaware_get_socials() {
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

	return apply_filters( 'delaware_header_socials', $socials );
}

// Rating reviews

function delaware_rating_stars( $score ) {
	$score     = min( 10, abs( $score ) );
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

	echo join( "\n", $stars );
}

/**
 * Check is blog
 *
 * @since  1.0
 */

if ( ! function_exists( 'delaware_is_blog' ) ) :
	function delaware_is_blog() {
		if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is catalog
 *
 * @return bool
 */
if ( ! function_exists( 'delaware_is_catalog' ) ) :
	function delaware_is_catalog() {

		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'delaware_is_portfolio' ) ) :
	function delaware_is_portfolio() {
		if ( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is services
 *
 * @since  1.0
 */

if ( ! function_exists( 'delaware_is_service' ) ) :
	function delaware_is_service() {
		if ( is_post_type_archive( 'service' ) || is_tax( 'service_category' ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'delaware_portfolio_isotope' ) ) {
	function delaware_portfolio_isotope() {
		if ( ! intval( delaware_get_option( 'portfolio_cats_filters' ) ) ) {
			return;
		}

		$cats = array();

		global $wp_query;

		$cats_number = delaware_get_option( 'portfolio_cat_number' );
		$count       = 0;

		while ( $wp_query->have_posts() ) {

			$wp_query->the_post();
			$post_categories = wp_get_post_terms( get_the_ID(), 'portfolio_category' );

			foreach ( $post_categories as $cat ) {
				if ( empty( $cats[ $cat->term_id ] ) ) {
					$cats[ $cat->term_id ] = array( 'name' => $cat->name, 'slug' => $cat->slug, );
				}
			}
		}

		$list_category = array();

		foreach ( $cats as $category ) {
			if ( $count == $cats_number ):break; endif;
			$slug            = esc_attr( $category['slug'] );
			$name            = esc_html( $category['name'] );
			$list_category[] = '<span class="button" data-filter=".portfolio_category-' . $slug . '">' . $name . '</span>';
			$count ++;
		}

		$list_category = implode( ' ', $list_category );
		$viewall       = esc_html__( 'View all', 'delaware' );

		printf(
			'<div class="text-center categories-filter portfolio-cats-filter" id="portfolio-cats-filters">
				<span class="button active" data-filter="*">%s</span>
				%s
			</div>',
			$viewall,
			$list_category
		);
	}
}

add_action( 'delaware_portfolio_before_content', 'delaware_portfolio_isotope' );

/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'delaware_pf_title' ) ) {
	function delaware_pf_title() { ?>
        <h3 class="entry-title text-center">
            <a href="<?php the_permalink(); ?>">
				<?php echo get_the_title(); ?>
            </a>
        </h3>
		<?php
	}
}

/**
 * Check is portfolio
 *
 * @since  1.0
 */
if ( ! function_exists( 'delaware_pf_descr' ) ) {
	function delaware_pf_descr() {
		?>
        <div class="descr text-center">
			<?php
			$content   = get_the_excerpt();
			$number_ex = delaware_get_option( 'portfolio_excerpt_descr' );
			echo delaware_content_limit( $content, $number_ex, false );
			?>
        </div>
		<?php
	}
}

/**
 * Check is portfolio
 *
 * @since  1.0
 */
if ( ! function_exists( 'delaware_portfolio_category' ) ) {
	function delaware_portfolio_category() {
		$terms = get_the_terms( get_the_ID(), 'portfolio_category' );
		if ( $terms && ! is_wp_error( $terms ) ) :
			return sprintf(
				'<a class="category" href="%s">%s</a>',
				esc_url( get_term_link( $terms[0]->term_id, 'portfolio_category' ) ),
				$terms[0]->name
			);
		endif;
	}
}

/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'delaware_is_portfolio' ) ) :
	function delaware_is_portfolio() {
		if ( is_post_type_archive( 'portfolio_project' ) || is_tax( 'portfolio_category' ) ) {
			return true;
		}

		return false;
	}
endif;

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

/**
 * Get or display limited words from given string.
 * Strips all tags and shortcodes from string.
 *
 * @since 1.0.0
 *
 * @param integer $num_words The maximum number of words
 * @param string $more More link.
 * @param bool $echo Echo or return output
 *
 * @return string|void Limited content.
 */
function delaware_content_limit( $content, $num_words, $more = "&hellip;", $echo = true ) {
	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'delaware_content_limit_allowed_tags', '<script>,<style>' ) );

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more ) {
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'delaware' ), the_title_attribute( 'echo=0' ) ),
			esc_html( $more )
		);
	} else {
		$output = sprintf( '<p>%s</p>', $content );
	}

	if ( ! $echo ) {
		return $output;
	}

	return $output;
}


if ( ! function_exists( 'delaware_content_limit_fix' ) ) :
	/**
	 * Prints content limit.
	 */
	function delaware_content_limit_fix( $num_words, $more = "&hellip;" ) {
		$content = get_the_excerpt();

		// Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = wp_trim_words( $content, $num_words );

		if ( $more ) {
			echo sprintf(
				'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
				$content,
				get_permalink(),
				sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'delaware' ), the_title_attribute( 'echo=0' ) ),
				esc_html( $more )
			);
		} else {
			echo sprintf( '<div class="entry-content">%s</div>', $content );
		}
	}
endif;

if ( ! function_exists( 'delaware_post_entry_content' ) ) :

	function delaware_post_entry_content() {

		$excerpt_length = intval( delaware_get_option( 'excerpt_length_blog' ) );

		delaware_content_limit_fix( $excerpt_length, '' );

	}
endif;
