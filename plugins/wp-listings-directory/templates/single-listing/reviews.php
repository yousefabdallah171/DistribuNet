<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews">
	
	<div id="comments">
		<?php if ( have_comments() ) : ?>

			<ol class="comment-list">
				<?php wp_list_comments( array( 'callback' => array( 'WP_Listings_Directory_Review', 'listing_comments' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="apus-pagination">';
				paginate_comments_links( apply_filters( 'wp_listings_directory_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="apus-noreviews text-danger"><?php esc_html_e( 'There are no reviews yet.', 'wp-listings-directory' ); ?></p>

		<?php endif; ?>
	</div>
	<?php $commenter = wp_get_current_commenter(); ?>
	<div id="review_form_wrapper" class="commentform">
		<div id="review_form">
			<?php
				$comment_form = array(
					'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'wp-listings-directory' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'wp-listings-directory' ), get_the_title() ),
					'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'wp-listings-directory' ),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<div class="row"><div class="col-xs-12 col-sm-12"><div class="form-group"><label>'.esc_html__( 'Name', 'wp-listings-directory' ).'</label>'.
						            '<input id="author" placeholder="'.esc_attr__( 'Your Name', 'wp-listings-directory' ).'" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
						'email'  => '<div class="col-xs-12 col-sm-12"><div class="form-group"><label>'.esc_html__( 'Email', 'wp-listings-directory' ).'</label>' .
						            '<input id="email" placeholder="'.esc_attr__( 'your@mail.com', 'wp-listings-directory' ).'" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div></div>',
					),
					'label_submit'  => esc_html__( 'Submit Review', 'wp-listings-directory' ),
					'logged_in_as'  => '',
					'comment_field' => ''
				);

				$comment_form['must_log_in'] = '<div class="must-log-in">' .  __( 'You must be <a href="">logged in</a> to post a review.', 'wp-listings-directory' ) . '</div>';
				
				$comment_form['comment_field'] .= '<div class="form-group"><label>'.esc_html__( 'Review', 'wp-listings-directory' ).'</label><textarea id="comment" class="form-control" placeholder="'.esc_attr__( 'Write Comment', 'wp-listings-directory' ).'" name="comment" cols="45" rows="5" aria-required="true"></textarea></div>';
				
				comment_form($comment_form);
			?>
		</div>
	</div>
</div>