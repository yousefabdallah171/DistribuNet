<?php
/**
 * Claim
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class WP_Listings_Directory_Claim {

	public static function init() {
        add_action( 'wpld_ajax_wp_listings_directory_add_claim_listing',  array( __CLASS__, 'claim_listing' ) );

        add_action( 'save_post', array( __CLASS__, 'update_listing' ) );
	}

    public static function claim_listing() {
        $listing_id = !empty($_POST['listing_id']) ? $_POST['listing_id'] : '';
        $listing = get_post($listing_id);
        if ( is_user_logged_in() && is_object($listing) ) {

            $author_nicename = '';
            $emailexist = false;

            $current_user = wp_get_current_user();
            $userID = $current_user->ID;
            $userData = get_user_by( 'id', $userID );
            $claimer_email = $userData->user_email;

            $claimer_name = !empty($_POST['fullname']) ? sanitize_text_field($_POST['fullname']) : '';
            $post_title = $listing->post_title;
            $post_url = get_permalink($listing);
            $posttitle = esc_html__('Claim for', 'wp-listings-directory').' '. $post_title;
            $message = !empty($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
            $phone = !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

            $details = $claimer_name . ' : ' . $claimer_email . ' : ' . $phone.' : ' . $message;

            $claim_post = array(
                'post_title'    => wp_strip_all_tags( $posttitle ),
                'post_type'   => 'claim',
                'post_status'   => 'publish',
            );

            $post_id = wp_insert_post( $claim_post );
            self::uptdate_claim_meta($post_id, 'claim_for', $listing_id);
            self::uptdate_claim_meta($post_id, 'status', 'pending');
            self::uptdate_claim_meta($post_id, 'detail', $details);

            // send email
            $args = array(
                'listing_title' => $post_title,
                'listing_url' => $post_url,
                'email' => $claimer_email
            );

            // send email for claimer
            $args['type'] = 'claimer';
            self::send_email($args);

            // send email for admin
            $args['email'] = get_option( 'admin_email' );
            $args['type'] = 'admin';
            self::send_email($args);

            // send email for author
            $post_author = $listing->post_author;
            $user = get_user_by( 'id', $post_author );
            $author_email = $user->user_email;
            $args['email'] = $author_email;
            $args['type'] = 'author';
            self::send_email($args);

            $result = $post_id;

            $return = array(
                'msg' => esc_html__('Claim has been submitted.','wp-listings-directory'),
                'result' => $result
            );
        } else {
            $return = array(
                'msg' => esc_html__('Can not claim this listing', 'wp-listings-directory'),
                'result' => false
            );
        }
        echo json_encode($return);
        exit();
    }

    public static function send_email($args) {
        extract($args);
        $subject = WP_Listings_Directory_Email::render_email_vars( array('user_name' => $user->display_name), 'claim_'.$type.'_notice', 'subject');
        $content = WP_Listings_Directory_Email::render_email_vars( array('user_name' => $user->display_name), 'claim_'.$type.'_notice', 'content');

        $subject = str_replace('{{listing_title}}', $listing_title, $subject);
        $subject = str_replace('{{listing_url}}', $listing_url, $subject);

        $content = str_replace('{{listing_title}}', $listing_title, $content);
        $content = str_replace('{{listing_url}}', $listing_url, $content);

        $headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), get_option( 'admin_email' ) );
        WP_Listings_Directory_Email::wp_mail( $email, $subject, $content, $headers );
    }

    public static function uptdate_claim_meta($post_id, $key, $value) {
        update_post_meta($post_id, WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX.$key, $value);
    }

    public static function get_claim_meta($post_id, $key, $single = true) {
        return get_post_meta($post_id, WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX.$key, $single);
    }

    public static function update_listing($post_id) {
        $post_type = get_post_type($post_id);
        
        if ( $post_type != 'claim' ) {
            return;
        }

        $claim_for = self::get_claim_meta($post_id, 'claim_for');

        $status = self::get_claim_meta($post_id, 'status');
        if ( !empty($_POST[WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'status']) ) {
        	$status = $_POST[WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'status'];
        }
        
        $listing = get_post($claim_for);
        if( empty($listing) ) {
            return;
        }
        $author_id = $listing->post_author;

        if ( !empty($status) && $status == 'approved' ) {
            
            $oldusermeta = get_user_by( 'id', $author_id );
            $author_email = $oldusermeta->user_email;
            
            update_post_meta( $listing->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'claimed', 1 );
            
            $subject = WP_Listings_Directory_Email::render_email_vars( array('user_name' => $user->display_name), 'claim_approved_notice', 'subject');
	        $content = WP_Listings_Directory_Email::render_email_vars( array('user_name' => $user->display_name), 'claim_approved_notice', 'content');

	        $subject = str_replace('{{listing_title}}', $listing_title, $subject);
	        $subject = str_replace('{{listing_url}}', $listing_url, $subject);

	        $content = str_replace('{{listing_title}}', $listing_title, $content);
	        $content = str_replace('{{listing_url}}', $listing_url, $content);

	        $headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", get_bloginfo('name'), get_option( 'admin_email' ) );
	        WP_Listings_Directory_Email::wp_mail( $author_email, $subject, $content, $headers );
        }
        
    }
}

WP_Listings_Directory_Claim::init();