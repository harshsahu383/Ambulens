<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class Delaware_WooCommerce {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * @var string shop view
	 */
	public $new_duration;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Delaware_WooCommerce
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return;
		}

		// Define all hook
		add_action( 'template_redirect', array( $this, 'hooks' ) );

		// Need an early hook to ajaxify update mini shop cart
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );

	}

	/**
	 * Hooks to WooCommerce actions, filters
	 *
	 * @since  1.0
	 * @return void
	 */
	function hooks() {

		// WooCommerce Styles
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'wc_styles' ) );

		// Remove breadcrumb, use theme's instead
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Remove badges
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );

		// Add toolbars for shop page
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_toolbar' ) );

		// Add Bootstrap classes
		add_filter( 'post_class', array( $this, 'product_class' ), 30, 3 );

		// Remove product link
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 10 );

		// Add link to product title in shop loop
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'show_product_loop_title' ) );

		// Add box content
		add_action ( "woocommerce_shop_loop_item_title",  array( $this, 'after_box_started' ), 9 );
		add_action ( "woocommerce_after_shop_loop_item_title", array( $this, 'before_box_started' ), 10 );

		// Change next and prev text
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );

		// Change position rating single product
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );

		// Remove flash single product
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

		// Check related single products
		$related = delaware_get_option( 'related_product' );

		if ( ! intval( $related ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}

		// Check shownumber related single product
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_custom_number' ) );

		// Wrap product loop content
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_product_inner' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_inner' ), 50 );

		// Add product thumbnail
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_content_thumbnail' ) );
	}

	/**
	 * Ajaxify update cart viewer
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	function add_to_cart_fragments( $fragments ) {
		global $woocommerce;

		if ( empty( $woocommerce ) ) {
			return $fragments;
		}

		ob_start();
		$icon_cart = '<span class="shopping-cart-icon hidden-md hidden-xs hidden-sm"><svg viewBox="0 0 20 20"><use xlink:href="#shopping-cart"></use></svg></span>';
		$icon_cart = apply_filters( 'dl_icon_cart', $icon_cart );

		$text_cart = apply_filters( 'dl_text_cart', sprintf( '<span class="text-cart hidden-lg">%s</span>', esc_html__( 'Shopping Cart', 'delaware' ) ) );

		?>

		<a href="<?php echo esc_url( wc_get_cart_url() ) ?>" class="cart-contents icon-cart-contents">
			<?php echo sprintf( '%s', $icon_cart ); ?>
			<?php echo sprintf( '%s', $text_cart ); ?>
			<span class="mini-cart-counter"><?php echo intval( $woocommerce->cart->cart_contents_count ); ?></span>
		</a>

		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Remove default woocommerce styles
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function wc_styles( $styles ) {
		unset( $styles['woocommerce-layout'] );
		unset( $styles['woocommerce-smallscreen'] );

		return $styles;
	}

	/**
	 * Change next and previous icon of pagination nav
	 *
	 * @since  1.0
	 */
	function pagination_args( $args ) {
		$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
		$args['next_text'] = '<i class="fa fa-angle-right"></i>';

		return $args;
	}

	/**
	 * Print new product title shop page with link inside
	 */
	function show_product_loop_title() {
		?><h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4><?php
	}

	/**
	 * Add box content
	 */
	function after_box_started () {
		echo "<div class='product-info'>";
	}
	function before_box_started () {
		echo "</div>";
	}

	//  Check shownumber related single product
	function related_custom_number( $args ) {
	    $number_post = delaware_get_option( 'related_product_numbers' );
	    $number_column = delaware_get_option( 'related_product_columns' );
		$args['posts_per_page'] = $number_post; // 4 related products
		$args['columns'] = $number_column; // arranged in 2 columns
		return $args;
	}

	/**
	 * Wrap product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_product_inner() {
		echo '<div class="product-inner  clearfix">';
	}

	/**
	 * Wrap product content
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_product_inner() {
		echo '</div>';
	}

	/**
	 * Add Bootstrap's column classes for product
	 *
	 * @since 1.0
	 *
	 * @param array $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_class( $classes, $class = '', $post_id = '' ) {
		if ( ! $post_id || get_post_type( $post_id ) !== 'product' ) {
			return $classes;
		}

		global $product;

		if ( ! is_single( $post_id ) ) {
			global $woocommerce_loop;

			$sm_class = 'col-sm-4';

			if ( $woocommerce_loop['columns'] == 2 ) {
				$sm_class = 'col-sm-6';
			}


			$classes[] = 'col-xs-6 ' . $sm_class;

			if ( $woocommerce_loop['columns'] != 5 ) {
				$classes[] = 'col-md-' . ( 12 / $woocommerce_loop['columns'] );
			} else {
				$classes[] = 'col-md-1-5';
			}

			$classes[] = 'dl-' . $woocommerce_loop['columns'] . '-cols';
		}

		return $classes;
	}

	/**
	 * Display a tool bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_toolbar() {
		?>

		<div class="shop-toolbar">
			<div class="row-flex">
				<div class="toolbar-col-left col-flex-xs-12 col-flex-sm-6">
					<?php if ( $relsult_count = woocommerce_result_count() ) : ?>
						<span class="woo-character">(</span><?php $relsult_count ?><span class="woo-character">)</span>
					<?php endif; ?>
				</div>

				<div class="toolbar-col-right col-flex-xs-12 col-flex-sm-6 text-right">
					<?php woocommerce_catalog_ordering() ?>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * WooCommerce Loop Product Content Thumbs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_content_thumbnail() {
		printf( '<div class="dl-product-thumbnail">' );

		printf( '<a href ="%s">', esc_url( get_the_permalink() ) );

		if ( function_exists( 'woocommerce_get_product_thumbnail' ) ) {
			echo woocommerce_get_product_thumbnail();
		}

		echo '</a>';

		echo '</div>';

	}
}
