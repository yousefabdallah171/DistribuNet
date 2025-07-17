<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
?>
<div class="listing-detail-detail">
    <h3><?php esc_html_e('Details', 'wp-listings-directory'); ?></h3>
    <ul class="list">
        <?php if ( $meta_obj->check_post_meta_exist('listing_id') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Listing ID : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('listing_id')); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('lot_area') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Lot Area : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('lot_area')); ?> <?php echo wp_listings_directory_get_option('measurement_unit_area'); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('home_area') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Home Area : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('home_area')); ?> <?php echo wp_listings_directory_get_option('measurement_unit_area'); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('lot_dimensions') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Lot dimensions : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('lot_dimensions')); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('rooms') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Rooms : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('rooms')); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('beds') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Beds : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('beds')); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('baths') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Baths : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('baths')); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('garages') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Garages : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('garages')); ?></div>
            </li>
        <?php } ?>
        <?php if ( $meta_obj->check_post_meta_exist('price') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Price : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_price_html()); ?></div>
            </li>
        <?php } ?>

        <?php if ( $meta_obj->check_post_meta_exist('year_build') ) { ?>
            <li>
                <div class="text"><?php esc_html_e('Year built : ', 'wp-listings-directory'); ?></div>
                <div class="value"><?php echo trim($meta_obj->get_post_meta('year_build')); ?></div>
            </li>
        <?php } ?>

        <?php do_action('wp-listings-directory-single-listing-details', $post); ?>
    </ul>
</div>