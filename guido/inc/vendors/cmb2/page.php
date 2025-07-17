<?php

if ( !function_exists( 'guido_page_metaboxes' ) ) {
	function guido_page_metaboxes(array $metaboxes) {
		global $wp_registered_sidebars;
        $sidebars = array();

        if ( !empty($wp_registered_sidebars) ) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }
        }
        $headers = array_merge( array('global' => esc_html__( 'Global Setting', 'guido' )), guido_get_header_layouts() );
        $footers = array_merge( array('global' => esc_html__( 'Global Setting', 'guido' )), guido_get_footer_layouts() );

		$prefix = 'apus_page_';

        $columns = array(
            '' => esc_html__( 'Global Setting', 'guido' ),
            '1' => esc_html__('1 Column', 'guido'),
            '2' => esc_html__('2 Columns', 'guido'),
            '3' => esc_html__('3 Columns', 'guido'),
            '4' => esc_html__('4 Columns', 'guido'),
            '6' => esc_html__('6 Columns', 'guido')
        );

        // Listings Page
        $fields = array(
            array(
                'name' => esc_html__( 'Listings Layout', 'guido' ),
                'id'   => $prefix.'layout_type',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'guido' ),
                    'default' => esc_html__('Default', 'guido'),
                    'half-map' => esc_html__('Half Map', 'guido'),
                    'top-map' => esc_html__('Top Map', 'guido'),
                )
            ),
            array(
                'id' => $prefix.'display_mode',
                'type' => 'select',
                'name' => esc_html__('Default Display Mode', 'guido'),
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'guido' ),
                    'grid' => esc_html__('Grid', 'guido'),
                    'list' => esc_html__('List', 'guido'),
                )
            ),
            array(
                'id' => $prefix.'listings_columns',
                'type' => 'select',
                'name' => esc_html__('Grid Listing Columns', 'guido'),
                'options' => $columns,
            ),
            array(
                'id' => $prefix.'listings_list_columns',
                'type' => 'select',
                'name' => esc_html__('List Listing Columns', 'guido'),
                'options' => $columns,
            ),
            array(
                'id' => $prefix.'listings_pagination',
                'type' => 'select',
                'name' => esc_html__('Pagination Type', 'guido'),
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'guido' ),
                    'default' => esc_html__('Default', 'guido'),
                    'loadmore' => esc_html__('Load More Button', 'guido'),
                    'infinite' => esc_html__('Infinite Scrolling', 'guido'),
                ),
            ),

            array(
                'id' => $prefix.'listings_show_filter_top',
                'type' => 'select',
                'name' => esc_html__('Show Filter Top', 'guido'),
                'options' => array(
                    '' => esc_html__( 'Global Setting', 'guido' ),
                    'no' => esc_html__('No', 'guido'),
                    'yes' => esc_html__('Yes', 'guido')
                ),
            ),
            array(
                'id' => $prefix.'listings_filter_top_sidebar',
                'type' => 'select',
                'name' => esc_html__('Listings Filter Top Sidebar', 'guido'),
                'description' => esc_html__('Choose a filter top sidebar for your website.', 'guido'),
                'options' => array(
                    '' => esc_html__('Global Setting', 'guido'),
                    'listings-filter-top' => esc_html__('Listings Filter Top', 'guido'),
                    'listings-filter-top2' => esc_html__('Listings Filter Top 2', 'guido'),
                    'listings-filter-top-map' => esc_html__('Listings Filter Top Map', 'guido'),
                ),
                'default' => ''
            ),

            array(
                'id' => $prefix.'listings_half_map_filter_type',
                'type' => 'select',
                'name' => esc_html__('Half Map Filter Type', 'guido'),
                'options' => array(
                    '' => esc_html__('Global Setting', 'guido'),
                    'offcanvas' => esc_html__('Offcanvas Filter', 'guido'),
                    'filter-top' => esc_html__('Filter Top', 'guido'),
                ),
                'default' => ''
            ),
        );
        
        $metaboxes[$prefix . 'listings_setting'] = array(
            'id'                        => $prefix . 'listings_setting',
            'title'                     => esc_html__( 'Listings Settings', 'guido' ),
            'object_types'              => array( 'page' ),
            'context'                   => 'normal',
            'priority'                  => 'high',
            'show_names'                => true,
            'fields'                    => $fields
        );

        // General
	    $fields = array(
			array(
				'name' => esc_html__( 'Select Layout', 'guido' ),
				'id'   => $prefix.'layout',
				'type' => 'select',
				'options' => array(
					'main' => esc_html__('Main Content Only', 'guido'),
					'left-main' => esc_html__('Left Sidebar - Main Content', 'guido'),
					'main-right' => esc_html__('Main Content - Right Sidebar', 'guido')
				)
			),
			array(
                'id' => $prefix.'fullwidth',
                'type' => 'select',
                'name' => esc_html__('Is Full Width?', 'guido'),
                'default' => 'no',
                'options' => array(
                    'no' => esc_html__('No', 'guido'),
                    'yes' => esc_html__('Yes', 'guido')
                )
            ),
            array(
                'id' => $prefix.'left_sidebar',
                'type' => 'select',
                'name' => esc_html__('Left Sidebar', 'guido'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'right_sidebar',
                'type' => 'select',
                'name' => esc_html__('Right Sidebar', 'guido'),
                'options' => $sidebars
            ),
            array(
                'id' => $prefix.'show_breadcrumb',
                'type' => 'select',
                'name' => esc_html__('Show Breadcrumb?', 'guido'),
                'options' => array(
                    'no' => esc_html__('No', 'guido'),
                    'yes' => esc_html__('Yes', 'guido')
                ),
                'default' => 'yes',
            ),
            array(
                'id' => $prefix.'breadcrumb_color',
                'type' => 'colorpicker',
                'name' => esc_html__('Breadcrumb Background Color', 'guido')
            ),
            array(
                'id' => $prefix.'breadcrumb_image',
                'type' => 'file',
                'name' => esc_html__('Breadcrumb Background Image', 'guido')
            ),

            array(
                'id' => $prefix.'header_type',
                'type' => 'select',
                'name' => esc_html__('Header Layout Type', 'guido'),
                'description' => esc_html__('Choose a header for your website.', 'guido'),
                'options' => $headers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'header_transparent',
                'type' => 'select',
                'name' => esc_html__('Header Transparent', 'guido'),
                'description' => esc_html__('Choose a header for your website.', 'guido'),
                'options' => array(
                    'no' => esc_html__('No', 'guido'),
                    'yes' => esc_html__('Yes', 'guido')
                ),
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'header_fixed',
                'type' => 'select',
                'name' => esc_html__('Header Fixed Top', 'guido'),
                'description' => esc_html__('Choose a header position', 'guido'),
                'options' => array(
                    'no' => esc_html__('No', 'guido'),
                    'yes' => esc_html__('Yes', 'guido')
                ),
                'default' => 'no'
            ),
            array(
                'id' => $prefix.'footer_type',
                'type' => 'select',
                'name' => esc_html__('Footer Layout Type', 'guido'),
                'description' => esc_html__('Choose a footer for your website.', 'guido'),
                'options' => $footers,
                'default' => 'global'
            ),
            array(
                'id' => $prefix.'extra_class',
                'type' => 'text',
                'name' => esc_html__('Extra Class', 'guido'),
                'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'guido')
            )
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'Display Settings', 'guido' ),
			'object_types'              => array( 'page' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'guido_page_metaboxes' );

if ( !function_exists( 'guido_cmb2_style' ) ) {
	function guido_cmb2_style() {
        wp_enqueue_style( 'guido-cmb2-style', get_template_directory_uri() . '/inc/vendors/cmb2/assets/style.css', array(), '1.0' );
		wp_enqueue_script( 'guido-admin', get_template_directory_uri() . '/js/admin.js', array( 'jquery' ), '20150330', true );
	}
}
add_action( 'admin_enqueue_scripts', 'guido_cmb2_style' );


