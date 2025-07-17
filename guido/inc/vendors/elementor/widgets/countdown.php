<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Countdown extends Widget_Base {

	public function get_name() {
        return 'apus_element_countdown';
    }

	public function get_title() {
        return esc_html__( 'Apus Countdown', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Countdown', 'guido' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title here', 'guido' ),
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => esc_html__( 'Price', 'guido' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your Price here', 'guido' ),
            ]
        );
        $this->add_control(
            'des',
            [
                'label' => esc_html__( 'Content', 'guido' ),
                'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your content here', 'guido' ),
            ]
        );
        $this->add_control(
            'end_date', [
                'label' => esc_html__( 'End Date', 'guido' ),
                'type' => Controls_Manager::DATE_TIME,
                'picker_options' => [
                    'enableTime' => false
                ]
            ]
        );
        
        $this->add_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'guido' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'guido' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'guido' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'guido' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'guido' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-countdown' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'URL', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Button Link here', 'guido' ),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your button text here', 'guido' ),
            ]
        );

        $this->add_control(
            'btn_style',
            [
                'label' => esc_html__( 'Button Style', 'guido' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'btn-theme' => esc_html__('Theme Color', 'guido'),
                    'btn-theme btn-outline' => esc_html__('Theme Outline Color', 'guido'),
                    'btn-default' => esc_html__('Default ', 'guido'),
                    'btn-primary' => esc_html__('Primary ', 'guido'),
                    'btn-success' => esc_html__('Success ', 'guido'),
                    'btn-info' => esc_html__('Info ', 'guido'),
                    'btn-warning' => esc_html__('Warning ', 'guido'),
                    'btn-danger' => esc_html__('Danger ', 'guido'),
                    'btn-pink' => esc_html__('Pink ', 'guido'),
                    'btn-white' => esc_html__('White ', 'guido'),
                ),
                'default' => 'btn-default'
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'guido' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'guido'),
                    'style2' => esc_html__('Style 2(showdow)', 'guido'),
                    'style3' => esc_html__('Style 3(circle)', 'guido'),
                ),
                'default' => 'style1'
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'guido' ),
                'type'          => Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'guido' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style', 'guido' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'guido' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'guido' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Description Color', 'guido' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .des' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Description Typography', 'guido' ),
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .des',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        $end_date = !empty($end_date) ? strtotime($end_date) : '';
        if ( $end_date ) {
            wp_enqueue_script( 'countdown' );
            ?>
            <div class="widget-countdown <?php echo esc_attr($el_class.' '.$style); ?>">
                <?php if ( !empty($title) ) { ?>
                    <h2 class="title"><?php echo esc_html($title); ?></h2>
                <?php } ?>
                <?php if ( !empty($price) ) { ?>
                    <div class="price"><?php echo trim($price); ?></div>
                <?php } ?>
                <?php if ( !empty($des) ) { ?>
                    <div class="des"><?php echo trim($des); ?></div>
                <?php } ?>
                <div class="time-wrapper">
                    <div class="apus-countdown clearfix" data-time="timmer"
                        data-date="<?php echo date('m', $end_date).'-'.date('d', $end_date).'-'.date('Y', $end_date).'-'. date('H', $end_date) . '-' . date('i', $end_date) . '-' .  date('s', $end_date) ; ?>">
                    </div>
                </div>
                <?php if ( !empty($btn_text) && !empty($link) ) { ?>
                    <div class="url-bottom">
                        <a href="<?php echo esc_url($link); ?>" class="btn <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>"><?php echo esc_html($btn_text); ?></a>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Countdown );
} else {
    Plugin::instance()->widgets_manager->register( new Guido_Elementor_Countdown );
}