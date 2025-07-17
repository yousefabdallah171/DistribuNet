<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

$latitude = $meta_obj->get_post_meta( 'map_location_latitude' );
$longitude = $meta_obj->get_post_meta( 'map_location_longitude' );

?>
<?php if ( !empty($latitude) && !empty($longitude) ) : ?>
	<div id="listing-detail-location" class="listing-detail-map-street">
		<h3 class="title"><?php esc_html_e('Location', 'guido'); ?></h3>

    	<div class="single-listing-google-maps-wrapper">
		    <div id="single-listing-google-maps" class="single-listing-map"></div>
		</div>
		<div class="flex-middle-sm">
			<div class="ali-left">
    			<?php guido_listing_display_full_location($post, false, true); ?>
    		</div>
    		<div class="ali-right">
    			<?php guido_listing_display_location_btn($post); ?>
    		</div>
		</div>
	</div>
<?php endif; ?>