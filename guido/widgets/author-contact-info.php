<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

global $guido_author_obj;
if ( empty($guido_author_obj) ) {
    return;
}
$author_obj = $guido_author_obj;

extract( $args );
extract( $instance );
echo trim($before_widget);
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

$address = get_user_meta($author_obj->ID, '_user_address', true);
$phone = get_user_meta($author_obj->ID, '_user_phone', true);
$whatsapp = get_user_meta($author_obj->ID, '_user_whatsapp', true);
$socials = get_user_meta($author_obj->ID, '_user_socials', true);
$website = !empty($author_obj->user_url) ? $author_obj->user_url : '';
$email = !empty($author_obj->user_email) ? $author_obj->user_email : '';

?>
<div class="listing-author-contact-info listing-detail-contact-info">

    <ul class="list list-contact-info">
        <?php if ( $address ) { ?>
            <li>
                <div class="address">
                    <i class="flaticon-pin"></i><span><?php echo esc_attr($address); ?></span>
                </div>
                <a class="btn-readmore" href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $address ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php esc_html_e('Get Direction', 'guido'); ?></a>
            </li>
        <?php } ?>
        <?php if ( $phone ) { ?>
            <li>
                <?php guido_user_display_phone($phone, 'icon', true); ?>
            </li>
        <?php } ?>
        <?php if ( $whatsapp ) { ?>
            <li>
                <div class="listing-meta with-icon">
                    <i class="fab fa-whatsapp"></i>

                    <span class="value-suffix">
                        <a href="whatsapp://send?abid=<?php echo esc_attr($whatsapp); ?>"><?php esc_html_e('Whatsapp', 'guido'); ?></a>
                    </span>

                </div>
            </li>
        <?php } ?>

        <?php if ( $email ) { ?>
            <li>
                <div class="listing-meta with-icon">
                    <i class="flaticon-email"></i>

                    <span class="value-suffix">
                        <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    </span>

                </div>
            </li>
        <?php } ?>
        <?php if ( $website ) { ?>
            <li>
                <div class="listing-meta with-icon">
                    <i class="flaticon-link"></i>

                    <span class="value-suffix">
                        <a href="<?php echo esc_url($website); ?>"><?php echo esc_html($website); ?></a>
                    </span>

                </div>
            </li>
        <?php } ?>
    </ul>

    <!-- socials -->
    <?php if ( $socials ) {
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