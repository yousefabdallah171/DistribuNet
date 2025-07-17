<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Packages extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_packages';
    }

	public function get_title() {
        return esc_html__( 'Apus Packages', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
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
            'orderby',
            [
                'label' => esc_html__( 'Order by', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'guido'),
                    'date' => esc_html__('Date', 'guido'),
                    'ID' => esc_html__('ID', 'guido'),
                    'author' => esc_html__('Author', 'guido'),
                    'title' => esc_html__('Title', 'guido'),
                    'modified' => esc_html__('Modified', 'guido'),
                    'rand' => esc_html__('Random', 'guido'),
                    'comment_count' => esc_html__('Comment count', 'guido'),
                    'menu_order' => esc_html__('Menu order', 'guido'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Sort order', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'guido'),
                    'ASC' => esc_html__('Ascending', 'guido'),
                    'DESC' => esc_html__('Descending', 'guido'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => esc_html__( 'Number Product', 'guido' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'number',
                'description' => esc_html__( 'Number Product to display', 'guido' ),
                'default' => 3
            ]
        );
        $this->add_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'default' => 3,
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
            'section_title_style',
            [
                'label' => esc_html__( 'Button', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->start_controls_tabs( 'tabs_box_style' );

            $this->start_controls_tab(
                'tab_box_normal',
                [
                    'label' => esc_html__( 'Normal', 'guido' ),
                ]
            ); 

            $this->add_control(
                'btn_color',
                [
                    'label' => esc_html__( 'Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .subwoo-inner .add-cart .button' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .subwoo-inner .add-cart .button.loading::after' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'btn_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .subwoo-inner .add-cart .button' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                'btn_br_color',
                [
                    'label' => esc_html__( 'Border Color', 'guido' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        // Stronger selector to avoid section style from overwriting
                        '{{WRAPPER}} .subwoo-inner .add-cart .button' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_box_hover',
                [
                    'label' => esc_html__( 'Hover', 'guido' ),
                ]
            );


                $this->add_control(
                    'btn_hv_color',
                    [
                        'label' => esc_html__( 'Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .subwoo-inner:hover .add-cart .button' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .subwoo-inner .add-cart .added_to_cart' => 'color: {{VALUE}};',
                            '{{WRAPPER}} .subwoo-inner.is_featured .add-cart .button' => 'color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_control(
                    'btn_hv_bg_color',
                    [
                        'label' => esc_html__( 'Background Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .subwoo-inner:hover .add-cart .button' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .subwoo-inner .add-cart .added_to_cart' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .subwoo-inner.is_featured .add-cart .button' => 'background-color: {{VALUE}};',
                        ],
                    ]
                );
                $this->add_control(
                    'btn_hv_br_color',
                    [
                        'label' => esc_html__( 'Border Color', 'guido' ),
                        'type' => Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            // Stronger selector to avoid section style from overwriting
                            '{{WRAPPER}} .subwoo-inner:hover .add-cart .button' => 'border-color: {{VALUE}};',
                            '{{WRAPPER}} .subwoo-inner .add-cart .added_to_cart' => 'border-color: {{VALUE}};',
                            '{{WRAPPER}} .subwoo-inner.is_featured .add-cart .button' => 'border-color: {{VALUE}};',
                        ],
                    ]
                );


            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab normal and hover
        
        $this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        $loop = guido_get_products(array(
            'product_type' => 'listing_package',
            'post_per_page' => $number,
            'orderby' => $orderby,
            'order' => $order
        ));
        ?>
        <div class="woocommerce widget-subwoo <?php echo esc_attr($el_class); ?>">
            <?php if ($loop->have_posts()): ?>
                <div class="row">
                    <?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                        <div class="col-xs-6 col-sm-6 col-md-<?php echo esc_attr(12/$columns); ?>">
                            <div class="subwoo-inner <?php echo esc_attr($product->is_featured()?'is_featured':''); ?>">
                                <div class="item">
                                    <div class="header-sub">
                                        <h3 class="title"><?php the_title(); ?></h3>
                                        <div class="price"><?php echo (!empty($product->get_price())) ? $product->get_price_html() : esc_html__('Free','guido'); ?></div>
                                    </div>
                                    <div class="bottom-sub">
                                        <?php if ( has_excerpt() ) { ?>
                                            <div class="short-des"><?php the_excerpt(); ?></div>
                                        <?php } ?>
                                        <div class="button-action"><?php do_action( 'woocommerce_after_shop_loop_item' ); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
        <?php
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Packages );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_Packages );
}