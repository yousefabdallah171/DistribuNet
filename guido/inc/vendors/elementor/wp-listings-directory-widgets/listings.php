<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_Listings_Directory_Listings extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_listings';
    }

	public function get_title() {
        return esc_html__( 'Apus Listings', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

    public function get_tax_keys() {
        return array('type', 'category', 'feature', 'location');
    }

	protected function register_controls() {
        $meta_obj = WP_Listings_Directory_Listing_Meta::get_instance(0);

        $fields = $meta_obj->get_metas();

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Listings', 'guido' ),
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

        $this->add_control(
            'content',
            [
                'label' => esc_html__( 'Description', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
            ]
        );

        $tax_keys = $this->get_tax_keys();

        foreach( $tax_keys as $tax_key ) {
            if ( $meta_obj->check_post_meta_exist($tax_key) ) {
                $this->add_control(
                    $tax_key.'_slugs',
                    [
                        'label' => sprintf(esc_html__( '%s Slug', 'guido' ), $fields[WP_LISTINGS_DIRECTORY_LISTING_PREFIX.$tax_key]['name']),
                        'type' => Elementor\Controls_Manager::TEXTAREA,
                        'rows' => 1,
                        'default' => '',
                        'placeholder' => esc_html__( 'Enter slugs spearate by comma(,)', 'guido' ),
                    ]
                );
            }
        }

        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'guido' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'number',
                'description' => esc_html__( 'Limit listings to display', 'guido' ),
                'default' => 4
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
            'get_listings_by',
            [
                'label' => esc_html__( 'Get Listings By', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'featured' => esc_html__('Featured Listings', 'guido'),
                    'recent' => esc_html__('Recent Listings', 'guido'),
                ),
                'default' => 'recent'
            ]
        );

        $this->add_control(
            'listing_item_style',
            [
                'label' => esc_html__( 'Listing Item Style', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid Default', 'guido'),
                    'list' => esc_html__('List Default', 'guido'),
                ),
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'guido'),
                    'carousel' => esc_html__('Carousel', 'guido'),
                ),
                'default' => 'grid'
            ]
        );

        $columns = range( 1, 12 );
        $columns = array_combine( $columns, $columns );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'guido' ),
                'options' => $columns,
                'condition' => [
                    'columns!' => '1',
                    'layout_type' => 'carousel',
                ],
                'frontend_available' => true,
                'default' => 1,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your rows number here', 'guido' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label'         => esc_html__( 'Show Navigation', 'guido' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'guido' ),
                'label_off'     => esc_html__( 'Hide', 'guido' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'         => esc_html__( 'Show Pagination', 'guido' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'guido' ),
                'label_off'     => esc_html__( 'Hide', 'guido' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'style_action',
            [
                'label' => esc_html__( 'Style Pagination, Navigation', 'guido' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'guido'),
                    'st_white' => esc_html__('White', 'guido'),
                ),
                'default' => '',
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'slider_autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'guido' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'guido' ),
                'label_off'     => esc_html__( 'No', 'guido' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'guido' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'guido' ),
                'label_off'     => esc_html__( 'No', 'guido' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'view_all',
            [
                'label' => esc_html__( 'View All', 'guido' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'guido' ),
                'label_off' => esc_html__( 'Show', 'guido' ),
            ]
        );

        $this->add_control(
            'text_view',
            [
                'label' => esc_html__( 'Text View All', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'See All Listings',
                'condition' => [
                    'view_all' => ['yes'],
                ]
            ]
        );

        $this->add_control(
            'link_view',
            [
                'label' => esc_html__( 'View Link', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Link here', 'guido' ),
                'condition' => [
                    'view_all' => ['yes'],
                ]
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

        $args = array(
            'limit' => $limit,
            'get_listings_by' => $get_listings_by,
            'orderby' => $orderby,
            'order' => $order
        );
        
        $tax_keys = $this->get_tax_keys();
        foreach( $tax_keys as $tax_key ) {
            $args[$tax_key] = !empty($settings[$tax_key.'_slugs']) ? array_map('trim', explode(',', $settings[$tax_key.'_slugs'])) : array();
        }
        $loop = guido_get_listings($args);
        if ( $loop->have_posts() ) {
            $columns = !empty($columns) ? $columns : 3;
            $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 2;
            $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;
            
            $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
            $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
            $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;
            ?>
            <div class="widget-listings <?php echo esc_attr($layout_type.' item-'.$listing_item_style); ?> <?php echo esc_attr($el_class); ?>">
                <?php if ( $title || !empty($content) || ( $view_all == 'yes' && !(empty($link_view)) && !(empty($text_view)) ) ) { ?>
                    <div class="top-info-widget flex-middle">
                        <div class="info-left">
                            <?php if ( $title ) { ?>
                                <h2 class="title"><?php echo esc_html($title); ?></h2>
                            <?php } ?>
                            <?php if ( !empty($content) ) { ?>
                                <div class="description"><?php echo trim($content); ?></div>
                            <?php } ?>
                        </div>
                        <?php if ( $view_all == 'yes' && !(empty($link_view)) && !(empty($text_view)) ) { ?>
                            <div class="ali-right">
                                <a href="<?php echo esc_url( $link_view ); ?>" class="btn-view">
                                    <?php echo esc_html($text_view); ?><i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="widget-content">
                    <?php if ( $layout_type == 'carousel' ): ?>
                        <div class="slick-carousel <?php echo esc_attr($style_action); ?>"
                            data-items="<?php echo esc_attr($columns); ?>"
                            data-large="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-medium="2"
                            data-small="<?php echo esc_attr($columns_mobile); ?>"

                            data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                            data-slidestoscroll_smallmedium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                            data-slidestoscroll_extrasmall="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                            data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>" data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>" data-rows="<?php echo esc_attr( $rows ); ?>" data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>" data-autoplay="<?php echo esc_attr( $slider_autoplay ? 'true' : 'false' ); ?>">
                            <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                                <div class="item">
                                    <?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-'. $listing_item_style ); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <?php
                            $mdcol = 12/$columns;
                            $smcol = 12/$columns_tablet;
                            $xscol = 12/$columns_mobile;
                        ?>
                        <div class="row">
                            <?php while ( $loop->have_posts() ) : $loop->the_post();
                    
                                if($listing_item_style == 'list' || $listing_item_style == 'list-v1'){
                                    $smcol = 12;
                                }
                            ?>
                                <div class="col-xl-<?php echo esc_attr($mdcol); ?> col-md-<?php echo esc_attr($smcol); ?> col-<?php echo esc_attr( $xscol ); ?> list-item">
                                    <?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-'. $listing_item_style ); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_Listings_Directory_Listings );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_Listings_Directory_Listings );
}