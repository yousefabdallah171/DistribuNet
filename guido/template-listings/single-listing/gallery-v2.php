<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

$gallery = $meta_obj->get_post_meta( 'gallery' );
if ( has_post_thumbnail() || $gallery ) {
    $gallery_size = !empty($gallery_size) ? $gallery_size : 'guido-gallery-medium';
?>
<div class="listing-detail-gallery">
    
    <div class="listing-single-gallery-wrapper v2">
        <?php guido_listing_display_featured_icon($post, true); ?>
        
        <div class="slick-carousel gap-2" data-carousel="slick" data-items="3" data-large="2" data-medium="2" data-small="1" data-smallest="1" data-pagination="false" data-nav="true" data-slickparent="true" data-infinite="true">
            <?php if ( has_post_thumbnail() ) {
                $thumbnail_id = get_post_thumbnail_id($post);
            ?>
            <div class="item">
                <a href="<?php echo esc_url( get_the_post_thumbnail_url($post, 'full') ); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="p-popup-image">
                    <?php echo guido_get_attachment_thumbnail($thumbnail_id, $gallery_size);?>
                </a>
            </div>
            <?php } ?>

            <?php if ( $gallery ) {
                foreach ( $gallery as $id => $src ) {
                ?>
                    <div class="item">
                        <a href="<?php echo esc_url( $src ); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="p-popup-image">
                            <?php echo guido_get_attachment_thumbnail( $id, $gallery_size ); ?>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>
    </div>

</div>
<?php }