<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$address = get_user_meta($author_obj->ID, '_user_map_location_address', true);
$latitude = get_user_meta($author_obj->ID, '_user_map_location_latitude', true);
$longitude = get_user_meta($author_obj->ID, '_user_map_location_longitude', true);


if ( !empty($latitude) && !empty($longitude) ) : ?>
    <div id="listing-detail-location" class="listing-detail-map-street">
        <h3 class="title"><?php esc_html_e('Location', 'guido'); ?></h3>

        <div class="single-listing-google-maps-wrapper">
            <div id="single-listing-google-maps" class="single-listing-map"></div>
        </div>
        <?php if ( $address ) { ?>
            <div class="flex-middle-sm">
                <div class="ali-left">
                    <div class="listing-location with-icon"><i class="flaticon-map"></i> <a href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php echo esc_html($address); ?></a></div>
                </div>
                <div class="ali-right">
                    <a href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $address ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php esc_html_e('Get Direction', 'guido'); ?></a>
                </div>
            </div>
        <?php } ?>
    </div>
<?php endif; ?>