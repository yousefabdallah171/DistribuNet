<?php
/**
 * User
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_User {
	
	public static function init() {
		add_action( 'init', array( __CLASS__, 'role_caps' ) );

		// Ajax endpoints.
		add_action( 'wpld_ajax_wp_listings_directory_ajax_login',  array( __CLASS__, 'process_login' ) );
		add_action( 'wpld_ajax_wp_listings_directory_ajax_forgotpass',  array( __CLASS__, 'process_forgot_password' ) );
		add_action( 'wpld_ajax_wp_listings_directory_ajax_register',  array( __CLASS__, 'process_register' ) );
		add_action( 'wpld_ajax_wp_listings_directory_ajax_get_opt',  array( __CLASS__, 'process_get_otp' ) );
		add_action( 'wpld_ajax_wp_listings_directory_ajax_verify_opt',  array( __CLASS__, 'process_verify_otp' ) );
		add_action( 'wpld_ajax_wp_listings_directory_ajax_resend_opt',  array( __CLASS__, 'process_resend_otp' ) );
		
		add_action( 'wpld_ajax_wp_listings_directory_ajax_change_password',  array(__CLASS__,'process_change_password') );
		add_action( 'wpld_ajax_wp_listings_directory_ajax_resend_approve_account',  array(__CLASS__,'process_resend_approve_account') );

		// compatible handlers.
		add_action( 'wp_ajax_nopriv_wp_listings_directory_ajax_login',  array( __CLASS__, 'process_login' ) );
		add_action( 'wp_ajax_nopriv_wp_listings_directory_ajax_forgotpass',  array( __CLASS__, 'process_forgot_password' ) );
		add_action( 'wp_ajax_nopriv_wp_listings_directory_ajax_register',  array( __CLASS__, 'process_register' ) );
		
		add_action( 'wp_ajax_wp_listings_directory_ajax_change_password',  array(__CLASS__,'process_change_password') );
		add_action( 'wp_ajax_nopriv_wp_listings_directory_ajax_change_password',  array( __CLASS__, 'process_change_password' ) );


		//
		add_filter( 'wp_authenticate_user', array( __CLASS__, 'admin_user_auth_callback' ), 11, 2 );

		// action
		add_action( 'load-users.php', array( __CLASS__, 'process_update_user_action' ) );
		add_filter( 'wp_listings_directory_new_user_approve_validate_status_update', array( __CLASS__, 'validate_status_update' ), 10, 3 );

		add_action( 'wp_listings_directory_new_user_approve_approve_user', array( __CLASS__, 'approve_user' ) );
		add_action( 'wp_listings_directory_new_user_approve_deny_user', array( __CLASS__, 'deny_user' ) );
		
		// resend approve account
		add_action( 'wp_ajax_wp_listings_directory_ajax_resend_approve_account',  array(__CLASS__,'process_resend_approve_account') );
		add_action( 'wp_ajax_nopriv_wp_listings_directory_ajax_resend_approve_account',  array(__CLASS__,'process_resend_approve_account') );

		// Filters
		add_filter( 'user_row_actions', array( __CLASS__, 'user_table_actions' ), 10, 2 );
		add_filter( 'manage_users_columns', array( __CLASS__, 'add_column' ) );
		add_filter( 'manage_users_custom_column', array( __CLASS__, 'status_column' ), 10, 3 );

		add_action( 'restrict_manage_users', array( __CLASS__, 'status_filter' ), 10, 1 );
		add_action( 'pre_get_users', array( __CLASS__, 'filter_by_status' ) );

		// approve user
		add_action( 'wp', array( __CLASS__, 'process_approve_user' ) );


		// backend user profile
		add_action( 'cmb2_admin_init', array( __CLASS__, 'admin_register_user_profile_metabox') );

		// frontend
		add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'frontend_register_user_profile_metabox' ) );
		// add_action( 'wp', array( __CLASS__, 'process_change_profile' ) );
		add_action( 'cmb2_after_init', array( __CLASS__, 'process_change_profile' ) );
		add_action( 'profile_update', array( __CLASS__, 'process_admin_profile_update'), 100, 3 );
		// get avatar
		add_filter('get_avatar', array( __CLASS__, 'get_avatar'), 100, 5 );


	}

	public static function role_caps() {
	    if ( current_user_can('subscriber') ) {
		    $subscriber = get_role('subscriber');
		    $subscriber->add_cap('upload_files');
		    $subscriber->add_cap('edit_post');
		    $subscriber->add_cap('edit_published_pages');
		    $subscriber->add_cap('edit_others_pages');
		    $subscriber->add_cap('edit_others_posts');
	    }
	}

	public static function is_user_can_edit_listing($listing_id) {
		$return = true;
		if ( ! is_user_logged_in() || ! $listing_id ) {
			$return = false;
		} else {
			$listing = get_post( $listing_id );
			if ( ! $listing || ( absint( $listing->post_author ) !== get_current_user_id() && ! current_user_can( 'edit_post', $listing_id ) ) ) {
				$return = false;
			}
		}

		return apply_filters( 'wp-listings-directory-is-user-can-edit-listing', $return, $listing_id );
	}
	
	public static function process_login() {
   		check_ajax_referer( 'ajax-login-nonce', 'security_login' );
   		
   		$info = array();
   		
   		$info['user_login'] = isset($_POST['username']) ? $_POST['username'] : '';
	    $info['user_password'] = isset($_POST['password']) ? $_POST['password'] : '';
	    $info['remember'] = isset($_POST['remember']) ? true : false;
		
		if ( empty($info['user_login']) || empty($info['user_password']) ) {
            echo json_encode(array(
            	'status' => false,
            	'msg' => __('Please fill all form fields', 'wp-listings-directory')
            ));
            die();
        }

		if (filter_var($info['user_login'], FILTER_VALIDATE_EMAIL)) {
            $user_obj = get_user_by('email', $info['user_login']);
        } else {
            $user_obj = get_user_by('login', $info['user_login']);
        }
        $user_id = isset($user_obj->ID) ? $user_obj->ID : '0';
        $user_login_auth = self::get_user_status($user_id);
        if ( $user_login_auth == 'pending' && isset($user_obj->ID) ) {
            echo json_encode(array(
            	'status' => false,
            	'msg' => self::login_msg($user_obj)
            ));
            die();
        } elseif ( $user_login_auth == 'denied' && isset($user_obj->ID) ) {
        	echo json_encode(array(
            	'status' => false,
            	'msg' => __('Your account denied', 'wp-listings-directory')
            ));
            die();
        }

		$user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
			$result = json_encode(array('status' => false, 'msg' => esc_html__('Wrong username or password. Please try again!!!', 'wp-listings-directory')));
	    } else {
			wp_set_current_user($user_signon->ID);
	        $result = json_encode( array( 'status' => true, 'msg' => esc_html__('Signin successful, redirecting...', 'wp-listings-directory')) );
	    }

   		echo trim($result);
   		die();
	}

	public static function process_forgot_password() {
		// First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-lostpassword-nonce', 'security_lostpassword' );
		
		if ( WP_Listings_Directory_Recaptcha::is_recaptcha_enabled() ) {
			$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? WP_Listings_Directory_Recaptcha::is_recaptcha_valid( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : false;
			if ( !$is_recaptcha_valid ) {
				$error = esc_html__( 'Captcha is not valid', 'wp-listings-directory' );

				echo json_encode(array('status' => false, 'msg' => $error));
				wp_die();
			}
		}
		
		global $wpdb;
		
		$account = isset($_POST['user_login']) ? $_POST['user_login'] : '';
		
		if( empty( $account ) ) {
			$error = esc_html__( 'Enter an username or e-mail address.', 'wp-listings-directory' );
		} else {
			if(is_email( $account )) {
				if( email_exists($account) ) {
					$get_by = 'email';
				} else {
					$error = esc_html__( 'There is no user registered with that email address.', 'wp-listings-directory' );			
				}
			} else if (validate_username( $account )) {
				if( username_exists($account) ) {
					$get_by = 'login';
				} else {
					$error = esc_html__( 'There is no user registered with that username.', 'wp-listings-directory' );				
				}
			} else {
				$error = esc_html__( 'Invalid username or e-mail address.', 'wp-listings-directory' );		
			}
		}	
		
		do_action('wp-listings-directory-process-forgot-password', $_POST);

		if ( empty($error) ) {
			if (filter_var($account, FILTER_VALIDATE_EMAIL)) {
	            $user_obj = get_user_by('email', $account);
	        } else {
	            $user_obj = get_user_by('login', $account);
	        }
	        $user_id = isset($user_obj->ID) ? $user_obj->ID : '0';
	        $user_login_auth = self::get_user_status($user_id);
	        if ( $user_login_auth == 'pending' && isset($user_obj->ID) ) {
	            echo json_encode(array(
	            	'status' => false,
	            	'msg' => self::login_msg($user_obj)
	            ));
	            die();
	        } elseif ( $user_login_auth == 'denied' && isset($user_obj->ID) ) {
	            echo json_encode(array(
	            	'status' => false,
	            	'msg' => __('Your account denied.', 'wp-listings-directory')
	            ));
	            die();
	        }

			$random_password = wp_generate_password();
			$user = get_user_by( $get_by, $account );
			
			$update_user = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => $random_password ) );
				
			if( $update_user ) {
				$from = get_option('admin_email');
				
				$email_to = $user->user_email;
				$subject = WP_Listings_Directory_Email::render_email_vars( array('user_name' => $user->display_name), 'user_reset_password', 'subject');

				$email_content_args = array(
		        	'new_password' => $random_password,
		        	'user_name' => $user_name,
		        	'user_email' => $email_to,
		        );
				$content = WP_Listings_Directory_Email::render_email_vars( $email_content_args, 'user_reset_password', 'content');
					
				$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $from );
					
				$mail = WP_Listings_Directory_Email::wp_mail( $email_to, $subject, $content, $headers );
				
				if( $mail ) {
					$success = esc_html__( 'Check your email address for you new password.', 'wp-listings-directory' );
				} else {
					$error = esc_html__( 'System is unable to send you mail containg your new password.', 'wp-listings-directory' );						
				}
			} else {
				$error =  esc_html__( 'Oops! Something went wrong while updating your account.', 'wp-listings-directory' );
			}
		}
	
		if ( ! empty( $error ) ) {
			echo json_encode( array('status'=> false, 'msg'=> $error) );
		}
				
		if ( ! empty( $success ) ) {
			echo json_encode( array('status' => true, 'msg'=> $success ) );	
		}
		die();
	}

	public static function process_get_otp() {
		global $reg_errors;

		check_ajax_referer( 'ajax-register-nonce', 'security_register' );
		
        self::registration_validation( $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmpassword'] );
        if ( 1 > count( $reg_errors->get_error_messages() ) ) {

	        $jsondata =	WP_Listings_Directory_SMS::request_otp();
	        
	    } else {
	    	$jsondata = array('status' => false, 'msg' => implode(', <br>', $reg_errors->get_error_messages()) );
	    }
	    echo json_encode($jsondata);
	    exit;
	}

	public static function process_verify_otp() {
		try {

			if( isset( $_POST['otp'] ) ){

				$phone_otp_data = WP_Listings_Directory_SMS_Otp_Handler::get_otp_data();

				if( !is_array( $phone_otp_data ) ){
					$phone_otp_data = array();
				}


				//Check for incorrect limit
				if( isset( $phone_otp_data['incorrect'] ) && $phone_otp_data['incorrect'] > wp_listings_directory_get_option('phone_approve_incorrect_otp_limit', 10) ){
					wp_send_json(array( 'status' => false, 'msg' => __( 'Number of tries exceeded, Please try again in few minutes', 'wp-listings-directory' ) ));
				}

				if( isset( $phone_otp_data['otp'] ) && ( $phone_otp_data['otp'] === (int) $_POST['otp'] ) ){

					if( isset( $phone_otp_data['expiry'] ) && strtotime('now') > (int) $phone_otp_data['expiry'] ){
						wp_send_json(array( 'status' => false, 'msg' => __( 'OTP Expired', 'wp-listings-directory' ) ));
					}
					
					WP_Listings_Directory_SMS_Otp_Handler::set_otp_data( array(
						'verified' 			=> true,
						'form_token' 		=> sanitize_text_field( $_POST['token'] ),
						'incorrect' 		=> 0,
						'sent_items' 		=> 0,
						'expiry' 			=> '',
						'created' 			=> '', 
					) );

					//Hook functions on OTP verification
					do_action( 'wp_listings_directory_otp_validation_success', $phone_otp_data );

					wp_send_json(array( 'status' => true, 'msg' => __( 'Thank you for verifying your number.', 'wp-listings-directory' ) ));
				}

				$incorrect = isset( $phone_otp_data['incorrect'] ) ? $phone_otp_data['incorrect'] + 1 : 1;

				WP_Listings_Directory_SMS_Otp_Handler::set_otp_data( 'incorrect', $incorrect );

			}
			
			wp_send_json(array( 'status' => false, 'msg' => __( 'Invalid OTP', 'wp-listings-directory' ) ));

		} catch (Exception $e) {
			wp_send_json(array( 'status' => false, 'msg' => $e->getMessage() ));
		}
	}

	public static function process_resend_otp() {
		try {

			$SMSSent = WP_Listings_Directory_SMS_Otp_Handler::resendOTPSMS();

			if( is_wp_error( $SMSSent ) ){
				wp_send_json(array(
					'status' => false,
					'msg' 	 => $SMSSent->get_error_message(),
				));
			}
			wp_send_json(array(
				'status' 	=> true,
				'msg' 	=> __( 'OTP Resent', 'wp-listings-directory' ),
			));
		} catch (Exception $e) {

			do_action( 'wp_listings_directory_otp_resend_failed', WP_Listings_Directory_SMS_Otp_Handler::get_otp_data(), $e );

			wp_send_json(array(
				'status' 	 => false,
				'msg' 	 => $e->getMessage()
			));
		}
	}

	public static function process_register() {
		global $reg_errors;

		check_ajax_referer( 'ajax-register-nonce', 'security_register' );
		
		if ( !get_option('users_can_register') ) {
			$return = array( 'status' => false, 'msg' => esc_html__('The website does not allow account registration.', 'wp-listings-directory') );
            wp_send_json( $return );
		}
		
        self::registration_validation( $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmpassword'] );
        
        do_action('wp-listings-directory-before-process-register');

        if ( 1 > count( $reg_errors->get_error_messages() ) ) {

	 		$userdata = array(
		        'user_login' => sanitize_user( $_POST['username'] ),
		        'user_email' => sanitize_email( $_POST['email'] ),
		        'user_pass' => $_POST['password'],
		        'role' => 'subscriber',
	        );

	        $user_id = wp_insert_user( $userdata );
	        if ( ! is_wp_error( $user_id ) ) {
	        	
	        	$action = isset($_POST['action']) ? $_POST['action'] : '';
	        	
	        	$user_obj = get_userdata($user_id);
		        $users_requires_approval = wp_listings_directory_get_option('users_requires_approval', 'auto');
		        if ( ($users_requires_approval == 'email_approve' || $users_requires_approval == 'admin_approve') && $action == 'wp_listings_directory_ajax_register' ) {
		            $code = WP_Listings_Directory_Mixes::random_key();
		            update_user_meta($user_id, 'account_approve_key', $code);
		        	update_user_meta($user_id, 'user_account_status', 'pending');

		        	if ( wp_listings_directory_get_option('users_requires_approval', 'auto') == 'email_approve' ) {
						$user_email = stripslashes( $user_obj->user_email );
					} else {
						$user_email = get_option( 'admin_email', false );
					}

					$subject = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user_obj), 'user_register_need_approve', 'subject');
					$content = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user_obj), 'user_register_need_approve', 'content');

					$email_from = get_option( 'admin_email', false );
					$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
					// send the mail
					WP_Listings_Directory_Email::wp_mail( $user_email, $subject, $content, $headers );
		        } else {
		        	$user_email = stripslashes( $user_obj->user_email );
		        	$subject = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user_obj), 'user_register_auto_approve', 'subject');
					$content = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user_obj), 'user_register_auto_approve', 'content');

					$email_from = get_option( 'admin_email', false );
					$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
					// send the mail
					WP_Listings_Directory_Email::wp_mail( $user_email, $subject, $content, $headers );
		        }


	        	$phone_no 	= isset( $_POST['phone'] ) ? sanitize_text_field( trim( $_POST['phone'] ) ) : '';
				$phone_code = isset( $_POST['phone-cc'] ) ? sanitize_text_field( $_POST['phone-cc'] ): '';
				$phone = $phone_code.$phone_no;

	        	update_user_meta( $user_id, '_phone', $phone );

	        	$users_requires_approval = wp_listings_directory_get_option('users_requires_approval', 'auto');
	        	if ( $users_requires_approval == 'email_approve' || $users_requires_approval == 'admin_approve' ) {
	        		
	        		$jsondata = array(
	            		'status' => true,
	            		'msg' => self::register_msg($user_obj),
	            		'redirect' => false
	            	);
	        	} else {
	        		$jsondata = array(
	        			'status' => true,
	        			'msg' => esc_html__( 'You have registered, redirecting ...', 'wp-listings-directory' ),
	        			'redirect' => true,
	        			'role' => $userdata['role']
	        		);
	        		wp_set_auth_cookie($user_id);
	        	}
	        } else {
		        $jsondata = array('status' => false, 'msg' => esc_html__( 'Register user error!', 'wp-listings-directory' ) );
		    }
	    } else {
	    	$jsondata = array('status' => false, 'msg' => implode(', <br>', $reg_errors->get_error_messages()) );
	    }
	    echo json_encode($jsondata);
	    exit;
	}

	public static function registration_validation( $username, $email, $password, $confirmpassword ) {
		global $reg_errors;
		$reg_errors = new WP_Error;

		if ( WP_Listings_Directory_Recaptcha::is_recaptcha_enabled() ) {
			$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? WP_Listings_Directory_Recaptcha::is_recaptcha_valid( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : false;
			if ( !$is_recaptcha_valid ) {
				$reg_errors->add('field', esc_html__( 'Captcha is not valid', 'wp-listings-directory' ) );
			}
		}

		$page_id = wp_listings_directory_get_option('terms_conditions_page_id');
		if ( !empty($page_id) ) {
			if ( empty($_POST['terms_and_conditions']) ) {
				$reg_errors->add('field', esc_html__( 'Terms and Conditions are required', 'wp-listings-directory' ) );
			}
		}
		
		if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $confirmpassword ) ) {
		    $reg_errors->add('field', esc_html__( 'Required form field is missing', 'wp-listings-directory' ) );
		}

		if ( 4 > strlen( $username ) ) {
		    $reg_errors->add( 'username_length', esc_html__( 'Username too short. At least 4 characters is required', 'wp-listings-directory' ) );
		}

		if ( username_exists( $username ) ) {
	    	$reg_errors->add('user_name', esc_html__( 'The username already exists!', 'wp-listings-directory' ) );
		}

		if ( ! validate_username( $username ) ) {
		    $reg_errors->add( 'username_invalid', esc_html__( 'The username you entered is not valid', 'wp-listings-directory' ) );
		}

		if ( 5 > strlen( $password ) ) {
	        $reg_errors->add( 'password', esc_html__( 'Password length must be greater than 5', 'wp-listings-directory' ) );
	    }

	    if ( $password != $confirmpassword ) {
	        $reg_errors->add( 'password', esc_html__( 'Password must be equal Confirm Password', 'wp-listings-directory' ) );
	    }

	    if ( !is_email( $email ) ) {
		    $reg_errors->add( 'email_invalid', esc_html__( 'Email is not valid', 'wp-listings-directory' ) );
		}

		if ( email_exists( $email ) ) {
		    $reg_errors->add( 'email', esc_html__( 'Email Already in use', 'wp-listings-directory' ) );
		}
	}

	public static function process_change_password() {
		$old_password = sanitize_text_field( $_POST['old_password'] );
		$new_password = sanitize_text_field( $_POST['new_password'] );
		$retype_password = sanitize_text_field( $_POST['retype_password'] );

		if ( empty( $old_password ) || empty( $new_password ) || empty( $retype_password ) ) {
			echo json_encode(array('status' => false, 'msg'=> __( 'All fields are required.', 'wp-listings-directory' ) ));
			die();
		}

		if ( $new_password != $retype_password ) {
			echo json_encode(array('status' => false, 'msg'=> __( 'New and retyped password are not same.', 'wp-listings-directory' ) ));
			die();
		}

		$user = wp_get_current_user();
		if ( ! wp_check_password( $old_password, $user->data->user_pass, $user->ID ) ) {
			echo json_encode(array('status' => false, 'msg'=> __( 'Your old password is not correct.', 'wp-listings-directory' ) ));
			die();
		}

		do_action('wp-listings-directory-process-change-password', $_POST);

		wp_set_password( $new_password, $user->ID );
		echo json_encode(array('status' => true, 'msg'=> __( 'Your password has been successfully changed.', 'wp-listings-directory' ) ));
		die();
	}


	public static function process_resend_approve_account() {
		$user_login = isset($_POST['login']) ? $_POST['login'] : '';
		
		if ( empty($user_login) ) {
            echo json_encode(array(
            	'status' => false,
            	'msg' => __('Username or Email not exactly.', 'wp-listings-directory')
            ));
            die();
        }

		if (filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
            $user_obj = get_user_by('email', $user_login);
        } else {
            $user_obj = get_user_by('login', $user_login);
        }
        if ( !empty($user_obj->ID) ) {
	        $user_login_auth = self::get_user_status($user_obj->ID);
	        if ( $user_login_auth == 'pending' ) {
	        	if ( wp_listings_directory_get_option('users_requires_approval', 'auto') == 'email_approve' ) {
					$user_email = stripslashes( $user_obj->user_email );
				} else {
					$user_email = get_option( 'admin_email', false );
				}

				$subject = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user_obj), 'user_register_need_approve', 'subject');
				$content = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user_obj), 'user_register_need_approve', 'content');

				$email_from = get_option( 'admin_email', false );
				$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );

				// send the mail
				$result = WP_Listings_Directory_Email::wp_mail( $user_email, $subject, $content, $headers );
				if ( $result ) {
					echo json_encode(array(
		            	'status' => true,
		            	'msg' => __('Sent a email successfully.', 'wp-listings-directory')
		            ));
		            die();
				} else {
					echo json_encode(array(
		            	'status' => false,
		            	'msg' => __('Send a email error.', 'wp-listings-directory')
		            ));
		            die();
		        }
	        }
        }
        echo json_encode(array(
        	'status' => false,
        	'msg' => __('Your account is not available.', 'wp-listings-directory')
        ));
        die();
	}

	public static function admin_user_auth_callback($user, $password = '') {
    	global $pagenow;
	    
	    $status = self::get_user_status($user->ID);
	    $message = false;
		switch ( $status ) {
			case 'pending':
				$pending_message = self::login_msg($user);
				$message = new WP_Error( 'pending_approval', $pending_message );
				break;
			case 'denied':
				$denied_message = __('Your account denied.', 'wp-listings-directory');
				$message = new WP_Error( 'denied_access', $denied_message );
				break;
			case 'approved':
				$message = $user;
				break;
		}

	    return $message;
	}

	public static function process_approve_user() {
		$post = get_post();

		if ( is_object( $post ) ) {
			if ( strpos( $post->post_content, '[wp_listings_directory_approve_user]' ) !== false ) {
				
				$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
				$code = isset($_GET['approve-key']) ? $_GET['approve-key'] : 0;
				if ( !$user_id ) {
					$error = array(
						'error' => true,
						'msg' => __('The user is not exists.', 'wp-listings-directory')
					);

				}
				$user = get_user_by('ID', $user_id);
				if ( empty($user) ) {
					$error = array(
						'error' => true,
						'msg' => __('The user is not exists.', 'wp-listings-directory')
					);
				} else {
					$user_code = get_user_meta($user_id, 'account_approve_key', true);
					if ( $code != $user_code ) {
						$error = array(
							'error' => true,
							'msg' => __('Code is not exactly.', 'wp-listings-directory')
						);
					}
				}

				if ( empty($error) ) {
					$return = self::update_user_status($user_id, 'approve');
					$error = array(
						'error' => false,
						'msg' => __('Your account approved.', 'wp-listings-directory')
					);
					$_SESSION['approve_user_msg'] = $error;
				} else {
					$_SESSION['approve_user_msg'] = $error;
				}
			}
		}
	}

	public static function approve_user( $user_id ) {
		$user = get_user_by('ID', $user_id);

		wp_cache_delete( $user->ID, 'users' );
		wp_cache_delete( $user->data->user_login, 'userlogins' );

		$user_email = stripslashes( $user->data->user_email );

		$subject = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user), 'user_register_approved', 'subject');
		$content = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user), 'user_register_approved', 'content');

		$email_from = get_option( 'admin_email', false );
		$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
		// send the mail
		WP_Listings_Directory_Email::wp_mail( $user_email, $subject, $content, $headers );

		// change usermeta tag in database to approved
		update_user_meta( $user->ID, 'user_account_status', 'approved' );
		update_user_meta( $user->ID, 'account_approve_key', '' );

		do_action( 'wp-listings-directory-new_user_approve_user_approved', $user );
	}

	public static function deny_user( $user_id ) {
		$user = get_user_by('ID', $user_id);

		$user_email = stripslashes( $user->data->user_email );

		$subject = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user), 'user_register_denied', 'subject');
		$content = WP_Listings_Directory_Email::render_email_vars(array('user_obj' => $user), 'user_register_denied', 'content');

		$email_from = get_option( 'admin_email', false );
		$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), $email_from );
		// send the mail
		WP_Listings_Directory_Email::wp_mail( $user_email, $subject, $content, $headers );

		update_user_meta( $user->ID, 'user_account_status', 'denied' );

		do_action( 'wp-listings-directory-new_user_approve_user_denied', $user );
	}

	public static function get_user_status( $user_id ) {
		$user_status = get_user_meta( $user_id, 'user_account_status', true );

		if ( empty( $user_status ) ) {
			$user_status = 'approved';
		}

		return $user_status;
	}

	public static function update_user_status( $user, $status ) {
		$user_id = absint( $user );
		if ( !$user_id ) {
			return false;
		}

		if ( !in_array( $status, array( 'approve', 'deny' ) ) ) {
			return false;
		}

		$do_update = apply_filters( 'wp_listings_directory_new_user_approve_validate_status_update', true, $user_id, $status );
		if ( !$do_update ) {
			return false;
		}

		// where it all happens
		do_action( 'wp_listings_directory_new_user_approve_' . $status . '_user', $user_id );
		do_action( 'wp_listings_directory_new_user_approve_user_status_update', $user_id, $status );

		return true;
	}

	public static function process_update_user_action() {
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'approve', 'deny' ) ) && !isset( $_GET['new_role'] ) ) {
			check_admin_referer( 'wp-listings-directory' );

			$sendback = remove_query_arg( array( 'approved', 'denied', 'deleted', 'ids', 'wp-listings-directory-status-query-submit', 'new_role' ), wp_get_referer() );
			if ( !$sendback ) {
				$sendback = admin_url( 'users.php' );
			}

			$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );

			$status = sanitize_key( $_GET['action'] );
			$user = absint( $_GET['user'] );

			self::update_user_status( $user, $status );

			if ( $_GET['action'] == 'approve' ) {
				$sendback = add_query_arg( array( 'approved' => 1, 'ids' => $user ), $sendback );
			} else {
				$sendback = add_query_arg( array( 'denied' => 1, 'ids' => $user ), $sendback );
			}

			wp_redirect( $sendback );
			exit;
		}
	}

	public static function validate_status_update( $do_update, $user_id, $status ) {
		$current_status = self::get_user_status( $user_id );

		if ( $status == 'approve' ) {
			$new_status = 'approved';
		} else {
			$new_status = 'denied';
		}

		if ( $current_status == $new_status ) {
			$do_update = false;
		}

		return $do_update;
	}

	/**
	 * Add the approve or deny link where appropriate.
	 *
	 * @uses user_row_actions
	 * @param array $actions
	 * @param object $user
	 * @return array
	 */
	public static function user_table_actions( $actions, $user ) {
		if ( $user->ID == get_current_user_id() ) {
			return $actions;
		}

		if ( is_super_admin( $user->ID ) ) {
			return $actions;
		}

		$user_status = self::get_user_status( $user->ID );

		$approve_link = add_query_arg( array( 'action' => 'approve', 'user' => $user->ID ) );
		$approve_link = remove_query_arg( array( 'new_role' ), $approve_link );
		$approve_link = wp_nonce_url( $approve_link, 'wp-listings-directory' );

		$deny_link = add_query_arg( array( 'action' => 'deny', 'user' => $user->ID ) );
		$deny_link = remove_query_arg( array( 'new_role' ), $deny_link );
		$deny_link = wp_nonce_url( $deny_link, 'wp-listings-directory' );

		$approve_action = '<a href="' . esc_url( $approve_link ) . '">' . __( 'Approve', 'wp-listings-directory' ) . '</a>';
		$deny_action = '<a href="' . esc_url( $deny_link ) . '">' . __( 'Deny', 'wp-listings-directory' ) . '</a>';

		if ( $user_status == 'pending' ) {
			$actions[] = $approve_action;
			$actions[] = $deny_action;
		} else if ( $user_status == 'approved' ) {
			$actions[] = $deny_action;
		} else if ( $user_status == 'denied' ) {
			$actions[] = $approve_action;
		}

		return $actions;
	}

	/**
	 * Add the status column to the user table
	 *
	 * @uses manage_users_columns
	 * @param array $columns
	 * @return array
	 */
	public static function add_column( $columns ) {
		$the_columns['phone'] = __( 'Phone', 'wp-listings-directory' );
		$the_columns['user_status'] = __( 'Status', 'wp-listings-directory' );

		$newcol = array_slice( $columns, 0, -1 );
		$newcol = array_merge( $newcol, $the_columns );
		$columns = array_merge( $newcol, array_slice( $columns, 1 ) );

		return $columns;
	}

	/**
	 * Show the status of the user in the status column
	 *
	 * @uses manage_users_custom_column
	 * @param string $val
	 * @param string $column_name
	 * @param int $user_id
	 * @return string
	 */
	public static function status_column( $val, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'phone' :
				$phone = get_user_meta($user_id, '_phone', true);
				return $phone;
				break;
			case 'user_status' :
				$status = self::get_user_status( $user_id );
				if ( $status == 'approved' ) {
					$status_i18n = __( 'approved', 'wp-listings-directory' );
				} else if ( $status == 'denied' ) {
					$status_i18n = __( 'denied', 'wp-listings-directory' );
				} else if ( $status == 'pending' ) {
					$status_i18n = __( 'pending', 'wp-listings-directory' );
				}
				return $status_i18n;
				break;

			default:
		}

		return $val;
	}

	/**
	 * Add a filter to the user table to filter by user status
	 *
	 * @uses restrict_manage_users
	 */
	public static function status_filter( $which ) {
		$id = 'wp_listings_directory_filter-' . $which;

		$filter_button = submit_button( __( 'Filter', 'wp-listings-directory' ), 'button', 'wp-listings-directory-status-query-submit', false, array( 'id' => 'wp-listings-directory-status-query-submit' ) );
		$filtered_status = null;
		if ( ! empty( $_REQUEST['wp_listings_directory_filter-top'] ) || ! empty( $_REQUEST['wp_listings_directory_filter-bottom'] ) ) {
			$filtered_status = esc_attr( ( ! empty( $_REQUEST['wp_listings_directory_filter-top'] ) ) ? $_REQUEST['wp_listings_directory_filter-top'] : $_REQUEST['wp_listings_directory_filter-bottom'] );
		}
		$statuses = array('pending', 'approved', 'denied');
		?>
		<label class="screen-reader-text" for="<?php echo $id ?>"><?php _e( 'View all users', 'wp-listings-directory' ); ?></label>
		<select id="<?php echo $id ?>" name="<?php echo $id ?>" style="float: none; margin: 0 0 0 15px;">
			<option value=""><?php _e( 'View all users', 'wp-listings-directory' ); ?></option>
		<?php foreach ( $statuses as $status ) : ?>
			<option value="<?php echo esc_attr( $status ); ?>"<?php selected( $status, $filtered_status ); ?>><?php echo esc_html( $status ); ?></option>
		<?php endforeach; ?>
		</select>
		<?php echo apply_filters( 'wp_listings_directory_filter_button', $filter_button ); ?>
		<style>
			#wp-listings-directory-status-query-submit {
				float: right;
				margin: 2px 0 0 5px;
			}
		</style>
	<?php
	}

	/**
	 * Modify the user query if the status filter is being used.
	 *
	 * @uses pre_user_query
	 * @param $query
	 */
    public static function filter_by_status( $query ) {
		global $wpdb;

		if ( !is_admin() ) {
			return;
		}
		
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();
		if ( isset( $screen ) && 'users' != $screen->id ) {
			return;
		}
		$filter = null;
		if ( ! empty( $_REQUEST['wp_listings_directory_filter-top'] ) || ! empty( $_REQUEST['wp_listings_directory_filter-bottom'] ) ) {
			$filter = esc_attr( ( ! empty( $_REQUEST['wp_listings_directory_filter-top'] ) ) ? $_REQUEST['wp_listings_directory_filter-top'] : $_REQUEST['wp_listings_directory_filter-bottom'] );
		}
		if ( $filter != null ) {

			if ( 'approved' == $filter ) {
				$meta_query = array(
					'relation' => 'OR',
					array(
						'key' => 'user_account_status',
						'value' => $filter,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'user_account_status',
						'value' => '',
					),
					array(
						'key' => 'user_account_status',
						'compare' => 'NOT EXISTS',
					)
				);
				$query->set('meta_query', $meta_query);
			} else {
				$meta_query = array (array (
					'key' => 'user_account_status',
					'value' => $filter,
					'compare' => 'LIKE'
				));
				$query->set('meta_query', $meta_query);
			}
		}
	}

	public static function register_msg($user) {
		$requires_approval = wp_listings_directory_get_option('users_requires_approval', 'auto');

		if ( $requires_approval == 'email_approve' ) {
			return __('Registration complete. Before you can login, you must active your account sent to your email address.', 'wp-listings-directory');
		} elseif ( $requires_approval == 'admin_approve' ) {
			return __('Registration complete. Your account has to be confirmed by an administrator before you can login', 'wp-listings-directory');
		} else {
			return __('Your account has to be confirmed yet.', 'wp-listings-directory');
		}
	}
	
	public static function login_msg($user) {
		$requires_approval = wp_listings_directory_get_option('users_requires_approval', 'auto');
		
		if ( $requires_approval == 'email_approve' ) {
			return sprintf(__('Account account has not confirmed yet, you must active your account with the link sent to your email address. If you did not receive this email, please check your junk/spam folder. <a href="javascript:void(0);" class="wp-listings-directory-resend-approve-account-btn" data-login="%s">Click here</a> to resend the activation email.', 'wp-listings-directory'), $user->user_login );
		} elseif ( $requires_approval == 'admin_approve' ) {
			return __('Your account has to be confirmed by an administrator before you can login.', 'wp-listings-directory');
		} else {
			return __('Your account has to be confirmed yet.', 'wp-listings-directory');
		}
	}

	public static function admin_register_user_profile_metabox() {
		$prefix = WP_LISTINGS_DIRECTORY_USER_PREFIX;
		
		$socials = WP_Listings_Directory_Mixes::get_socials_network();
        $opt_socials = [];
        foreach ($socials as $key => $value) {
            $opt_socials[$key] = $value['title'];
        }

		$fields = array(
			array(
                'name'              => __( 'Avatar', 'wp-listings-directory' ),
                'id'                => $prefix . 'avatar',
                'type'              => 'file',
                'text'    => array(
			        'add_upload_file_text' => __( 'Add Image', 'wp-listings-directory' ),
			    ),
                'query_args' => array(
			        'type' => array(
			            'image/gif',
			            'image/jpeg',
			            'image/png',
			        ),
			    ),
			    'preview_size' => 'thumbnail',
            ),
			array(
                'name'              => __( 'Friendly Address', 'wp-listings-directory' ),
                'id'                => $prefix . 'address',
                'type'              => 'text',
            ),
            array(
                'id'                => $prefix . 'map_location',
                'name'              => __( 'Map Location', 'wp-listings-directory' ),
                'type'              => 'pw_map',
                'sanitization_cb'   => 'pw_map_sanitise',
                'split_values'      => true,
                'object_type' 		=> 'user'
            ),
            array(
                'name'              => __( 'Phone', 'wp-listings-directory' ),
                'id'                => $prefix . 'phone',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Whatsapp', 'wp-listings-directory' ),
                'id'                => $prefix . 'whatsapp',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Photos', 'wp-listings-directory' ),
                'id'                => $prefix . 'photos',
                'type'              => 'file_list',
                'query_args' => array( 'type' => 'image' ),
			    'preview_size' => 'thumbnail',
            ),
            array(
                'name'              => __( 'Socials', 'wp-listings-directory' ),
                'id'                => $prefix . 'socials',
                'type'              => 'group',
                'options'           => array(
                    'group_title'       => __( 'Network {#}', 'wp-listings-directory' ),
                    'add_button'        => __( 'Add Another Network', 'wp-listings-directory' ),
                    'remove_button'     => __( 'Remove Network', 'wp-listings-directory' ),
                    'sortable'          => false,
                    'closed'         => true,
                ),
                'fields'            => array(
                    array(
                        'name'      => __( 'Network', 'wp-listings-directory' ),
                        'id'        => 'network',
                        'type'      => 'select',
                        'options'   => $opt_socials
                    ),
                    array(
                        'name'      => __( 'Url', 'wp-listings-directory' ),
                        'id'        => 'url',
                        'type'      => 'text',
                    ),
                ),
            ),
		);

		$fields = apply_filters('wp-listings-directory-get-user-profile-fields-admin', $fields);

		$cmb_user = new_cmb2_box( array(
			'id'               => $prefix . 'edit',
			'title'            => __( 'User Profile', 'wp-listings-directory' ),
			'object_types'     => array( 'user' ),
			'show_names'       => true,
			'new_user_section' => 'add-new-user',
			'fields' => $fields
		) );
	}

	public static function frontend_register_user_profile_metabox($metaboxes) {
		if ( is_admin() ) {
			return $metaboxes;
		}
		$prefix = WP_LISTINGS_DIRECTORY_USER_PREFIX;
		if ( is_user_logged_in() ) {
			$userdata = wp_get_current_user();
			$first_name = get_user_meta( $userdata->ID, 'first_name', true );
			$last_name = get_user_meta( $userdata->ID, 'last_name', true );
			$description = get_user_meta( $userdata->ID, 'description', true );
			$email = $userdata->user_email;
			$url = $userdata->user_url;
		}

		$socials = WP_Listings_Directory_Mixes::get_socials_network();
        $opt_socials = [];
        foreach ($socials as $key => $value) {
            $opt_socials[$key] = $value['title'];
        }
        
		$fields = array(
			array(
                'name'              => __( 'Avatar', 'wp-listings-directory' ),
                'id'                => $prefix . 'avatar',
                'type'              => 'wp_listings_directory_file',
                'ajax'              => true,
                'file_multiple'    => false,
                'mime_types'        => array( 'gif', 'jpeg', 'jpg', 'jpg|jpeg|jpe', 'png' ),
                'allow_mime_types' => array(
                    'image/gif', 'image/jpeg', 'image/png'
                ),
                'object_type' 		=> 'user'
            ),
            array(
                'name'              => __( 'First Name', 'wp-listings-directory' ),
                'id'                => $prefix . 'first_name',
                'type'              => 'text',
                'default'			=> !empty($first_name) ? $first_name : '',
            ),
            array(
                'name'              => __( 'Last Name', 'wp-listings-directory' ),
                'id'                => $prefix . 'last_name',
                'type'              => 'text',
                'default'			=> !empty($last_name) ? $last_name : '',
            ),
            array(
                'name'              => __( 'Email', 'wp-listings-directory' ),
                'id'                => $prefix . 'email',
                'type'              => 'text',
                'default'			=> !empty($email) ? $email : '',
            ),
            array(
                'name'              => __( 'Phone', 'wp-listings-directory' ),
                'id'                => $prefix . 'phone',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Whatsapp', 'wp-listings-directory' ),
                'id'                => $prefix . 'whatsapp',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Website', 'wp-listings-directory' ),
                'id'                => $prefix . 'url',
                'type'              => 'text',
                'default'			=> !empty($url) ? $url : '',
            ),
            array(
                'name'              => __( 'Photos', 'wp-listings-directory' ),
                'id'                => $prefix . 'photos',
			    'type'              => 'wp_listings_directory_file',
                'ajax'              => true,
                'file_multiple'    => true,
                'mime_types'        => array( 'gif', 'jpeg', 'jpg', 'jpg|jpeg|jpe', 'png' ),
                'allow_mime_types' => array(
                    'image/gif', 'image/jpeg', 'image/png'
                ),
                'object_type' 		=> 'user'
            ),
            array(
                'name'              => __( 'Friendly Address', 'wp-listings-directory' ),
                'id'                => $prefix . 'address',
                'type'              => 'text',
            ),
            array(
                'id'                => $prefix . 'map_location',
                'name'              => __( 'Map Location', 'wp-listings-directory' ),
                'type'              => 'pw_map',
                'sanitization_cb'   => 'pw_map_sanitise',
                'split_values'      => true,
                'object_type' 		=> 'user'
            ),
            array(
                'name'              => __( 'Description', 'wp-listings-directory' ),
                'id'                => $prefix . 'description',
                'type'              => 'textarea',
                'default'			=> !empty($description) ? $description : '',
            ),
            array(
                'name'              => __( 'Socials', 'wp-listings-directory' ),
                'id'                => $prefix . 'socials',
                'type'              => 'group',
                'options'           => array(
                    'group_title'       => __( 'Network {#}', 'wp-listings-directory' ),
                    'add_button'        => __( 'Add Another Network', 'wp-listings-directory' ),
                    'remove_button'     => __( 'Remove Network', 'wp-listings-directory' ),
                    'sortable'          => false,
                    'closed'         => true,
                ),
                'fields'            => array(
                    array(
                        'name'      => __( 'Network', 'wp-listings-directory' ),
                        'id'        => 'network',
                        'type'      => 'select',
                        'options'   => $opt_socials
                    ),
                    array(
                        'name'      => __( 'Url', 'wp-listings-directory' ),
                        'id'        => 'url',
                        'type'      => 'text',
                    ),
                ),
            ),
		);
		
		$fields = apply_filters('wp-listings-directory-get-user-profile-fields', $fields);

		$metaboxes[ $prefix . 'fields_front' ] = array(
			'id'                        => $prefix . 'fields_front',
			'title'                     => __( 'General Options', 'wp-listings-directory' ),
			'object_types'              => array( 'user' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

		return $metaboxes;
	}

	public static function process_change_profile() {
		$prefix = WP_LISTINGS_DIRECTORY_USER_PREFIX;

		if ( ! isset( $_POST['submit-cmb-profile'] ) ) {
			return;
		}

		$cmb = cmb2_get_metabox( $prefix . 'fields_front', 0 );
		if ( ! isset( $_POST[ $cmb->nonce() ] ) || ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() ) ) {
			return;
		}

		$email = isset($_POST[$prefix.'email']) ? sanitize_email( $_POST[$prefix.'email'] ) : '';

		if ( empty( $email ) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'E-mail is required.', 'wp-listings-directory' ) );
			return;
		}
		$user = wp_get_current_user();
		if ( $email != $user->user_email && email_exists($email) ) {
			$_SESSION['messages'][] = array( 'danger', __( 'E-mail is exists.', 'wp-listings-directory' ) );
			return;
		}
		do_action('wp-listings-directory-before-change-profile-normal');

		$url = isset($_POST[$prefix.'url']) ? esc_url_raw( $_POST[$prefix.'url'] ) : '';
		$data = array(
			'ID'			=> $user->ID,
			'user_email'	=> $email,
			'user_url'	=> $url,
		);

		$result = wp_update_user( $data );

		if ( $result ) {
			
			$cmb->save_fields( $user->ID, 'user', $_POST );

			$first_name = isset($_POST[$prefix.'first_name']) ? sanitize_text_field( $_POST[$prefix.'first_name'] ) : '';
			$last_name = isset($_POST[$prefix.'last_name']) ? sanitize_text_field( $_POST[$prefix.'last_name'] ) : '';
			$description = isset($_POST[$prefix.'description']) ? sanitize_text_field( $_POST[$prefix.'description'] ) : '';
			$url = isset($_POST[$prefix.'url']) ? sanitize_text_field( $_POST[$prefix.'url'] ) : '';

			update_user_meta( $user->ID, 'first_name', $first_name );
			update_user_meta( $user->ID, 'last_name', $last_name );
			update_user_meta( $user->ID, 'description', $description );
			update_user_meta( $user->ID, 'url', $url );

			$_SESSION['messages'][] = array( 'success', __( 'Profile has been successfully updated.', 'wp-listings-directory' ) );
		} else {
			$_SESSION['messages'][] = array( 'danger', __( 'Can not update profile.', 'wp-listings-directory' ) );
		}
	}

	public static function process_admin_profile_update($user_id, $old_user_data, $userdata ) {
		$prefix = WP_LISTINGS_DIRECTORY_USER_PREFIX;
		if ( is_admin() ) {
			$url = $userdata->user_url;
			$email = $userdata->user_email;
			update_user_meta( $user_id, $prefix.'url', $url );
			update_user_meta( $user_id, $prefix.'email', $email );

			$first_name = get_user_meta( $user_id, 'first_name', true );
			$last_name = get_user_meta( $user_id, 'last_name', true );
			$description = get_user_meta( $user_id, 'description', true );

			update_user_meta( $user_id, $prefix.'first_name', $first_name );
			update_user_meta( $user_id, $prefix.'last_name', $last_name );
			update_user_meta( $user_id, $prefix.'description', $description );
		}
	}

	public static function get_avatar($avatar, $id_or_email = '', $size = '', $default = '', $alt = '') {
	    if (is_object($id_or_email)) {
	        
	        $avatar_url = get_user_meta( $id_or_email->ID, '_user_avatar', true );
	        if ( !empty($avatar_url) ) {
		        
	        	$avatar_id = attachment_url_to_postid($avatar_url);

		        if ( !empty($avatar_id) ) {
		            $avatar_url = wp_get_attachment_image_src($avatar_id, 'thumbnail');
		            if ( !empty($avatar_url[0]) ) {
		                $avatar = '<img src="'.esc_url($avatar_url[0]).'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" class="avatar avatar-'.esc_attr($size).' wp-user-avatar wp-user-avatar-'.esc_attr($size).' photo avatar-default" />';
		            }
		        }
	        }
	    } else {
	        $avatar_url = get_user_meta( $id_or_email, '_user_avatar', true );
	        if ( !empty($avatar_url) ) {
		        
	        	$avatar_id = attachment_url_to_postid($avatar_url);
		        
		        if ( !empty($avatar_id) ) {
		            $avatar_url = wp_get_attachment_image_src($avatar_id, 'thumbnail');
		            if ( !empty($avatar_url[0]) ) {
		                $avatar = '<img src="'.esc_url($avatar_url[0]).'" width="'.esc_attr($size).'" height="'.esc_attr($size).'" alt="'.esc_attr($alt).'" class="avatar avatar-'.esc_attr($size).' wp-user-avatar wp-user-avatar-'.esc_attr($size).' photo avatar-default" />';
		            }
		        }
	        }
	    }
	    return $avatar;
	}
}

WP_Listings_Directory_User::init();