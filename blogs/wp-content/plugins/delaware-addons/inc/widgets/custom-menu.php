<?php

if( ! class_exists('Delaware_Custom_Menu_Widget') ) {
	class Delaware_Custom_Menu_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Sets up a new Custom Menu widget instance.
		 *
		 * @since  3.0.0
		 * @access public
		 */
		public function __construct() {
			$this->defaults = array(
				'title'      => '',
				'nav_menu'   => '',
				'nav_menu_1' => '',
				'icon'       => 0,
			);

			parent::__construct(
				'dl-custom-menu',
				esc_html__( 'Delaware - Custom Menu', 'delaware' ),
				array(
					'description'                 => esc_html__( 'Add a custom menu to your sidebar.', 'delaware' ),
					'customize_selective_refresh' => true,
				)
			);
		}

		/**
		 * Outputs the content for the current Custom Menu widget instance.
		 *
		 * @since  3.0.0
		 * @access public
		 *
		 * @param array $args Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Custom Menu widget instance.
		 */
		public function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->defaults );
			extract( $args );

			$css = '';

			$nav_menu   = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
			$nav_menu_1 = ! empty( $instance['nav_menu_1'] ) ? wp_get_nav_menu_object( $instance['nav_menu_1'] ) : false;
			$show_icon  = $instance['icon'];

			$css .= intval( $show_icon ) ? ' show-icon' : '';
			$css .= empty( $instance['nav_menu_1'] ) ? ' disable-menu-2' : '';

			if ( ! $nav_menu && ! $nav_menu_1 ) {
				return;
			}

			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			$nav_menu_args = array(
				'fallback_cb' => '',
				'menu'        => $nav_menu
			);

			$nav_menu_1_args = array(
				'fallback_cb' => '',
				'menu'        => $nav_menu_1
			);

			/**
			 * Filters the arguments for the Custom Menu widget.
			 *
			 * @since 4.2.0
			 * @since 4.4.0 Added the `$instance` parameter.
			 *
			 * @param array $nav_menu_args {
			 *                                    An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
			 *
			 * @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
			 * @type mixed $menu Menu ID, slug, or name.
			 * }
			 *
			 * @param WP_Term $nav_menu Nav menu object for the current menu.
			 * @param array $args Display arguments for the current widget.
			 * @param array $instance Array of settings for the current widget.
			 */
			echo '<div class="custom-menu-area ' . esc_attr( $css ) . '">';

			if ( $nav_menu ) {
				echo '<div class="custom-menu custom-menu-1">';
				wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );
				echo '</div>';
			}

			if ( $nav_menu_1 ) {
				echo '<div class="custom-menu custom-menu-2">';
				wp_nav_menu( apply_filters( 'widget_nav_menu_1_args', $nav_menu_1_args, $nav_menu_1, $args, $instance ) );
				echo '</div>';
			}

			echo '<div class="clear"></div>';
			echo '</div>';

			echo wp_kses_post( $after_widget );
		}

		/**
		 * Handles updating settings for the current Custom Menu widget instance.
		 *
		 * @since  3.0.0
		 * @access public
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 *
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			if ( ! empty( $new_instance['title'] ) ) {
				$instance['title'] = sanitize_text_field( $new_instance['title'] );
			}
			if ( ! empty( $new_instance['nav_menu'] ) ) {
				$instance['nav_menu'] = (int) $new_instance['nav_menu'];
			}
			if ( ! empty( $new_instance['nav_menu_1'] ) ) {
				$instance['nav_menu_1'] = (int) $new_instance['nav_menu_1'];
			}

			$instance['icon'] = ! empty( $new_instance['icon'] );

			return $instance;
		}

		/**
		 * Outputs the settings form for the Custom Menu widget.
		 *
		 * @since  3.0.0
		 * @access public
		 *
		 * @param array $instance Current settings.
		 *
		 * @global WP_Customize_Manager $wp_customize
		 */
		public function form( $instance ) {
			global $wp_customize;
			$title      = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu   = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
			$nav_menu_1 = isset( $instance['nav_menu_1'] ) ? $instance['nav_menu_1'] : '';
			$icon       = isset( $instance['icon'] ) ? $instance['icon'] : '';

			// Get menus
			$menus   = wp_get_nav_menus();
			$menus_1 = wp_get_nav_menus();

			// If no menus exists, direct the user to go and create some.
			?>
            <p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) {
				echo ' style="display:none" ';
			} ?>>
				<?php
				if ( $wp_customize instanceof WP_Customize_Manager ) {
					$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
				} else {
					$url = admin_url( 'nav-menus.php' );
				}
				?>
				<?php echo sprintf( esc_html__( 'No menus have been created yet. <a href="%s">Create some</a>.', 'delaware' ), esc_attr( $url ) ); ?>
            </p>
            <div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) {
				echo ' style="display:none" ';
			} ?>>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'delaware' ) ?></label>
                    <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                           value="<?php echo esc_attr( $title ); ?>"/>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"><?php esc_html_e( 'Select Menu 1:', 'delaware' ); ?></label>
                    <select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>"
                            name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
                        <option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'delaware' ); ?></option>
						<?php foreach ( $menus as $menu ) : ?>`
                            <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
								<?php echo esc_html( $menu->name ); ?>
                            </option>
						<?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu_1' ) ); ?>"><?php esc_html_e( 'Select Menu 2:', 'delaware' ); ?></label>
                    <select id="<?php echo esc_attr( $this->get_field_id( 'nav_menu_1' ) ); ?>"
                            name="<?php echo esc_attr( $this->get_field_name( 'nav_menu_1' ) ); ?>">
                        <option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'delaware' ); ?></option>
						<?php foreach ( $menus_1 as $menu ) : ?>
                            <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu_1, $menu->term_id ); ?>>
								<?php echo esc_html( $menu->name ); ?>
                            </option>
						<?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $icon ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Show Icon', 'delaware' ); ?></label>
                </p>
				<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
                    <p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) {
						echo 'display: none;';
					} ?>">
                        <button type="button" class="button"><?php esc_html_e( 'Edit Menu', 'delaware' ) ?></button>
                    </p>
				<?php endif; ?>
            </div>
			<?php
		}
	}
}