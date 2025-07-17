<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

global $post;
if ( empty($post->post_type) || $post->post_type != 'listing' ) {
    return;
}

extract( $args );
extract( $instance );
echo trim($before_widget);
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

$latitude = $meta_obj->get_post_meta( 'map_location_latitude' );
$longitude = $meta_obj->get_post_meta( 'map_location_longitude' );
?>
<div class="listing-detail-contact-info">
	<?php if ( !empty($latitude) && !empty($longitude) ) : ?>
        <div id="single-listing-google-maps" class="single-listing-map"></div>
    <?php endif; ?>

    <ul class="list list-contact-info">
        <li>
            <?php guido_listing_display_full_location($post, 'icon', true); ?>
            <?php guido_listing_display_location_btn($post); ?>
        </li>
        <li>
            <?php guido_listing_display_phone($post, 'icon'); ?>
        </li>
        <li>
            <?php echo guido_listing_display_email($post, 'flaticon-email'); ?>
        </li>
        <li>
            <?php echo guido_listing_display_website($post, 'flaticon-link'); ?>
        </li>
    </ul>

    <!-- socials -->
    <?php if ( $meta_obj->check_post_meta_exist('socials') && ($socials = $meta_obj->get_post_meta( 'socials' )) ) {
        $all_socials = WP_Listings_Directory_Mixes::get_socials_network();
    ?>
        <ul class="socials-list list">
            <?php foreach ($socials as $social) { ?>
                <?php if ( !empty($social['url']) && !empty($social['network']) ) {
                    $icon_class = !empty( $all_socials[$social['network']]['icon'] ) ? $all_socials[$social['network']]['icon'] : '';
                ?>
                    <li>
                        <a href="<?php echo esc_html($social['url']); ?>">
                            <i class="<?php echo esc_attr($icon_class); ?>"></i>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    <?php } ?>
</div>
<?php echo trim($after_widget);