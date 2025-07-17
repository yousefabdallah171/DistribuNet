<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
guido_load_select2();
$rand = guido_random_key();

$login_title = !empty($login_title) ? $login_title : '';
$reset_password_title = !empty($reset_password_title) ? $reset_password_title : '';
?>
<div class="login-form-wrapper">
	
	<div id="login-form-wrapper-<?php echo esc_attr($rand); ?>" class="form-container form-login-register-inner ">
		<?php if ( $login_title ) { ?>
			<h2 class="title"><?php echo trim($login_title); ?></h2>
		<?php } ?>
		<form class="login-form form-theme" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
			<div class="form-group">
				<input autocomplete="off" type="text" name="username" placeholder="<?php esc_attr_e('Username / Email', 'guido'); ?>" class="form-control" id="username_or_email">
			</div>
			<div class="form-group">
				<input name="password" type="password" class="password required form-control" placeholder="<?php esc_attr_e('Password','guido'); ?>" id="login_password">
			</div>
			<div class="row form-group">
				<div class="col-sm-6">
					<label for="user-remember-field">
						<input type="checkbox" name="remember" id="user-remember-field" value="true"> <?php echo esc_html__('Keep me signed in','guido'); ?>
					</label>
				</div>
				<div class="col-sm-6 forgot-password-text">
					<a href="#forgot-password-form-wrapper-<?php echo esc_attr($rand); ?>" class="back-link" title="<?php esc_attr_e('Forgot Password','guido'); ?>"><?php echo esc_html__("Lost Your Password?",'guido'); ?></a>
				</div>
			</div>
			<div class="form-group mb-30">
				<input type="submit" class="btn btn-theme w-100" name="submit" value="<?php esc_attr_e('Sign in','guido'); ?>"/>
			</div>
			<?php
				wp_nonce_field('ajax-login-nonce', 'security_login');
			?>
			<?php do_action('login_form'); ?>
		</form>

		<?php if ( defined('GUIDO_DEMO_MODE') && GUIDO_DEMO_MODE ) { ?>
			<div class="sign-in-demo-notice">
				Username: <strong>demo</strong><br>
				Password: <strong>demo</strong>
			</div>
		<?php } ?>

	</div>
	<!-- reset form -->
	<div id="forgot-password-form-wrapper-<?php echo esc_attr($rand); ?>" class="form-container form-login-register-inner form-forgot-password-inner">
		<?php if ( $reset_password_title ) { ?>
			<h2 class="title"><?php echo trim($reset_password_title); ?></h2>
		<?php } ?>
		<form name="forgotpasswordform" class="forgotpassword-form form-theme" action="<?php echo esc_url( site_url('wp-login.php?action=lostpassword', 'login_post') ); ?>" method="post">
			<div class="lostpassword-fields">
				<div class="form-group">
					<input type="text" name="user_login" class="user_login form-control" placeholder="<?php esc_attr_e('Username / Email','guido'); ?>" id="lostpassword_username">
				</div>
				<?php
					do_action('lostpassword_form');
					wp_nonce_field('ajax-lostpassword-nonce', 'security_lostpassword');
				?>

				<?php if ( WP_Listings_Directory_Recaptcha::is_recaptcha_enabled() ) { ?>
		            <div id="recaptcha-contact-form" class="ga-recaptcha" data-sitekey="<?php echo esc_attr(wp_listings_directory_get_option( 'recaptcha_site_key' )); ?>"></div>
		      	<?php } ?>

				<div class="form-group">
					<input type="submit" class="btn btn-theme btn-block" name="wp-submit" value="<?php esc_attr_e('Get New Password', 'guido'); ?>" tabindex="100" />
					<input type="button" class="btn btn-danger btn-block btn-cancel" value="<?php esc_attr_e('Cancel', 'guido'); ?>" tabindex="101" />
				</div>
			</div>
			<div class="lostpassword-link"><a href="#login-form-wrapper-<?php echo esc_attr($rand); ?>" class="back-link"><?php echo esc_html__('Back To Login', 'guido'); ?></a></div>
		</form>
	</div>
</div>