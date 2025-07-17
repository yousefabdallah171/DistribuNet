<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

global $post;
if ( empty($post->post_type) || $post->post_type != 'listing' ) {
    return;
}

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
if ( !( $price = $meta_obj->get_price_html() ) ) {
    return;
}

extract( $args );
extract( $instance );
echo trim($before_widget);
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

$price_range = guido_listing_display_price_range($post, '', false);

$listing_is_claimed = get_post_meta( get_the_ID(), WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'claimed', true );

?>
<div class="listing-detail-price">
    <?php if ( $price_range ) { ?>
        <div class="price-range d-flex align-items-center">
            <div class="name"><?php echo trim($meta_obj->get_post_meta_title('price_range')); ?></div>   
            <div class="value ms-auto"><?php echo trim($price_range); ?></div>
        </div>
    <?php } ?>
	<div class="price d-flex align-items-center">
        <div class="name"><?php esc_html_e('Price', 'guido'); ?></div>   
        <div class="value ms-auto"><?php echo trim($price); ?></div>
    </div>
    <?php if ( !$listing_is_claimed ) { ?>
        <div class="claim">
            <?php esc_html_e('Claim your free business page to have your changes published immediately.', 'guido'); ?>
            <a href="#claim-listing-<?php echo esc_attr($post->ID); ?>" class="claim-this-business-btn"><?php esc_html_e( 'Claim this business', 'guido' ); ?></a>
        </div>
        
        <div id="claim-listing-<?php echo esc_attr($post->ID); ?>" class="claim-listing-form-wrapper mfp-hide" data-effect="fadeIn">
            
            <h4 class="title text-theme"><?php esc_html_e('Claim this listing', 'guido'); ?></h4>
            
            <form action="" class="claim-listing-form" method="post">
                
                <div class="msg"></div>
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="<?php esc_attr_e( 'Fullname', 'guido' ); ?>" required="required">
                </div><!-- /.form-group -->
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" placeholder="<?php esc_attr_e( 'Phone', 'guido' ); ?>" required="required">
                </div><!-- /.form-group -->
                <div class="form-group">
                    <textarea class="form-control" name="message" placeholder="<?php esc_attr_e( 'Additional proof to expedite your claim approval...', 'guido' ); ?>" cols="30" rows="5" required="required"></textarea>
                </div><!-- /.form-group -->
                <input type="hidden" name="listing_id" value="<?php echo esc_attr($post->ID); ?>">
                <button class="button btn btn-block btn-theme" name="submit-claim-listing" value=""><?php echo esc_html__( 'Claim This Business', 'guido' ); ?></button>
            </form>

        </div>
    <?php } ?>
</div>
<?php echo trim($after_widget);