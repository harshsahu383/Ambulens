<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Delaware
 */
if ( 'full-content' == delaware_get_layout() ) {
    return;
}

$col = 'col-md-4 mf-widget-col-4';

if ( delaware_is_catalog() || (function_exists('is_product') && is_product()) ) {
	$col = 'col-md-3';
}

$sidebar = 'blog-sidebar';

if( is_page() ) {
	$sidebar = 'page-sidebar';
} elseif ( delaware_is_catalog() || (function_exists('is_product') && is_product()) ) {
	$sidebar = 'shop-sidebar';
} elseif ( delaware_is_service() || is_singular( 'service' ) ) {
	$sidebar = 'service-sidebar';
	$col = 'col-md-3';
}

?>

<aside id="primary-sidebar" class="widgets-area primary-sidebar <?php echo esc_attr( $sidebar ) ?> col-xs-12 col-sm-12 <?php echo esc_attr( $col ) ?>">
    <div class="delaware-widget">
		<?php
		if (is_active_sidebar($sidebar)) {
			dynamic_sidebar($sidebar);
		}
		?>
    </div>
</aside><!-- #secondary -->
