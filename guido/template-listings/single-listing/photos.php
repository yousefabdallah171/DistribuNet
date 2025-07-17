<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

if ( $meta_obj->check_post_meta_exist('gallery') && ($gallery = $meta_obj->get_post_meta( 'gallery' )) ) {
?>
    <div id="listing-detail-photo" class="listing-detail-photo">
    	<h4 class="title"><?php echo trim($meta_obj->get_post_meta_title('gallery')); ?></h4>
    	<div class="content-bottom">
	    	<div class="row row-responsive row-photo">
		        <?php $i = 1; foreach ($gallery as $attach_id => $img_url) {
		        	$additional_class = '';
	                if ( $i > 4 ) {
	                    $additional_class = 'd-none';
	                }
	                $more_image_class = $more_image_html = '';
	                if ( $i == 4 && count($gallery) > 4 ) {
	                    $more_image_html = '<span class="view-more-gallery">+'.(count($gallery) - 4).'</span>';
	                    $more_image_class = 'view-more-image';
	                } else {
	                	$more_image_html = '<span class="flaticon-zoom"></span>';
	                }
	        	?>
		            <div class="col-3 item <?php echo esc_attr($additional_class); ?>">
		            	<div class="photo-item">
		            		<a href="<?php echo esc_url($img_url); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="popup-image-gallery <?php echo esc_attr($more_image_class); ?>">
		            			<?php echo guido_get_attachment_thumbnail($attach_id, '170x150'); ?>
		            			<?php echo trim($more_image_html); ?>
		                	</a>
		                </div>
		            </div>
		        <?php $i++; } ?>
	        </div>
        </div>
    </div>
<?php }