<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package delaware
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title"><?php echo esc_html__( 'Read Comments', 'delaware' ) ?></h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-above" class="navigation comment-navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'delaware' ); ?></h2>
				<div class="nav-links">

					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'delaware' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'delaware' ) ); ?></div>

				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list clearfix">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'callback'    => 'delaware_comment'
			) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-below" class="navigation comment-navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'delaware' ); ?></h2>
				<div class="nav-links">

					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'delaware' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'delaware' ) ); ?></div>

				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'delaware' ); ?></p>
	<?php endif; ?>

	<?php
	$form_args = array(
			'class_form'      => 'comment-form',
	        // change the title of send button
	        'label_submit'=> esc_html__( 'Submit', 'delaware' ),
	        // change the title of the reply section
	        'title_reply'=> esc_html__( 'Add Your Comments', 'delaware' ),
	        // remove "Text or HTML to be displayed after the set of comment fields"
	        'comment_notes_after' => '',
	        // redefine your own textarea (the comment body)
	        'comment_field' => '<div class="form-group comment-form-comment"><label for="comment">' . esc_html__( 'Your Comments', 'delaware' ) . '</label><br /><textarea id="comment" name="comment" rows="4"  aria-required="true"></textarea></div>',
	);

	comment_form( $form_args );
	?>

</div><!-- #comments -->
