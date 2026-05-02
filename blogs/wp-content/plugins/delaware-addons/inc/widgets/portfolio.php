<?php

if( ! class_exists('Delaware_Portfolio_Widget') ) {
	class Delaware_Portfolio_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Constructor
		 *
		 * @return Delaware_Portfolio_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'title'   => '',
				'limit'   => 6,
				'order'   => 'DESC',
				'orderby' => 'date',
				'thumb'   => 1,
			);

			parent::__construct(
				'dl-portfolio-widget',
				esc_html__( 'Delaware - Portfolio', 'delaware' ),
				array(
					'classname'   => 'dl-portfolio-widget',
					'description' => esc_html__( 'Advanced portfolio widget.', 'delaware' )
				)
			);
		}

		/**
		 * Display widget
		 *
		 * @param array $args Sidebar configuration
		 * @param array $instance Widget settings
		 *
		 * @return void
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			extract( $args );

			$query_args = array(
				'posts_per_page'      => apply_filters( 'delaware_portfolio_widget_limit', $instance['limit'] ),
				'post_type'           => 'portfolio',
				'orderby'             => $instance['orderby'],
				'order'               => $instance['order'],
				'ignore_sticky_posts' => true,
			);

			$query = new WP_Query( $query_args );

			if ( ! $query->have_posts() ) {
				return;
			}

			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			echo '<div class="list-portfolio-widget row-flex">';
			echo '<div class="portfolio-widget-wrapper row-flex col-flex-lg-12 col-flex-md-8 col-flex-sm-12 col-flex-xs-6">';

			$class = $instance['thumb'] ? '' : 'no-thumbnail';

			while ( $query->have_posts() ) : $query->the_post();

				if ( $instance['thumb'] ) {
					printf(
						'<div class="portfolio-widget-item col-flex-xs-4 %s">
						<a class="widget-thumb" href="%s" title="%s">%s</a>
					</div>',
						esc_attr( $class ),
						get_permalink(),
						get_the_title(),
						get_the_post_thumbnail( get_the_ID(), 'delaware-portfolio-widget-thumb' )
					);
				}

			endwhile;
			wp_reset_postdata();

			echo '</div>';
			echo '</div>';

			echo wp_kses_post( $after_widget );

		}

		/**
		 * Update widget
		 *
		 * @param array $new_instance New widget settings
		 * @param array $old_instance Old widget settings
		 *
		 * @return array
		 */
		function update( $new_instance, $old_instance ) {
			$new_instance['title'] = strip_tags( $new_instance['title'] );
			$new_instance['limit'] = intval( $new_instance['limit'] );
			$new_instance['thumb'] = ! empty( $new_instance['thumb'] );

			return $new_instance;
		}

		/**
		 * Display widget settings
		 *
		 * @param array $instance Widget settings
		 *
		 * @return void
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'delaware' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $instance['title'] ); ?>">
            </p>
            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2"
                       value="<?php echo intval( $instance['limit'] ); ?>">
                <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'delaware' ); ?></label>
            </p>
            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox"
                       value="1" <?php checked( $instance['thumb'] ); ?>>
                <label for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"><?php esc_html_e( 'Show Thumbnail', 'delaware' ); ?></label>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By', 'delaware' ); ?></label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
					<?php foreach ( $order_by = $this->service_cat_order_by() as $name => $value ) : ?>
                        <option value="<?php echo esc_attr( $value ) ?>" <?php selected( $name, $instance['orderby'] ) ?>><?php echo ucfirst( $name ) ?></option>
					<?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'delaware' ); ?></label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
					<?php foreach ( $order_by = $this->service_cat_order() as $name => $value ) : ?>
                        <option value="<?php echo esc_attr( $value ) ?>" <?php selected( $name, $instance['order'] ) ?>><?php echo ucfirst( $name ) ?></option>
					<?php endforeach; ?>
                </select>
            </p>
			<?php
		}

		/**
		 * Get service order by
		 *
		 * @return array
		 */
		function service_cat_order_by() {
			$order_by = array(
				esc_html__( 'date', 'delaware' ) => 'date',
				esc_html__( 'ID', 'delaware' )   => 'ID',
				esc_html__( 'name', 'delaware' ) => 'name',
			);

			return $order_by;
		}

		/**
		 * Get service order
		 *
		 * @return array
		 */
		function service_cat_order() {
			$order = array(
				esc_html__( 'DESC', 'delaware' ) => 'DESC',
				esc_html__( 'ASC', 'delaware' )  => 'ASC',
			);

			return $order;
		}
	}
}