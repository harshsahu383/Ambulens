<?php
if ( ! function_exists( 'delaware_modify_user_contact_methods' ) ) :
	function delaware_modify_user_contact_methods( $user_contact ) {

		// Add user contact methods
		$user_contact['facebook'] = esc_html__( 'Facebook Username' );
		$user_contact['twitter']  = esc_html__( 'Twitter Username' );
		$user_contact['google']   = esc_html__( 'Google Username' );
		$user_contact['youtube']  = esc_html__( 'Youtube Username' );

		return $user_contact;
	}
endif;

add_filter( 'user_contactmethods', 'delaware_modify_user_contact_methods' );

/**
 * Hooks for share socials
 *
 * @package Delaware
 */

if ( ! function_exists( 'delaware_addons_share_link_socials' ) ) :
	function delaware_addons_share_link_socials( $title, $link, $media ) {
		$socials = array();
		if ( is_singular( 'post' ) ) {
			$socials = delaware_get_option( 'post_socials_share' );
		}

		//		if ( is_singular('post') ) {
		//			$socials = delaware_get_option( 'post_socials_share' );
		//		} elseif ( is_singular('portfolio') ) {
		//			$socials = delaware_get_option( 'single_portfolio_socials_share' );
		//		} elseif ( is_product() ) {
		//			$socials = delaware_get_option( 'single_product_socials_share' );
		//		}

		$socials_html = '';
		if ( $socials ) {
			if ( in_array( 'facebook', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-facebook delaware-facebook" title="%s" href="http://www.facebook.com/sharer.php?u=%s&t=%s" target="_blank"><i class="fa fa-facebook"></i></a></li>',
					esc_attr( $title ),
					urlencode( $link ),
					urlencode( $title )
				);
			}

			if ( in_array( 'twitter', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-twitter delaware-twitter" href="http://twitter.com/share?text=%s&url=%s" title="%s" target="_blank"><i class="fa fa-twitter"></i></a></li>',
					esc_attr( $title ),
					urlencode( $link ),
					urlencode( $title )
				);
			}

			if ( in_array( 'pinterest', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-pinterest delaware-pinterest" href="http://pinterest.com/pin/create/button?media=%s&url=%s&description=%s" title="%s" target="_blank"><i class="fa fa-pinterest"></i></a></li>',
					urlencode( $media ),
					urlencode( $link ),
					esc_attr( $title ),
					urlencode( $title )
				);
			}

			if ( in_array( 'google', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-google-plus delaware-google-plus" href="https://plus.google.com/share?url=%s&text=%s" title="%s" target="_blank"><i class="fa fa-google-plus"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title ),
					urlencode( $title )
				);
			}

			if ( in_array( 'linkedin', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-linkedin delaware-linkedin" href="http://www.linkedin.com/shareArticle?url=%s&title=%s" title="%s" target="_blank"><i class="fa fa-linkedin"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title ),
					urlencode( $title )
				);
			}

			if ( in_array( 'tumblr', $socials ) ) {
				$socials_html .= sprintf(
					'<li><a class="share-tumblr delaware-tumblr" href="http://www.tumblr.com/share/link?url=%s" title="%s" target="_blank"><i class="fa fa-tumblr"></i></a></li>',
					urlencode( $link ),
					esc_attr( $title )
				);
			}
		}

		if ( $socials_html ) {
			return sprintf( '<ul class="social-share socials-inline">%s</ul>', $socials_html );
		}
		?>
		<?php
	}

endif;

