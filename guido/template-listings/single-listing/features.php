<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

if ( !$meta_obj->check_post_meta_exist('feature') ) {
    return;
}

$features = get_the_terms($post->ID, 'listing_feature');

?>

<?php if ( ! empty( $features ) ) : ?>
    <div id="listing-detail-features" class="listing-section listing-features">
        <h3 class="title"><?php echo trim($meta_obj->get_post_meta_title('feature')); ?></h3>

        <div class="description-inner">
            <div class="description-inner-wrapper">
                <ul class="listing_features clearfix list">
                    <?php foreach ( $features as $feature ) {
                        $icon = guido_listing_term_icon($feature);
                    ?>
                        <li>
                            <?php if (!empty($icon)) { ?>
                                <span class="feature-icon left-inner">
                                    <?php echo trim($icon); ?>
                                </span>
                            <?php } ?>
                            <span class="feature-title"><?php echo esc_html( $feature->name ); ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <?php do_action('wp-listings-directory-single-listing-features', $post); ?>
        </div>
        
    </div><!-- /.listing-features -->
<?php endif; ?>