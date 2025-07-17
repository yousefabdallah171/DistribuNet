<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Popup_Video extends Widget_Base {

	public function get_name() {
        return 'apus_element_popup_video';
    }

	public function get_title() {
        return esc_html__( 'Apus Popup Video', 'guido' );
    }

	public function get_icon() {
        return 'eicon-youtube';
    }

	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'guido' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_link',
            [
                'label' => esc_html__( 'Youtube Video Link', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'url',
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

        ?>
        <div class="widget-video <?php echo esc_attr($el_class);?>">
            <div class="video-wrapper-inner">
                <?php
                if ( !empty($img_src['id']) ) {
                ?>
                    <?php echo guido_get_attachment_thumbnail($img_src['id'], 'full'); ?>
                <?php } ?>
                <a class="popup-video" href="<?php echo esc_url($video_link); ?>">
                    <span class="popup-video-inner">
                        <i class="fa fa-play"></i>
                    </span>
                </a>
            </div>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Popup_Video );
} else {
    Plugin::instance()->widgets_manager->register( new Guido_Elementor_Popup_Video );
}