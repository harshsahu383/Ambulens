<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Delaware
 */

get_header();
?>

	<div id="primary" class="<?php delaware_content_columns(); ?> content-area content-single-portfolio">
		<?php while ( have_posts() ) : the_post(); ?>


			<div id="post-<?php the_ID(); ?>" <?php post_class() ?>>
				<?php the_content(); ?>
			</div>


			<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; ?>

		<?php get_template_part( 'parts/portfolio/portfolio_related' ); ?>

	</div><!-- #primary -->

<?php get_footer(); ?>