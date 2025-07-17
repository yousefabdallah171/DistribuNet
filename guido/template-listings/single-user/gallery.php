<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$gallery = get_user_meta($author_obj->ID, '_user_gallery', true);

if ( has_post_thumbnail() || $gallery ) {
    $gallery_size = !empty($gallery_size) ? $gallery_size : 'guido-gallery-large';
    $thumbs_pos = !empty($thumbs_pos) ? $thumbs_pos : 'horizontal';
    $nb_columns = !empty($nb_columns) ? $nb_columns : 5;
?>
<div class="listing-detail-gallery clearfix <?php echo esc_attr($thumbs_pos); ?>">
    
    <div class="listing-single-gallery-wrapper">
        <div class="slick-carousel gap-10 listing-single-gallery" data-carousel="slick" data-items="1" data-smallmedium="1" data-extrasmall="1" data-pagination="false" data-nav="true" data-slickparent="true">
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

    <div class="wrapper-thumbs">
        <div class="slick-carousel gap-10 listing-single-gallery-thumbs <?php echo esc_attr($thumbs_pos == 'vertical' ? 'vertical' : ''); ?>" data-carousel="slick" data-items="<?php echo esc_attr($nb_columns); ?>" data-smallmedium="<?php echo esc_attr($nb_columns); ?>" data-extrasmall="2" data-smallest="2" data-pagination="false" data-nav="true" data-asnavfor=".listing-single-gallery" data-slidestoscroll="1" data-focusonselect="true" <?php echo trim($thumbs_pos == 'vertical' ? 'data-vertical="true"' : ''); ?>>
            
            <?php if ( $gallery ) {
                foreach ( $gallery as $id => $src ) {
                ?>
                    <div class="p-popup-image">
                        <?php echo guido_get_attachment_thumbnail( $id, 'guido-gallery-thumbs' ); ?>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>
<?php }