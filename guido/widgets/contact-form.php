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

$author_email = $author_obj->user_email;
$display_name = $author_obj->display_name;

if ( ! empty( $author_email ) ) :
	extract( $args );
	extract( $instance );
	echo trim($before_widget);
	$title = apply_filters('widget_title', sprintf($title, $display_name));

	if ( $title ) {
	    echo trim($before_title)  . trim( $title ) . $after_title;
	}

	$email = $phone = '';
	if ( is_user_logged_in() ) {
		$current_user_id = get_current_user_id();
		$userdata = get_userdata( $current_user_id );
		$email = $userdata->user_email;
	}

	$rand_id = guido_random_key();
?>	

	<div class="contact-form-agent">
	    <form method="post" action="?" class="contact-form-wrapper form-theme">
	    	<div class="row">
		        <div class="col-sm-12">
			        <div class="form-group">
			            <input id="contact-form-name-<?php echo esc_attr($rand_id); ?>" type="text" class="form-control" name="name" required="required">
			            <label for="contact-form-name-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Name', 'guido' ); ?></label>
			        </div><!-- /.form-group -->
			    </div>
			    <div class="col-sm-12">
			        <div class="form-group">
			            <input id="contact-form-email-<?php echo esc_attr($rand_id); ?>" type="email" class="form-control" name="email" required="required" value="<?php echo esc_attr($email); ?>">
			            <label for="contact-form-email-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Email', 'guido' ); ?></label>
			        </div><!-- /.form-group -->
			    </div>
			    <div class="col-sm-12">
			        <div class="form-group">
			            <input id="contact-form-phone-<?php echo esc_attr($rand_id); ?>" type="text" class="form-control" name="phone" required="required" value="<?php echo esc_attr($phone); ?>">
			            <label for="contact-form-phone-<?php echo esc_attr($rand_id); ?>" class="for-control"><?php esc_attr_e( 'Phone', 'guido' ); ?></label>
			        </div><!-- /.form-group -->
			    </div>
	        </div>
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
			
			<input type="hidden" name="user_id" value="<?php echo esc_attr($author_obj->ID); ?>">
	        <button class="button btn btn-theme btn-block" name="contact-form"><?php echo esc_html__( 'Send Message', 'guido' ); ?></button>
	    </form>
	</div>
<?php
	echo trim($after_widget);
endif; ?>