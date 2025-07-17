<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

global $post;
if ( !empty($post->post_type) && $post->post_type == 'listing' ) {
	$author_id = $post->post_author;
	
	$author_email = get_the_author_meta('user_email');

	if ( ! empty( $author_email ) ) :
		extract( $args );
		extract( $instance );
		echo trim($before_widget);
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$title = apply_filters('widget_title', $title);

		if ( $title ) {
		    echo trim($before_title)  . trim( $title ) . $after_title;
		}

		$name = $email = $phone = '';
		if ( is_user_logged_in() ) {
			$current_user_id = get_current_user_id();
			$userdata = get_userdata( $current_user_id );

			$name = $userdata->display_name;
			$email = $userdata->user_email;
			$phone = get_user_meta($current_user_id, '_user_phone', true);
		}

		$rand_id = guido_random_key();
	?>	

		<div class="contact-form-author">
			
		    <form method="post" action="?" class="contact-form-wrapper form-theme">
		    	
		        <div class="form-group">
		            <input id="contact-form-name-<?php echo esc_attr($rand_id); ?>" type="text" placeholder="<?php esc_attr_e( 'Your Name', 'guido' ); ?>" class="form-control" name="name" required="required" value="<?php echo esc_attr($name); ?>">
		        </div><!-- /.form-group -->
		    
		        <div class="form-group">
		            <input id="contact-form-email-<?php echo esc_attr($rand_id); ?>" type="email" placeholder="<?php esc_attr_e( 'Email', 'guido' ); ?>" class="form-control" name="email" required="required" value="<?php echo esc_attr($email); ?>">
		        </div><!-- /.form-group -->
		    
		        <div class="form-group">
		            <input id="contact-form-phone-<?php echo esc_attr($rand_id); ?>" type="text" placeholder="<?php esc_attr_e( 'Phone', 'guido' ); ?>" class="form-control style2" name="phone" required="required" value="<?php echo esc_attr($phone); ?>">
		        </div><!-- /.form-group -->
				
		        <div class="form-group">
		            <textarea id="contact-form-message-<?php echo esc_attr($rand_id); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Message', 'guido' ); ?>" name="message" required="required"></textarea>
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
		        <button class="button btn btn-theme btn-inverse d-block" name="contact-form"><?php echo esc_html__( 'Send Message', 'guido' ); ?></button>
		    </form>
		    
		    <?php do_action('wp-listings-directory-single-listing-contact-form', $post, $author_id); ?>
		    
		</div>
		<?php echo trim($after_widget);
	endif;
}