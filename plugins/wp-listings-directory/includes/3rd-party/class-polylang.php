<?php
/**
 * Polylang
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Polylang {
	
	public static function init() {
		if ( did_action( 'pll_init' ) ) {

			add_filter( 'wp-listings-directory-get-custom-fields-key', array(__CLASS__, 'custom_fields_key'), 100, 1 );
			add_filter( 'wp-listings-directory-get-custom-fields-data', array(__CLASS__, 'get_custom_fields_data'), 100, 1 );

			add_filter( 'wp-listings-directory-post-id', array(__CLASS__, 'get_post_id'), 10, 2 );

			
			add_filter( 'wp_listings_directory_settings_listing_submission', array(__CLASS__, 'hide_page_selection'), 100 );
			add_filter( 'wp_listings_directory_settings_pages', array(__CLASS__, 'hide_page_selection'), 100 );
			
			add_filter( 'wp-listings-directory-current-lang', array(__CLASS__, 'get_listings_lang') );
		}
	}

	public static function get_listings_lang($lang) {
		if ( function_exists( 'pll_current_language' ) && function_exists( 'pll_is_translated_post_type' ) && pll_is_translated_post_type( 'listing' ) ) {
			return pll_current_language();
		}
		return $lang;
	}

	public static function get_icl_object_id($post_id, $post_type) {

        $current_lang = pll_current_language();
        $translations = pll_get_post_translations($post_id);
        $icl_post_id = !empty($translations[$current_lang]) ? $translations[$current_lang] : 0;
        if ($icl_post_id > 0) {
            $post_id = $icl_post_id;
        }

        return $post_id;
	}

	public static function get_all_translations_object_id($post_id) {
		if ( function_exists('pll_get_post_translations') ) {
			$post_ids = pll_get_post_translations($post_id);
		} else {
			$post_ids = array($post_id);
		}
		
        return $post_ids;
	}
	
	public static function custom_fields_key($key) {
		if ( function_exists( 'pll_current_language' ) && function_exists( 'pll_default_language' ) ) {
			$default_lang = pll_default_language();
			$current_lang = pll_current_language();
			if ( $default_lang != $current_lang ) {
				$key = $key.'_'.$current_lang;
			}
		}
		return $key;
	}

	public static function get_custom_fields_data($value) {
		if ( empty($value) ) {
			$value = get_option('wp_listings_directory_fields_data', array());
		}
		return $value;
	}

	public static function get_post_id($post_id, $post_type = 'page') {
		if ( function_exists( 'pll_get_post' ) ) {
			$post_id = pll_get_post( $post_id );
		}
		return absint( $post_id );
	}

	public static function hide_page_selection($fields) {
		$current_lang = pll_current_language();
		$default_lang = pll_default_language();
		if ( $current_lang == $default_lang ) {
			return $fields;
		}
		$tab = '';
		if ( !empty($_GET['tab']) ) {
			$tab = '&tab='.$_GET['tab'];
		}
		
		$url_to_edit_page = admin_url( 'edit.php?post_type=listing&page=listing-settings'.$tab.'&lang=' . $default_lang );

		foreach ($fields as $key => $field) {
			if ( !empty($field['page-type']) && $field['page-type'] == 'page' ) {
				$fields[$key]['type'] = 'wp_listings_directory_hidden';
				$fields[$key]['human_value'] = __( 'Page Not Set', 'wp-listings-directory' );

				$current_value = get_option( $field['id'] );
				if ( $current_value ) {
					$page = pll_get_post( $current_value, $current_lang );

					if ( $page ) {
						$fields[$key]['human_value'] = $page->post_title;
					}
				}
				
				// translators: Placeholder (%s) is the URL to edit the primary language in WPML.
				$fields[$key]['desc'] = sprintf( __( '<a href="%s">Switch to primary language</a> to edit this setting.', 'wp-listings-directory' ), $url_to_edit_page );
			}
		}
		return $fields;
	}

}

WP_Listings_Directory_Polylang::init();