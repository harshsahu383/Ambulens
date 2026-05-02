<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Delaware
 */

get_header();

$blog_view = delaware_get_option( 'blog_view' );
$row = 'row-flex';

if ( 'masonry' == $blog_view ) {
	$row = 'row';
}

?>

<div id="primary" class="content-area <?php delaware_content_columns(); ?>">
	<main id="main" class="site-main">
		<div id="masonry_blog_list" class="blog-list <?php echo esc_attr( $row ) ?>">


			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'parts/content', get_post_format() );
					?>

				<?php endwhile; ?>



			<?php else : ?>

				<?php get_template_part( 'parts/content', 'none' ); ?>

			<?php endif; ?>

		</div>
		<?php delaware_numeric_pagination(); ?>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
