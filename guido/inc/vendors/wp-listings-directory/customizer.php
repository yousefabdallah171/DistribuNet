<?php

function guido_wp_cardealer_customize_register( $wp_customize ) {
    global $wp_registered_sidebars;
    $sidebars = array();

    if ( is_admin() && !empty($wp_registered_sidebars) ) {
        foreach ($wp_registered_sidebars as $sidebar) {
            $sidebars[$sidebar['id']] = $sidebar['name'];
        }
    }

    $columns = array( '1' => esc_html__('1 Column', 'guido'),
        '2' => esc_html__('2 Columns', 'guido'),
        '3' => esc_html__('3 Columns', 'guido'),
        '4' => esc_html__('4 Columns', 'guido'),
        '5' => esc_html__('5 Columns', 'guido'),
        '6' => esc_html__('6 Columns', 'guido'),
        '7' => esc_html__('7 Columns', 'guido'),
        '8' => esc_html__('8 Columns', 'guido'),
    );

    // Listings Panel
    $wp_customize->add_panel( 'guido_settings_listing', array(
        'title' => esc_html__( 'Listings Settings', 'guido' ),
        'priority' => 4,
    ) );

    // General Section
    $wp_customize->add_section('guido_settings_listing_general', array(
        'title'    => esc_html__('General', 'guido'),
        'priority' => 1,
        'panel' => 'guido_settings_listing',
    ));

    // Breadcrumbs Setting ?
    $wp_customize->add_setting('guido_theme_options[listing_breadcrumbs_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Guido_WP_Customize_Heading_Control($wp_customize, 'listing_breadcrumbs_setting', array(
        'label'    => esc_html__('Breadcrumbs Settings', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'settings' => 'guido_theme_options[listing_breadcrumbs_setting]',
    )));

    // Breadcrumbs
    $wp_customize->add_setting('guido_theme_options[show_listing_breadcrumbs]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_show_listing_breadcrumbs', array(
        'settings' => 'guido_theme_options[show_listing_breadcrumbs]',
        'label'    => esc_html__('Breadcrumbs', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'type'     => 'checkbox',
    ));

    // Breadcrumbs Background Color
    $wp_customize->add_setting('guido_theme_options[listing_breadcrumb_color]', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'listing_breadcrumb_color', array(
        'label'    => esc_html__('Breadcrumbs Background Color', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'settings' => 'guido_theme_options[listing_breadcrumb_color]',
    )));

    // Breadcrumbs Background
    $wp_customize->add_setting('guido_theme_options[listing_breadcrumb_image]', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',

    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'listing_breadcrumb_image', array(
        'label'    => esc_html__('Breadcrumbs Background', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'settings' => 'guido_theme_options[listing_breadcrumb_image]',
    )));

    // Other Setting ?
    $wp_customize->add_setting('guido_theme_options[listing_other_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Guido_WP_Customize_Heading_Control($wp_customize, 'listing_other_setting', array(
        'label'    => esc_html__('Other Settings', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'settings' => 'guido_theme_options[listing_other_setting]',
    )));

    // Show Full Phone Number
    $wp_customize->add_setting('guido_theme_options[listing_show_full_phone]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => '1',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_listing_show_full_phone', array(
        'settings' => 'guido_theme_options[listing_show_full_phone]',
        'label'    => esc_html__('Show Full Phone Number', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'type'     => 'checkbox',
    ));

    // Enable Favorite
    $wp_customize->add_setting('guido_theme_options[listing_enable_favorite]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => '1',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_listing_enable_favorite', array(
        'settings' => 'guido_theme_options[listing_enable_favorite]',
        'label'    => esc_html__('Enable Favorite', 'guido'),
        'section'  => 'guido_settings_listing_general',
        'type'     => 'checkbox',
    ));



    // Listing Archives
    $wp_customize->add_section('guido_settings_listing_archive', array(
        'title'    => esc_html__('Listing Archives', 'guido'),
        'priority' => 2,
        'panel' => 'guido_settings_listing',
    ));

    // General Setting ?
    $wp_customize->add_setting('guido_theme_options[listings_general_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Guido_WP_Customize_Heading_Control($wp_customize, 'listings_general_setting', array(
        'label'    => esc_html__('General Settings', 'guido'),
        'section'  => 'guido_settings_listing_archive',
        'settings' => 'guido_theme_options[listings_general_setting]',
    )));

    // Is Full Width
    $wp_customize->add_setting('guido_theme_options[listings_fullwidth]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_listings_fullwidth', array(
        'settings' => 'guido_theme_options[listings_fullwidth]',
        'label'    => esc_html__('Is Full Width', 'guido'),
        'section'  => 'guido_settings_listing_archive',
        'type'     => 'checkbox',
    ));

    // layout
    $wp_customize->add_setting( 'guido_theme_options[listings_layout_type]', array(
        'default'        => 'default',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_layout', array(
        'label'   => esc_html__('Listings Layout Style', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => array(
            'default' => esc_html__('Default', 'guido'),
            'half-map' => esc_html__('Half Map', 'guido'),
            'top-map' => esc_html__('Top Map', 'guido'),
        ),
        'settings' => 'guido_theme_options[listings_layout_type]',
        'description' => esc_html__('Select the variation you want to apply on your blog.', 'guido'),
    ) );

    // layout
    $wp_customize->add_setting( 'guido_theme_options[listings_layout_sidebar]', array(
        'default'        => 'left-main',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( new Guido_WP_Customize_Radio_Image_Control( 
        $wp_customize, 
        'apus_settings_listings_layout_sidebar', 
        array(
            'label'   => esc_html__('Layout Type', 'guido'),
            'section' => 'guido_settings_listing_archive',
            'type'    => 'select',
            'choices' => array(
                'main' => array(
                    'title' => esc_html__('Main Only', 'guido'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                ),
                'left-main' => array(
                    'title' => esc_html__('Left - Main Sidebar', 'guido'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                ),
                'main-right' => array(
                    'title' => esc_html__('Main - Right Sidebar', 'guido'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                ),
            ),
            'settings' => 'guido_theme_options[listings_layout_sidebar]',
            'description' => wp_kses(__('Select a sidebar layout for layout type <strong>"Default", "Top Map"</strong>.', 'guido'), array('strong' => array())),
        ) 
    ));


    // Show Filter Top
    $wp_customize->add_setting('guido_theme_options[listings_show_filter_top]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_listings_show_filter_top', array(
        'settings' => 'guido_theme_options[listings_show_filter_top]',
        'label'    => esc_html__('Show Filter Top', 'guido'),
        'section'  => 'guido_settings_listing_archive',
        'type'     => 'checkbox',
    ));

    // Listings Filter Top Sidebar
    $wp_customize->add_setting( 'guido_theme_options[listings_filter_top_sidebar]', array(
        'default'        => 'listings-filter-top',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_filter_top_sidebar', array(
        'label'   => esc_html__('Listings Filter Top Sidebar', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => array(
            'listings-filter-top' => esc_html__('Listings Filter Top', 'guido'),
            'listings-filter-top2' => esc_html__('Listings Filter Top 2', 'guido'),
            'listings-filter-top-map' => esc_html__('Listings Filter Top Map', 'guido'),
        ),
        'settings' => 'guido_theme_options[listings_filter_top_sidebar]',
    ) );


    // Half Filter Type
    $wp_customize->add_setting( 'guido_theme_options[listings_half_map_filter_type]', array(
        'default'        => 'offcanvas',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_listings_half_map_filter_type', array(
        'label'   => esc_html__('Half Map Filter Type', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => array(
            'offcanvas' => esc_html__('Offcanvas Filter', 'guido'),
            'filter-top' => esc_html__('Filter Top', 'guido'),
        ),
        'settings' => 'guido_theme_options[listings_half_map_filter_type]',
    ) );


    // Display Mode
    $wp_customize->add_setting( 'guido_theme_options[listings_display_mode]', array(
        'default'        => 'grid',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_display_mode', array(
        'label'   => esc_html__('Display Mode', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => array(
            'grid' => esc_html__('Grid', 'guido'),
            'list' => esc_html__('List', 'guido'),
        ),
        'settings' => 'guido_theme_options[listings_display_mode]',
    ) );

    // Grid Columns
    $wp_customize->add_setting( 'guido_theme_options[listings_columns]', array(
        'default'        => '3',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_listings_columns', array(
        'label'   => esc_html__('Listings Grid Columns', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'guido_theme_options[listings_columns]',
    ) );

    // List Columns
    $wp_customize->add_setting( 'guido_theme_options[listings_list_columns]', array(
        'default'        => '2',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_listings_list_columns', array(
        'label'   => esc_html__('Listings List Columns', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'guido_theme_options[listings_list_columns]',
    ) );

    // Pagination
    $wp_customize->add_setting( 'guido_theme_options[listings_pagination]', array(
        'default'        => 'default',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_archive_listings_pagination', array(
        'label'   => esc_html__('Listings Pagination', 'guido'),
        'section' => 'guido_settings_listing_archive',
        'type'    => 'select',
        'choices' => array(
            'default' => esc_html__('Default', 'guido'),
            'loadmore' => esc_html__('Load More Button', 'guido'),
            'infinite' => esc_html__('Infinite Scrolling', 'guido'),
        ),
        'settings' => 'guido_theme_options[listings_pagination]',
    ) );



    // Single Listing
    $wp_customize->add_section('guido_settings_listing_single', array(
        'title'    => esc_html__('Listing Single', 'guido'),
        'priority' => 3,
        'panel' => 'guido_settings_listing',
    ));

    // General Setting ?
    $wp_customize->add_setting('guido_theme_options[listing_single_general_setting]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Guido_WP_Customize_Heading_Control($wp_customize, 'listing_single_general_setting', array(
        'label'    => esc_html__('General Settings', 'guido'),
        'section'  => 'guido_settings_listing_single',
        'settings' => 'guido_theme_options[listing_single_general_setting]',
    )));

    // Is Full Width
    $wp_customize->add_setting('guido_theme_options[listing_fullwidth]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_listing_fullwidth', array(
        'settings' => 'guido_theme_options[listing_fullwidth]',
        'label'    => esc_html__('Is Full Width', 'guido'),
        'section'  => 'guido_settings_listing_single',
        'type'     => 'checkbox',
    ));

    // Listing Layout
    $wp_customize->add_setting( 'guido_theme_options[listing_layout_type]', array(
        'default'        => 'v1',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_single_listing_layout_type', array(
        'label'   => esc_html__('Listing Layout', 'guido'),
        'section' => 'guido_settings_listing_single',
        'type'    => 'select',
        'choices' => array(
            'v1' => esc_html__('Layout 1', 'guido'),
            'v2' => esc_html__('Layout 2', 'guido'),
            'v3' => esc_html__('Layout 3', 'guido'),
        ),
        'settings' => 'guido_theme_options[listing_layout_type]',
    ) );

    // Show Social Share
    $wp_customize->add_setting('guido_theme_options[show_listing_social_share]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'       => '1',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_show_listing_social_share', array(
        'settings' => 'guido_theme_options[show_listing_social_share]',
        'label'    => esc_html__('Show Social Share', 'guido'),
        'section'  => 'guido_settings_listing_single',
        'type'     => 'checkbox',
    ));

    $contents = apply_filters('guido_listing_single_sort_content', array(
        'description' => esc_html__('Description', 'guido'),
        'features' => esc_html__('Features', 'guido'),
        'photos' => esc_html__('Photos Gallery', 'guido'),
        'menu_prices' => esc_html__('Menus Price', 'guido'),
        'faq' => esc_html__('Frequently Asked Questions', 'guido'),
        'video' => esc_html__('Video', 'guido'),
        'related' => esc_html__('Related', 'guido'),
    ));
    foreach ($contents as $key => $value) {
        // Show Social Share
        $wp_customize->add_setting('guido_theme_options[show_listing_'.$key.']', array(
            'capability' => 'edit_theme_options',
            'type'       => 'option',
            'default'       => '1',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('guido_theme_options_show_listing_'.$key, array(
            'settings' => 'guido_theme_options[show_listing_'.$key.']',
            'label'    => sprintf(esc_html__('Show %s', 'guido'), $value),
            'section'  => 'guido_settings_listing_single',
            'type'     => 'checkbox',
        ));
    }

    // Show Description View More
    $wp_customize->add_setting('guido_theme_options[show_listing_desc_view_more]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'       => '1',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_show_listing_desc_view_more', array(
        'settings' => 'guido_theme_options[show_listing_desc_view_more]',
        'label'    => esc_html__('Show Description View More', 'guido'),
        'section'  => 'guido_settings_listing_single',
        'type'     => 'checkbox',
    ));

    // Number related listings
    $wp_customize->add_setting( 'guido_theme_options[listing_related_number]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_single_listing_related_number', array(
        'label'   => esc_html__('Number related listings', 'guido'),
        'section' => 'guido_settings_listing_single',
        'type'    => 'number',
        'settings' => 'guido_theme_options[listing_related_number]',
    ) );

    // Related Listings Columns
    $wp_customize->add_setting( 'guido_theme_options[listing_related_columns]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_single_listing_related_columns', array(
        'label'   => esc_html__('Related Listings Columns', 'guido'),
        'section' => 'guido_settings_listing_single',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'guido_theme_options[listing_related_columns]',
    ) );


    // Print Listing
    $wp_customize->add_section('guido_settings_listing_print', array(
        'title'    => esc_html__('Listing Print', 'guido'),
        'priority' => 4,
        'panel' => 'guido_settings_listing',
    ));

    // Show Print Button
    $wp_customize->add_setting('guido_theme_options[listing_enable_printer]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'       => '1',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_listing_enable_printer', array(
        'settings' => 'guido_theme_options[listing_enable_printer]',
        'label'    => esc_html__('Show Print Button', 'guido'),
        'section'  => 'guido_settings_listing_print',
        'type'     => 'checkbox',
    ));

    // Print Logo
    $wp_customize->add_setting('guido_theme_options[print-logo]', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',

    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'print-logo', array(
        'label'    => esc_html__('Print Logo', 'guido'),
        'section'  => 'guido_settings_listing_print',
        'settings' => 'guido_theme_options[print-logo]',
    )));

    $contents = apply_filters('guido_listing_single_print_content', array(
        'header' => esc_html__('Print Header', 'guido'),
        'qrcode' => esc_html__('Qrcode', 'guido'),
        'author' => esc_html__('Author', 'guido'),
        'description' => esc_html__('Description', 'guido'),
        'detail' => esc_html__('Detail', 'guido'),
        'features' => esc_html__('Features', 'guido'),
        'gallery' => esc_html__('Gallery', 'guido'),
    ));

    foreach ($contents as $key => $value) {
        // Show Social Share
        $wp_customize->add_setting('guido_theme_options[show_print_'.$key.']', array(
            'capability' => 'edit_theme_options',
            'type'       => 'option',
            'default'       => '1',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('guido_theme_options_show_print_'.$key, array(
            'settings' => 'guido_theme_options[show_print_'.$key.']',
            'label'    => sprintf(esc_html__('Show %s', 'guido'), $value),
            'section'  => 'guido_settings_listing_print',
            'type'     => 'checkbox',
        ));
    }




    // User Profile Settings
    $wp_customize->add_section('guido_settings_listing_user_profile', array(
        'title'    => esc_html__('User Profile Settings', 'guido'),
        'priority' => 5,
        'panel' => 'guido_settings_listing',
    ));

    // layout
    $wp_customize->add_setting( 'guido_theme_options[user_single_layout]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_user_profile_layout', array(
        'label'   => esc_html__('Layout Type', 'guido'),
        'section' => 'guido_settings_listing_user_profile',
        'type'    => 'select',
        'choices' => array(
            'main' => esc_html__('Main Only', 'guido'),
            'left-main' => esc_html__('Left - Main Sidebar', 'guido'),
            'main-right' => esc_html__('Main - Right Sidebar', 'guido'),
        ),
        'settings' => 'guido_theme_options[user_single_layout]',
        'description' => esc_html__('Select the variation you want to apply on your blog.', 'guido'),
    ) );

    // Is Full Width
    $wp_customize->add_setting('guido_theme_options[user_profile_fullwidth]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_user_profile_fullwidth', array(
        'settings' => 'guido_theme_options[user_profile_fullwidth]',
        'label'    => esc_html__('Is Full Width', 'guido'),
        'section'  => 'guido_settings_listing_user_profile',
        'type'     => 'checkbox',
    ));

    

    // Left Sidebar
    $wp_customize->add_setting( 'guido_theme_options[user_profile_left_sidebar]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_user_profile_left_sidebar', array(
        'label'   => esc_html__('Archive Left Sidebar', 'guido'),
        'section' => 'guido_settings_listing_user_profile',
        'type'    => 'select',
        'choices' => $sidebars,
        'settings' => 'guido_theme_options[user_profile_left_sidebar]',
        'description' => esc_html__('Choose a sidebar for left sidebar', 'guido'),
    ) );

    // Right Sidebar
    $wp_customize->add_setting( 'guido_theme_options[user_profile_right_sidebar]', array(
        'default'        => '',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_user_profile_right_sidebar', array(
        'label'   => esc_html__('Archive Right Sidebar', 'guido'),
        'section' => 'guido_settings_listing_user_profile',
        'type'    => 'select',
        'choices' => $sidebars,
        'settings' => 'guido_theme_options[user_profile_right_sidebar]',
        'description' => esc_html__('Choose a sidebar for right sidebar', 'guido'),
    ) );

    // Show User Listings
    $wp_customize->add_setting('guido_theme_options[show_user_listings]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_show_user_listings', array(
        'settings' => 'guido_theme_options[show_user_listings]',
        'label'    => esc_html__('Show User Listings', 'guido'),
        'section'  => 'guido_settings_listing_user_profile',
        'type'     => 'checkbox',
    ));

    // Show User Reviews
    $wp_customize->add_setting('guido_theme_options[show_user_reviews]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
        'default'    => 1,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('guido_theme_options_show_user_reviews', array(
        'settings' => 'guido_theme_options[show_user_reviews]',
        'label'    => esc_html__('Show User Reviews', 'guido'),
        'section'  => 'guido_settings_listing_user_profile',
        'type'     => 'checkbox',
    ));

    // Number user listings
    $wp_customize->add_setting( 'guido_theme_options[user_listings_number]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_user_profile_user_listings_number', array(
        'label'   => esc_html__('Number user listings', 'guido'),
        'section' => 'guido_settings_listing_user_profile',
        'type'    => 'number',
        'settings' => 'guido_theme_options[user_listings_number]',
    ) );

    // Number user listings columnscolumns
    $wp_customize->add_setting( 'guido_theme_options[user_listings_columns]', array(
        'default'        => '4',
        'type'           => 'option',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'guido_settings_listing_user_profile_user_listings_columns', array(
        'label'   => esc_html__('Number user listings columns', 'guido'),
        'section' => 'guido_settings_listing_user_profile',
        'type'    => 'select',
        'choices' => $columns,
        'settings' => 'guido_theme_options[user_listings_columns]',
    ) );
}
add_action( 'customize_register', 'guido_wp_cardealer_customize_register', 15 );