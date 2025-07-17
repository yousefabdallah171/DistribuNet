<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
?>
<div id="listing-detail-description" class="description inner">
	
    <h3 class="title"><?php echo trim($meta_obj->get_post_meta_title('description')); ?></h3>
    <div class="description-inner">
    	<div class="description-inner-wrapper">
        	<?php the_content(); ?>
        </div>
        <?php if ( guido_get_config('show_listing_desc_view_more', true) ) { ?>
	        <div class="show-more-less-wrapper">
	        	<a href="javascript:void(0);" class="show-more text-hover-link"><?php esc_html_e('Show more', 'guido'); ?></a>
	        	<a href="javascript:void(0);" class="show-less text-hover-link"><?php esc_html_e('Show less', 'guido'); ?></a>
	        </div>
	    <?php } ?>
        <?php do_action('wp-listings-directory-single-listing-description', $post); ?>
    </div>
</div>