<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Delaware
 */

get_header(); ?>

<div id="primary" class="content-area <?php delaware_content_columns(); ?>">
    <main id="main" class="site-main">

        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( '404', 'delaware' ); ?></h1>
                <p class="line-1"><?php esc_html_e( 'OOPPS! THE PAGE YOU WERE LOOKING FOR, COULDN\'T BE FOUND.', 'delaware' ); ?></p>
                <p class="line-2"><?php esc_html_e( 'Try the search below to find matching pages:', 'delaware' ); ?></p>
            </header><!-- .page-header -->

            <div class="page-content">
                <form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'delaware' ) ?></span>
                        <input type="search" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>"
                               placeholder="<?php esc_attr_e( 'Search...', 'delaware' ) ?>" name="s">
                    </label>
                    <input type="submit" class="search-submit" value="<?php echo esc_attr( 'Search', 'delaware' ) ?>">
                </form>

                <div class="back-home">
                    <a href="<?php echo esc_url( home_url( '/' ) ) ?>"><?php esc_html_e( 'Back to Home Page', 'delaware' ) ?></a>
                </div>

            </div><!-- .page-content -->
        </section><!-- .error-404 -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
