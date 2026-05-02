<?php
/**
 * Custom functions for displaying comments
 *
 * @package Delaware
 */

/**
 * Comment callback function
 *
 * @param object $comment
 * @param array $args
 * @param int $depth
 */
function delaware_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    $avatar_class = get_avatar($comment, 60) ? '' : 'no-avatar';
    ?>

    <li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
     <article id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
        <div class="comment-author vcard">
            <?php echo get_avatar($comment, 60); ?>
        </div>
        <div class="comment-meta commentmetadata clearfix <?php echo esc_attr($avatar_class); ?>">
            <?php printf('<cite class="author-name">%s - </cite>', get_comment_author_link()); ?>
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" class="date">
				<?php
				/* translators: 1: date, 2: time */
				printf( esc_html__( '%s', 'delaware' ), get_comment_date()); ?>:
			</a>
            
                <?php get_comment_meta( 'rating' ); ?>
           
            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'delaware'); ?></em>
            <?php endif; ?>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <?php
            comment_reply_link(array_merge($args, array('add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__('Reply', 'delaware'))));
            edit_comment_link(esc_html__('Edit', 'delaware'), '  ', '');
            ?>
        </div>
        </article>
    </li>
    <?php
}

