<?php
/**
 * Locations
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WP_ListingDealer_Taxonomy_Listing_Location{

	/**
	 *
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ), 1 );
        // Add image field to add/edit forms
        add_action('listing_location_add_form_fields', [__CLASS__, 'add_image_field'], 10, 2);
        add_action('listing_location_edit_form_fields', [__CLASS__, 'edit_image_field'], 10, 2);
        add_action('created_listing_location', [__CLASS__, 'save_image_field'], 10, 2);
        add_action('edited_listing_location', [__CLASS__, 'save_image_field'], 10, 2);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_media']);
	}

	/**
	 *
	 */
	public static function definition() {
		$singular = __( 'Location', 'wp-listings-directory' );
		$plural   = __( 'Locations', 'wp-listings-directory' );

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

		$rewrite_slug = get_option('wp_listings_directory_listing_location_slug');
		if ( empty($rewrite_slug) ) {
			$rewrite_slug = _x( 'listing-location', 'Listing location slug - resave permalinks after changing this', 'wp-listings-directory' );
		}
		$rewrite = array(
			'slug'         => $rewrite_slug,
			'with_front'   => false,
			'hierarchical' => false,
		);
		register_taxonomy( 'listing_location', 'listing', array(
			'labels'            => apply_filters( 'wp_listings_directory_taxomony_listing_location_labels', $labels ),
			'hierarchical'      => true,
			'rewrite'           => $rewrite,
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'		=> true
		) );
	}

    // Add image field to add form
    public static function add_image_field($taxonomy) {
        ?>
        <div class="form-field term-group">
            <label for="listing_location_icon_image"><?php _e('صورة المدينة', 'wp-listings-directory'); ?></label>
            <input type="hidden" id="listing_location_icon_image" name="listing_location_icon_image" value="">
            <div id="listing_location_icon_image_preview"></div>
            <button type="button" class="button button-secondary" id="listing_location_icon_image_upload"><?php _e('اختر صورة', 'wp-listings-directory'); ?></button>
        </div>
        <script>
        jQuery(document).ready(function($){
            var frame;
            $('#listing_location_icon_image_upload').on('click', function(e){
                e.preventDefault();
                if(frame){ frame.open(); return; }
                frame = wp.media({ title: 'اختر صورة المدينة', button: { text: 'استخدام الصورة' }, multiple: false });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#listing_location_icon_image').val(attachment.id);
                    $('#listing_location_icon_image_preview').html('<img src="'+attachment.sizes.thumbnail.url+'" style="max-width:120px;max-height:120px;border-radius:8px;">');
                });
                frame.open();
            });
        });
        </script>
        <?php
    }

    // Edit image field
    public static function edit_image_field($term, $taxonomy) {
        $image_id = get_term_meta($term->term_id, '_icon_image', true);
        $img_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
        ?>
        <tr class="form-field term-group-wrap">
            <th scope="row"><label for="listing_location_icon_image"><?php _e('صورة المدينة', 'wp-listings-directory'); ?></label></th>
            <td>
                <input type="hidden" id="listing_location_icon_image" name="listing_location_icon_image" value="<?php echo esc_attr($image_id); ?>">
                <div id="listing_location_icon_image_preview">
                    <?php if($img_url) echo '<img src="'.esc_url($img_url).'" style="max-width:120px;max-height:120px;border-radius:8px;">'; ?>
                </div>
                <button type="button" class="button button-secondary" id="listing_location_icon_image_upload"><?php _e('اختر صورة', 'wp-listings-directory'); ?></button>
            </td>
        </tr>
        <script>
        jQuery(document).ready(function($){
            var frame;
            $('#listing_location_icon_image_upload').on('click', function(e){
                e.preventDefault();
                if(frame){ frame.open(); return; }
                frame = wp.media({ title: 'اختر صورة المدينة', button: { text: 'استخدام الصورة' }, multiple: false });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#listing_location_icon_image').val(attachment.id);
                    $('#listing_location_icon_image_preview').html('<img src="'+attachment.sizes.thumbnail.url+'" style="max-width:120px;max-height:120px;border-radius:8px;">');
                });
                frame.open();
            });
        });
        </script>
        <?php
    }

    // Save image field
    public static function save_image_field($term_id) {
        if (isset($_POST['listing_location_icon_image'])) {
            update_term_meta($term_id, '_icon_image', intval($_POST['listing_location_icon_image']));
        }
    }

    // Enqueue media uploader
    public static function enqueue_media($hook) {
        if (strpos($hook, 'edit-tags.php') !== false || strpos($hook, 'term.php') !== false) {
            wp_enqueue_media();
        }
    }
}

WP_ListingDealer_Taxonomy_Listing_Location::init();