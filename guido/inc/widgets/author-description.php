<?php

class Guido_Widget_Author_Description extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_author_description',
            esc_html__('Author Detail:: Description', 'guido'),
            array( 'description' => esc_html__( 'Show author description', 'guido' ), )
        );
        $this->widgetName = 'author_contact_form';
    }
    
    public function widget( $args, $instance ) {
        get_template_part('widgets/author-description', '', array('args' => $args, 'instance' => $instance));
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
        return $new_instance;
    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Guido_Widget_Author_Description' );
