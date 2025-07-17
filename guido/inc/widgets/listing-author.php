<?php

class Guido_Widget_Listing_Author extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_listing_author',
            esc_html__('Listing Detail:: Author', 'guido'),
            array( 'description' => esc_html__( 'Show listing author', 'guido' ), )
        );
        $this->widgetName = 'listing_author';
    }

    public function widget( $args, $instance ) {
        get_template_part('widgets/listing-author', '', array('args' => $args, 'instance' => $instance));
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'guido' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Guido_Widget_Listing_Author' );
