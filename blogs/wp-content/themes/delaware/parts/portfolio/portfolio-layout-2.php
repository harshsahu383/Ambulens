<?php
/**
 * @package Delaware
 */
?>

<div class="entry-header">

	<?php if ( has_post_thumbnail() ) : ?>

		<a href="<?php the_permalink(); ?>" class="entry-thumbnail">
			<?php the_post_thumbnail( 'delaware-portfolio-grid-2' ); ?>
		</a>

	<?php endif; ?>


	<div class="entry-content">
		<div class="entry-meta">
			<?php echo delaware_portfolio_category(); ?>
		</div>
		<?php delaware_pf_title(); ?>
	</div>

</div><!-- .entry-header -->
<div class="entry-summary">
	<?php delaware_pf_descr(); ?>
	<div class="read-more">
		<a href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'delaware' ); ?>
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
</div><!-- .entry-content -->

