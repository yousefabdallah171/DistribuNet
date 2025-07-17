<?php
/**
 * Listing Meta
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Listing_Meta {

	private static $_instance = null;
	private $metas = null;
	private $post_id = null;

	public static function get_instance($post_id) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self($post_id);
		} else {
			self::$_instance->post_id = $post_id;
		}
		return self::$_instance;
	}

	public function __construct($post_id) {
		$this->post_id = $post_id;
		$this->metas = $this->get_post_metas();
	}

	public function get_post_metas() {
		$return = array();
		$fields = WP_Listings_Directory_Custom_Fields::get_custom_fields(array(), false);
		if ( !empty($fields) ) {
			foreach ($fields as $field) {
				if ( !empty($field['id']) ) {
					$return[$field['id']] = $field;
				}
			}
		}
		return apply_filters('wp-listings-directory-get-listing-metas', $return);
	}

	public function get_metas() {
		return $this->metas;
	}

	public function check_post_meta_exist($key) {
		if ( isset($this->metas[WP_LISTINGS_DIRECTORY_LISTING_PREFIX.$key]) ) {
			return true;
		}
		return false;
	}

	public function check_custom_post_meta_exist($key) {
		if ( isset($this->metas[$key]) ) {
			return true;
		}
		return false;
	}
	
	public function get_post_meta($key) {
		return get_post_meta($this->post_id, WP_LISTINGS_DIRECTORY_LISTING_PREFIX.$key, true);
	}

	public function get_custom_post_meta($key) {
		return get_post_meta($this->post_id, $key, true);
	}

	public function get_post_meta_title($key) {
		if ( !empty($this->metas[WP_LISTINGS_DIRECTORY_LISTING_PREFIX.$key]) && isset($this->metas[WP_LISTINGS_DIRECTORY_LISTING_PREFIX.$key]['name'])) {
			return $this->metas[WP_LISTINGS_DIRECTORY_LISTING_PREFIX.$key]['name'];
		}
		return '';
	}

	public function get_custom_post_meta_title($key) {
		if ( !empty($this->metas[$key]) && isset($this->metas[$key]['name'])) {
			return $this->metas[$key]['name'];
		}
		return '';
	}
	
	public function get_price_html($html = true) {
		$price_from = $this->get_price_from_html($html);
		$price_to = $this->get_price_to_html($html);
		$price_html = '';
		if ( $price_from ) {
			$price_html = $price_from;
		}
		if ( $price_to ) {
			$price_html .= (!empty($price_html) ? ' - ' : '').$price_to;
		}

		return apply_filters( 'wp-listings-directory-get-price-html', $price_html, $this->post_id, $this );
	}

	public function get_price_from_html( $html = true ) {
		
		if ( !$this->check_post_meta_exist('price_from') ) {
			return false;
		}
		$price = $this->get_post_meta( 'price_from' );

		if ( $price == '0' ) {
			$price = 0;
		} elseif ( empty( $price ) || ! is_numeric( $price ) ) {
			return false;
		}

		if ( !$html ) {
			$price = WP_Listings_Directory_Price::format_price_without_html( $price );
		} else {
			$price = WP_Listings_Directory_Price::format_price( $price );
		}

		return apply_filters( 'wp-listings-directory-get-price-from-html', $price, $this->post_id, $html );
	}

	public function get_price_to_html( $html = true ) {

		if ( !$this->check_post_meta_exist('price_to') ) {
			return false;
		}
		$price = $this->get_post_meta( 'price_to' );

		if ( $price == '0' ) {
			$price = 0;
		} elseif ( empty( $price ) || ! is_numeric( $price ) ) {
			return false;
		}

		if ( !$html ) {
			$price = WP_Listings_Directory_Price::format_price_without_html( $price );
		} else {
			$price = WP_Listings_Directory_Price::format_price( $price );
		}

		return apply_filters( 'wp-listings-directory-get-price-to-html', $price, $this->post_id, $html );
	}
}
