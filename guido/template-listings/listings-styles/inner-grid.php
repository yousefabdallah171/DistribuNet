<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;
?>

<?php do_action( 'wp_listings_directory_before_listing_content', $post->ID ); ?>

<article <?php post_class('map-item listing-grid listing-item'); ?> <?php guido_listing_item_map_meta($post); ?>>

    <div class="listing-thumbnail-wrapper">
        <?php guido_listing_display_image( $post, 'guido-listing-grid' ); ?>
        
        <div class="top-label d-flex align-items-center">

            <div class="d-flex align-items-center">
                <!-- price range, open hour -->
                <?php guido_listing_display_price_range($post); ?>
                <?php guido_display_time_status($post); ?>
            </div>
            <?php
                $featured = guido_listing_display_featured_icon($post, false);
                if ( $featured ) {
                    ?>
                    <div class="ms-auto">
                        <?php echo trim($featured); ?>
                    </div>
                    <?php
                }
            ?>
        </div>

        <div class="bottom-label">
            <?php guido_listing_display_rating($post); ?>
        </div>

        <?php guido_listing_display_logo($post); ?>
    </div>

    <div class="top-info">
        <div class="listing-information">
            
    		<?php the_title( sprintf( '<h2 class="listing-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            
            <?php guido_listing_display_tageline($post); ?>
            
            <div class="listing-metas d-flex align-items-center flex-wrap">
                <?php
                    guido_listing_display_phone($post, 'icon');
                    guido_listing_display_short_location($post, 'icon');
                ?>
            </div>
    	</div>

        <div class="listing-information-bottom d-flex align-items-center">
            
            <?php guido_listing_display_category_first($post); ?>
            <div class="ms-auto list-action d-flex align-items-center">
                <a href="<?php the_permalink(); ?>"><span class="flaticon-zoom"></span></a>
                <?php
                if ( guido_get_config('listing_enable_favorite', true) ) {
                    $args = array(
                        'added_icon_class' => 'flaticon-love',
                        'add_icon_class' => 'flaticon-love',
                    );
                    WP_Listings_Directory_Favorite::display_favorite_btn($post->ID, $args);
                }
                ?>
            </div>
        </div>
    </div>
</article><!-- #post-## -->

<?php do_action( 'wp_listings_directory_after_listing_content', $post->ID ); ?>