<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

$gallery = $meta_obj->get_post_meta( 'gallery' );
if ( has_post_thumbnail() || $gallery ) {
    $gallery_size = !empty($gallery_size) ? $gallery_size : 'guido-gallery-xl';
    $gallery_second_size = !empty($gallery_second_size) ? $gallery_second_size : 'thumbnail';

    $first_class = 'col-12 ';
    $second_class = 'col-6 ';
    if ( $gallery ) {
        $first_class = 'col-md-8 c1 col-12';
        if ( count($gallery) == 1 ) {
            $second_class = 'col-12';
            $gallery_second_size = '350x530';
        } elseif ( count($gallery) == 2 ) {
            $second_class = 'col-12';
            $gallery_second_size = '350x260';
        }
    }
?>
<div class="listing-detail-gallery">
    <div class="row row-10 list-gallery-listing-v3">
        <?php if ( has_post_thumbnail() ) {
            $thumbnail_id = get_post_thumbnail_id($post);
        ?>
            <div class="<?php echo esc_attr($first_class); ?>">
                <div class="listing-single-gallery-wrapper">
                    <a href="<?php echo esc_url( get_the_post_thumbnail_url($post, 'full') ); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="popup-image-gallery">
                        <?php echo guido_get_attachment_thumbnail($thumbnail_id, $gallery_size);?>
                    </a>

                    <?php guido_listing_display_featured_icon($post, true); ?>
                    
                </div>
            </div>
        <?php } ?>

        <?php if ( $gallery ) { ?>
            <div class="col-md-4 c2 col-12">
                <div class="row row-10">
                    <?php $i=1; foreach ( $gallery as $id => $src ) {
                        
                        $additional_class = '';
                        if ( $i > 6 ) {
                            $additional_class = 'd-none';
                        }

                        $more_image_class = $more_image_html = '';
                        if ( $i == 6 && count($gallery) > 6 ) {
                            $more_image_html = '<span class="view-more-gallery">+'.(count($gallery) - 4).'</span>';
                            $more_image_class = 'view-more-image';
                        }
                    ?>
                        <div class="<?php echo esc_attr($second_class.' '.$additional_class); ?>">
                            <a href="<?php echo esc_url( $src ); ?>" data-elementor-lightbox-slideshow="guido-gallery" class="popup-image-gallery <?php echo esc_attr($more_image_class); ?>">
                                <?php
                                if ( $i <= 6 ) {
                                    echo guido_get_attachment_thumbnail( $id, $gallery_second_size );
                                    echo trim($more_image_html);
                                }
                                ?>
                            </a>
                        </div>
                    <?php $i++; } ?>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<?php }