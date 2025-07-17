<?php
/*
Plugin Name: CMB2 Field Type: Select2
Plugin URI: https://github.com/mustardBees/cmb-field-select2
GitHub Plugin URI: https://github.com/mustardBees/cmb-field-select2
Description: Select2 field type for CMB2.
Version: 3.0.3
Author: Phil Wylie
Author URI: https://www.philwylie.co.uk/
License: GPLv2+
*/

/**
 * Class PW_CMB2_Field_Taxonomy_Select2_Search
 */
class PW_CMB2_Field_Taxonomy_Select2_Search {

	/**
	 * Current version number
	 */
	const VERSION = '3.0.3';

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	public function __construct() {
		add_filter( 'cmb2_render_pw_taxonomy_select_search', array( $this, 'render_pw_taxonomy_select' ), 10, 5 );
		add_filter( 'cmb2_render_pw_taxonomy_multiselect_search', array( $this, 'render_pw_taxonomy_multiselect' ), 10, 5 );
		add_filter( 'cmb2_sanitize_pw_taxonomy_multiselect_search', array( $this, 'pw_taxonomy_multiselect_sanitize' ), 10, 4 );
		add_filter( 'cmb2_sanitize_pw_taxonomy_select_search', array( $this, 'pw_taxonomy_select_sanitize' ), 10, 4 );
		add_filter( 'cmb2_types_esc_pw_taxonomy_multiselect_search', array( $this, 'pw_taxonomy_multiselect_escaped_value' ), 10, 3 );
		add_filter( 'cmb2_repeat_table_row_types', array( $this, 'pw_taxonomy_multiselect_table_row_class' ), 10, 1 );


		// Ajax endpoints.
		add_action( 'wpld_ajax_wpjb_search_terms',  array($this, 'search_terms') );
	}

	/**
	 * Render select box field
	 */
	public function render_pw_taxonomy_select( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		$this->setup_admin_scripts();

		if ( version_compare( CMB2_VERSION, '2.2.2', '>=' ) ) {
			$field_type_object->type = new CMB2_Type_Select( $field_type_object );
		}

		echo $field_type_object->select( array(
			'class'            => 'pw_taxonomy_select2_search pw_taxonomy_select_search',
			'desc'             => $field_type_object->_desc( true ),
			'options'          => $this->get_pw_taxonomy_options( $field_escaped_value, $field_type_object ),
			'data-placeholder' => $field->args( 'attributes', 'placeholder' ) ? $field->args( 'attributes', 'placeholder' ) : $field->args( 'description' ),
			'data-taxonomy' => $field_type_object->field->args( 'taxonomy' ),
		) );
	}

	/**
	 * Render multi-value select input field
	 */
	public function render_pw_taxonomy_multiselect( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		$this->setup_admin_scripts();

		if ( version_compare( CMB2_VERSION, '2.2.2', '>=' ) ) {
			$field_type_object->type = new CMB2_Type_Select( $field_type_object );
		}

		$a = $field_type_object->parse_args( 'pw_taxonomy_multiselect_search', array(
			'multiple'         => 'multiple',
			'style'            => 'width: 99%',
			'class'            => 'pw_taxonomy_select2_search pw_taxonomy_multiselect_search',
			'name'             => $field_type_object->_name() . '[]',
			'id'               => $field_type_object->_id(),
			'desc'             => $field_type_object->_desc( true ),
			'options'          => $this->get_pw_taxonomy_options( $field_escaped_value, $field_type_object ),
			'data-placeholder' => $field->args( 'attributes', 'placeholder' ) ? $field->args( 'attributes', 'placeholder' ) : $field->args( 'description' ),
			'data-taxonomy' => $field_type_object->field->args( 'taxonomy' ),
		) );

		$attrs = $field_type_object->concat_attrs( $a, array( 'desc', 'options' ) );
		echo sprintf( '<select%s>%s</select>%s', $attrs, $a['options'], $a['desc'] );
	}

	/**
	 * Return list of options for pw_taxonomy_multiselect
	 *
	 * Return the list of options, with selected options at the top preserving their order. This also handles the
	 * removal of selected options which no longer exist in the options array.
	 */
	public function get_pw_taxonomy_options( $field_escaped_value = array(), $field_type_object ) {
		$options = array();
		
		$field_escaped_value = $this->options_terms($field_type_object->field);
		
		// If we have selected items, we need to preserve their order
		if ( ! empty( $field_escaped_value ) ) {
			if ( !is_array($field_escaped_value) ) {
				$field_escaped_value = array($field_escaped_value);
			}

			$options = (array) $this->get_terms($field_type_object->field->args( 'taxonomy' ), array('include' => $field_escaped_value) );

			// $options = $this->sort_array_by_array( $options, $field_escaped_value );
		}

		$selected_items = '';
		$other_items = '';

		foreach ( $options as $opt ) {

			// Clone args & modify for just this item
			$option = array(
				'value' => $opt['id'],
				'label' => $opt['name'],
			);

			// Split options into those which are selected and the rest
			if ( in_array( $opt['id'], (array) $field_escaped_value ) ) {
				$option['checked'] = true;
				$selected_items .= $field_type_object->select_option( $option );
			} else {
				$other_items .= $field_type_object->select_option( $option );
			}
		}

		return $selected_items . $other_items;
	}

	public function options_terms($field) {
		if ( empty($field->data_args()['id']) ) {
			return array();
		}
		$object_id = $field->data_args()['id'];
		$terms = get_the_terms( $object_id, $field->args( 'taxonomy' ) );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			foreach ( $terms as $index => $term ) {
				$terms[ $index ] = $term->term_id;
			}
		}

		return $terms;
	}

	/**
	 * Sort an array by the keys of another array
	 *
	 * @author Eran Galperin
	 * @link http://link.from.pw/1Waji4l
	 */
	public function sort_array_by_array( array $array, array $orderArray ) {
		$ordered = array();

		foreach ( $orderArray as $key ) {
			if ( array_key_exists( $key, $array ) ) {
				$ordered[ $key ] = $array[ $key ];
				unset( $array[ $key ] );
			}
		}

		return $ordered + $array;
	}

	/**
	 * Handle sanitization for repeatable fields
	 */
	public function pw_taxonomy_multiselect_sanitize( $check, $meta_value, $object_id, $field_args ) {
		if ( empty($meta_value) || !is_array( $meta_value ) ) {
			return $check;
		}
		if ( $field_args['repeatable'] ) {
			foreach ( $meta_value as $key => $val ) {
				$meta_value[$key] = array_map( 'absint', $val );
				wp_set_object_terms( $object_id, array_map( 'absint', $val ), $field_args['taxonomy'], false );
			}
		} else {
			$meta_value = array_map( 'absint', $meta_value );
			wp_set_object_terms( $object_id, $meta_value, $field_args['taxonomy'], false );
		}

		return $meta_value;
	}

	/**
	 * Handle sanitization for repeatable fields
	 */
	public function pw_taxonomy_select_sanitize( $check, $meta_value, $object_id, $field_args ) {
		if ( empty( $meta_value ) ) {
			return $check;
		}

		if ( is_array($meta_value) ) {
			$meta_value = array_map( 'absint', $meta_value );
		} else {
			$meta_value = intval($meta_value);
		}
		
		wp_set_object_terms( $object_id, $meta_value, $field_args['taxonomy'], false );

		return $meta_value;
	}

	/**
	 * Handle escaping for repeatable fields
	 */
	public function pw_taxonomy_multiselect_escaped_value( $check, $meta_value, $field_args ) {
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
			return $check;
		}

		foreach ( $meta_value as $key => $val ) {
			$meta_value[$key] = array_map( 'esc_attr', $val );
		}

		return $meta_value;
	}

	/**
	 * Add 'table-layout' class to multi-value select field
	 */
	public function pw_taxonomy_multiselect_table_row_class( $check ) {
		$check[] = 'pw_taxonomy_multiselect_search';

		return $check;
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function setup_admin_scripts() {
		$asset_path = apply_filters( 'pw_cmb2_field_select2_asset_path', plugins_url( '', __FILE__  ) );

		wp_enqueue_script( 'pw-taxonomy-select2-loadmore-init', $asset_path . '/js/script.js', array( 'cmb2-scripts', 'wpld-select2', 'jquery-ui-sortable' ), self::VERSION );
		wp_enqueue_style( 'pw-taxonomy-select2-loadmore-tweaks', $asset_path . '/css/style.css', array( 'wpld-select2' ), self::VERSION );

		wp_localize_script( 'pw-taxonomy-select2-loadmore-init', 'wp_listings_directory_tax_search_opts', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajaxurl_endpoint' => WP_Listings_Directory_Ajax::get_endpoint(),
		));
	}

    public function get_terms($taxonomy, $query_args = array(), $per_page = 0, $page = 1) {
        $return = array();

        $offset = ( $page - 1 ) * $per_page;
        $defaults = array(
	        'taxonomy' => $taxonomy,
	        'hide_empty' => false,
	        'orderby' => 'name',
            'order' => 'ASC',
            'number' => $per_page,
            'offset' => $offset,
            'lang' => apply_filters( 'wp-listings-directory-current-lang', null )
	    );
	    $args = wp_parse_args( $query_args, $defaults );

	    $terms_hash = 'wpld_cats_' . md5( wp_json_encode( $args ) . WP_Listings_Directory_Cache_Helper::get_transient_version('wpld_get_' . $taxonomy) );
		$terms      = get_transient( $terms_hash );

		if ( empty( $terms ) ) {
		    $terms = get_terms( $taxonomy, $args );
		    set_transient( $terms_hash, $terms, DAY_IN_SECONDS * 7 );
		}

	    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
	    	$return = array();
	    	foreach( $terms as $term ) {
	    		$return[] = array('id' => $term->term_id, 'name' => $term->name);
	    	}
	    }
		
        return $return;
    }

    public function get_terms_count($taxonomy, $query_args = array() ) {
        $defaults = array(
	        'taxonomy' => $taxonomy,
	        'hide_empty' => false,
	        'orderby' => 'name',
            'order' => 'ASC',
            'lang' => apply_filters( 'wp-listings-directory-current-lang', null )
	    );
	    $args = wp_parse_args( $query_args, $defaults );

	    $terms_hash = 'wpld_cats_' . md5( wp_json_encode( $args ) . WP_Listings_Directory_Cache_Helper::get_transient_version('wpld_get_' . $taxonomy) );
		$total_terms      = get_transient( $terms_hash );

		if ( empty( $total_terms ) ) {
		    $total_terms = wp_count_terms( $taxonomy, $args );
		    set_transient( $terms_hash, $total_terms, DAY_IN_SECONDS * 7 );
		}

        return $total_terms;
    }

    public function search_terms() {
    	$search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    	$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 1;
    	
    	$taxonomy = isset($_GET['taxonomy']) ? sanitize_text_field($_GET['taxonomy']) : '';
    	$parent = isset($_GET['parent']) ? sanitize_text_field($_GET['parent']) : '';

    	$per_page = 6;

    	$args = array('search' => $search, 'parent' => $parent);

    	$terms = $this->get_terms($taxonomy, $args, $per_page, $page);

    	$options = array();
    	if ( $terms ){
    		$options = $terms;
    	}

    	$total_terms = $this->get_terms_count($taxonomy, $args);
		$pages = ceil($total_terms/$per_page);

    	$return = array(
    		'pages' => $pages,
    		'results' => $options,
    	);

    	wp_send_json($return);
    }
}
$pw_cmb2_field_select2 = new PW_CMB2_Field_Taxonomy_Select2_Search();
