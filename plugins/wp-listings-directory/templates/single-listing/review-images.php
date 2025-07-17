<?php

$attachments = get_comment_meta(get_comment_ID(), 'attachments', TRUE);

if ( !empty($attachments) && is_array($attachments) ) {
	?>
    <div id="comment-attactments-<?php echo esc_attr(get_comment_ID()); ?>" class="comment-attactments row">
	<?php
	$count = 1;
	$total = count($attachments);
	foreach ($attachments as $attachmentId) {
		$attachmentLink = wp_get_attachment_url($attachmentId);
		$img = wp_get_attachment_image_src($attachmentId, 'thumbnail');
		if ( isset($img[0]) && $img[0] ) {
			$img_src = $img[0];
			?>
			<div class="col-sm-3 col-xs-6 attachment <?php echo esc_attr($count > 4 ? 'hidden' : ''); ?>">
				<div class="p-relative">
					<a href="<?php echo esc_url($attachmentLink); ?>" data-elementor-lightbox-slideshow="guido-comment-gallery-<?php echo esc_attr(get_comment_ID()); ?>">
						<img src="<?php echo esc_url($img[0]); ?>">
					</a>
					<?php if ( $count == 4 && $total > 4 ) { ?><span class="show-more-images">+<?php echo trim($total - 4); ?></span><?php } ?>
				</div>
			</div>
			<?php
		}
		$count++;
	}
	?>
    </div>
    <?php
}