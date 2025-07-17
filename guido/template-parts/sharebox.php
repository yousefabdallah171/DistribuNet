<?php
global $post;
wp_enqueue_script('addthis');
?>
<div class="apus-social-share">
	<span class="share-action d-flex align-items-center">
		<span class="d-flex justify-content-center align-items-center share-icon"><i class="flaticon-upload"></i></span><?php esc_html_e('Share Post', 'guido'); ?>
	</span>
	<div class="bo-social-icons">
		
		<?php if ( guido_get_config('facebook_share', 1) ): ?>
 
			<a class="facebook d-flex align-items-center" href="https://www.facebook.com/sharer.php?s=100&u=<?php the_permalink(); ?>&i=<?php echo urlencode($img); ?>" target="_blank" title="<?php echo esc_attr__('Share on facebook', 'guido'); ?>">
				<?php echo esc_html__('Facebook', 'guido'); ?>
			</a>
 
		<?php endif; ?>
		<?php if ( guido_get_config('twitter_share', 1) ): ?>
			<a class="twitter d-flex align-items-center" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>" target="_blank" title="<?php echo esc_attr__('Share on Twitter', 'guido'); ?>">
				<?php echo esc_html__('Twitter', 'guido'); ?>
			</a>
 
		<?php endif; ?>
		<?php if ( guido_get_config('linkedin_share', 1) ): ?>
 
			<a class="linkedin d-flex align-items-center" href="https://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" target="_blank" title="<?php echo esc_attr__('Share on LinkedIn', 'guido'); ?>">
				<?php echo esc_html__('LinkedIn', 'guido'); ?>
			</a>
 
		<?php endif; ?>

		<?php if ( guido_get_config('pinterest_share', 1) ): ?>
 
			<a class="pinterest d-flex align-items-center" href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;media=<?php echo urlencode($img); ?>" target="_blank" title="<?php echo esc_attr__('Share on Pinterest', 'guido'); ?>">
				<?php echo esc_html__('Pinterest', 'guido'); ?>
			</a>
 
		<?php endif; ?>
	</div>
</div>