<?php
/**
 * @package Delaware
 */

global $columns_service_vc;

$columns = intval( delaware_get_option( 'service_columns' ));

if( isset($columns_service_vc) ) {
    $columns = intval($columns_service_vc);
}

$css = 'service-wrapper col-' . $columns . ' col-flex-sm-6 col-flex-xs-6 col-flex-md-' . 12 / $columns;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $css ); ?>>
	<div class="entry-header">
		<a href="<?php the_permalink(); ?>" class="entry-thumbnail">
			<?php the_post_thumbnail( 'delaware-service-grid-thumb' ); ?>
			<span class="svg-icon icon-link">
				<svg viewBox="0 0 20 20">
					<use xlink:href="#link"></use>
				</svg>
			</span>
		</a>
	</div>
	<!-- .entry-header -->

	<div class="entry-content">
		<?php delaware_pf_title(); ?>
		<div class="descr text-center">
			<?php
			$content = get_the_excerpt();
			$number  = delaware_get_option( 'service_excerpt_descr' );
			echo delaware_content_limit( $content, $number, false );
			?>
		</div>
		<div class="read-more">
			<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'delaware' ); ?>
				<span class="svg-icon icon-next">
					<svg viewBox="0 0 20 20">
						<use xlink:href="#next"></use>
					</svg>
				</span>
			</a>
		</div>
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'delaware' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
	<!-- .entry-content -->
</article><!-- #post-## -->