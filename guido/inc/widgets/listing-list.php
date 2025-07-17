<?php

class Guido_Widget_Listing_List extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apus_widget_listing_list',
            esc_html__('Apus Simple Listings List', 'guido'),
            array( 'description' => esc_html__( 'Show list of listing', 'guido' ), )
        );
        $this->widgetName = 'listing_list';
    }
    
    public function widget( $args, $instance ) {
        get_template_part('widgets/listing-list', '', array('args' => $args, 'instance' => $instance));
    }
    
    public function form( $instance ) {
        $defaults = array(
            'title' => 'Latest Listings',
            'number_post' => '4',
            'orderby' => '',
            'order' => '',
            'get_listings_by' => 'recent',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        // Widget admin form
        $orderbys = array(
            '' => esc_html__('Default', 'guido'),
            'date' => esc_html__('Date', 'guido'),
            'ID' => esc_html__('ID', 'guido'),
            'author' => esc_html__('Author', 'guido'),
            'title' => esc_html__('Title', 'guido'),
            'modified' => esc_html__('Modified', 'guido'),
            'rand' => esc_html__('Random', 'guido'),
            'comment_count' => esc_html__('Comment count', 'guido'),
            'menu_order' => esc_html__('Menu order', 'guido'),
        );
        $orders = array(
            '' => esc_html__('Default', 'guido'),
            'ASC' => esc_html__('Ascending', 'guido'),
            'DESC' => esc_html__('Descending', 'guido'),
        );
        $get_listings_bys = array(
            'featured' => esc_html__('Featured Listings', 'guido'),
            'urgent' => esc_html__('Urgent Listings', 'guido'),
            'recent' => esc_html__('Recent Listings', 'guido'),
        );


        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'guido' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>">
                <?php echo esc_html__('Order By:', 'guido' ); ?>
            </label>
            <br>
            <select id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <?php foreach ($orderbys as $key => $title) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected($instance['orderby'], $key); ?> ><?php echo esc_html( $title ); ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>">
                <?php echo esc_html__('Order:', 'guido' ); ?>
            </label>
            <br>
            <select id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <?php foreach ($orders as $key => $title) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected($instance['order'], $key); ?> ><?php echo esc_html( $title ); ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('get_listings_by')); ?>">
                <?php echo esc_html__('Get listings by:', 'guido' ); ?>
            </label>
            <br>
            <select id="<?php echo esc_attr($this->get_field_id('get_listings_by')); ?>" name="<?php echo esc_attr($this->get_field_name('get_listings_by')); ?>">
                <?php foreach ($get_listings_bys as $key => $title) { ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected($instance['get_listings_by'], $key); ?> ><?php echo esc_html( $title ); ?></option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'number_post' )); ?>"><?php esc_html_e( 'Num Posts:', 'guido' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number_post' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number_post' )); ?>" type="text" value="<?php echo esc_attr($instance['number_post']); ?>" />
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['number_post'] = ( ! empty( $new_instance['number_post'] ) ) ? strip_tags( $new_instance['number_post'] ) : '';
        $instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';
        $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';
        $instance['get_listings_by'] = ( ! empty( $new_instance['get_listings_by'] ) ) ? strip_tags( $new_instance['get_listings_by'] ) : '';
        return $instance;

    }
}

call_user_func( implode('_', array('register', 'widget') ), 'Guido_Widget_Listing_List' );
