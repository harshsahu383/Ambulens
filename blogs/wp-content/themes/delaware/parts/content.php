<?php
/**
 * @package Delaware
 */
global $mf_post;

$size = 'delaware-blog-thumb';
$columns = intval( delaware_get_option( 'blog_grid_columns' ) );
$blog_view = delaware_get_option( 'blog_view' );

$css_class = 'dl-blog-wrapper';

if ( 'grid' == $blog_view ) {
	$size = 'delaware-blog-grid-thumb';
	$css_class .= ' blog-wrapper-col-' . $columns . ' col-flex-sm-6 col-flex-xs-6 col-flex-md-' . 12 / $columns;

} elseif ( 'masonry' == $blog_view ) {
	$size = 'delaware-blog-masonry-thumb';
	$css_class .= ' blog-wrapper-col-3 blog-wrapper-masonry col-md-4 col-sm-6 col-xs-6';

} elseif ( 'classic' == $blog_view ) {
	$css_class .= ' col-flex-xs-12';
}

if ( isset($mf_post['css']) ) {
	$css_class .= $mf_post['css'];
}

if ( isset($mf_post['size']) ) {
	$size = $mf_post['size'];
}

$count_text = intval( delaware_get_option('excerpt_length_blog') );
if ($count_text <= 0 ){
	$css_class .= ' no-content';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
    <div class="blog-wrapper">
        <header class="entry-header">
			<?php if ( has_post_thumbnail() ) : ?>
                <div class="entry-thumbnail">
                    <a href="<?php the_permalink() ?>" class="thumb">
						<?php the_post_thumbnail( $size ); ?>
						<span class="dl-icon-link svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#link"></use></svg></span>
					</a>
                </div>
			<?php endif; ?>
	        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php if ( 'post' == get_post_type() ) : ?>
                <div class="entry-meta">
					<?php delaware_entry_meta(); ?>
                </div><!-- .entry-meta -->
			<?php endif; ?>

        </header><!-- .entry-header -->

	    <?php delaware_post_entry_content(); ?>
        <?php if ($blog_view=="classic") : ?>
            <a href="<?php echo esc_url(get_permalink()) ?>" class="read-more"><?php echo esc_html__('Read More','delaware') ?></a>
        <?php endif;?>
    </div>
</article><!-- #post-## -->
