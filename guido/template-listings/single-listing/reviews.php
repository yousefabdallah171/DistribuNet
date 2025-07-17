<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;

if ( ! comments_open() ) {
	return;
}

?>
<?php if ( have_comments() ) : ?>
	<div id="comments">
		<div class="review-title-wrapper">
			<?php if ( WP_Listings_Directory_Review::review_enable() ) { ?>
				<h3 class="comments-title">
					<?php
						comments_number( esc_html__('0 Reviews', 'guido'), esc_html__('1 Review', 'guido'), esc_html__('% Reviews', 'guido') );
					?>
				</h3>
			<?php } else { ?>
				<h3 class="comments-title">
					<?php comments_number( esc_html__('0 Comments', 'guido'), esc_html__('1 Comment', 'guido'), esc_html__('% Comments', 'guido') ); ?>
				</h3>
			<?php } ?>
		</div>
		
		<?php if ( WP_Listings_Directory_Review::review_enable() ) {
			$reviews = get_post_meta( $post->ID, '_average_ratings', true );
			$categories = wp_listings_directory_get_option('listing_review_category');
			if ( !empty($categories) ) { ?>
				<ul class="list-category-rating list clearfix">
		    		<?php foreach ($categories as $category) {
			            $rate = isset($reviews[$category['key']]) ? $reviews[$category['key']] : 0;
			            ?>
			            <li class="rating-inner d-flex align-items-center">
			            	<div class="category-label">
			            		<?php echo !empty($category['name']) ? $category['name'] : ''; ?>
			            	</div>
			            	<div class="ms-auto d-flex align-items-center category-value">
				                <div class="percent-wrapper progress">
				                	<div class="percent progress-bar" style="<?php echo esc_attr( 'width: ' . ( $rate * 20 ) . '%' ) ?>"></div>
				                </div>
				                <div class="value">
				                	<?php echo number_format(round($rate, 2),1,'.',''); ?>
				                </div>
			                </div>
			            </li>
			        <?php } ?>
		        </ul>
	    	<?php } ?>
		<?php } ?>

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
		
	</div>
<?php endif; ?>

<div id="reviews">

	<?php $commenter = wp_get_current_commenter(); ?>
	<div id="review_form_wrapper">
		<div id="review_form">
			<?php
				$comment_form = array(
					'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'guido' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'guido' ), get_the_title() ),
					'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'guido' ),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<div class="row row-20"><div class="col-12 col-sm-6"><div class="form-group">'.
						            '<input id="author" class="form-control" name="author" placeholder="'.esc_attr__( 'Name', 'guido' ).'" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div></div>',
						'email'  => '<div class="col-12 col-sm-6"><div class="form-group">' .
						            '<input id="email" class="form-control" name="email" placeholder="'.esc_attr__( 'Email', 'guido' ).'" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div></div></div>',
					),
					'label_submit'  => esc_html__( 'Submit Review', 'guido' ),
					'logged_in_as'  => '',
					'comment_field' => '',
					'title_reply_before' => '<h4 class="title comment-reply-title">',
					'title_reply_after'  => '</h4>',
					'class_submit' => 'btn btn-theme'
				);

				$comment_form['must_log_in'] = '<div class="must-log-in">' . wp_kses(__( 'You must be <a href="javascript:void(0)">logged in</a> to post a review.', 'guido' ), array('a' => array('class' => array(), 'href' => array())) ) . '</div>';
				
				$comment_form['comment_field'] .= '<div class="form-group space-comment"><textarea id="comment" placeholder="'.esc_attr__( 'Review', 'guido' ).'" class="form-control" name="comment" cols="45" rows="5" aria-required="true" required></textarea></div>';
				
				$comment_form['comment_field'] .= WP_Listings_Directory_Review_Image::display_upload_field();
				
				guido_comment_form($comment_form);
			?>
		</div>
	</div>
</div>