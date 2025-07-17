<?php
/**
 * Post Type: Car Listing
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Post_Type_Listing {
	public static $prefix = WP_LISTINGS_DIRECTORY_LISTING_PREFIX;
	public static function init() {
		add_filter('use_block_editor_for_post_type', array( __CLASS__, 'disable_gutenberg' ), 10, 2);
	  	add_action( 'init', array( __CLASS__, 'register_post_type' ) );
	  	add_action( 'admin_menu', array( __CLASS__, 'add_pending_count_to_menu' ) );

	  	add_filter( 'cmb2_admin_init', array( __CLASS__, 'metaboxes' ) );
  		
	  	add_filter( 'manage_edit-listing_columns', array( __CLASS__, 'custom_columns' ) );
		add_action( 'manage_listing_posts_custom_column', array( __CLASS__, 'custom_columns_manage' ) );
		add_action( 'restrict_manage_posts', array( __CLASS__, 'filter_car_by_taxonomy' ) );
		// add_action( 'parse_query', array( __CLASS__, 'filter_car_by_taxonomy_in_query' ) );

		add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );

		add_action( 'pending_to_publish', array( __CLASS__, 'set_expiry_date' ) );
		add_action( 'pending_payment_to_publish', array( __CLASS__, 'set_expiry_date' ) );
		add_action( 'preview_to_publish', array( __CLASS__, 'set_expiry_date' ) );
		add_action( 'draft_to_publish', array( __CLASS__, 'set_expiry_date' ) );
		add_action( 'auto-draft_to_publish', array( __CLASS__, 'set_expiry_date' ) );
		add_action( 'expired_to_publish', array( __CLASS__, 'set_expiry_date' ) );

		add_action( 'wp_listings_directory_check_for_expired_listings', array('WP_Listings_Directory_Listing', 'check_for_expired_listings') );
		add_action( 'wp_listings_directory_delete_old_previews', array('WP_Listings_Directory_Listing', 'delete_old_previews') );

		add_action( 'wp_listings_directory_email_daily_notices', array( 'WP_Listings_Directory_Listing', 'send_admin_expiring_notice' ) );
		add_action( 'wp_listings_directory_email_daily_notices', array( 'WP_Listings_Directory_Listing', 'send_author_expiring_notice' ) );
		add_action( 'template_redirect', array( 'WP_Listings_Directory_Listing', 'track_listing_view' ), 20 );

		add_action( "cmb2_save_field_".self::$prefix."expiry_date", array( __CLASS__, 'save_expiry_date' ), 10, 3 );

		// Ajax endpoints.
		add_action( 'wpld_ajax_wp_listings_directory_ajax_remove_listing',  array(__CLASS__,'process_remove_listing') );
	}

	public static function disable_gutenberg($current_status, $post_type) {
	    if ($post_type === 'listing') {
	    	return false;
	    }
	    return $current_status;
	}

	public static function register_post_type() {
		$singular = __( 'Listing', 'wp-listings-directory' );
		$plural   = __( 'Listings', 'wp-listings-directory' );

		$labels = array(
			'name'                  => $plural,
			'singular_name'         => $singular,
			'add_new'               => sprintf(__( 'Add New %s', 'wp-listings-directory' ), $singular),
			'add_new_item'          => sprintf(__( 'Add New %s', 'wp-listings-directory' ), $singular),
			'edit_item'             => sprintf(__( 'Edit %s', 'wp-listings-directory' ), $singular),
			'new_item'              => sprintf(__( 'New %s', 'wp-listings-directory' ), $singular),
			'all_items'             => sprintf(__( 'All %s', 'wp-listings-directory' ), $plural),
			'view_item'             => sprintf(__( 'View %s', 'wp-listings-directory' ), $singular),
			'search_items'          => sprintf(__( 'Search %s', 'wp-listings-directory' ), $singular),
			'not_found'             => sprintf(__( 'No %s found', 'wp-listings-directory' ), $singular),
			'not_found_in_trash'    => sprintf(__( 'No %s found in Trash', 'wp-listings-directory' ), $singular),
			'parent_item_colon'     => '',
			'menu_name'             => $plural,
		);
		$has_archive = true;
		$listing_archive = get_option('wp_listings_directory_listing_archive_slug');
		if ( $listing_archive ) {
			$has_archive = $listing_archive;
		}
		$listing_rewrite_slug = get_option('wp_listings_directory_listing_base_slug');
		if ( empty($listing_rewrite_slug) ) {
			$listing_rewrite_slug = _x( 'listing', 'Listing slug - resave permalinks after changing this', 'wp-listings-directory' );
		}
		$rewrite = array(
			'slug'       => $listing_rewrite_slug,
			'with_front' => false
		);
		register_post_type( 'listing',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title', 'editor', 'thumbnail', 'comments' ),
				'public'            => true,
				'has_archive'       => $has_archive,
				'rewrite'           => $rewrite,
				'menu_position'     => 51,
				'categories'        => array(),
				'menu_icon'         => 'dashicons-admin-home',
				'show_in_rest'		=> true,
			)
		);
	}

	/**
	 * Adds pending count to WP admin menu label
	 *
	 * @access public
	 * @return void
	 */
	public static function add_pending_count_to_menu() {
		global $menu;
		$menu_item_index = null;

		foreach( $menu as $index => $menu_item ) {
			if ( ! empty( $menu_item[5] ) && $menu_item[5] == 'menu-posts-listing' ) {
				$menu_item_index = $index;
				break;
			}
		}

		if ( $menu_item_index ) {
			$count = WP_Listings_Directory_Cache_Helper::get_listings_count();

			if ( $count > 0 ) {
				$menu_title = $menu[ $menu_item_index ][0];
				$menu_title = sprintf('%s <span class="awaiting-mod"><span class="pending-count">%d</span></span>', $menu_title, $count );
				$menu[ $menu_item_index ][0] = $menu_title;
			}
		}
	}

	public static function save_expiry_date($updated, $action, $obj) {
		if ( $action != 'disabled' ) {
			$key = self::$prefix.'expiry_date';
			$data_to_save = $obj->data_to_save;
			$post_id = !empty($data_to_save['post_ID']) ? $data_to_save['post_ID'] : '';
			$expiry_date = isset($data_to_save[$key]) ? $data_to_save[$key] : '';
			if ( empty( $expiry_date ) ) {
				if ( wp_listings_directory_get_option( 'submission_duration' ) ) {
					$expires = WP_Listings_Directory_Listing::calculate_listing_expiry( $post_id );
					update_post_meta( $post_id, $key, $expires );
				} else {
					delete_post_meta( $post_id, $key );
				}
			} else {
				update_post_meta( $post_id, self::$prefix.'expiry_date', date( 'Y-m-d', strtotime( sanitize_text_field( $expiry_date ) ) ) );
			}

		}
	}

	public static function save_post($post_id, $post) {
		if ( $post->post_type === 'listing' ) {
			$post_args = array();
			if ( !empty($_POST[self::$prefix . 'posted_by']) ) {
				$post_args['post_author'] = $_POST[self::$prefix . 'posted_by'];
			}

			if ( !empty($_POST[self::$prefix . 'featured']) ) {
				$menu_order = -1;
			} else {
				$menu_order = 0;
			}

			$post_args['menu_order'] = apply_filters('wp-listings-directory-listing-set-menu-order', $menu_order, $post_id);

			$expiry_date = get_post_meta( $post_id, self::$prefix.'expiry_date', true );
			$today_date = date( 'Y-m-d', current_time( 'timestamp' ) );
			$is_listing_expired = $expiry_date && $today_date > $expiry_date;

			if ( $is_listing_expired && ! WP_Listings_Directory_Listing::is_listing_status_changing( null, 'draft' ) ) {

				if ( !empty($_POST) ) {
					if ( WP_Listings_Directory_Listing::is_listing_status_changing( 'expired', 'publish' ) ) {
						if ( empty($_POST[self::$prefix.'expiry_date']) || strtotime( $_POST[self::$prefix.'expiry_date'] ) < current_time( 'timestamp' ) ) {
							$expires = WP_Listings_Directory_Listing::calculate_listing_expiry( $post_id );
							update_post_meta( $post_id, self::$prefix.'expiry_date', WP_Listings_Directory_Listing::calculate_listing_expiry( $post_id ) );
							if ( isset( $_POST[self::$prefix.'expiry_date'] ) ) {
								$_POST[self::$prefix.'expiry_date'] = $expires;
							}
						}
					} else {
						$post_args['post_status'] = 'expired';
					}
				}
			}
			if ( !empty($post_args) ) {
				$post_args['ID'] = $post_id;

				remove_action('save_post', array( __CLASS__, 'save_post' ), 10, 2 );
				wp_update_post( $post_args );
				add_action('save_post', array( __CLASS__, 'save_post' ), 10, 2 );
			}

			delete_transient( 'wp_listings_directory_filter_counts' );
			
			clean_post_cache( $post_id );
		}
	}

	public static function set_expiry_date( $post ) {

		if ( $post->post_type === 'listing' ) {

			// See if it is already set.
			if ( metadata_exists( 'post', $post->ID, self::$prefix.'expiry_date' ) ) {
				$expires = get_post_meta( $post->ID, self::$prefix.'expiry_date', true );

				// if ( $expires && strtotime( $expires ) < current_time( 'timestamp' ) ) {
				// 	update_post_meta( $post->ID, self::$prefix.'expiry_date', '' );
				// }
			}

			// See if the user has set the expiry manually.
			if ( ! empty( $_POST[self::$prefix.'expiry_date'] ) ) {
				update_post_meta( $post->ID, self::$prefix.'expiry_date', date( 'Y-m-d', strtotime( sanitize_text_field( $_POST[self::$prefix.'expiry_date'] ) ) ) );
			} elseif ( ! isset( $expires ) ) {
				// No manual setting? Lets generate a date if there isn't already one.
				$expires = WP_Listings_Directory_Listing::calculate_listing_expiry( $post->ID );
				update_post_meta( $post->ID, self::$prefix.'expiry_date', $expires );

				// In case we are saving a post, ensure post data is updated so the field is not overridden.
				if ( isset( $_POST[self::$prefix.'expiry_date'] ) ) {
					$_POST[self::$prefix.'expiry_date'] = $expires;
				}
			}
		}
	}

	public static function process_remove_listing() {
		if ( !isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wp-listings-directory-delete-listing-nonce' )  ) {
			$return = array( 'status' => false, 'msg' => esc_html__('Your nonce did not verify.', 'wp-listings-directory') );
		   	echo wp_json_encode($return);
		   	exit;
		}

		if ( ! is_user_logged_in() ) {
	        $return = array( 'status' => false, 'msg' => esc_html__('Please login to remove this listing', 'wp-listings-directory') );
		   	echo wp_json_encode($return);
		   	exit;
		}
		$listing_id = empty( $_POST['listing_id'] ) ? false : intval( $_POST['listing_id'] );
		if ( !$listing_id ) {
			$return = array( 'status' => false, 'msg' => esc_html__('Listing not found', 'wp-listings-directory') );
		   	echo wp_json_encode($return);
		   	exit;
		}
		$is_allowed = WP_Listings_Directory_Mixes::is_allowed_to_remove( get_current_user_id(), $listing_id );

		if ( ! $is_allowed ) {
	        $return = array( 'status' => false, 'msg' => esc_html__('You can not remove this listing.', 'wp-listings-directory') );
		   	echo wp_json_encode($return);
		   	exit;
		}

		do_action( 'wp-listings-directory-process-remove-before-save', $listing_id );

		if ( wp_delete_post( $listing_id ) ) {
			$return = array( 'status' => true, 'msg' => esc_html__('Listing has been successfully removed.', 'wp-listings-directory') );
		   	echo wp_json_encode($return);
		   	exit;
		} else {
			$return = array( 'status' => false, 'msg' => esc_html__('An error occured when removing an item.', 'wp-listings-directory') );
		   	echo wp_json_encode($return);
		   	exit;
		}
	}

	public static function metaboxes() {
		global $pagenow;
		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			do_action('wp-listings-directory-listing-fields-admin');
		}
	}
	
	/**
	 * Custom admin columns for post type
	 *
	 * @access public
	 * @return array
	 */
	public static function custom_columns($columns) {
		if ( isset($columns['comments']) ) {
			unset($columns['comments']);
		}
		if ( isset($columns['date']) ) {
			unset($columns['date']);
		}
		if ( isset($columns['author']) ) {
			unset($columns['author']);
		}
		$c_fields = array();
		foreach ($columns as $key => $column) {
			$c_fields[$key] = $column;
		}

		$fields_new = [];
		
		$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance(0);
		if ( $meta_obj->check_post_meta_exist('condition') ) {
			$fields_new['condition'] = __('Condition', 'wp-listings-directory');
		}
		if ( $meta_obj->check_post_meta_exist('category') ) {
			$fields_new['category'] = __('Category', 'wp-listings-directory');
		}
		if ( $meta_obj->check_post_meta_exist('type') ) {
			$fields_new['type'] = __('Type', 'wp-listings-directory');
		}
		if ( $meta_obj->check_post_meta_exist('make') ) {
			$fields_new['make'] = __('Make', 'wp-listings-directory');
		}

		$fields_last = array(
			'price' 			=> __( 'Price', 'wp-listings-directory' ),
			'featured' 			=> __( 'Featured', 'wp-listings-directory' ),
			'posted' 			=> __( 'Posted', 'wp-listings-directory' ),
			'expires' 			=> __( 'Expires', 'wp-listings-directory' ),
			'listing_status' 	=> __( 'Status', 'wp-listings-directory' ),
		);
		$fields = array_merge($c_fields, $fields_new, $fields_last);

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
		global $post;
		switch ( $column ) {
			case 'condition':
				$terms = get_the_terms( get_the_ID(), 'listing_condition' );
				if ( ! empty( $terms ) ) {
					$i = 1; foreach ( $terms as $term ) {
						echo sprintf( '<a href="?post_type=listing&listing_condition=%s">%s</a>', $term->slug, $term->name ).($i < count($terms) ? trim(', ') : '');
						$i++;
					}
				} else {
					echo '-';
				}
				break;
			case 'type':
				$terms = get_the_terms( get_the_ID(), 'listing_type' );
				if ( ! empty( $terms ) ) {
					$i = 1; foreach ( $terms as $term ) {
						echo sprintf( '<a href="?post_type=listing&listing_type=%s">%s</a>', $term->slug, $term->name ).($i < count($terms) ? trim(', ') : '');
						$i++;
					}
				} else {
					echo '-';
				}
				break;
			case 'category':
				$terms = get_the_terms( get_the_ID(), 'listing_category' );
				if ( ! empty( $terms ) ) {
					$i = 1; foreach ( $terms as $term ) {
						echo sprintf( '<a href="?post_type=listing&listing_category=%s">%s</a>', $term->slug, $term->name ).($i < count($terms) ? trim(', ') : '');
						$i++;
					}
				} else {
					echo '-';
				}
				break;
			case 'make':
				$terms = get_the_terms( get_the_ID(), 'listing_make' );
				if ( ! empty( $terms ) ) {
					$i = 1; foreach ( $terms as $term ) {
						echo sprintf( '<a href="?post_type=listing&listing_make=%s">%s</a>', $term->slug, $term->name ).($i < count($terms) ? trim(', ') : '');
						$i++;
					}
				} else {
					echo '-';
				}
				break;
			case 'price':
				$obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
				echo $obj->get_price_html();
				break;
			case 'posted':
				echo '<strong>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $post->post_date ) ) ) . '</strong><span><br>';
				echo ( empty( $post->post_author ) ? esc_html__( 'by a guest', 'wp-listings-directory' ) : sprintf( esc_html__( 'by %s', 'wp-listings-directory' ), '<a href="' . esc_url( add_query_arg( 'author', $post->post_author ) ) . '">' . esc_html( get_the_author() ) . '</a>' ) ) . '</span>';
				break;
			case 'expires':
				$expires = get_post_meta( $post->ID, self::$prefix.'expiry_date', true);
				if ( $expires ) {
					echo '<strong>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ) . '</strong>';
				} else {
					echo '&ndash;';
				}
				break;
			case 'featured':
				$featured = get_post_meta( get_the_ID(), self::$prefix . 'featured', true );

				if ( ! empty( $featured ) ) {
					echo '<div class="dashicons dashicons-star-filled"></div>';
				} else {
					echo '<div class="dashicons dashicons-star-empty"></div>';
				}
				break;
			case 'listing_status':

				$post_status = get_post_status_object( $post->post_status );
				if ( !empty($post_status->label) ) {
					$status_text = $post_status->label;
				} else {
					$status_text = $post->post_status;
				}

				echo sprintf( '<a href="?post_type=listing&post_status=%s" class="post-status %s">%s</a>', esc_attr( $post->post_status ), esc_attr( $post->post_status ), '<span class="status-' . esc_attr( $post->post_status ) . '">' . esc_html( $status_text ) . '</span>' );
				break;
		}
	}

	public static function filter_car_by_taxonomy() {
		global $typenow;
		if ($typenow == 'listing') {
			$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance(0);

			// Type
			if ( $meta_obj->check_post_meta_exist('type') ) {
				$selected = isset($_GET['listing_type']) ? $_GET['listing_type'] : '';
				$tax_args = array(
				  	'taxonomy'     => 'listing_type',
				  	'orderby'      => 'name',
				  	'show_count'   => 1,
				  	'hierarchical' => 1,
				  	'name' => 'listing_type',
				  	'selected' => $selected,
				  	'show_option_all' => esc_html__('All types', 'wp-listings-directory'),
				  	'value_field' => 'slug'
				);
				wp_dropdown_categories($tax_args);
			}

			// locations
			if ( $meta_obj->check_post_meta_exist('location') ) {
				$selected = isset($_GET['listing_location']) ? $_GET['listing_location'] : '';
				$tax_args = array(
				  	'taxonomy'     => 'listing_location',
				  	'orderby'      => 'name',
				  	'show_count'   => 1,
				  	'hierarchical' => 1,
				  	'name' => 'listing_location',
				  	'selected' => $selected,
				  	'show_option_all' => esc_html__('All locations', 'wp-listings-directory'),
				  	'value_field' => 'slug'
				);
				wp_dropdown_categories($tax_args);
			}

			// categories
			if ( $meta_obj->check_post_meta_exist('category') ) {
				$selected = isset($_GET['listing_category']) ? $_GET['listing_category'] : '';
				$tax_args = array(
				  	'taxonomy'     => 'listing_category',
				  	'orderby'      => 'name',
				  	'show_count'   => 1,
				  	'hierarchical' => 1,
				  	'name' => 'listing_category',
				  	'selected' => $selected,
				  	'show_option_all' => esc_html__('All categories', 'wp-listings-directory'),
				  	'value_field' => 'slug'
				);
				wp_dropdown_categories($tax_args);
			}
		}
	}

	// public static function filter_car_by_taxonomy_in_query($query) {
	// 	global $pagenow;

	// 	$post_author = isset($_GET['post_author']) ? $_GET['post_author'] : '';
	// 	$q_vars    = &$query->query_vars;

	// 	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == 'listing' ) {
	// 		if ( !empty($post_author) ) {
	// 			$q_vars['author'] = $post_author;
	// 		}
	// 	}
		
	// }

}
WP_Listings_Directory_Post_Type_Listing::init();


