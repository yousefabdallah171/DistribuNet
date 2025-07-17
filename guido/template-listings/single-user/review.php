<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract($args);

$rating = get_comment_meta( $comment->comment_ID, '_rating_avg', true );

?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="the-comment">
		<div class="avatar">
			<?php echo guido_get_avatar( $comment->user_id, '70', '' ); ?>
		</div>
		<div class="comment-box">
			
			<div class="clearfix">
				<div class="name-comment">
					<?php comment_author(); ?>
				</div>
				<div class="d-flex align-items-center">
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<span class="date"><em><?php esc_html_e( 'Your comment is awaiting approval', 'guido' ); ?></em></span>
					<?php else : ?>
						<span class="date">
							<?php echo get_comment_date( get_option('date_format', 'd M, Y') ); ?>
						</span>
					<?php endif; ?>
					<div class="ms-auto">
						<div class="star-rating" title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'guido' ), $rating ) ?>">
							<?php echo WP_Listings_Directory_Review::print_review($rating); ?>
							<span class="review-avg"><?php echo number_format((float)$rating, 1, '.', ''); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="comment-text">
				<?php comment_text(); ?>

				<?php WP_Listings_Directory_Review_Image::display_review_images(); ?>
			</div>
		</div>
	</div>