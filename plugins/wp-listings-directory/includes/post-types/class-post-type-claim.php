<?php
/**
 * Post Type: Claim
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Post_Type_Claim {
	public static function init() {
	  	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
	  	add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'fields' ) );

	  	add_filter( 'manage_edit-saved_search_columns', array( __CLASS__, 'custom_columns' ) );
		add_action( 'manage_saved_search_posts_custom_column', array( __CLASS__, 'custom_columns_manage' ) );
	}

	public static function register_post_type() {
		$singular = __( 'Claim', 'wp-listings-directory' );
		$plural   = __( 'Claims', 'wp-listings-directory' );

		$labels = array(
			'name'                  => $plural,
			'singular_name'         => $singular,
			'add_new'               => sprintf(__( 'Add New %s', 'wp-listings-directory' ), $singular),
			'add_new_item'          => sprintf(__( 'Add New %s', 'wp-listings-directory' ), $singular),
			'edit_item'             => sprintf(__( 'Edit %s', 'wp-listings-directory' ), $singular),
			'new_item'              => sprintf(__( 'New %s', 'wp-listings-directory' ), $singular),
			'all_items'             => sprintf(__( '%s', 'wp-listings-directory' ), $plural),
			'view_item'             => sprintf(__( 'View %s', 'wp-listings-directory' ), $singular),
			'search_items'          => sprintf(__( 'Search %s', 'wp-listings-directory' ), $singular),
			'not_found'             => sprintf(__( 'No %s found', 'wp-listings-directory' ), $singular),
			'not_found_in_trash'    => sprintf(__( 'No %s found in Trash', 'wp-listings-directory' ), $singular),
			'parent_item_colon'     => '',
			'menu_name'             => $plural,
		);
		
		register_post_type( 'claim',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title' ),
				'public'            => true,
		        'has_archive'       => false,
		        'publicly_queryable' => false,
				'show_in_menu'		=> 'edit.php?post_type=listing',
				'show_in_rest'		=> true,
				'capabilities' => array(
				    'create_posts' => false,
				),
				'map_meta_cap' => true,
			)
		);
	}

	/**
	 * Defines custom fields
	 *
	 * @access public
	 * @param array $metaboxes
	 * @return array
	 */
	public static function fields( array $metaboxes ) {
		
		$fields = array();
		$claim_for_title = '';
		if ( isset($_GET['post']) && $_GET['post'] && is_admin() ) {
			$post = get_post($_GET['post']);
			if ( $post && $post->post_type == 'claim' ) {
				$listing_id = get_post_meta($post->ID, WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'claim_for', true);
				$claim_for_title = get_the_title($listing_id);
			}
		}
		$fields[] = array(
			'name'              => __( 'Claim For: ', 'wp-listings-directory' ). $claim_for_title,
			'id'                => WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'claim_for_title',
			'type'              => 'title',
		);

		$fields[] = array(
			'name'              => __( 'Status', 'wp-listings-directory' ),
			'id'                => WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'status',
			'type'              => 'select',
			'options' 			=> array(
				'pending' => esc_html__( 'pending', 'wp-listings-directory' ),
				'approved' => esc_html__( 'Approved', 'wp-listings-directory' ),
				'decline' => esc_html__( 'Decline', 'wp-listings-directory' ),
			)
		);
		
		$fields[] = array(
			'name'              => __( 'Claim Details', 'wp-listings-directory' ),
			'id'                => WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'detail',
			'type'              => 'textarea',
		);

		$metaboxes[ WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'general' ] = array(
			'id'                        => WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX . 'general',
			'title'                     => __( 'General Options', 'wp-listings-directory' ),
			'object_types'              => array( 'claim' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'show_in_rest'				=> true,
			'fields'                    => $fields
		);
		return $metaboxes;
	}
	/**
	 * Custom admin columns for post type
	 *
	 * @access public
	 * @return array
	 */
	public static function custom_columns() {
		$fields = array(
			'cb' 				=> '<input type="checkbox" />',
			'title' 			=> esc_html__( 'Title', 'wp-listings-directory' ),
			'author' 			=> esc_html__( 'Author', 'wp-listings-directory' ),
			'status' 		=> esc_html__( 'Status', 'wp-listings-directory' ),
			'date' 		=> esc_html__( 'Date', 'wp-listings-directory' ),
		);
		return $fields;
	}

	/**
	 * Custom admin columns implementation
	 *
	 * @access public
	 * @param string $column
	 * @return array
	 */
	public static function custom_columns_manage( $column ) {
		switch ( $column ) {
			case 'status':
				$status = get_post_meta( get_the_ID(), WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX.'claim_status', true );
				$statuses = array(
					'pending' => esc_html__( 'pending', 'wp-listings-directory' ),
					'approved' => esc_html__( 'Approved', 'wp-listings-directory' ),
					'decline' => esc_html__( 'Decline', 'wp-listings-directory' ),
				);
				echo isset($statuses[$status]) ? $statuses[$status] : '';
				break;
		}
	}

}
WP_Listings_Directory_Post_Type_Claim::init();