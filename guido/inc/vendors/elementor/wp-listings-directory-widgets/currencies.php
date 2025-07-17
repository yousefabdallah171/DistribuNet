<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Listings_Directory_Currencies extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_currencies';
    }

	public function get_title() {
        return esc_html__( 'Apus Currencies Switcher', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Currencies', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__( 'Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .dropdown-toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'guido' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'guido' ),
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        ?>
        <div class="widget-currencies <?php echo esc_attr($el_class); ?>">
            <?php echo do_shortcode('[wp_listings_directory_currencies]'); ?>
        </div>
        <?php
    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Listings_Directory_Currencies );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_Listings_Directory_Currencies );
}