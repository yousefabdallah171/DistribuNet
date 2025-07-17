<?php

remove_action( 'widgets_init', array( WP_Private_Message::getInstance(), 'register_widgets' ) );

add_action( 'wp-listings-directory-single-listing-contact-form', 'guido_private_message_form', 10, 2 );
function guido_private_message_form($post, $user_id) {
	$userdata = get_userdata( $user_id );
	?>
	<div class="send-private-wrapper">
		<a href="#send-private-message-wrapper-<?php echo esc_attr($post->ID); ?>" class="send-private-message-btn btn btn-second d-block"><?php esc_html_e('Send Private Message', 'guido'); ?></a>
	</div>

	<div id="send-private-message-wrapper-<?php echo esc_attr($post->ID); ?>" class="send-private-message-wrapper mfp-hide" data-effect="fadeIn">
		<h3 class="title"><?php echo sprintf(esc_html__('Send message to "%s"', 'guido'), $userdata->display_name); ?></h3>
		<?php
		if ( is_user_logged_in() ) {
			?>
			<form id="send-message-form" class="send-message-form" action="?" method="post">
                <div class="form-group">
                    <input type="text" class="form-control style2" name="subject" placeholder="<?php esc_attr_e( 'Subject', 'guido' ); ?>" required="required">
                </div><!-- /.form-group -->
                <div class="form-group">
                    <textarea class="form-control message style2" name="message" placeholder="<?php esc_attr_e( 'Enter text here...', 'guido' ); ?>" required="required"></textarea>
                </div><!-- /.form-group -->

                <?php wp_nonce_field( 'wp-private-message-send-message', 'wp-private-message-send-message-nonce' ); ?>
              	<input type="hidden" name="recipient" value="<?php echo esc_attr($user_id); ?>">
              	<input type="hidden" name="action" value="wp_private_message_send_message">
                <button class="button btn btn-theme btn-inverse btn-block send-message-btn"><?php echo esc_html__( 'Send Message', 'guido' ); ?></button>
        	</form>
			<?php
		} else {
			$login_url = '';
			if ( function_exists('wp_listings_directory_get_option') ) {
				$login_register_page_id = wp_listings_directory_get_option('login_register_page_id');
				$login_url = get_permalink( $login_register_page_id );
			}
			?>
			<a href="<?php echo esc_url($login_url); ?>" class="login"><?php esc_html_e('Please login to send a private message', 'guido'); ?></a>
			<?php
		}
		?>
	</div>
	<?php
}

function guido_author_private_message_form($user_id) {
	$userdata = get_userdata( $user_id );
	?>
	<div class="send-private-wrapper">
		<a href="#send-private-message-wrapper-<?php echo esc_attr($user_id); ?>" class="send-private-message-btn btn btn-theme btn-inverse"><?php esc_html_e('Send Private Message', 'guido'); ?></a>
	</div>

	<div id="send-private-message-wrapper-<?php echo esc_attr($user_id); ?>" class="send-private-message-wrapper mfp-hide" data-effect="fadeIn">
		<h3 class="title"><?php echo sprintf(esc_html__('Send message to "%s"', 'guido'), $userdata->display_name); ?></h3>
		<?php
		if ( is_user_logged_in() ) {
			?>
			<form id="send-message-form" class="send-message-form" action="?" method="post">
                <div class="form-group">
                    <input type="text" class="form-control style2" name="subject" placeholder="<?php esc_attr_e( 'Subject', 'guido' ); ?>" required="required">
                </div><!-- /.form-group -->
                <div class="form-group">
                    <textarea class="form-control message style2" name="message" placeholder="<?php esc_attr_e( 'Enter text here...', 'guido' ); ?>" required="required"></textarea>
                </div><!-- /.form-group -->

                <?php wp_nonce_field( 'wp-private-message-send-message', 'wp-private-message-send-message-nonce' ); ?>
              	<input type="hidden" name="recipient" value="<?php echo esc_attr($user_id); ?>">
              	<input type="hidden" name="action" value="wp_private_message_send_message">
                <button class="button btn btn-theme btn-block send-message-btn"><?php echo esc_html__( 'Send Message', 'guido' ); ?></button>
        	</form>
			<?php
		} else {
			$login_url = '';
			if ( function_exists('wp_listings_directory_get_option') ) {
				$login_register_page_id = wp_listings_directory_get_option('login_register_page_id');
				$login_url = get_permalink( $login_register_page_id );
			}
			?>
			<a href="<?php echo esc_url($login_url); ?>" class="login"><?php esc_html_e('Please login to send a private message', 'guido'); ?></a>
			<?php
		}
		?>
	</div>
	<?php
}


function guido_private_message_user_avatar($user_id) {
	echo guido_get_avatar($user_id, 54);
}