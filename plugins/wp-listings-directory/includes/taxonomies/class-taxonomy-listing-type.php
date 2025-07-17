<?php
/**
 * Types
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WP_ListingDealer_Taxonomy_Listing_Type{

	/**
	 *
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ), 1 );
	}

	/**
	 *
	 */
	public static function definition() {
		$singular = __( 'Type', 'wp-listings-directory' );
		$plural   = __( 'Types', 'wp-listings-directory' );

		$labels = array(
			'name'              => sprintf(__( 'Listing %s', 'wp-listings-directory' ), $plural),
			'singular_name'     => $singular,
			'search_items'      => sprintf(__( 'Search %s', 'wp-listings-directory' ), $plural),
			'all_items'         => sprintf(__( 'All %s', 'wp-listings-directory' ), $plural),
			'parent_item'       => sprintf(__( 'Parent %s', 'wp-listings-directory' ), $singular),
			'parent_item_colon' => sprintf(__( 'Parent %s:', 'wp-listings-directory' ), $singular),
			'edit_item'         => __( 'Edit', 'wp-listings-directory' ),
			'update_item'       => __( 'Update', 'wp-listings-directory' ),
			'add_new_item'      => __( 'Add New', 'wp-listings-directory' ),
			'new_item_name'     => sprintf(__( 'New %s', 'wp-listings-directory' ), $singular),
			'menu_name'         => $plural,
		);

		$rewrite_slug = get_option('wp_listings_directory_listing_type_slug');
		if ( empty($rewrite_slug) ) {
			$rewrite_slug = _x( 'listing-type', 'Listing type slug - resave permalinks after changing this', 'wp-listings-directory' );
		}
		$rewrite = array(
			'slug'         => $rewrite_slug,
			'with_front'   => false,
			'hierarchical' => false,
		);
		register_taxonomy( 'listing_type', 'listing', array(
			'labels'            => apply_filters( 'wp_listings_directory_taxomony_listing_type_labels', $labels ),
			'hierarchical'      => true,
			'rewrite'           => $rewrite,
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'		=> true,
		) );
	}
}

WP_ListingDealer_Taxonomy_Listing_Type::init();