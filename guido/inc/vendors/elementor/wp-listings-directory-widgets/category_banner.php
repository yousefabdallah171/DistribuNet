<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Listings_Directory_Category_Banner extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_category_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Category Banner', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Category Banner', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title here', 'guido' ),
            ]
        );

        $this->add_control(
            'slug',
            [
                'label' => esc_html__( 'Category Slug', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Category Slug here', 'guido' ),
            ]
        );

        $this->add_control(
            'custom_url',
            [
                'label' => esc_html__( 'Custom URL', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your custom url here', 'guido' ),
            ]
        );

        $this->add_control(
            'image_icon',
            [
                'label' => esc_html__( 'Image or Icon', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'icon' => esc_html__('Icon', 'guido'),
                    'image' => esc_html__('Image', 'guido'),
                ),
                'default' => 'image'
            ]
        );

        $this->add_control(
            'category_icon',
            [
                'label' => esc_html__( 'Category Icon', 'guido' ),
                'type' => Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-star',
                'condition' => [
                    'image_icon' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'img_bg_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image', 'guido' ),
                'type' => Elementor\Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'guido' ),
                'condition' => [
                    'image_icon' => 'image',
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'guido'),
                ),
                'default' => 'style1'
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


        $this->start_controls_section(
            'section_bg',
            [
                'label' => esc_html__( 'Background', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_widget_style' );

            $this->start_controls_tab(
                'tab_bg_normal',
                [
                    'label' => esc_html__( 'Normal', 'guido' ),
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background',
                    'selector' => '{{WRAPPER}} .category-banner-inner',
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_bg_hover',
                [
                    'label' => esc_html__( 'Hover', 'guido' ),
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background_hvoer',
                    'selector' => '{{WRAPPER}} .category-banner-inner:hover',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_bg_overlay',
            [
                'label' => esc_html__( 'Background Overlay', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background_overlay',
                    'selector' => '{{WRAPPER}} .category-banner-inner:hover:before',
                ]
            );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__( 'Content Style', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_content_style' );

            $this->start_controls_tab(
                'tab_content_normal',
                [
                    'label' => esc_html__( 'Normal', 'guido' ),
                ]
            );

                $this->add_control(
                    'title_color',
                    [
                        'label' => esc_html__( 'Title Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .category-banner-inner .title' => 'color: {{VALUE}};',
                        ],
                    ]
                );


                $this->add_control(
                    'icon_color',
                    [
                        'label' => esc_html__( 'Icon Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .category-banner-inner .banner-image' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'border_box',
                        'label' => esc_html__( 'Border Box', 'guido' ),
                        'selector' => '{{WRAPPER}} .category-banner-inner',
                    ]
                );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_content_hover',
                [
                    'label' => esc_html__( 'Hover', 'guido' ),
                ]
            );

                $this->add_control(
                    'title_hv_color',
                    [
                        'label' => esc_html__( 'Title Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .category-banner-inner:hover .title' => 'color: {{VALUE}};',
                        ],
                    ]
                );


                $this->add_control(
                    'icon_hv_color',
                    [
                        'label' => esc_html__( 'Icon Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .category-banner-inner:hover .banner-image' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'border_box_hv',
                        'label' => esc_html__( 'Border Box', 'guido' ),
                        'selector' => '{{WRAPPER}} .category-banner-inner:hover',
                    ]
                );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-listing-category-banner <?php echo esc_attr($el_class); ?>">

            <?php
            $term = get_term_by( 'slug', $slug, 'listing_category' );
            $link = $custom_url;
            if ($term) {
                if ( empty($link) ) {
                    $link = get_term_link( $term, 'listing_category' );
                }
                if ( empty($title) ) {
                    $title = $term->name;
                }
            }
            ?>

            <a class="category-banner-inner <?php echo esc_attr($style); ?>" href="<?php echo esc_url($link); ?>">
                
                <?php
                if ( $image_icon == 'image' ) {
                    if ( !empty($img_bg_src['id']) ) {
                    ?>
                        <div class="banner-image image">
                            <?php echo guido_get_attachment_thumbnail($img_bg_src['id'], 'full'); ?>
                        </div>
                    <?php } ?>
                <?php } else {
                    if ( !empty($category_icon) ) {
                    ?>
                        <div class="banner-image icon"><i class="<?php echo esc_attr($category_icon); ?>"></i></div>
                    <?php }
                } ?>

                <div class="inner">
                    <?php if ( !empty($title) ) { ?>
                        <h4 class="title">
                            <?php echo trim($title); ?>
                        </h4>
                    <?php } ?>

                </div>
            </a>

        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Listings_Directory_Category_Banner );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_Listings_Directory_Category_Banner );
}