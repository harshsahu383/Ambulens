<?php
/**
 * Hooks for template archive
 *
 * @package Delaware
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function delaware_setup_author()
{
    global $wp_query;

    if ($wp_query->is_author() && isset($wp_query->post)) {
        $GLOBALS['authordata'] = get_userdata($wp_query->post->post_author);
    }
}

add_action('wp', 'delaware_setup_author');

/**
 * Add CSS classes to posts
 *
 * @param array $classes
 *
 * @return array
 */
function delaware_post_class($classes)
{

    $classes[] = has_post_thumbnail() ? '' : 'no-thumb';

    return $classes;
}

add_filter('post_class', 'delaware_post_class');

/**
 * Add icon list as svg at the footer
 * It is hidden
 */
function delaware_include_shadow_icons()
{
    echo '<div id="del-svg-defs" class="del-svg-defs hidden">';
    include get_template_directory() . '/img/sprite.svg';
    echo '</div>';
}

add_action('delaware_before_site', 'delaware_include_shadow_icons');

