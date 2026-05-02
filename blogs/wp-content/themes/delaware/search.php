<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Delaware
 */

get_header(); ?>

	<section id="primary" class="content-area <?php delaware_content_columns(); ?>">
		<main id="main" class="site-main row-flex">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'parts/content', 'search' );
				?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'parts/content', 'none' ); ?>

		<?php endif; ?>
		<?php delaware_numeric_pagination(); ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
