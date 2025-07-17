<?php
/**
 * Categories
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WP_Listings_Directory_Taxonomy_Car_Category{

	/**
	 *
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'definition' ), 1 );


		add_filter( "manage_edit-listing_category_columns", array( __CLASS__, 'tax_columns' ) );
		add_filter( "manage_listing_category_custom_column", array( __CLASS__, 'tax_column' ), 10, 3 );
		add_action( "listing_category_add_form_fields", array( __CLASS__, 'add_fields_form' ) );
		add_action( "listing_category_edit_form_fields", array( __CLASS__, 'edit_fields_form' ), 10, 2 );

		add_action( 'create_term', array( __CLASS__, 'save' ), 10, 3 );
		add_action( 'edit_term', array( __CLASS__, 'save' ), 10, 3 );
	}

	/**
	 *
	 */
	public static function definition() {
		$singular = __( 'Category', 'wp-listings-directory' );
		$plural   = __( 'Categories', 'wp-listings-directory' );

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

		$rewrite_slug = get_option('wp_listings_directory_listing_category_slug');
		if ( empty($rewrite_slug) ) {
			$rewrite_slug = _x( 'listing-category', 'Listing category slug - resave permalinks after changing this', 'wp-listings-directory' );
		}
		$rewrite = array(
			'slug'         => $rewrite_slug,
			'with_front'   => false,
			'hierarchical' => false,
		);
		register_taxonomy( 'listing_category', 'listing', array(
			'labels'            => apply_filters( 'wp_listings_directory_taxomony_listing_category_labels', $labels ),
			'hierarchical'      => true,
			'rewrite'           => $rewrite,
			'public'            => true,
			'show_ui'           => true,
			'show_in_rest'		=> true,
		) );
	}

	public static function tax_columns( $columns ) {
		$new_columns = array();
		foreach ($columns as $key => $value) {
			if ( $key == 'name' ) {
				$new_columns['icon'] = esc_html__( 'Icon', 'wp-listings-directory' );
			}
			$new_columns[$key] = $value;
		}
		return $new_columns;
	}

	public static function tax_column( $columns, $column, $id ) {
		if ( $column == 'icon' ) {
			$icon_type_value = get_term_meta( $id, '_icon_type', true );
			$icon_font_value = get_term_meta( $id, '_icon_font', true );
			$icon_image_value = get_term_meta( $id, '_icon_image', true );
			if ( $icon_type_value == 'font' && !empty($icon_font_value) ) {
				$columns .= '<i class="'.esc_attr($icon_font_value).'"></i>';
			} elseif ( $icon_type_value == 'image' && !empty($icon_image_value) ) {
				$image_url = wp_get_attachment_image_src($icon_image_value, 'full');
				if ( !empty($image_url[0]) ) {
					$columns .= '<img src="'.esc_url($image_url[0]).'" alt="'.esc_attr__( 'icon', 'wp-listings-directory' ).'" />';
				}
			}
		}
		return $columns;
	}
	
	public static function add_fields_form($taxonomy) {
		?>

		<div class="form-field">
			<label><?php esc_html_e( 'Icon Type', 'wp-listings-directory' ); ?></label>
			<?php self::icon_type_field(); ?>
		</div>
		<div class="form-field icon-type-wrapper icon-type-font">
			<label><?php esc_html_e( 'Icon Font', 'wp-listings-directory' ); ?></label>
			<?php self::icon_font_field(); ?>
		</div>
		<div class="form-field icon-type-wrapper icon-type-image">
			<label><?php esc_html_e( 'Icon Image', 'wp-listings-directory' ); ?></label>
			<?php self::icon_image_field(); ?>
		</div>
		<div class="form-field">
			<label><?php esc_html_e( 'Color', 'wp-listings-directory' ); ?></label>
			<?php self::color_field('_color'); ?>
		</div>
		<?php
	}

	public static function edit_fields_form( $term, $taxonomy ) {
		$icon_type_value = get_term_meta( $term->term_id, '_icon_type', true );
		$icon_font_value = get_term_meta( $term->term_id, '_icon_font', true );
		$icon_image_value = get_term_meta( $term->term_id, '_icon_image', true );
		
		?>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon Type', 'wp-listings-directory' ); ?></label></th>
			<td>
				<?php self::icon_type_field($icon_type_value); ?>
			</td>
		</tr>
		<tr class="form-field icon-type-wrapper icon-type-font">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon Font', 'wp-listings-directory' ); ?></label></th>
			<td>
				<?php self::icon_font_field($icon_font_value); ?>
			</td>
		</tr>
		<tr class="form-field icon-type-wrapper icon-type-image">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon Image', 'wp-listings-directory' ); ?></label></th>
			<td>
				<?php self::icon_image_field($icon_image_value); ?>
			</td>
		</tr>
		<?php 
			$color_value = get_term_meta( $term->term_id, '_color', true );
		?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Color', 'wp-listings-directory' ); ?></label></th>
				<td>
					<?php self::color_field('_color', $color_value); ?>
				</td>
			</tr>
		<?php
	}

	public static function icon_type_field( $val = '' ) {
		?>
		<label>
			<input name="_icon_type" type="radio" value="font" <?php echo trim(empty($val) || $val == 'font' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Icon Font', 'wp-listings-directory'); ?>
		</label>
		<label>
			<input name="_icon_type" type="radio" value="image" <?php echo trim($val == 'image' ? 'checked="checked"' : ''); ?>> <?php esc_html_e('Icon Image', 'wp-listings-directory'); ?>
		</label>
		<?php
	}

	public static function icon_font_field( $val = '' ) {
		?>
		<input id="apus_tax_icon_font" name="_icon_font" type="text" value="<?php echo esc_attr($val); ?>">
		<?php
	}

	public static function icon_image_field( $val = '' ) {
		$avatar_url = '';
		if ( !empty($val) ) {
			$avatar_url = wp_get_attachment_image_src($val, 'full');
		}
		?>
		<div class="icon-image-wrapper">
			<div class="screenshot-user avatar-screenshot">
	            <?php if ( !empty($avatar_url[0]) ) { ?>
	                <img src="<?php echo esc_url($avatar_url[0]); ?>" alt="<?php esc_attr_e( 'Image', 'wp-listings-directory' ); ?>" />
	            <?php } ?>
	        </div>
	        <input class="widefat upload_image" name="_icon_image" type="hidden" value="<?php echo esc_attr($avatar); ?>" />
	        <div class="upload_image_action">
	            <input type="button" class="button radius-3x btn btn-theme user-add-image" value="<?php esc_attr_e( 'Add Icon Image', 'wp-listings-directory' ); ?>">
	            <input type="button" class="button radius-3x btn btn-theme-second user-remove-image" value="<?php esc_attr_e( 'Remove Icon Image', 'wp-listings-directory' ); ?>">
	        </div>
        </div>
		<?php
	}

	public static function color_field( $name, $val = '' ) {
		?>
		<input class="tax_color_input" name="<?php echo esc_attr($name); ?>" type="text" value="<?php echo esc_attr($val); ?>">
		<?php
	}

	public static function save( $term_id, $tt_id, $taxonomy ) {
		if ( $taxonomy == 'listing_category' ) {
		    update_term_meta( $term_id, '_icon_type', isset( $_POST['_icon_type'] ) ? $_POST['_icon_type'] : 'font' );
		    update_term_meta( $term_id, '_icon_font', isset( $_POST['_icon_font'] ) ? $_POST['_icon_font'] : '' );
		    update_term_meta( $term_id, '_icon_image', isset( $_POST['_icon_image'] ) ? $_POST['_icon_image'] : '' );

		    if ( isset( $_POST['_color'] ) ) {
		    	update_term_meta( $term_id, '_color', $_POST['_color'] );
		    }
	    }
	}

}

WP_Listings_Directory_Taxonomy_Car_Category::init();