<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Listings_Directory_Search_Form extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_search_form';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings Search Form', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Search Form', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'guido' ),
            ]
        );

        $fields = apply_filters( 'wp-listings-directory-default-listing-filter-fields', array() );
        $search_fields = array( '' => esc_html__('Choose a field', 'guido') );
        foreach ($fields as $key => $field) {
            $name = $field['name'];
            if ( empty($field['name']) ) {
                $name = $key;
            }
            $search_fields[$key] = $name;
        }

        $repeater = new Elementor\Repeater();

        $repeater->add_control(
            'filter_field',
            [
                'label' => esc_html__( 'Filter field', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $search_fields
            ]
        );
        
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
            ]
        );

        $repeater->add_control(
            'placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
            ]
        );

        $repeater->add_control(
            'enable_autocompleate_search',
            [
                'label' => esc_html__( 'Enable autocompleate search', 'guido' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Yes', 'guido' ),
                'label_off' => esc_html__( 'No', 'guido' ),
                'condition' => [
                    'filter_field' => 'title',
                ],
            ]
        );

        $custom_menus = array( '' => esc_html__('None', 'guido'));
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
        if ( is_array( $menus ) && ! empty( $menus ) ) {
            foreach ( $menus as $menu ) {
                if ( is_object( $menu ) && isset( $menu->name, $menu->term_id ) ) {
                    $custom_menus[ $menu->term_id ] = $menu->name;
                }
            }
        }

        $repeater->add_control(
            'search_suggestions_menu',
            [
                'label' => esc_html__( 'Search Suggestions Menu', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $custom_menus,
                'default' => '',
                'condition' => [
                    'filter_field' => 'title',
                ],
            ]
        );

        $repeater->add_control(
            'style',
            [
                'label' => esc_html__( 'Price Style', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    'slider' => esc_html__('Price Slider', 'guido'),
                    'text' => esc_html__('Pice Min/max Input Text', 'guido'),
                    'list' => esc_html__('Price List', 'guido'),
                ],
                'default' => 'slider',
                'condition' => [
                    'filter_field' => 'price',
                ],
            ]
        );
        $repeater->add_control(
            'price_range_size',
            [
                'label' => esc_html__( 'Price range size', 'guido' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'text',
                'default' => 1000,
                'condition' => [
                    'filter_field' => 'price',
                    'style' => 'list',
                ],
            ]
        );
        $repeater->add_control(
            'price_range_max',
            [
                'label' => esc_html__( 'Max price ranges', 'guido' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'text',
                'default' => 10,
                'condition' => [
                    'filter_field' => 'price',
                    'style' => 'list',
                ],
            ]
        );
        $repeater->add_control(
            'min_price_placeholder',
            [
                'label' => esc_html__( 'Min Price Placeholder', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Min Price',
                'condition' => [
                    'filter_field' => 'price',
                    'style' => 'text',
                ],
            ]
        );

        $repeater->add_control(
            'max_price_placeholder',
            [
                'label' => esc_html__( 'Max Price Placeholder', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Max Price',
                'condition' => [
                    'filter_field' => 'price',
                    'style' => 'text',
                ],
            ]
        );

        $repeater->add_control(
            'slider_style',
            [
                'label' => esc_html__( 'Layout Style', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => [
                    'slider' => esc_html__('Slider', 'guido'),
                    'text' => esc_html__('Input Text', 'guido'),
                ],
                'default' => 'slider',
                'condition' => [
                    'filter_field' => ['mileage', 'year'],
                ],
            ]
        );

        $repeater->add_control(
            'min_placeholder',
            [
                'label' => esc_html__( 'Min Placeholder', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Min',
                'condition' => [
                    'filter_field' => ['mileage', 'year'],
                    'slider_style' => 'text',
                ],
            ]
        );

        $repeater->add_control(
            'max_placeholder',
            [
                'label' => esc_html__( 'Max Placeholder', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Max',
                'condition' => [
                    'filter_field' => ['mileage', 'year'],
                    'slider_style' => 'text',
                ],
            ]
        );

        $repeater->add_control(
            'suffix',
            [
                'label' => esc_html__( 'Suffix', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Miles',
                'condition' => [
                    'filter_field' => ['mileage'],
                ],
            ]
        );

        $repeater->add_control(
            'filter_layout',
            [
                'label' => esc_html__( 'Filter Layout', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'select' => esc_html__('Select', 'guido'),
                    'radio' => esc_html__('Radio Button', 'guido'),
                    'check_list' => esc_html__('Check Box', 'guido'),
                ),
                'default' => 'select',
                'condition' => [
                    'filter_field' => ['category', 'type', 'feature', 'location'],
                ],
            ]
        );
        
        $columns = array();
        for ($i=1; $i <= 12 ; $i++) { 
            $columns[$i] = sprintf(esc_html__('%d Columns', 'guido'), $i);
        }
        $repeater->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'default' => 1
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'guido' ),
                'type' => Elementor\Controls_Manager::ICON
            ]
        );

        $this->add_control(
            'main_search_fields',
            [
                'label' => esc_html__( 'Main Search Fields', 'guido' ),
                'type' => Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'show_advance_search',
            [
                'label'         => esc_html__( 'Show Advanced Search', 'guido' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'guido' ),
                'label_off'     => esc_html__( 'Hide', 'guido' ),
                'return_value'  => true,
                'default'       => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'advance_search_fields',
            [
                'label' => esc_html__( 'Advanced Search Fields', 'guido' ),
                'type' => Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'filter_btn_text',
            [
                'label' => esc_html__( 'Button Text', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Find Listing',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'advanced_btn_text',
            [
                'label' => esc_html__( 'Advanced Text', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Advanced',
            ]
        );

        $this->add_control(
            'btn_columns',
            [
                'label' => esc_html__( 'Button Columns', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'default' => 1
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout Type', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'horizontal' => esc_html__('Horizontal', 'guido'),
                    'vertical' => esc_html__('Vertical', 'guido'),
                ),
                'default' => 'horizontal',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__( 'Style', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'style_df' => esc_html__('Default', 'guido'),
                    'style1' => esc_html__('Style 1', 'guido'),
                    'style2' => esc_html__('Style 2', 'guido'),
                ),
                'default' => 'style_df'
            ]
        );

        $this->add_control(
            'show_reset',
            [
                'label'         => esc_html__( 'Show Reset button', 'guido' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'guido' ),
                'label_off'     => esc_html__( 'Hide', 'guido' ),
                'return_value'  => true,
                'default'       => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'reset_btn_text',
            [
                'label' => esc_html__( 'Reset Button Text', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Reset Search',
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'guido' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'guido' ),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'label' => esc_html__( 'Typography', 'guido' ),
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .btn-submit',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__( 'Normal', 'guido' ),
                ]
            );
            
            $this->add_control(
                'button_color',
                [
                    'label' => esc_html__( 'Button Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background_button',
                    'label' => esc_html__( 'Background', 'guido' ),
                    'types' => [ 'classic', 'gradient', 'video' ],
                    'selector' => '{{WRAPPER}} .btn-submit',
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_button',
                    'label' => esc_html__( 'Border', 'guido' ),
                    'selector' => '{{WRAPPER}} .btn-submit',
                ]
            );

            $this->add_control(
                'padding_button',
                [
                    'label' => esc_html__( 'Padding', 'guido' ),
                    'type' => Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'btn_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'guido' ),
                    'type' => Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'guido' ),
                ]
            );

            $this->add_control(
                'button_hover_color',
                [
                    'label' => esc_html__( 'Button Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit:hover, {{WRAPPER}} .btn-submit:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'background_button_hover',
                    'label' => esc_html__( 'Background', 'guido' ),
                    'types' => [ 'classic', 'gradient', 'video' ],
                    'selector' => '{{WRAPPER}} .btn-submit:hover, {{WRAPPER}} .btn-submit:focus',
                ]
            );

            $this->add_control(
                'button_hover_border_color',
                [
                    'label' => esc_html__( 'Border Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'condition' => [
                        'border_button_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit:hover, {{WRAPPER}} .btn-submit:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'padding_button_hover',
                [
                    'label' => esc_html__( 'Padding', 'guido' ),
                    'type' => Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit:hover, {{WRAPPER}} .btn-submit:focus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'btn_hv_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'guido' ),
                    'type' => Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .btn-submit:hover, {{WRAPPER}} .btn-submit:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab 

        $this->end_controls_section();


        $this->start_controls_section(
            'section_border_style',
            [
                'label' => esc_html__( 'Box', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'padding_box',
            [
                'label' => esc_html__( 'Padding', 'guido' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .widget-listing-search-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bg_box',
            [
                'label' => esc_html__( 'Background Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-listing-search-form' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => esc_html__( 'Box Shadow', 'guido' ),
                'selector' => '{{WRAPPER}} .widget-listing-search-form',
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'guido' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .widget-listing-search-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_typography_style',
            [
                'label' => esc_html__( 'Typography', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-search' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .advance-search-btn' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .circle-check' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .form-control' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .form-control::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .form-control:-ms-input-placeholder ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .form-control::placeholder ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .select2-selection--single .select2-selection__placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__( 'Border Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-control' => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .select2-container--default.select2-container .select2-selection--single' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'border_hv_color',
            [
                'label' => esc_html__( 'Border Active Color', 'guido' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-control:focus' => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .select2-container--default.select2-container.select2-container--open .select2-selection--single' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_advance_style',
            [
                'label' => esc_html__( 'Advance', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_advance_style' );

            $this->start_controls_tab(
                'tab_advance_normal',
                [
                    'label' => esc_html__( 'Normal', 'guido' ),
                ]
            );

            $this->add_control(
                'color_ad',
                [
                    'label' => esc_html__( 'Button Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .advance-search-btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_advance_hover',
                [
                    'label' => esc_html__( 'Hover', 'guido' ),
                ]
            );

            $this->add_control(
                'color_ad_active',
                [
                    'label' => esc_html__( 'Button Active Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .advance-search-btn:hover,
                        {{WRAPPER}} .advance-search-btn:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        
        $search_page_url = WP_Listings_Directory_Mixes::get_listings_page_url();

        guido_load_select2();

        $filter_fields = apply_filters( 'wp-listings-directory-default-listing-filter-fields', array() );
        $instance = array();
        $widget_id = guido_random_key();
        $args = array( 'widget_id' => $widget_id );
        ?>
        <div class="widget-listing-search-form <?php echo esc_attr($el_class.' '.$style.' '.$layout_type); ?>">
            
            <?php if ( $title ) { ?>
                <h2 class="title"><?php echo esc_html($title); ?></h2>
            <?php } ?>
            
            <form id="filter-listing-form-<?php echo esc_attr($widget_id); ?>" action="<?php echo esc_url($search_page_url); ?>" class="form-search filter-listing-form <?php echo esc_attr($style); ?>" method="GET">
                <div class="search-form-inner">
                    <?php if ( $layout_type == 'horizontal' ) { ?>
                        <div class="main-inner clearfix">
                            <div class="content-main-inner">
                                <div class="row list-fileds">
                                    <?php
                                        $this->form_fields_display($main_search_fields, $filter_fields, $instance, $args);
                                    ?>
                                    <div class="col-12 col-md-<?php echo esc_attr($btn_columns); ?> form-group-search <?php echo trim( ($btn_columns == 1)?'width-10':'' ); ?>">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <?php if ( $show_advance_search && !empty($advance_search_fields) ) { ?>
                                                <div class="advance-link">
                                                    <a href="javascript:void(0);" class="advance-search-btn">
                                                        
                                                        <?php
                                                            if ( !empty($advanced_btn_text) ) {
                                                                echo esc_html($advanced_btn_text);
                                                            }
                                                        ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                            <button class="btn-submit btn d-block btn-theme btn-inverse" type="submit">
                                                <?php if( !empty($filter_btn_text) ) { ?>
                                                    <?php echo trim($filter_btn_text); ?>
                                                <?php }else{ ?>
                                                    <i class="flaticon-loupe"></i>
                                                <?php } ?>
                                            </button>

                                            <?php if ( $show_reset ) { ?>
                                                <a href="javascript:void(0);" class="reset-search-btn">
                                                    <?php
                                                        if ( !empty($reset_btn_text) ) {
                                                            echo esc_html($reset_btn_text);
                                                        }
                                                    ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                        if ( $show_advance_search && !empty($advance_search_fields) ) {
                            ?>
                            <div id="advance-search-wrapper-<?php echo esc_attr($widget_id); ?>" class="advance-search-wrapper">
                                <div class="advance-search-wrapper-fields form-theme">
                                    <div class="inner-search-advance">
                                        <div class="inner">
                                            <div class="row row-20">
                                                <?php
                                                    $this->form_fields_display($advance_search_fields, $filter_fields, $instance, $args);
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    <?php } else { ?>
                        <div class="main-inner clearfix">
                            <div class="content-main-inner">
                                <div class="row">
                                    <?php
                                        $this->form_fields_display($main_search_fields, $filter_fields, $instance, $args);
                                    ?>

                                    <?php if( $show_advance_search && !empty($advance_search_fields)) { ?>
                                        <div class="col-12">
                                            <div class="form-group space-20">
                                                <div class="advance-link">
                                                    <a href="javascript:void(0);" class="advance-search-btn">
                                                        
                                                        <?php
                                                            if ( !empty($advanced_btn_text) ) {
                                                                echo esc_html($advanced_btn_text);
                                                            }
                                                        ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>

                                <?php
                                if ( $show_advance_search && !empty($advance_search_fields) ) {
                                    ?>
                                    <div id="advance-search-wrapper-<?php echo esc_attr($widget_id); ?>" class="advance-search-wrapper">
                                        <div class="advance-search-wrapper-fields form-theme">

                                            <div class="inner-search-advance">
                                                <div class="inner">
                                                    <div class="row">
                                                        <?php
                                                            $this->form_fields_display($advance_search_fields, $filter_fields, $instance, $args);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="row">
                                    <div class="col-12 col-md-<?php echo esc_attr($btn_columns); ?> form-group-search">
                                        <button class="btn-submit d-block btn w-100 btn-theme btn-inverse" type="submit">
                                            <?php if( !empty($filter_btn_text) ) { ?>
                                                <?php echo trim($filter_btn_text); ?>
                                            <?php }else{ ?>
                                                <i class="flaticon-loupe"></i>
                                            <?php } ?>
                                        </button>
                                    </div>
                                </div>

                                <!-- Save Search -->
                                <?php if ( $show_reset ) { ?>
                                    <div class="row">
                                        <div class="col-12 search-action">
                                            <?php if ( $show_reset ) { ?>
                                                <a href="javascript:void(0);" class="reset-search-btn">
                                                    <?php
                                                        if ( !empty($reset_btn_text) ) {
                                                            echo esc_html($reset_btn_text);
                                                        }
                                                    ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        
                        
                    <?php } ?>
                </div>
            </form>

        </div>
        <?php
    }

    public function form_fields_display($search_fields, $filter_fields, $instance, $args) {
        $i = 1;
        if ( !empty($search_fields) ) {
            $sub_class = '';
            $i = 1;
            foreach ($search_fields as $item) {

                if ( empty($filter_fields[$item['filter_field']]['field_call_back']) ) {
                    continue;
                }
                $filter_field = $filter_fields[$item['filter_field']];
                if ( $item['filter_field'] == 'title' ) {
                    if ($item['enable_autocompleate_search']) {
                        wp_enqueue_script( 'handlebars', get_template_directory_uri() . '/js/handlebars.min.js', array(), null, true);
                        wp_enqueue_script( 'typeahead-jquery', get_template_directory_uri() . '/js/typeahead.bundle.min.js', array('jquery', 'handlebars'), null, true);
                        $filter_field['add_class'] = 'apus-autocompleate-input';
                    }

                    if ( !empty($item['search_suggestions_menu']) ) {
                        $filter_field['suggestions_menu'] = $item['search_suggestions_menu'];
                    }
                } elseif ( $item['filter_field'] == 'price' ) {
                    $filter_field['style'] = $item['style'];
                    $filter_field['min_price_placeholder'] = $item['min_price_placeholder'];
                    $filter_field['max_price_placeholder'] = $item['max_price_placeholder'];
                    $filter_field['price_range_size'] = $item['price_range_size'];
                    $filter_field['price_range_max'] = $item['price_range_max'];
                } elseif ( in_array($item['filter_field'], ['mileage', 'year']) ) {
                    $filter_field['slider_style'] = $item['slider_style'];
                    $filter_field['min_placeholder'] = $item['min_placeholder'];
                    $filter_field['max_placeholder'] = $item['max_placeholder'];
                }
                if ( in_array($item['filter_field'], ['mileage']) ) {
                    $filter_field['suffix'] = $item['suffix'];
                }
                if ( isset($item['icon']) ) {
                    $filter_field['icon'] = $item['icon'];
                }
                if ( isset($item['placeholder']) ) {
                    $filter_field['placeholder'] = $item['placeholder'];
                }
                
                if ( !empty($item['title']) ) {
                    $filter_field['name'] = $item['title'];
                    $filter_field['show_title'] = true;
                } else {
                    $filter_field['show_title'] = false;
                }

                if ( $item['filter_field'] == 'feature' ) {
                    $sub_class = 'wrapper-feature';
                } else {
                    $sub_class = '';
                }

                if ( $item['filter_layout'] && in_array($item['filter_field'], array('category', 'type', 'feature', 'location')) ) {
                    switch ($item['filter_layout']) {
                        case 'radio':
                            $filter_field['field_call_back'] = array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_radio_list');
                            break;
                        case 'check_list':
                            $filter_field['field_call_back'] = array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_check_list');
                            break;
                        default:
                            if ( $item['filter_field'] == 'location' ) {
                                $filter_field['field_call_back'] = array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_location_select');
                            } else {
                                $filter_field['field_call_back'] = array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_select');
                            }
                            break;
                    }
                }

                $columns = !empty($item['columns']) ? $item['columns'] : 12;
                $columns_tablet = !empty($item['columns_tablet']) ? $item['columns_tablet'] : $columns;
                $columns_mobile = !empty($item['columns_mobile']) ? $item['columns_mobile'] : 12;
                
                ?>
                <div class="col-<?php echo esc_attr($columns_mobile); ?> col-md-<?php echo esc_attr($columns_tablet); ?> col-lg-<?php echo esc_attr($columns); ?> <?php echo esc_attr($sub_class); ?> <?php echo trim( ($item['icon'])?'has-icon':'' ); ?> <?php echo esc_attr( ( count($search_fields) == $i ) ? 'item-last':'' ); ?>">
                    <?php call_user_func( $filter_field['field_call_back'], $instance, $args, $item['filter_field'], $filter_field ); ?>

                    
                </div>
                <?php $i++;
            }
        }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Listings_Directory_Search_Form );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_Listings_Directory_Search_Form );
}