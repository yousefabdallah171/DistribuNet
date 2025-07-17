<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;
$author_id = $post->post_author;

$author_email = get_the_author_meta('user_email');
$userdata = get_userdata( $author_id );

$a_title_html = $userdata->display_name;
$a_phone = get_user_meta($author_id, '_user_phone', true);
$a_phone = guido_user_display_phone($a_phone, 'no-title', false);

$whatsapp = get_user_meta($author_id, '_user_whatsapp', true);


if ( ! empty( $author_email ) ) {
?>
<div id="listing-detail-detail" class="listing-detail-detail">
    <?php
    $name = $email = $phone = '';
    if ( is_user_logged_in() ) {
        $current_user_id = get_current_user_id();
        $userdata = get_userdata( $current_user_id );

        $name = $userdata->display_name;
        $email = $userdata->user_email;
        $phone = get_user_meta($current_user_id, '_phone', true);
    }

    $rand_id = guido_random_key();
    ?>  

    <div class="inner contact-form-agent">
        <div class="contact-form--inner">
            <div class="agent-content-wrapper flex-middle">
                <div class="agent-thumbnail">
                    <?php echo guido_get_avatar($post->post_author, 180); ?>
                </div>
                <div class="agent-content">
                    <h3><?php echo trim($a_title_html); ?></h3>
                    <div class="phone"><?php echo trim($a_phone); ?></div>
                </div>
            </div>

            <div class="content-bio">
                <?php
                    $description = get_the_author_meta( 'description' );
                    if ( $description ) {
                        echo trim(guido_substring($description, 10, '...'));
                    }
                ?>
            </div>

            <a href="#contact-form-popup-wrapper" class="btn btn-theme contact-form-popup-btn"><?php esc_html_e('Send Message', 'guido'); ?></a>

            <!-- whatsapp -->
            <?php
            if ( $whatsapp ) {
                ?>
                <a class="btn btn-green btn-whatsapp" href="whatsapp://send?abid=<?php echo esc_attr($whatsapp); ?>"><?php esc_html_e('Whatsapp', 'guido'); ?></a>
                <?php
            }
            ?>
        </div>
        <div id="contact-form-popup-wrapper" class="contact-form-popup-wrapper mfp-hide" data-effect="fadeIn">
            <form method="post" action="?" class="contact-form-wrapper form-theme">
                <div class="form-group">
                    <input id="contact-form-name-<?php echo esc_attr($rand_id); ?>" type="text" class="form-control" name="name" required="required" value="<?php echo esc_attr($name); ?>">
                    <label for="contact-form-name-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Your Name', 'guido' ); ?></label>
                </div><!-- /.form-group -->
            
                <div class="form-group">
                    <input id="contact-form-email-<?php echo esc_attr($rand_id); ?>" type="email" class="form-control" name="email" required="required" value="<?php echo esc_attr($email); ?>">
                    <label for="contact-form-email-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Email', 'guido' ); ?></label>
                </div><!-- /.form-group -->
            
                <div class="form-group">
                    <input id="contact-form-phone-<?php echo esc_attr($rand_id); ?>" type="text" class="form-control style2" name="phone" required="required" value="<?php echo esc_attr($phone); ?>">
                    <label for="contact-form-phone-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Phone', 'guido' ); ?></label>
                </div><!-- /.form-group -->
                
                <div class="form-group">
                    <textarea id="contact-form-message-<?php echo esc_attr($rand_id); ?>" class="form-control" name="message" required="required"></textarea>
                    <label for="contact-form-message-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Message', 'guido' ); ?></label>
                </div><!-- /.form-group -->

                <?php if ( WP_Listings_Directory_Recaptcha::is_recaptcha_enabled() ) { ?>
                    <div id="recaptcha-contact-form" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(wp_listings_directory_get_option( 'recaptcha_site_key' )); ?>"></div>
                <?php } ?>

                <?php
                    $page_id = wp_listings_directory_get_option('terms_conditions_page_id');
                    if ( !empty($page_id) ) {
                        $page_id = WP_Listings_Directory_Mixes::get_lang_post_id($page_id);
                        $page_url = get_permalink($page_id);
                    ?>
                    <div class="form-group">
                        <label for="register-terms-and-conditions">
                            <input type="checkbox" name="terms_and_conditions" value="on" id="register-terms-and-conditions" required>
                            <?php
                                echo sprintf(wp_kses(__('I have read and accept the <a href="%s">Terms and Privacy Policy</a>', 'guido'), array('a' => array('href' => array())) ), esc_url($page_url));
                            ?>
                        </label>
                    </div>
                <?php } ?>

                <input type="hidden" name="post_id" value="<?php echo esc_attr($post->ID); ?>">
                <button class="button btn btn-theme btn-block" name="contact-form"><?php echo esc_html__( 'Send Message', 'guido' ); ?></button>
            </form>
        </div>
        
        <?php do_action('wp-listings-directory-single-listing-contact-form', $post, $author_id); ?>
        
    </div>
</div>
<?php }