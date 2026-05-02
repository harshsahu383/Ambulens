<?php
/**
 * Display breadcrumbs for posts, pages, archive page with the microdata that search engines understand
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417
 *
 * @param array|string $args
 */
function delaware_breadcrumbs( $args = '' ) {
	$args = wp_parse_args( $args, array(
		'separator'         => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
		'home_class'        => 'home',
		'before'            => '',
		'after'             => '',
		'before_item'       => '',
		'after_item'        => '',
		'taxonomy'          => 'category',
		'display_last_item' => true,
		'show_on_front'     => false,
		'labels'            => array(
			'home'      => esc_html__( 'Home', 'delaware' ),
			'archive'   => esc_html__( 'Archives', 'delaware' ),
			'blog'      => esc_html__( 'Blog', 'delaware' ),
			'search'    => esc_html__( 'Search results for', 'delaware' ),
			'not_found' => esc_html__( 'Not Found', 'delaware' ),
			'author'    => esc_html__( 'Author Archives:', 'delaware' ),
			'day'       => esc_html__( 'Daily Archives:', 'delaware' ),
			'month'     => esc_html__( 'Monthly Archives:', 'delaware' ),
			'year'      => esc_html__( 'Yearly Archives:', 'delaware' ),
		),
	) );

	$args = apply_filters( 'delaware_breadcrumbs_args', $args );

	if ( is_front_page() && ! $args['show_on_front'] ) {
		return;
	}

	$items = array();

	// HTML template for each item
	$item_tpl      = $args['before_item'] . '
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a href="%s" itemprop="url"><span itemprop="title">%s</span></a>
		</span>
	' . $args['after_item'];
	$item_text_tpl = $args['before_item'] . '
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<span itemprop="title">%s</span>
		</span>
	' . $args['after_item'];

	// Home
	if ( ! $args['home_class'] ) {
		$items[] = sprintf( $item_tpl, get_home_url(), $args['labels']['home'] );
	} else {
		$items[] = sprintf(
			'%s<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
				<a class="%s" href="%s" itemprop="url"><span itemprop="title">%s</span></a>
			</span>%s',
			$args['before_item'],
			$args['home_class'],
			get_home_url(),
			$args['labels']['home'],
			$args['after_item']
		);
	}

	// Front page
	if ( is_front_page() ) {
		$items   = array();
		$items[] = sprintf( $item_text_tpl, $args['labels']['home'] );
	}
	// Blog
	elseif ( is_home() && ! is_front_page() ) {
		$items[] = sprintf(
			$item_text_tpl,
			$args['labels']['blog']
		);
	}
	// Single
	elseif ( is_single() ) {
		// Terms

		$taxonomy = $args['taxonomy'];

		if ( is_singular( 'product' ) ) {
			$taxonomy = 'product_cat';

			if ( $page_id = get_option( 'woocommerce_shop_page_id' ) ) {
				$items[] = sprintf( $item_tpl, esc_url( get_permalink( $page_id ) ), get_the_title( $page_id ) );
			}
		} elseif ( is_singular( 'portfolio' ) ) {
			$taxonomy = 'portfolio_category';

			if ( $page_id = get_option( 'delaware_portfolio_page_id' ) ) {
				$items[] = sprintf( $item_tpl, esc_url( get_permalink( $page_id ) ), get_the_title( $page_id ) );
			}
		} elseif ( is_singular( 'service' ) ) {
			$taxonomy = 'service_category';

			if ( $page_id = get_option( 'delaware_service_page_id' ) ) {
				$items[] = sprintf( $item_tpl, esc_url( get_permalink( $page_id ) ), get_the_title( $page_id ) );
			}
		}

		$terms   = get_the_terms( get_the_ID(),  $taxonomy );
		if( $terms ) {
			$term    = current( $terms );
			$terms   = delaware_get_term_parents( $term->term_id, $taxonomy );
			$terms[] = $term->term_id;

			foreach ( $terms as $term_id ) {
				$term    = get_term( $term_id, $taxonomy );
				$items[] = sprintf( $item_tpl, get_term_link( $term, $taxonomy ), $term->name );
			}
		}

		if ( $args['display_last_item'] ) {
			$items[] = sprintf( $item_text_tpl, get_the_title() );
		}
	}
	// Page
	elseif ( is_page() ) {
		if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) ) {
			if ( $page_id = get_option( 'woocommerce_shop_page_id' ) ) {
				$items[] = sprintf( $item_tpl, esc_url( get_permalink( $page_id ) ), get_the_title( $page_id ) );
			}

		} else {
			$pages = delaware_get_post_parents( get_queried_object_id() );
			foreach ( $pages as $page ) {
				$items[] = sprintf( $item_tpl, esc_url( get_permalink( $page ) ), get_the_title( $page ) );
			}
		}

		if ( $args['display_last_item'] ) {
			$items[] = sprintf( $item_text_tpl, get_the_title() );
		}
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		if ( $args['display_last_item'] ) {
			$items[] = sprintf( $item_text_tpl, $title );
		}
	}
	// Taxonomy
	elseif ( is_tax() || is_category() || is_tag() ) {
		$current_term = get_queried_object();

		if( $current_term ) {
			$terms        = delaware_get_term_parents( get_queried_object_id(), $current_term->taxonomy );
			if( $terms ) {
				foreach ( $terms as $term_id )
				{
					$term    = get_term( $term_id, $current_term->taxonomy );
					$items[] = sprintf( $item_tpl, get_category_link( $term_id ), $term->name );
				}
			}

			if ( $args['display_last_item'] )
				$items[] = sprintf( $item_text_tpl, $current_term->name );
		}

	}
	// Search
	elseif ( is_search() ) {
		$items[] = sprintf( $item_text_tpl, $args['labels']['search'] . ' &quot;' . get_search_query() . '&quot;' );
	}
	// 404
	elseif ( is_404() ) {
		$items[] = sprintf( $item_text_tpl, $args['labels']['not_found'] );
	}
	// Author archive
	elseif ( is_author() ) {
		// Queue the first post, that way we know what author we're dealing with (if that is the case).
		the_post();
		$items[] = sprintf(
			$item_text_tpl,
			$args['labels']['author'] . ' <span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>'
		);
		rewind_posts();
	}
	// Day archive
	elseif ( is_day() ) {
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( esc_html__( '%s %s', 'delaware' ), $args['labels']['day'], get_the_date() )
		);
	}
	// Month archive
	elseif ( is_month() ) {
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( esc_html__( '%s %s', 'delaware' ), $args['labels']['month'], get_the_date( 'F Y' ) )
		);
	}
	// Year archive
	elseif ( is_year() ) {
		$items[] = sprintf(
			$item_text_tpl,
			sprintf( esc_html__( '%s %s', 'delaware' ), $args['labels']['year'], get_the_date( 'Y' ) )
		);
	}
	// Archive
	elseif ( is_post_type_archive( 'portfolio' ) ) {

		$portfolio_page_id = get_option( 'delaware_portfolio_page_id' );

		if ( $portfolio_page_id && get_post( $portfolio_page_id ) ) {
			$title = get_the_title( $portfolio_page_id );
		} else {
			$title = _x( 'Project', 'Project post type breadcrumb', 'delaware' );
		}

		$items[] = sprintf(
			$item_text_tpl,
			$title
		);

	} elseif ( is_post_type_archive( 'service' ) ) {

		$service_page_id = get_option( 'delaware_service_page_id' );

		if ( $service_page_id && get_post( $service_page_id ) ) {
			$title = get_the_title( $service_page_id );
		} else {
			$title = _x( 'Service', 'Service post type breadcrumb', 'delaware' );
		}

		$items[] = sprintf(
			$item_text_tpl,
			$title
		);

	} else {
		$items[] = sprintf(
			$item_text_tpl,
			$args['labels']['archive']
		);
	}

	echo implode( $args['separator'], $items );
}

/**
 * Searches for term parents' IDs of hierarchical taxonomies, including current term.
 * This function is similar to the WordPress function get_category_parents() but handles any type of taxonomy.
 * Modified from Hybrid Framework
 *
 * @param int|string    $term_id  The term ID
 * @param object|string $taxonomy The taxonomy of the term whose parents we want.
 *
 * @return array Array of parent terms' IDs.
 */
function delaware_get_term_parents( $term_id = '', $taxonomy = 'category' ) {
	// Set up some default arrays.
	$list = array();

	// If no term ID or taxonomy is given, return an empty array.
	if ( empty( $term_id ) || empty( $taxonomy ) ) {
		return $list;
	}

	do {
		$list[] = $term_id;

		// Get next parent term
		$term    = get_term( $term_id, $taxonomy );
		$term_id = $term->parent;
	} while ( $term_id );

	// Reverse the array to put them in the proper order for the trail.
	$list = array_reverse( $list );
	array_pop( $list );

	return $list;
}

/**
 * Gets parent posts' IDs of any post type, include current post
 * Modified from Hybrid Framework
 *
 * @param int|string $post_id ID of the post whose parents we want.
 *
 * @return array Array of parent posts' IDs.
 */
function delaware_get_post_parents( $post_id = '' ) {
	// Set up some default array.
	$list = array();

	// If no post ID is given, return an empty array.
	if ( empty( $post_id ) ) {
		return $list;
	}

	do {
		$list[] = $post_id;

		// Get next parent post
		$post    = get_post( $post_id );
		$post_id = $post->post_parent;
	} while ( $post_id );

	// Reverse the array to put them in the proper order for the trail.
	$list = array_reverse( $list );
	array_pop( $list );

	return $list;
}
