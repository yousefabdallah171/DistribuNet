<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="register-form-wrapper">
  	<div class="container-form">
      	<form name="registerForm" method="post" class="register-form">

			<div class="form-group">
				<label for="register-username"><?php esc_html_e('Username', 'wp-listings-directory'); ?></label>
				<sup class="required-field">*</sup>
				<input type="text" class="form-control" name="username" id="register-username" placeholder="<?php esc_attr_e('Enter Username','wp-listings-directory'); ?>">
			</div>
			<div class="form-group">
				<label for="register-email"><?php esc_html_e('Email', 'wp-listings-directory'); ?></label>
				<sup class="required-field">*</sup>
				<input type="text" class="form-control" name="email" id="register-email" placeholder="<?php esc_attr_e('Enter Email','wp-listings-directory'); ?>">
			</div>

			<?php if ( wp_listings_directory_get_option('users_requires_approval') == 'phone_approve' && wp_listings_directory_get_option('phone_approve_show_country_code') == 'on' ) { ?>
				<div class="form-group">
					<label for="register-phone"><?php esc_html_e('Phone', 'wp-listings-directory'); ?></label>

					<?php
						$cc_list = include WP_LISTINGS_DIRECTORY_PLUGIN_DIR.'includes/sms/countries-phone.php';
						if ( wp_listings_directory_get_option('phone_approve_default_country_code') == 'geolocation' ) {
							$default_cc = WP_Listings_Directory_SMS_Geolocation::get_phone_code();
						} else {
							$default_cc = wp_listings_directory_get_option('phone_approve_default_country_code_custom');
						}
					?>
					<select class="form-control" name="phone-cc">

						<option disabled><?php esc_html_e( 'Select Country Code', 'wp-listings-directory' ); ?></option>
						<?php foreach( $cc_list as $country_code => $country_phone_code ): ?>
							<option value="<?php echo esc_attr($country_phone_code); ?>" <?php selected($country_phone_code, $default_cc); ?>><?php echo esc_html($country_code.' '.$country_phone_code); ?></option>
						<?php endforeach; ?>
					</select>
					
					<input type="text" class="form-control" name="phone" id="register-phone" placeholder="<?php esc_attr_e('Phone','wp-listings-directory'); ?>">
				</div>
			<?php } ?>

			<div class="form-group">
				<label for="password"><?php esc_html_e('Password', 'wp-listings-directory'); ?></label>
				<sup class="required-field">*</sup>
				<input type="password" class="form-control" name="password" id="password" placeholder="<?php esc_attr_e('Enter Password','wp-listings-directory'); ?>">
			</div>
			<div class="form-group">
				<label for="confirmpassword"><?php esc_html_e('Confirm Password', 'wp-listings-directory'); ?></label>
				<sup class="required-field">*</sup>
				<input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="<?php esc_attr_e('Enter Password','wp-listings-directory'); ?>">
			</div>

			<?php wp_nonce_field('ajax-register-nonce', 'security_register'); ?>

			<?php if ( WP_Listings_Directory_Recaptcha::is_recaptcha_enabled() ) { ?>
	            <div id="recaptcha-contact-form" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(wp_listings_directory_get_option( 'recaptcha_site_key' )); ?>"></div>
	      	<?php } ?>

			<div class="form-group">
				<button type="submit" class="btn btn-second btn-block" name="submitRegister">
					<?php echo esc_html__('Register now', 'wp-listings-directory'); ?>
				</button>
			</div>

			<?php do_action('register_form'); ?>
      	</form>
    </div>

</div>
