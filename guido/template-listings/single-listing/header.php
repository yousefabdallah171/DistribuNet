<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;
?>
<div class="top-header-detail-listing">
    <div class="container">
        <div class="header-detail-top">
            <div class="title-wrapper">
                <?php the_title( '<h1 class="listing-title">', '</h1>' ); ?>
            </div>
            <div class="listing-metas d-flex flex-wrap metas-space align-items-center">
                <?php
                    guido_listing_display_phone($post, 'icon');
                    guido_listing_display_short_location($post, 'icon');
                ?>
            </div>
        </div>

        <div class="d-md-flex align-items-center header-detail-bottom">
            <div class="col-12 col-md-5">
                <div class="left-infor d-flex metas-space flex-wrap align-items-center">
                    <?php guido_listing_display_rating($post); ?>
                    <?php guido_listing_display_price_range($post); ?>
                </div>
            </div>
            <div class="listing-action-detail col-12 col-md-7">
                <div class="list-action float-md-end">
                    <?php get_template_part('template-parts/sharebox'); ?>
                    <?php
                        if ( guido_get_config('listing_enable_favorite', true) ) {
                            $args = array(
                                'added_icon_class' => 'flaticon-love',
                                'add_icon_class' => 'flaticon-love',
                                'show_text' => true,
                                'add_text' => esc_html__('Save', 'guido'),
                                'added_text' => esc_html__('Saved', 'guido'),
                            );
                            WP_Listings_Directory_Favorite::display_favorite_btn($post->ID, $args);
                        }
                    ?>
                    <?php if ( WP_Listings_Directory_Review::review_enable() ) { ?>
                        <a href="#reviews" class="review btn btn-theme btn-white"><?php esc_html_e('Submit Review', 'guido'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>