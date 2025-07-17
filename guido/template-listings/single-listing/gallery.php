<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

$gallery = $meta_obj->get_post_meta( 'gallery' );
if ( has_post_thumbnail() || $gallery ) {
    $gallery_size = !empty($gallery_size) ? $gallery_size : 'guido-gallery-large';
?>
<div class="listing-detail-gallery listing-detail-gallery-v1 clearfix">
    
    <div class="listing-single-gallery-wrapper">
        <?php guido_listing_display_featured_icon($post, true); ?>
        
        <div class="slick-carousel no-gap listing-single-gallery" data-carousel="slick" data-items="1" data-large="1" data-medium="1" data-small="1" data-smallest="1" data-pagination="false" data-nav="true">
            <?php if ( has_post_thumbnail() ) {
                $thumbnail_id = get_post_thumbnail_id($post);
            ?>
                <a href="<?php echo esc_url( get_the_post_thumbnail_url($post, 'full') ); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="p-popup-image">
                    <?php echo guido_get_attachment_thumbnail($thumbnail_id, $gallery_size);?>
                </a>
            <?php } ?>

            <?php if ( $gallery ) {
                foreach ( $gallery as $id => $src ) {
                ?>
                    <a href="<?php echo esc_url( $src ); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="p-popup-image">
                        <?php echo guido_get_attachment_thumbnail( $id, $gallery_size ); ?>
                    </a>
                <?php }
            } ?>
        </div>
    </div>
</div>
<?php }