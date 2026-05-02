<?php
/**
 * Custom functions for nav menu
 *
 * @package Delaware
 */


/**
 * Display numeric pagination
 *
 * @since 1.0
 * @return void
 */
function delaware_numeric_pagination() {
	global $wp_query;

	if( $wp_query->max_num_pages < 2 ) {
        return;
	}

	$portfolio_nav = delaware_get_option( 'portfolio_pagination_style' );
	$type_nav  = delaware_get_option( 'blog_nav_type' );
	$view_more = apply_filters( 'delaware_portfolio_view_more_text', esc_html__( 'LOAD MORE', 'delaware' ) );
	$css = '';

	$next_html = sprintf(
		'<span id="delaware-portfolio-previous-ajax" class="nav-previous-ajax">
			<span class="nav-text">%s</span>
			<span class="loading-icon">
				<span class="bubble">
					<span class="dot"></span>
				</span>
				<span class="bubble">
					<span class="dot"></span>
				</span>
				<span class="bubble">
					<span class="dot"></span>
				</span>
			</span>
		</span>',
		$view_more
	);

	if ( delaware_is_blog() && $type_nav == 'ajax' ) {
		$css .= ' blog-nav-ajax';
	} elseif ( delaware_is_portfolio() && $portfolio_nav == 'ajax' ) {
		$css .= ' portfolio-nav-ajax';
	}

	?>
	<nav class="navigation paging-navigation numeric-navigation <?php echo esc_attr( $css ); ?>">
		<?php
		$big = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
			'next_text' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
			'type'      => 'plain',
		);

		if (
			( delaware_is_blog() && $type_nav == 'ajax' ) ||
			( delaware_is_portfolio() && $portfolio_nav == 'ajax' )
		) {
			$args['prev_text'] = '';
			$args['next_text'] = $next_html;
		}

		echo paginate_links( $args );
		?>
	</nav>
<?php
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0
 * @return void
 */
function delaware_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( wp_kses_post( esc_html__( '<span class="meta-nav">&larr;</span> Older posts', 'delaware' ) ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( wp_kses_post( esc_html__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'delaware' ) ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}


/**
 * Display navigation to next/previous post when applicable.
 *
 * @since 1.0
 * @return void
 */
function delaware_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation">
		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">' . esc_html__( "&laquo; Previous Post", "delaware" ) . '</span><h4 class="nav-title">%title</h4>', 'Previous post link', 'delaware' ) );
			next_post_link( '<div class="nav-next">%link</div>', _x( '<span class="meta-nav">' . esc_html__( "Next Topic &raquo;", "delaware" ) . '</span><h4 class="nav-title">%title</h4>', 'Next post link', 'delaware' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}