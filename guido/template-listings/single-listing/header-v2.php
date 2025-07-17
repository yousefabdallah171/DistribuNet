<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;
?>
<div class="top-header-detail-listing v2">
    <div class="container">
        <div class="d-lg-flex align-items-center">

            <div class="header-detail-top-v2 col-12 col-lg-7">
                <div class="d-flex align-items-center">
                    <?php guido_listing_display_logo( $post ); ?>
                    <div class="detail-top">
                        <div class="title-wrapper">
                            <?php the_title( '<h1 class="listing-title">', '</h1>' ); ?>
                        </div>
                        <div class="listing-metas d-flex flex-wrap metas-space align-items-center">
                            <?php
                                guido_listing_display_phone($post, 'icon');
                                guido_listing_display_short_location($post, 'icon');
                                guido_listing_display_rating($post);
                                guido_listing_display_price_range($post);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="listing-action-detail col-12 col-lg-5">
                <div class="list-action float-lg-end">
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
                        <a href="#reviews" class="review btn btn-theme"><?php esc_html_e('Submit Review', 'guido'); ?></a>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>