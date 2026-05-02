<?php
/**
 * @package Delaware
 */

$css = 'portfolio-wrapper';
$columns = delaware_get_option( 'delaware_portfolio_columns' );

if ( delaware_get_option( 'delaware_portfolio_layout' ) == 'grid' ) {
	$css .= ' columns-' . intval( $columns ) . ' col-sm-6 col-xs-6 col-md-' . 12 / intval( $columns );

} else {
	$css .= ' masonry col-md-4 col-sm-6 col-xs-6';
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $css ); ?>>
	<div class="portfolio-inner">
		<?php
		$layout = 1;

		if ( delaware_get_option( 'delaware_portfolio_layout' ) == 'grid' && $columns == 3 ) {
			$layout = 2;
		}

		get_template_part( 'parts/portfolio/portfolio-layout', $layout );
		?>
	</div>
</article><!-- #post-## -->