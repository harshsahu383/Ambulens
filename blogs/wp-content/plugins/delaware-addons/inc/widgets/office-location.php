<?php

if( ! class_exists('Delaware_Office_Location_Widget') ) {
	class Delaware_Office_Location_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		public $w_id = '';

		/**
		 * Constructor
		 *
		 * @return Delaware_Office_Location_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'title'    => '',
				'location' => '',
				'phone'    => '',
				'email'    => '',
				'url'      => '',
				'address'  => '',
				'time'     => '',
			);

			parent::__construct(
				'delaware-office-location-widget',
				esc_html__( 'Delaware - Office Location', 'delaware' ),
				array(
					'classname'   => 'delaware-office-location-widget',
					'description' => esc_html__( 'Induscity office location widget.', 'delaware' )
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


			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			if ( ! $instance['location'] ) {
				echo wp_kses_post( $after_widget );

				return;
			}

			$info = $office = array();

			$location = explode( '|', $instance['location'] );
			$phone    = explode( '|', $instance['phone'] );
			$email    = explode( '|', $instance['email'] );
			$address  = explode( '|', $instance['address'] );
			$time     = explode( '|', $instance['time'] );
			$url      = explode( '|', $instance['url'] );

			$count1 = count( $location );
			$count2 = count( $phone );
			$count3 = count( $email );
			$count4 = count( $address );
			$count5 = count( $time );

			$count = array( $count1, $count2, $count3, $count4, $count5 );

			$max = max( $count );

			$id = $this->get_id_number( __CLASS__ );

			for ( $i = 0; $i < $max; $i ++ ) {
				$p = $e = $a = $t = $u = '';

				if ( isset( $url[ $i ] ) && $url[ $i ] ) {
					$u = $url[ $i ];
				}

				if ( isset( $phone[ $i ] ) && $phone[ $i ] ) {
					$p = sprintf( '<li class="phone"><a href="tel:%s"><span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#phone-call"></use></svg></span>%s</a></li>', esc_attr( $phone[ $i ] ), $phone[ $i ] );
				}

				if ( isset( $email[ $i ] ) && $email[ $i ] ) {
					$e = sprintf( '<li class="email"><a href="mailto:%s"><span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#mail"></use></svg></span>%s</a></li>', esc_attr( $u ), $email[ $i ] );
				}

				if ( isset( $address[ $i ] ) && $address[ $i ] ) {
					$a = sprintf( '<li class="address"><span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#placeholder"></use></svg></span>%s</li>', $address[ $i ] );
				}

				if ( isset( $time[ $i ] ) && $time[ $i ] ) {
					$t = sprintf( '<li class="time"><span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#wall-clock"></use></svg></span>%s</li>', $time[ $i ] );
				}

				$info[] = sprintf( '<ul class="office-%s topbar-office" id="office-location-%s-%s">%s%s%s%s</ul>', $i + 1, $id, $i + 1, $p, $e, $a, $t );

				if ( isset( $location[ $i ] ) && $location[ $i ] ) {
					$office[] = sprintf( '<li class="location" data-tab="office-location-%s-%s">%s</li>', $id, $i + 1, isset( $location[ $i ] ) ? $location[ $i ] : '' );
				}
			}

			echo '<div class="office-location clearfix">';
			echo '<div class="office-switcher"><span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#global"></use></svg></span><span>' . esc_html__( 'Location:', 'delaware' ) . '</span><a class="current-office" href="#"></a>';
			echo '<ul>' . implode( '', $office ) . '</ul>';
			echo '</div>';
			echo implode( '', $info );
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
			$new_instance['title']    = strip_tags( $new_instance['title'] );
			$new_instance['location'] = strip_tags( $new_instance['location'] );
			$new_instance['phone']    = strip_tags( $new_instance['phone'] );
			$new_instance['time']     = strip_tags( $new_instance['time'] );
			$new_instance['address']  = strip_tags( $new_instance['address'] );
			$new_instance['email']    = strip_tags( $new_instance['email'] );
			$new_instance['url']      = strip_tags( $new_instance['url'] );

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
                <label for="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>"><?php esc_html_e( 'Location', 'delaware' ); ?></label>
                <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'location' ) ); ?>"><?php echo wp_kses( $instance['location'], wp_kses_allowed_html( 'post' ) ); ?></textarea>
                <span><?php esc_html_e( 'Enter the location in this format "Location 1|Location 2|Location 3...". Each location is separated by "|"', 'delaware' ) ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone', 'delaware' ); ?></label>
                <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>"><?php echo wp_kses( $instance['phone'], wp_kses_allowed_html( 'post' ) ) ?></textarea>
                <span><?php esc_html_e( 'Enter the phone number in this format "Phone 1|Phone 2|Phone 3...". Each phone number is separated by "|"', 'delaware' ) ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'time' ) ); ?>"><?php esc_html_e( 'Time', 'delaware' ); ?></label>
                <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'time' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'time' ) ); ?>"><?php echo wp_kses( $instance['time'], wp_kses_allowed_html( 'post' ) ) ?></textarea>
                <span><?php esc_html_e( 'Enter the time in this format "Time 1|Time 2|Time 3...". Each time is separated by "|"', 'delaware' ) ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address', 'delaware' ); ?></label>
                <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>"><?php echo wp_kses( $instance['address'], wp_kses_allowed_html( 'post' ) ) ?></textarea>
                <span><?php esc_html_e( 'Enter the address in this format "Address 1|Address 2|Address 3...". Each address is separated by "|"', 'delaware' ) ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email', 'delaware' ); ?></label>
                <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>"><?php echo wp_kses( $instance['email'], wp_kses_allowed_html( 'post' ) ) ?></textarea>
                <span><?php esc_html_e( 'Enter the email in this format "Email 1|Email 2|Email 3...". Each email is separated by "|"', 'delaware' ) ?></span>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'Email Url', 'delaware' ); ?></label>
                <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"
                          name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>"><?php echo wp_kses( $instance['url'], wp_kses_allowed_html( 'post' ) ) ?></textarea>
                <span><?php esc_html_e( 'Enter the email in this format "Email url 1|Email url 2|Email url 3...". Each url is separated by "|"', 'delaware' ) ?></span>
            </p>
			<?php
		}

		function get_id_number( $widget ) {
			if ( isset( $this->ids[ $widget ] ) ) {
				$this->ids[ $widget ] ++;
			} else {
				$this->ids[ $widget ] = 1;
			}

			return $this->ids[ $widget ];
		}
	}
}