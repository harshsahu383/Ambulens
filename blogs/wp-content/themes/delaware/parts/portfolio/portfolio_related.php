<?php
/**
 * The template part for displaying related posts
 *
 * @package Delaware
 */

// Only support portfolio
if ( 'portfolio' != get_post_type() ) {
	return;
}

if ( ! intval( delaware_get_option( 'portfolio_related' ) ) ) {
	return;
}

$css = 'portfolio-wrapper columns-3 col-sm-6 col-xs-12 col-md-4';

$args = array(
	'post_type'              => 'portfolio',
	'posts_per_page'         => intval( delaware_get_option( 'portfolio_related_item' ) ),
	'post__not_in'           => array( get_the_ID() ),
	'no_found_rows'          => true,
	'category__in'           => wp_get_post_categories( get_the_ID() ),
	'update_post_term_cache' => false,
	'update_post_meta_cache' => false,
);

$related = new wp_query( $args );

if ( ! $related->have_posts() ) {
	return;
}

?>
	<div class="delaware-related-portfolio">
		<div class="related-section-title">
			<?php
			if ( $titles = delaware_get_option( 'portfolio_related_title' ) ) {

				echo sprintf( '<h2 class="related-title">%s</h2>', $titles );
			}
			?>
		</div>
		<div class="list-portfolio row">
			<?php while ( $related->have_posts() ) : $related->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( $css ); ?>>
					<div class="portfolio-inner">
						<?php get_template_part( 'parts/portfolio/portfolio-layout-2' ); ?>
					</div>
				</article>

			<?php endwhile; ?>
		</div>
	</div>

<?php
wp_reset_postdata();
