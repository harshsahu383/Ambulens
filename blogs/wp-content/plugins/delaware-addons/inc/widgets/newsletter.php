<?php

if( ! class_exists('Delaware_Newsletter_Widget') ) {
	class Delaware_Newsletter_Widget extends WP_Widget {
		/**
		 * Thiết lập widget: đặt tên, base ID
		 */
		protected $defaults;

		function __construct() {
			$this->defaults = array(
				'title'   => '',
				'content' => '',
				'style'   => '1',
			);

			parent::__construct(
				'delaware_newsletter_widget',
				esc_html__( 'Delaware - Newsletter', 'delaware' ),

				array(
					'classname'   => 'delaware-newsletter-widget',
					'description' => esc_html__( 'Display newsletter.', 'delaware' ),
				)
			);
		}

		/**
		 * Tạo form option cho widget
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			$style1   = $instance['style'] == '1' ? 'selected' : '';
			$style2   = $instance['style'] == '2' ? 'selected' : '';
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'delaware' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $instance['title'] ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php esc_html_e( 'Content', 'delaware' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>"
                          rows="4"><?php echo esc_attr( $instance['content'] ); ?></textarea>
                <span><?php esc_html_e( 'Go to MailChimp for WP > Form to get shortcode.', 'delaware' ); ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'delaware' ) ?></label>
                <select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                    <option value='1'<?php echo esc_attr( $style1 ); ?>>1</option>
                    <option value='2'<?php echo esc_attr( $style2 ); ?>>2</option>
                </select>
            </p>
			<?php

		}

		/**
		 * save widget form
		 */

		function update( $new_instance, $old_instance ) {
			$new_instance['title']   = strip_tags( $new_instance['title'] );
			$new_instance['content'] = strip_tags( $new_instance['content'] );
			$new_instance['style']   = strip_tags( $new_instance['style'] );

			return $new_instance;
		}

		/**
		 * Show widget
		 */

		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			extract( $args );

			if ( empty( $instance['content'] ) ) {
				return;
			}

			$title = apply_filters( 'widget_title', $instance['title'] );

			echo wp_kses_post( $before_widget );

			echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );

			$style_css = $instance['style'];

			?>
            <div class="widget-newsletter newsletter-style-<?php echo esc_attr( $style_css ); ?>">

				<?php
				echo do_shortcode( do_shortcode( wp_kses( $instance['content'], wp_kses_allowed_html( 'post' ) ) ) );
				?>
            </div>
			<?php
			echo wp_kses_post( $after_widget );
		}
	}
}