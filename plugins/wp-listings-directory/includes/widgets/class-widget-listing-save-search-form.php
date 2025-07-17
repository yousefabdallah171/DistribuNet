<?php
/**
 * Widget: Listing Save Search Form
 *
 * @package    wp-listings-directory
 * @author     Habq 
 * @license    GNU General Public License, version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}

class WP_Listings_Directory_Widget_Listing_Save_Search_Form extends WP_Widget {
	/**
	 * Initialize widget
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		parent::__construct(
			'save_search_form_widget',
			__( 'Listing Save Search Form', 'wp-listings-directory' ),
			array(
				'description' => __( 'Listing save search form for add email alert.', 'wp-listings-directory' ),
			)
		);
	}

	/**
	 * Frontend
	 *
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		include WP_Listings_Directory_Template_Loader::locate( 'widgets/listing-save-search-form' );
	}

	/**
	 * Update
	 *
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * Backend
	 *
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : 'Save Search';
		?>

		<!-- TITLE -->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
		        <?php echo __( 'Title', 'wp-listings-directory' ); ?>
		    </label>

		    <input  class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<!-- BUTTON TEXT -->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>">
		        <?php echo __( 'Button text', 'wp-listings-directory' ); ?>
		    </label>

		    <input  class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
		</p>

		
		<?php
	}
}
register_widget('WP_Listings_Directory_Widget_Listing_Save_Search_Form');