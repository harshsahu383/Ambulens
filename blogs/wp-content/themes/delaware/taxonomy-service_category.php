<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Delaware
 */

get_header(); ?>

<div id="primary" class="content-area content-single-service <?php delaware_content_columns(); ?>">
	<main id="main" class="site-main">

		<div class="row-flex">
			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'parts/content-service', get_post_format() );
					?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'parts/content', 'none' ); ?>

			<?php endif; ?>
		</div>

	</main>
	<!-- #main -->

	<?php delaware_numeric_pagination(); ?>
</div><!-- #primary -->

<?php get_footer(); ?>
