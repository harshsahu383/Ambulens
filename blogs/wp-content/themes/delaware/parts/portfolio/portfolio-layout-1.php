<?php
/**
 * @package Delaware
 */
$size = 'delaware-portfolio-grid-3';

if ( delaware_get_option( 'delaware_portfolio_layout' ) == 'masonry' ) {
	$size = 'delaware-masonry-thumb';
}

?>


<div class="entry-header">

	<?php if ( has_post_thumbnail() ) : ?>

	<a href="<?php the_permalink(); ?>" class="entry-thumbnail">
		<?php the_post_thumbnail( $size ); ?>
	</a>

	<?php endif; ?>

    <?php if ( delaware_get_option( 'delaware_portfolio_layout' ) == 'grid' ): ?>
    <a href="<?php the_permalink(); ?>" class="btn-plus"><svg viewBox="0 0 20 20"><use xlink:href="#plus-zoom"></use></svg></a>
    <?php endif; ?>

    <div class="entry-summary hover-ct">
		<?php
		if ( delaware_get_option( 'delaware_portfolio_layout' ) == 'grid' ) :
			delaware_pf_descr();
		endif;
		?>
		<div class="read-more">
			<a href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'Read more', 'delaware' ); ?>
				<span class="svg-icon icon-next"><svg viewBox="0 0 20 20"><use xlink:href="#next"></use></svg></span>
			</a>
		</div>
	</div>

</div><!-- .entry-header -->
<div class="entry-content">
    <div class="entry-meta">
		<?php echo delaware_portfolio_category(); ?>
    </div>

	<?php delaware_pf_title(); ?>

	<?php
	wp_link_pages( array(
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'delaware' ),
		'after'  => '</div>',
	) );
	?>
</div>