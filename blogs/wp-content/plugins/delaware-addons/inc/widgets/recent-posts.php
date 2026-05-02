<?php

if( ! class_exists('Delaware_Recent_Posts_Widget') ) {
	class Delaware_Recent_Posts_Widget extends WP_Widget {
		/**
		 * Holds widget settings defaults, populated in constructor.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Constructor
		 *
		 * @return Delaware_Recent_Posts_Widget
		 */
		function __construct() {
			$this->defaults = array(
				'style'      => 'style1',
				'title'      => '',
				'limit'      => 3,
				'thumb'      => 1,
				'thumb_size' => 'widget-thumb',
				'cat'        => '',
				'catname'    => 1,
				'date'       => 1,
				'more'       => 1,
			);

			parent::__construct(
				'recent-posts-widget',
				esc_html__( 'Delaware - Recent Posts', 'delaware' ),
				array(
					'classname'   => 'recent-posts-widget',
					'description' => esc_html__( 'Advanced recent posts widget.', 'delaware' )
				),
				array( 'width' => 590 )
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
				'posts_per_page'      => $instance['limit'],
				'post_type'           => 'post',
				'ignore_sticky_posts' => true,
			);
			if ( ! empty( $instance['cat'] ) && is_array( $instance['cat'] ) ) {
				$query_args['category__in'] = $instance['cat'];
			}

			$query = new WP_Query( $query_args );

			if ( ! $query->have_posts() ) {
				return;
			}

			$after_recent = $instance['more'] ? sprintf( '<div class="more-post"><a href="%s">%s</a><svg viewBox=\'0 0 20 20\'><use xlink:href="#next"></use></svg></div>', get_permalink( get_option( 'page_for_posts' ) ), esc_html__( 'Read More Post', 'delaware' ) ) : '';
			echo wp_kses_post( $before_widget );

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo wp_kses_post( $before_title ) . $title . wp_kses_post( $after_title );
			}

			$class = $instance['thumb'] ? '' : 'no-thumbnail';
			echo "<div class='box-recent-post'>";
			while ( $query->have_posts() ) : $query->the_post();
				?>
                <article class="recent-post <?php echo esc_attr( $class ); ?>">
					<?php
					if ( $instance['thumb'] ) {
						$src = get_the_post_thumbnail( get_the_ID(), 'thumbnail' );

						if ( $src ) {
							printf(
								'<a class="widget-thumb" href="%s" title="%s">%s</a>',
								get_permalink(),
								the_title_attribute( 'echo=0' ),
								$src
							);
						}
					}
					?>
                    <div class="post-text">
                        <h3 class="entry-title hide"><?php the_title(); ?></h3>
						<?php if ( $instance['style'] == "style1" ) : ?>
                            <a class="post-title" href="<?php the_permalink(); ?>"
                               title="<?php the_title_attribute(); ?>"
                               rel="bookmark"><?php the_title(); ?></a>
						<?php endif; ?>
                        <div class="entry-meta">
							<?php
							if ( $instance['catname'] ) {
								$category = get_the_category();
								echo '<a class="meta category-post" href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . esc_html( $category[0]->name ) . '</a>';
							}
							?>
							<?php if ( $instance['style'] == "style1" ) : ?>
                                <span class="meta post-by">By <?php the_author_meta( 'display_name' ); ?></span>
							<?php endif; ?>
							<?php
							if ( $instance['date'] ) {
								echo '<time class="post-date meta" datetime="' . esc_attr( get_the_time( 'c' ) ) . '"> <i class="fa fa-clock-o"></i> ' . get_the_time( "F" ) . ' ' . get_the_time( "d" ) . ', ' . get_the_time( "Y" ) . '</time>';
							}
							?>
                        </div>
						<?php if ( $instance['style'] == "style2" ) : ?>
                            <a class="post-title" href="<?php the_permalink(); ?>"
                               title="<?php the_title_attribute(); ?>"
                               rel="bookmark"><?php the_title(); ?></a>
						<?php endif; ?>
						<?php if ( $instance['more'] ) : ?>
                            <div class="btn-recent">
                                <a href="<?php the_permalink(); ?>">
									<?php echo esc_html__( 'Continue Reading...', 'delaware' ) ?>
                                </a>
                            </div>
						<?php endif; ?>
                    </div>
                </article>
			<?php
			endwhile;
			echo "</div>";
			echo wp_kses_post( $after_recent );
			wp_reset_postdata();

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
			$new_instance['style']   = ( $new_instance['style'] );
			$new_instance['title']   = strip_tags( $new_instance['title'] );
			$new_instance['cat']     = array_filter( $new_instance['cat'] );
			$new_instance['limit']   = intval( $new_instance['limit'] );
			$new_instance['thumb']   = ! empty( $new_instance['thumb'] );
			$new_instance['date']    = ! empty( $new_instance['date'] );
			$new_instance['more']    = ! empty( $new_instance['more'] );
			$new_instance['catname'] = ! empty( $new_instance['catname'] );

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
                <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style box', 'delaware' ) ?></label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
                    <option <?php selected( $instance['style'], 'style1' ); ?>
                            value="<?php esc_attr_e( 'style1', 'delaware' ) ?>"><?php esc_html_e( 'Style 1', 'delaware' ) ?></option>
                    <option <?php selected( $instance['style'], 'style2' ); ?>
                            value="<?php esc_attr_e( 'style2', 'delaware' ) ?>"><?php esc_html_e( 'Style 2', 'delaware' ) ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'delaware' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $instance['title'] ); ?>">
            </p>

            <div style="width: 280px; float: left; margin-right: 20px;">
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" size="2"
                           value="<?php echo intval( $instance['limit'] ); ?>">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number Of Posts', 'delaware' ); ?></label>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Select Category: ', 'delaware' ); ?></label>
                    <select class="widefat" multiple="multiple"
                            id="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"
                            name="<?php echo esc_attr( $this->get_field_name( 'cat' ) ); ?>[]">
                        <option value="" <?php selected( empty( $instance['cat'] ) ); ?>><?php esc_html_e( 'All Categories', 'delaware' ); ?></option>
						<?php
						$categories = get_terms( 'category' );
						foreach ( $categories as $category ) {
							printf(
								'<option value="%s"%s>%s</option>',
								$category->term_id,
								selected( in_array( $category->term_id, (array) $instance['cat'] ) ),
								$category->name
							);
						}
						?>
                    </select>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['thumb'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>"><?php esc_html_e( 'Show Thumbnail', 'delaware' ); ?></label>
                </p>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>"><?php esc_html_e( 'Thumbnail Size', 'delaware' ); ?></label>
                    <select name="<?php echo esc_attr( $this->get_field_name( 'thumb_size' ) ); ?>"
                            id="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>" class="widefat">
						<?php foreach ( $sizes = $this->get_image_sizes() as $name => $size ) : ?>
                            <option value="<?php echo esc_attr( $name ) ?>" <?php selected( $name, $instance['thumb_size'] ) ?>><?php echo ucfirst( $name ) . " ({$size['width']}x{$size['height']})" ?></option>
						<?php endforeach; ?>
                    </select>
                </p>
            </div>

            <div style="width: 280px; float: right;">
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'catname' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'catname' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['catname'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'catname' ) ); ?>"><?php esc_html_e( 'Show Categogy', 'delaware' ); ?></label>
                </p>

                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['date'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Show Date', 'delaware' ); ?></label>
                </p>
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'more' ) ); ?>"
                           name="<?php echo esc_attr( $this->get_field_name( 'more' ) ); ?>" type="checkbox"
                           value="1" <?php checked( $instance['more'] ); ?>>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'more' ) ); ?>"><?php esc_html_e( 'Show button', 'delaware' ); ?></label>
                </p>

            </div>

            <div style="clear: both;"></div>
			<?php
		}

		/**
		 * Get available image sizes with width and height following
		 *
		 * @return array|bool
		 */
		public static function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes       = array();
			$image_sizes = get_intermediate_image_sizes();

			// Create the full array with sizes and crop info
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
					$sizes[ $size ]['width']  = get_option( $size . '_size_w' );
					$sizes[ $size ]['height'] = get_option( $size . '_size_h' );
				} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
					$sizes[ $size ] = array(
						'width'  => $_wp_additional_image_sizes[ $size ]['width'],
						'height' => $_wp_additional_image_sizes[ $size ]['height'],
					);
				}
			}

			return $sizes;
		}
	}
}