<?php
/**
 * @package Delaware
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-header">
        <div class="entry-thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), 'full' ); ?></div>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <div class="entry-meta">
			<?php delaware_entry_meta( true ); ?>
        </div>
    </div>
    <div class="entry-content">
		<?php the_content(); ?>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'delaware' ),
			'after'  => '</div>',
		) );
		?>
    </div><!-- .entry-content -->

	<?php if ( intval( delaware_get_option( 'show_post_social_share' ) ) ) : ?>
        <div class="mf-single-post-socials-share">
			<?php if ( function_exists( 'delaware_addons_share_link_socials' ) ) : ?>
                <span class="share-icon svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#share"></use></svg></span>
                <h4><?php esc_html_e( 'Share', 'delaware' ); ?></h4>
				<?php echo delaware_addons_share_link_socials( get_the_title(), get_the_permalink(), get_the_post_thumbnail() ); ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>

	<?php delaware_entry_footer(); ?>
</article><!-- #post-## -->
