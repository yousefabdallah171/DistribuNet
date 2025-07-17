<?php

//namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_User_Header_Notification extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_header_notification';
    }

	public function get_title() {
        return esc_html__( 'Apus Message Notification', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-header-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
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

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'guido' ),
                'type' => Elementor\Controls_Manager::CHOOSE,
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
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Title', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color Icon', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .message-notification i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Color Hover Icon', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .message-notification:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .message-notification:focus i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        $count = 0;
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $args = array(
                'post_per_page' => 1,
                'paged' => 1,
                'author' => $user_id,
                'meta_query' => array(
                    array(
                        'relation' => 'OR',
                        array(
                            'key'       => '_read_'.$user_id,
                            'value'     => '',
                            'compare'   => '==',
                        ),
                        array(
                            'key' => '_read_'.$user_id,
                            'compare' => 'NOT EXISTS',
                        )
                    )
                )
            );
            $loop = WP_Private_Message_Message::get_list_messages($args);
            $count = $loop->found_posts;
        }
        $page_id = wp_private_message_get_option('message_dashboard_page_id');
        $page_url = get_permalink($page_id);
        ?>
        <div class="message-top <?php echo esc_attr($el_class); ?>">
            <a class="message-notification" href="<?php echo esc_url($page_url); ?>">
                <i class="ti-bell"></i>
                <?php if ( 1==1 ) { ?>
                    <span class="unread-count bg-warning"><?php echo esc_html($count); ?></span>
                <?php } ?>
            </a>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_User_Header_Notification );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_User_Header_Notification );
}