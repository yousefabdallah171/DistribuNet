<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$columns = guido_get_config('listing_related_columns', 4);

$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
$limit = apply_filters('wp_listings_directory_number_user_listings', guido_get_config('user_listings_number', 4));
$args = array(
    'post_type' => 'listing',
    'posts_per_page' => $limit,
    'author' => $author_obj->ID,
    'paged'         => $paged,
);
$listings = new WP_Query( $args );
if( $listings->have_posts() ):
?>
    <div class="my-listings">
        <h4 class="title">
            <?php esc_html_e( 'My Listings', 'guido' ); ?>
        </h4>
        <div class="widget-content">
            <div class="row">
                <?php while ( $listings->have_posts() ) : $listings->the_post(); ?>
                    <div class="col-xs-12">
                        <?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-list' ); ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        WP_Listings_Directory_Mixes::custom_pagination( array(
            'max_num_pages' => $listings->max_num_pages,
            'prev_text'     => '<i class="ti-angle-left"></i>',
            'next_text'     => '<i class="ti-angle-right"></i>',
            'wp_query'      => $listings
        )); ?>
    </div>
<?php endif; ?>