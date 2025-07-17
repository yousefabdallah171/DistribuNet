<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Listings_Directory_Location_Banner extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_location_banner';
    }

	public function get_title() {
        return esc_html__( 'Apus Location Banner', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Location Banner', 'guido' ),
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
                'label' => esc_html__( 'Location Slug', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your Location Slug here', 'guido' ),
            ]
        );

        $this->add_control(
            'show_nb_listings',
            [
                'label' => esc_html__( 'Show Number Listings', 'guido' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'guido' ),
                'label_off' => esc_html__( 'Show', 'guido' ),
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
            'img_bg_src',
            [
                'name' => 'image',
                'label' => esc_html__( 'Image', 'guido' ),
                'type' => Elementor\Controls_Manager::MEDIA,
                'placeholder'   => esc_html__( 'Upload Image Here', 'guido' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'style1' => esc_html__('Style 1', 'guido'),
                    'style2' => esc_html__('Style 2', 'guido'),
                ),
                'default' => 'style1'
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__( 'Height', 'guido' ),
                'type' => Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'style' => 'style1',
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


        $this->start_controls_section(
            'section_overlay',
            [
                'label' => esc_html__( 'Background Overlay', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

       $this->start_controls_tabs( 'tabs_button_style' );

            $this->start_controls_tab(
                'tab_bg_normal',
                [
                    'label' => esc_html__( 'Normal', 'guido' ),
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background_overlay',
                    'selector' => '{{WRAPPER}} .type-banner-inner .banner-image::before',
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
                    'name' => 'background_hvoer_overlay',
                    'selector' => '{{WRAPPER}} .type-banner-inner:hover .banner-image::before',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__( 'Typography', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Title Typography', 'guido' ),
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Stronger selector to avoid section style from overwriting
                    '{{WRAPPER}} .number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Number Typography', 'guido' ),
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .number',
            ]
        );

        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        ?>
        <div class="widget-listing-type-banner <?php echo esc_attr($el_class); ?>">

            <?php
            $term = get_term_by( 'slug', $slug, 'listing_location' );
            $link = $custom_url;
            if ($term) {
                if ( empty($link) ) {
                    $link = get_term_link( $term, 'listing_location' );
                }
                if ( empty($title) ) {
                    $title = $term->name;
                }
            }
            ?>

            <a class="type-banner-inner <?php echo esc_attr($style); ?>" href="<?php echo esc_url($link); ?>">
                
                <?php
                if ( !empty($img_bg_src['id']) ) {
                ?>
                    <div class="banner-image">
                        <?php echo guido_get_attachment_thumbnail($img_bg_src['id'], 'full'); ?>
                    </div>
                <?php } ?>

                <div class="inner">
                    <div class="info-city">
                        
                        <?php if ( !empty($title) ) { ?>
                            <h4 class="title">
                                <?php echo trim($title); ?>
                            </h4>
                        <?php } ?>
                        <?php if ( $show_nb_listings ) {
                                $args = array(
                                    'fields' => 'ids',
                                    'location' => array($slug),
                                    'limit' => 1
                                );
                                $query = guido_get_listings($args);
                                $count = $query->found_posts;
                                $number_listings = $count ? WP_Listings_Directory_Mixes::format_number($count) : 0;
                        ?>
                        <div class="number"><?php echo sprintf(_n('<span>%d</span> Listing', '<span>%d</span> Listings', $count, 'guido'), $number_listings); ?></div>
                        <?php } ?>
                    </div>
                </div>
            </a>

        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Listings_Directory_Location_Banner );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_Listings_Directory_Location_Banner );
}