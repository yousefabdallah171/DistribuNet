<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

if ( $meta_obj->check_post_meta_exist('video') && ($video = $meta_obj->get_post_meta( 'video' )) ) {
?>
    <div id="listing-detail-video" class="listing-detail-video widget">
    	<h4 class="title"><?php echo trim($meta_obj->get_post_meta_title('video')); ?></h4>
    	<div class="content-bottom embed-responsive embed-responsive-16by9">
	    	
	    	<?php
				if ( strpos($video, 'www.aparat.com') !== false ) {
				    $path = parse_url($video, PHP_URL_PATH);
					$matches = preg_split("/\/v\//", $path);
					
					if ( !empty($matches[1]) ) {
					    $output = '<iframe src="http://www.aparat.com/video/video/embed/videohash/'. $matches[1] . '/vt/frame"
					                allowFullScreen="true"
					                webkitallowfullscreen="true"
					                mozallowfullscreen="true"
					                height="720"
					                width="1280" >
					                </iframe>';

					    echo trim($output);
					}
			   	} else {
					echo apply_filters( 'the_content', '[embed width="1280" height="720"]' . esc_attr( $video ) . '[/embed]' );
				}
			?>
        </div>
    </div>
<?php }