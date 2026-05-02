<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Delaware
 */

get_header();
?>

<div id="primary" class="<?php delaware_content_columns();?> content-area service-content">
		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class() ?>>
				<?php the_content(); ?>
			</div>

		<?php endwhile; ?>

</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

