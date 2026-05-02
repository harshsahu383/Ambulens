<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Delaware
 */

get_header(); ?>

<div id="primary" class="content-area <?php delaware_content_columns(); ?>">
	<main id="main" class="site-main">
		<?php do_action( 'delaware_portfolio_before_content' ); ?>

		<div id="delaware_portfolio_grid" class="row">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>


				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'parts/content-portfolio', get_post_format() );
					?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'parts/content', 'none' ); ?>

			<?php endif; ?>
		</div>

		<?php delaware_numeric_pagination(); ?>
	</main>
	<!-- #main -->

</div><!-- #primary -->

<?php get_footer(); ?>
