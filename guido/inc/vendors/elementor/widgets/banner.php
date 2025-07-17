<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Banner extends Widget_Base {

	public function get_name() {
        return 'apus_element_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Banner', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Banner', 'guido' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image', 'guido' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'guido' ),
            ]
        );

        $this->add_responsive_control(
            'img_align',
            [
                'label' => esc_html__( 'Image Alignment', 'guido' ),
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
                    '{{WRAPPER}} .banner-image' => 'text-align: {{VALUE}};',
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
            'content',
            [
                'label' => esc_html__( 'Content', 'guido' ),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Enter your content here', 'guido' ),
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

        $this->add_responsive_control(
            'content_align',
            [
                'label' => esc_html__( 'Content Alignment', 'guido' ),
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
                    '{{WRAPPER}} .banner-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'guido' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'guido'),
                    'style2' => esc_html__('Style 2', 'guido'),
                    'style3' => esc_html__('Style 3', 'guido'),
                    'style4' => esc_html__('Style 4', 'guido'),
                ),
                'default' => 'style1'
            ]
        );
        $this->add_control(
            'vertical',
            [
                'label' => esc_html__( 'Vertical Content', 'guido' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'flex-top' => esc_html__('Top', 'guido'),
                    'flex-middle' => esc_html__('Middle', 'guido'),
                    'flex-bottom' => esc_html__('Bottom', 'guido'),
                ),
                'default' => 'flex-middle'
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

    }

	protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        $img_bg_src = ( isset( $img_bg_src['id'] ) && $img_bg_src['id'] != 0 ) ? wp_get_attachment_url( $img_bg_src['id'] ) : '';
        $style_bg = '';
        if ( !empty($img_bg_src) ) {
            $style_bg = 'style="background-image:url('.esc_url($img_bg_src).')"';
        }
        ?>
        <div class="widget-banner updow <?php echo esc_attr($el_class.' '.$style); ?>" <?php echo trim($style_bg); ?>>
            <?php if ( !empty($link) ) { ?>
                <a href="<?php echo esc_url($link); ?>">
            <?php } ?>
                <div class="inner <?php echo esc_attr($vertical); ?>">
                    <?php
                    if ( !empty($img_src['id']) ) {
                    ?>
                        <div class="p-static col-xs-<?php echo esc_attr(!empty($content) ? '6':'12' ); ?>">
                            <div class="banner-image">
                                <?php echo guido_get_attachment_thumbnail($img_src['id'], 'full'); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ( (!empty($content) && !empty($btn_text)) || !empty($content) ) { ?>
                        <div class="p-static col-xs-6 col-sm-<?php echo esc_attr( (!empty($img_src['id']))? '6':'12' ); ?>">
                            <div class="banner-content">
                                <?php if ( !empty($content) ) { ?>
                                    <?php echo trim($content); ?>
                                <?php } ?>
                                <?php if ( !empty($btn_text) ) { ?>
                                    <div class="link-bottom">
                                        <span class="btn radius-50 <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>"><?php echo esc_html($btn_text); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ( !empty($btn_text) && empty($content) ) { ?>
                        <span class="btn radius-50 <?php echo esc_attr(!empty($btn_style) ? $btn_style : ''); ?>"><?php echo esc_html($btn_text); ?></span>
                    <?php } ?>
                </div>
            <?php if ( !empty($link) ) { ?>
                </a>
            <?php } ?>
        </div>
        <?php

    }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Banner );
} else {
    Plugin::instance()->widgets_manager->register( new Guido_Elementor_Banner );
}