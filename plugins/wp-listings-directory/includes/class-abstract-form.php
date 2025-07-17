<?php
/**
 * Abstract Form
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Abstract_Form {
	protected $steps = array();
	public $form_name = '';
	protected $step = 0;
	protected $listing_id = 0;
	public $errors = array();
	public $success_msg = array();

	public function __construct() {
		add_filter( 'cmb2_meta_boxes', array( $this, 'fields_front' ) );
	}

	public function process() {
		
		$step_key = $this->get_step_key( $this->step );

		if ( $step_key && is_callable( $this->steps[ $step_key ]['handler'] ) ) {
			call_user_func( $this->steps[ $step_key ]['handler'] );
		}

		$next_step_key = $this->get_step_key( $this->step );

		if ( $next_step_key && $step_key !== $next_step_key && isset( $this->steps[ $next_step_key ]['before_view'] ) && is_callable( $this->steps[ $next_step_key ]['before_view'] ) ) {
			call_user_func( $this->steps[ $next_step_key ]['before_view'] );
		}
		// if the step changed, but the next step has no 'view', call the next handler in sequence.
		if ( $next_step_key && $step_key !== $next_step_key && ! is_callable( $this->steps[ $next_step_key ]['view'] ) ) {
			$this->process();
		}
	}

	public function output( $atts = array() ) {
		$step_key = $this->get_step_key( $this->step );
		$output = '';
		if ( $step_key && is_callable( $this->steps[ $step_key ]['view'] ) ) {
			ob_start();
				call_user_func( $this->steps[ $step_key ]['view'], $atts );
				$output = ob_get_contents();
			ob_end_clean();
		}
		return $output;
	}

	public function get_listing_id() {
		return $this->listing_id;
	}

	public function set_step( $step ) {
		$this->step = absint( $step );
	}

	public function get_step() {
		return $this->step;
	}

	public function get_step_key( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}
		$keys = array_keys( $this->steps );
		return isset( $keys[ $step ] ) ? $keys[ $step ] : '';
	}

	public function next_step() {
		$this->step ++;
	}

	public function previous_step() {
		$this->step --;
	}

	public function get_form_action() {
		return '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	public function get_form_name() {
		return $this->form_name;
	}

	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	public function get_errors() {
		return $this->errors;
	}

	public function fields_front($metaboxes) {
		$post_id = $this->listing_id;

		do_action('wp-listings-directory-before-listing-fields-front', $post_id);

		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
			$featured_image = get_post_thumbnail_id( $post_id );
		}
		$init_fields = apply_filters( 'wp-listings-directory-listing-fields-front', array(), $post_id );

		uasort( $init_fields, array( 'WP_Listings_Directory_Mixes', 'sort_array_by_priority') );


		$fields = array();
		$i = 1;
		$heading_count = 0;
		$index = 0;
		foreach ($init_fields as $field) {
			$rfield = $field;
			if ( $i == 1 ) {
				if ( $field['type'] !== 'title' ) {
					$fields[] = array(
						'name' => esc_html__('General', 'wp-listings-directory'),
						'type' => 'title',
						'id'   => WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'heading_general_title',
						'priority' => 0,
						'before_row' => '<div id="heading-'.WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'heading_general_title" class="before-group-row before-group-row-'.$heading_count.' active"><div class="before-group-row-inner">',
					);
					$heading_count = 1;
					$index = 0;
				}
			}
			
			if ( $rfield['id'] == WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'title' ) {
				$rfield['default'] = !empty( $post ) ? $post->post_title : '';
			} elseif ( $rfield['id'] == WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'description' ) {
				$rfield['default'] = !empty( $post ) ? $post->post_content : '';
			} elseif ( $rfield['id'] == WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'featured_image' ) {
				$rfield['default'] = !empty( $featured_image ) ? $featured_image : '';
			}
			if ( $rfield['type'] == 'title' ) {
				$before_row = '';
				// $before_row = '<div class="submit-button-wrapper">';
				// if ( $heading_count > 1 ) {
				// 	$before_row .= '<button type="button" class="job-submission-previous-btn btn btn-theme" data-index="'.($index - 1).'">' . esc_html__( 'Previous Step', 'wp-listings-directory' ) . '</button>';
				// }
				// if ( $i > 1 && $i < count($init_fields) ) {
				// 	$before_row .= '<button type="button" class="job-submission-next-btn btn btn-theme" data-index="'.($index + 1).'">' . esc_html__( 'Next Step', 'wp-listings-directory' ) . '</button>';
				// }
				// $before_row .= '</div>';

				if ( $i > 1 ) {
					$before_row .= '</div></div>';
				}
				$classes = '';
				if ( !empty($rfield['number_columns']) ) {
					$classes = 'columns-'.$rfield['number_columns'];
				}
				$before_row .= '<div id="heading-'.$rfield['id'].'" class="before-group-row before-group-row-'.$heading_count.' '.($heading_count == 0 ? 'active' : '').' '.$classes.'"><div class="before-group-row-inner">';

				$rfield['before_row'] = $before_row;

				$heading_count++;
				$index++;
			}

			if ( $i == count($init_fields) ) {
				if ( $rfield['type'] == 'group' ){
					$rfield['after_group'] = '</div></div>';
				} else {
					$rfield['after_row'] = '</div></div>';
				}
			}

			$fields[] = $rfield;

			$i++;
		}

		$fields[] = array(
			'id'                => WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'post_type',
			'type'              => 'hidden',
			'default'           => 'listing',
			'priority'          => 100,
		);
		// var_dump($fields); die;
		
		$fields = apply_filters( 'wp-listings-directory-get-listing-fields', $fields, $post_id );

		$metaboxes[ WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'fields_front' ] = array(
			'id'                        => WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'fields_front',
			'title'                     => __( 'General Options', 'wp-listings-directory' ),
			'object_types'              => array( 'listing' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

		do_action('wp-listings-directory-after-listing-fields-front', $post_id);

		return $metaboxes;
	}

	public function form_output() {
		$metaboxes = apply_filters( 'cmb2_meta_boxes', array() );
		if ( ! isset( $metaboxes[ WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'fields_front' ] ) ) {
			return __( 'A metabox with the specified \'metabox_id\' doesn\'t exist.', 'wp-listings-directory' );
		}
		$metaboxes_form = $metaboxes[ WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'fields_front' ];

		if ( ! $this->listing_id ) {
			unset( $_POST );
		}
		
		if ( ! empty( $this->listing_id ) && ! empty( $_POST['object_id'] ) ) {
			$this->listing_id = intval( $_POST['object_id'] );
		}

		$submit_button_text = __( 'Save & Preview', 'wp-listings-directory' );
		if ( ! empty( $this->listing_id ) ) {
			$submit_button_text = __( 'Update', 'wp-listings-directory' );
			// Check post author permission
			$post = get_post( $this->listing_id );

			if ( $post && $post->post_author != get_current_user_id() ) {
				return __( 'You are not allowed to modify this listing.', 'wp-listings-directory' );
			}
		}

		wp_enqueue_script('wpld-select2');
		wp_enqueue_style('wpld-select2');

		echo WP_Listings_Directory_Template_Loader::get_template_part( 'submission/listing-submit-form', array(
			'post_id' => $this->listing_id,
			'metaboxes_form' => $metaboxes_form,
			'listing_id'         => $this->listing_id,
			'step'           => $this->get_step(),
			'form_obj'       => $this,
			'submit_button_text' => apply_filters( 'wp_listings_directory_submit_listing_form_submit_button_text', $submit_button_text ),
		) );
	}
}
