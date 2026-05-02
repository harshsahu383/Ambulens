<?php

if( ! class_exists('Delaware_Social_Links_Widget') ) {
	class Delaware_Social_Links_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $default;

		/**
		 * List of supported socials
		 *
		 * @var array
		 */
		protected $socials;

		/**
		 * Constructor
		 */
		function __construct() {
			$socials = array(
				'facebook'     => esc_html__( 'Facebook', 'delaware' ),
				'twitter'      => esc_html__( 'Twitter', 'delaware' ),
				'google-plus'  => esc_html__( 'Google Plus', 'delaware' ),
				'youtube-play' => esc_html__( 'Youtube', 'delaware' ),
				'tumblr'       => esc_html__( 'Tumblr', 'delaware' ),
				'skype'        => esc_html__( 'Skype', 'delaware' ),
				'linkedin'     => esc_html__( 'Linkedin', 'delaware' ),
				'pinterest'    => esc_html__( 'Pinterest', 'delaware' ),
				'flickr'       => esc_html__( 'Flickr', 'delaware' ),
				'instagram'    => esc_html__( 'Instagram', 'delaware' ),
				'dribbble'     => esc_html__( 'Dribbble', 'delaware' ),
				'stumbleupon'  => esc_html__( 'StumbleUpon', 'delaware' ),
				'github'       => esc_html__( 'Github', 'delaware' ),
				'rss'          => esc_html__( 'RSS', 'delaware' ),
			);

			$this->socials = apply_filters( 'delaware_social_media', $socials );
			$this->default = array(
				'title'       => '',
				'description' => '',
			);

			foreach ( $this->socials as $k => $v ) {
				$this->default["{$k}_title"] = $v;
				$this->default["{$k}_url"]   = '';
			}

			parent::__construct(
				'delaware-social-links-widget',
				esc_html__( 'Delaware - Social Links', 'delaware' ),
				array(
					'classname'   => 'delaware-social-links-widget',
					'description' => esc_html__( 'Display links to social media networks.', 'delaware' ),
				),
				array( 'width' => 600 )
			);
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @param array $args An array of standard parameters for widgets in this theme
		 * @param array $instance An array of settings for this widget instance
		 *
		 * @return void Echoes it's output
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->default );

			echo wp_kses_post( $args['before_widget'] );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}

			if ( ! empty( $instance['description'] ) ) {
				echo '<div class="widget-desc">' . $instance['description'] . '</div>';
			}

			foreach ( $this->socials as $social => $label ) {
				if ( ! empty( $instance[ $social . '_url' ] ) ) {
					printf(
						'<a href="%s" class="share-%s tooltip-enable social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank"><i class="fa fa-%s"></i></a>',
						esc_url( $instance[ $social . '_url' ] ),
						esc_attr( $social ),
						esc_attr( $instance[ $social . '_title' ] ),
						esc_attr( $social )
					);
				}
			}

			echo wp_kses_post( $args['after_widget'] );
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @param array $instance
		 *
		 * @return string|void
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->default );
			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'delaware' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title'] ); ?>"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'delaware' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"
                       value="<?php echo esc_attr( $instance['description'] ); ?>"/>
            </p>

			<?php
			foreach ( $this->socials as $social => $label ) {
				printf(
					'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
					$label,
					$this->get_field_name( $social . '_url' ),
					esc_attr__( 'URL', 'delaware' ),
					$instance[ $social . '_url' ]
				);
			}
		}
	}
}