<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Testimonials extends Widget_Base {

    public function get_name() {
        return 'apus_element_testimonials';
    }

    public function get_title() {
        return esc_html__( 'Apus Testimonials', 'guido' );
    }

    public function get_icon() {
        return 'eicon-testimonial';
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

        $repeater = new Repeater();

        $repeater->add_control(
            'img_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Choose Image', 'guido' ),
                'type' => Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Brand Image', 'guido' ),
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => esc_html__( 'Name', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'content', [
                'label' => esc_html__( 'Content', 'guido' ),
                'type' => Controls_Manager::TEXTAREA
            ]
        );

        $repeater->add_control(
            'listing',
            [
                'label' => esc_html__( 'Job', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'stars',
            [
                'label' => esc_html__( 'Star', 'guido' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    1 => esc_html__('1 Star', 'guido'),
                    2 => esc_html__('2 Stars', 'guido'),
                    3 => esc_html__('3 Stars', 'guido'),
                    4 => esc_html__('4 Stars', 'guido'),
                    5 => esc_html__('5 Stars', 'guido'),
                ),
                'default' => 5
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link To', 'guido' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'Enter your social link here', 'guido' ),
                'placeholder' => esc_html__( 'https://your-link.com', 'guido' ),
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__( 'Testimonials', 'guido' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'guido' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'number',
                'default' => '1'
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label' => esc_html__( 'Show Nav', 'guido' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'guido' ),
                'label_off' => esc_html__( 'Show', 'guido' ),
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__( 'Show Pagination', 'guido' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'guido' ),
                'label_off' => esc_html__( 'Show', 'guido' ),
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'guido' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'guido'),
                    'style2' => esc_html__('Style 2', 'guido'),
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
            'section_box_style',
            [
                'label' => esc_html__( 'Style Box', 'guido' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border_box',
                'label' => esc_html__( 'Border Box', 'guido' ),
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_box',
                'label' => esc_html__( 'Box Shadow Box', 'guido' ),
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hv_box',
                'label' => esc_html__( 'Box Shadow Hover Box', 'guido' ),
                'selector' => '{{WRAPPER}} .testimonials-item:hover .description',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_img',
                'label' => esc_html__( 'Box Shadow Image', 'guido' ),
                'selector' => '{{WRAPPER}} .avarta',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hv_img',
                'label' => esc_html__( 'Box Shadow Hover Image', 'guido' ),
                'selector' => '{{WRAPPER}} .testimonials-item:hover .avarta',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Style Info', 'guido' ),
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
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'guido' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->add_control(
            'test_title_color',
            [
                'label' => esc_html__( 'Testimonial Title Color', 'guido' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .name-client' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .name-client a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Testimonial Title Typography', 'guido' ),
                'name' => 'test_title_typography',
                'selector' => '{{WRAPPER}} .name-client',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Content Color', 'guido' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Content Typography', 'guido' ),
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .description',
            ]
        );

        $this->add_control(
            'listing_color',
            [
                'label' => esc_html__( 'Listing Color', 'guido' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .listing' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Listing Typography', 'guido' ),
                'name' => 'listing_typography',
                'selector' => '{{WRAPPER}} .listing',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings();

        extract( $settings );

        if ( !empty($testimonials) ) {
            ?>
            <div class="widget-testimonials <?php echo esc_attr($el_class.' '.$layout_type); ?>">

                <?php if ( $layout_type == 'style1' ) { ?>
                    <div class="slick-carousel testimonial-main <?php echo trim( ($columns >= count($testimonials))?'hidden-dots':'' ); ?>" data-items="<?php echo esc_attr($columns); ?>" data-large="1" data-medium="1" data-small="1" data-smallest="1" data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>" data-centerMode="true" data-infinite="true">
                        <?php foreach ($testimonials as $item) { ?>
                        <div class="item">
                            <div class="testimonials-item <?php echo trim( $layout_type ); ?>">
                                <span class="comma">“</span>
                                <?php if ( isset( $item['img_src']['id'] ) ) { ?>
                                <div class="wrapper-avarta">
                                    <div class="m-auto avarta d-flex justify-content-center align-items-center">
                                        <?php echo guido_get_attachment_thumbnail($item['img_src']['id'], 'full'); ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="info-testimonials">
                                    <?php if ( !empty($item['name']) ) {

                                        $title = '<h3 class="name-client">'.$item['name'].'</h3>';
                                        if ( ! empty( $item['link']['url'] ) ) {
                                            $title = sprintf( '<h3 class="name-client"><a href="'.esc_url($item['link']['url']).'" target="'.esc_attr($item['link']['is_external'] ? '_blank' : '_self').'" '.($item['link']['nofollow'] ? 'rel="nofollow"' : '').'>%1$s</a></h3>', $item['name'] );
                                        }
                                        echo trim($title);
                                    ?>
                                    <?php } ?>
                                    <?php if ( !empty($item['listing']) ) { ?>
                                        <div class="listing"><?php echo esc_html($item['listing']); ?></div>
                                    <?php } ?> 
                                </div>
                            
                                <?php if ( !empty($item['content']) ) { ?>
                                    <div class="description"><?php echo trim($item['content']); ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                <?php } else { ?>
                    <div class="slick-carousel testimonial-main <?php echo trim( ($columns >= count($testimonials))?'hidden-dots':'' ); ?>" data-items="<?php echo esc_attr($columns); ?>" data-large="1" data-medium="1" data-small="1" data-smallest="1" data-pagination="<?php echo esc_attr($show_pagination ? 'true' : 'false'); ?>" data-nav="<?php echo esc_attr($show_nav ? 'true' : 'false'); ?>">
                        <?php foreach ($testimonials as $item) { ?>
                        <div class="item">
                            <div class="testimonials-item clearfix <?php echo trim( $layout_type ); ?>">

                                <div class="top-info">
                                    <div class="star-rating"><span style="width:<?php echo esc_attr($item['stars']*20); ?>%"></span></div>
                                    <?php if ( !empty($item['content']) ) { ?>
                                        <div class="description"><?php echo trim($item['content']); ?></div>
                                    <?php } ?>
                                </div>

                                <div class="bottom-info">
                                    <div class="d-flex align-items-center">
                                        <?php if ( isset( $item['img_src']['id'] ) ) { ?>
                                            <div class="wrapper-avarta">
                                                <div class="avarta">
                                                    <?php echo guido_get_attachment_thumbnail($item['img_src']['id'], 'full'); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="info-testimonials">
                                            <?php if ( !empty($item['name']) ) {

                                                $title = '<h3 class="name-client">'.$item['name'].'</h3>';
                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    $title = sprintf( '<h3 class="name-client"><a href="'.esc_url($item['link']['url']).'" target="'.esc_attr($item['link']['is_external'] ? '_blank' : '_self').'" '.($item['link']['nofollow'] ? 'rel="nofollow"' : '').'>%1$s</a></h3>', $item['name'] );
                                                }
                                                echo trim($title);
                                            ?>
                                            <?php } ?>
                                            <?php if ( !empty($item['listing']) ) { ?>
                                                <div class="listing"><?php echo esc_html($item['listing']); ?></div>
                                            <?php } ?> 
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                    
            </div>
            <?php
        }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Testimonials );
} else {
    Plugin::instance()->widgets_manager->register( new Guido_Elementor_Testimonials );
}