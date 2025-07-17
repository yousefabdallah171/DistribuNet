<?php
/**
 * Permalink Settings
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Listings_Directory_Permalink_Settings {
	
	public static function init() {
		add_action('admin_init', array( __CLASS__, 'setup_fields') );
		add_action('admin_init', array( __CLASS__, 'settings_save') );
	}

	public static function setup_fields() {
		$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance(0);

		add_settings_field(
			'wp_listings_directory_listing_base_slug',
			__( 'Listing base', 'wp-listings-directory' ),
			array( __CLASS__, 'listing_base_slug_input' ),
			'permalink',
			'optional'
		);
		if ( $meta_obj->check_post_meta_exist('type') ) {
			add_settings_field(
				'wp_listings_directory_listing_type_slug',
				__( 'Listing type base', 'wp-listings-directory' ),
				array( __CLASS__, 'listing_type_slug_input' ),
				'permalink',
				'optional'
			);
		}
		if ( $meta_obj->check_post_meta_exist('location') ) {
			add_settings_field(
				'wp_listings_directory_listing_location_slug',
				__( 'Listing location base', 'wp-listings-directory' ),
				array( __CLASS__, 'listing_location_slug_input' ),
				'permalink',
				'optional'
			);
		}
		if ( $meta_obj->check_post_meta_exist('category') ) {
			add_settings_field(
				'wp_listings_directory_listing_category_slug',
				__( 'Listing category base', 'wp-listings-directory' ),
				array( __CLASS__, 'listing_category_slug_input' ),
				'permalink',
				'optional'
			);
		}

		//
		add_settings_field(
			'wp_listings_directory_listing_archive_slug',
			__( 'Listing archive page', 'wp-listings-directory' ),
			array( __CLASS__, 'listing_archive_slug_input' ),
			'permalink',
			'optional'
		);

	}

	public static function listing_base_slug_input() {
		$value = get_option('wp_listings_directory_listing_base_slug');
		?>
		<input name="wp_listings_directory_listing_base_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'listing', 'wp-listings-directory' ); ?>" />
		<?php
	}

	public static function listing_category_slug_input() {
		$value = get_option('wp_listings_directory_listing_category_slug');
		?>
		<input name="wp_listings_directory_listing_category_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'listing-category', 'wp-listings-directory' ); ?>" />
		<?php
	}

	public static function listing_type_slug_input() {
		$value = get_option('wp_listings_directory_listing_type_slug');
		?>
		<input name="wp_listings_directory_listing_type_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'listing-type', 'wp-listings-directory' ); ?>" />
		<?php
	}

	public static function listing_location_slug_input() {
		$value = get_option('wp_listings_directory_listing_location_slug');
		?>
		<input name="wp_listings_directory_listing_location_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'listing-location', 'wp-listings-directory' ); ?>" />
		<?php
	}

	public static function listing_archive_slug_input() {
		$value = get_option('wp_listings_directory_listing_archive_slug');
		?>
		<input name="wp_listings_directory_listing_archive_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'listings', 'wp-listings-directory' ); ?>" />
		<?php
	}

	public static function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( isset( $_POST['permalink_structure'] ) ) {
			if ( function_exists( 'switch_to_locale' ) ) {
				switch_to_locale( get_locale() );
			}
			if ( isset($_POST['wp_listings_directory_listing_base_slug']) ) {
				update_option( 'wp_listings_directory_listing_base_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_base_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_category_slug']) ) {
				update_option( 'wp_listings_directory_listing_category_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_category_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_color_slug']) ) {
				update_option( 'wp_listings_directory_listing_color_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_color_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_condition_slug']) ) {
				update_option( 'wp_listings_directory_listing_condition_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_condition_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_cylinder_slug']) ) {
				update_option( 'wp_listings_directory_listing_cylinder_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_cylinder_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_type_slug']) ) {
				update_option( 'wp_listings_directory_listing_type_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_type_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_location_slug']) ) {
				update_option( 'wp_listings_directory_listing_location_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_location_slug']) );
			}
			if ( isset($_POST['wp_listings_directory_listing_category_slug']) ) {
				update_option( 'wp_listings_directory_listing_category_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_category_slug']) );
			}
			

			if ( isset($_POST['wp_listings_directory_listing_archive_slug']) ) {
				update_option( 'wp_listings_directory_listing_archive_slug', sanitize_title_with_dashes($_POST['wp_listings_directory_listing_archive_slug']) );
			}

		}
	}
}

WP_Listings_Directory_Permalink_Settings::init();
