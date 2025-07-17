<?php
/**
 * Plugin Name: WP Listings Directory
 * Plugin URI: http://apusthemes.com/wp-listings-directory/
 * Description: The latest plugins Listing Directory you want. Completely all features, easy customize and override layout, functions. Supported global payment, build market, single, list listing...etc. All fields are defined dynamic, they will help you can build any kind of Listings Directory website.
 * Version: 1.0.20
 * Author: Habq
 * Author URI: http://apusthemes.com/
 * Requires at least: 3.8
 * Tested up to: 6.0.2
 *
 * Text Domain: wp-listings-directory
 * Domain Path: /languages/
 *
 * @package wp-listings-directory
 * @category Plugins
 * @author Habq
 */
if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

if ( !class_exists("WP_Listings_Directory") ) {
	
	final class WP_Listings_Directory {

		private static $instance;

		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Listings_Directory ) ) {
				self::$instance = new WP_Listings_Directory;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				
				add_action( 'activated_plugin', array( self::$instance, 'plugin_order' ) );
				add_action( 'tgmpa_register', array( self::$instance, 'register_plugins' ) );
				add_action( 'widgets_init', array( self::$instance, 'register_widgets' ) );

				self::$instance->libraries();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 *
		 */
		public function setup_constants(){
			define( 'WP_LISTINGS_DIRECTORY_PLUGIN_VERSION', '1.0.20' );

			define( 'WP_LISTINGS_DIRECTORY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			define( 'WP_LISTINGS_DIRECTORY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			define( 'WP_LISTINGS_DIRECTORY_LISTING_PREFIX', '_listing_' );
			define( 'WP_LISTINGS_DIRECTORY_USER_PREFIX', '_user_' );
			
			define( 'WP_LISTINGS_DIRECTORY_LISTING_SAVED_SEARCH_PREFIX', '_saved_search_' );
			define( 'WP_LISTINGS_DIRECTORY_LISTING_CLAIM_PREFIX', '_claim_' );
		}

		public function includes() {
			global $wp_listings_directory_options;
			// Admin Settings
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/admin/class-settings.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/admin/class-permalink-settings.php';

			$wp_listings_directory_options = wp_listings_directory_get_settings();
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-mixes.php';
			
			// post type
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/post-types/class-post-type-listing.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/post-types/class-post-type-saved-search.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/post-types/class-post-type-claim.php';

			// custom fields
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/custom-fields/class-fields-manager.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/custom-fields/class-custom-fields-html.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/custom-fields/class-custom-fields.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/custom-fields/class-custom-fields-display.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-price.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-listing.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-listing-meta.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-claim.php';

			$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance(0);
			
			$custom_all_fields = WP_Listings_Directory_Fields_Manager::get_custom_fields_data();
			
			// taxonomies
			if ( $meta_obj->check_post_meta_exist('category') || empty($custom_all_fields) ) {
				require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/taxonomies/class-taxonomy-listing-category.php';
			}
			if ( $meta_obj->check_post_meta_exist('type') || empty($custom_all_fields) ) {
				require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/taxonomies/class-taxonomy-listing-type.php';
			}
			
			if ( $meta_obj->check_post_meta_exist('location') || empty($custom_all_fields) ) {
				require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/taxonomies/class-taxonomy-listing-location.php';
			}

			if ( $meta_obj->check_post_meta_exist('feature') || empty($custom_all_fields) ) {
				require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/taxonomies/class-taxonomy-listing-feature.php';
			}
			

			//
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-scripts.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-template-loader.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-review.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-review-image.php';
			
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-query.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-shortcodes.php';

			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-abstract-form.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-submit-form.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-edit-form.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-user.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-image.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-recaptcha.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-email.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-abstract-filter.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-listing-filter.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-saved-search.php';

			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-ajax.php';

			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-social.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-cache-helper.php';

			// social login
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/socials/class-social-facebook.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/socials/class-social-google.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/socials/class-social-linkedin.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/socials/class-social-twitter.php';

			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/class-favorite.php';

			

			// 3rd-party
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/3rd-party/class-wpml.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/3rd-party/class-polylang.php';

			if ( wp_listings_directory_get_option('users_requires_approval') == 'phone_approve' ) {
				require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/sms/class-sms.php';
			}

			add_action('init', array( __CLASS__, 'register_post_statuses' ) );
		}


		public static function register_post_statuses() {
			register_post_status(
				'expired',
				array(
					'label'                     => _x( 'Expired', 'post status', 'wp-listings-directory' ),
					'public'                    => false,
					'protected'                 => true,
					'exclude_from_search'       => true,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'wp-listings-directory' ),
				)
			);
			register_post_status(
				'preview',
				array(
					'label'                     => _x( 'Preview', 'post status', 'wp-listings-directory' ),
					'public'                    => false,
					'exclude_from_search'       => true,
					'show_in_admin_all_list'    => false,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop( 'Preview <span class="count">(%s)</span>', 'Preview <span class="count">(%s)</span>', 'wp-listings-directory' ),
				)
			);
			register_post_status(
				'pending_approve',
				array(
					'label'                     => _x( 'Pending Approval', 'post status', 'wp-listings-directory' ),
					'public'                    => false,
					'protected'                 => true,
					'exclude_from_search'       => true,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop( 'Pending Approve <span class="count">(%s)</span>', 'Pending Approve <span class="count">(%s)</span>', 'wp-listings-directory' ),
				)
			);
		}
		public static function register_widgets() {
			// widgets
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/widgets/class-widget-listing-filter.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'includes/widgets/class-widget-listing-save-search-form.php';
		}
		/**
		 * Loads third party libraries
		 *
		 * @access public
		 * @return void
		 */
		public static function libraries() {
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_map/cmb-field-map.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_tags/cmb2-field-type-tags.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_file/cmb2-field-type-file.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_image_select/cmb2-field-type-image-select.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_ajax_search/cmb2-field-ajax-search.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_select2/cmb-field-select2.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_taxonomy_select2/cmb-field-taxonomy-select2.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_taxonomy_select2_parent/cmb-field-taxonomy-select2-parent.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_taxonomy_select2_search/cmb-field-taxonomy-select2-search.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_taxonomy_location/cmb-field-taxonomy-location.php';

			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_rate_exchange/cmb2-field-type-rate_exchange.php';
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_enable_input/cmb2-field-enable-input.php';

			// new
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2_field_hours/cmb2-field-hours.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/cmb2/cmb2-tabs/plugin.php';
			
			require_once WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'libraries/class-tgm-plugin-activation.php';
		}

		/**
	     * Loads this plugin first
	     *
	     * @access public
	     * @return void
	     */
	    public static function plugin_order() {
		    $wp_path_to_this_file = preg_replace( '/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR.'/$2', __FILE__ );
		    $this_plugin = plugin_basename( trim( $wp_path_to_this_file ) );
		    $active_plugins = get_option( 'active_plugins' );
		    $this_plugin_key = array_search( $this_plugin, $active_plugins );
			if ( $this_plugin_key ) {
				array_splice( $active_plugins, $this_plugin_key, 1 );
				array_unshift( $active_plugins, $this_plugin );
			    update_option( 'active_plugins', $active_plugins );
		    }
	    }

		/**
		 * Install plugins
		 *
		 * @access public
		 * @return void
		 */
		public static function register_plugins() {
			$plugins = array(
	            array(
		            'name'      => 'CMB2',
		            'slug'      => 'cmb2',
		            'required'  => true,
	            )
			);

			tgmpa( $plugins );
		}

		public static function maybe_schedule_cron_properties() {
			if ( ! wp_next_scheduled( 'wp_listings_directory_check_for_expired_properties' ) ) {
				wp_schedule_event( time(), 'hourly', 'wp_listings_directory_check_for_expired_properties' );
			}
			if ( ! wp_next_scheduled( 'wp_listings_directory_delete_old_previews' ) ) {
				wp_schedule_event( time(), 'daily', 'wp_listings_directory_delete_old_previews' );
			}
			if ( ! wp_next_scheduled( 'wp_listings_directory_email_daily_notices' ) ) {
				wp_schedule_event( time(), 'daily', 'wp_listings_directory_email_daily_notices' );
			}
		}

		/**
		 * Unschedule cron properties. This is run on plugin deactivation.
		 */
		public static function unschedule_cron_properties() {
			wp_clear_scheduled_hook( 'wp_listings_directory_check_for_expired_properties' );
			wp_clear_scheduled_hook( 'wp_listings_directory_delete_old_previews' );
			wp_clear_scheduled_hook( 'wp_listings_directory_email_daily_notices' );
		}

		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for WP_Listings_Directory's languages directory
			$lang_dir = WP_LISTINGS_DIRECTORY_PLUGIN_DIR . 'languages/';
			$lang_dir = apply_filters( 'wp_listings_directory_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-listings-directory' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'wp-listings-directory', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/wp-listings-directory/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/wp-listings-directory folder
				load_textdomain( 'wp-listings-directory', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/wp-listings-directory/languages/ folder
				load_textdomain( 'wp-listings-directory', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'wp-listings-directory', false, $lang_dir );
			}
		}
	}
}

add_filter('wp_listings_directory_taxomony_listing_type_labels', function($labels) {
    $singular = 'الموزع';
    $plural = 'الموزعين';
    return array(
        'name'              => $plural,
        'singular_name'     => $singular,
        'search_items'      => 'بحث ' . $plural,
        'all_items'         => 'كل ' . $plural,
        'parent_item'       => 'الأصل ' . $singular,
        'parent_item_colon' => 'الأصل: ' . $singular,
        'edit_item'         => 'تعديل ' . $singular,
        'update_item'       => 'تحديث ' . $singular,
        'add_new_item'      => 'إضافة ' . $singular . ' جديد',
        'new_item_name'     => 'اسم ' . $singular . ' جديد',
        'menu_name'         => $plural,
    );
});

add_filter('wp_listings_directory_taxomony_listing_category_labels', function($labels) {
    $singular = 'الفئة';
    $plural = 'الفئات';
    return array(
        'name'              => $plural,
        'singular_name'     => $singular,
        'search_items'      => 'بحث ' . $plural,
        'all_items'         => 'كل ' . $plural,
        'parent_item'       => 'الأصل ' . $singular,
        'parent_item_colon' => 'الأصل: ' . $singular,
        'edit_item'         => 'تعديل ' . $singular,
        'update_item'       => 'تحديث ' . $singular,
        'add_new_item'      => 'إضافة ' . $singular . ' جديد',
        'new_item_name'     => 'اسم ' . $singular . ' جديد',
        'menu_name'         => $plural,
    );
});

add_filter('wp_listings_directory_taxomony_listing_location_labels', function($labels) {
    $singular = 'المدينة';
    $plural = 'المدن';
    return array(
        'name'              => $plural,
        'singular_name'     => $singular,
        'search_items'      => 'بحث ' . $plural,
        'all_items'         => 'كل ' . $plural,
        'parent_item'       => 'الأصل ' . $singular,
        'parent_item_colon' => 'الأصل: ' . $singular,
        'edit_item'         => 'تعديل ' . $singular,
        'update_item'       => 'تحديث ' . $singular,
        'add_new_item'      => 'إضافة ' . $singular . ' جديد',
        'new_item_name'     => 'اسم ' . $singular . ' جديد',
        'menu_name'         => $plural,
    );
});

add_filter('post_type_labels_listing', function($labels) {
    $singular = 'الموزع';
    $plural = 'الموزعين';
    $labels->name = $plural;
    $labels->singular_name = $singular;
    $labels->add_new = 'إضافة ' . $singular . ' جديد';
    $labels->add_new_item = 'إضافة ' . $singular . ' جديد';
    $labels->edit_item = 'تعديل ' . $singular;
    $labels->new_item = $singular . ' جديد';
    $labels->view_item = 'عرض ' . $singular;
    $labels->search_items = 'بحث ' . $plural;
    $labels->not_found = 'لا يوجد ' . $plural;
    $labels->not_found_in_trash = 'لا يوجد ' . $plural . ' في سلة المهملات';
    $labels->all_items = 'كل ' . $plural;
    $labels->menu_name = $plural;
    $labels->name_admin_bar = $singular;
    return $labels;
});

register_activation_hook( __FILE__, array( 'WP_Listings_Directory', 'maybe_schedule_cron_properties' ) );
register_deactivation_hook( __FILE__, array( 'WP_Listings_Directory', 'unschedule_cron_properties' ) );

function WP_Listings_Directory() {
	return WP_Listings_Directory::getInstance();
}

add_action( 'plugins_loaded', 'WP_Listings_Directory' );
