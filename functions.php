<?php
/**
 *
 * @Packge      Kiddino
 * @Author      Vecuro
 * @version     1.0
 *
 */

/**
 * Enqueue style of child theme
 */
function kiddino_child_enqueue_styles() {

    wp_enqueue_style( 'kiddino-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'kiddino-child-style', get_stylesheet_directory_uri() . '/style.css',array( 'kiddino-style' ),wp_get_theme()->get('Version'));

    wp_enqueue_script( 'custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'kiddino_child_enqueue_styles', 100000 );
// منع الوصول المباشر
if (!defined('ABSPATH')) exit;

/**
 * إعداد القالب
 */
function distributor_connect_setup() {
    load_theme_textdomain('distributor-connect', get_template_directory() . '/languages');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'distributor_connect_setup');

/**
 * تحميل ملفات CSS وJS
 */
function distributor_connect_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('distributor-main', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), $theme_version);
    wp_enqueue_style('distributor-header', get_stylesheet_directory_uri() . '/assets/css/_header.css', array('distributor-main'), $theme_version);
    wp_enqueue_style('distributor-archive', get_stylesheet_directory_uri() . '/assets/css/archive.css', array('distributor-main'), $theme_version);
    wp_enqueue_style('distributor-single', get_stylesheet_directory_uri() . '/assets/css/single.css', array('distributor-main'), $theme_version);
    wp_enqueue_style('distributor-forms', get_stylesheet_directory_uri() . '/assets/css/forms.css', array('distributor-main'), $theme_version);
    wp_enqueue_style('distributor-responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css', array('distributor-main'), $theme_version);
    wp_enqueue_style('distributor-style', get_stylesheet_uri(), array('distributor-main'), $theme_version);
    wp_enqueue_script('distributor-main-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), $theme_version, true);
    wp_enqueue_script('distributor-forms-js', get_stylesheet_directory_uri() . '/assets/js/forms.js', array('jquery'), $theme_version, true);
    wp_enqueue_script('distributor-search-filter', get_stylesheet_directory_uri() . '/assets/js/search-filter.js', array('jquery'), $theme_version, true);
    // Localize for AJAX
    wp_localize_script('distributor-search-filter', 'distributor_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('distributor_nonce'),
        'strings' => array(
            'submitting' => __('جاري الإرسال...', 'distributor-connect'),
            'error' => __('حدث خطأ. حاول مرة أخرى.', 'distributor-connect'),
            'required' => __('هذا الحقل مطلوب.', 'distributor-connect'),
            'invalid_email' => __('يرجى إدخال بريد إلكتروني صحيح.', 'distributor-connect'),
            'invalid_phone' => __('يرجى إدخال رقم هاتف صحيح.', 'distributor-connect')
        )
    ));
}
add_action('wp_enqueue_scripts', 'distributor_connect_enqueue_assets');

/**
 * تضمين الملفات الأساسية
 */
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-taxonomies.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/admin-functions.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/shortcodes.php';

/**
 * تسجيل القوائم
 */
function distributor_connect_menus() {
    register_nav_menus(array(
        'primary' => __('القائمة الرئيسية', 'distributor-connect'),
        'footer' => __('قائمة الفوتر', 'distributor-connect'),
        'mobile' => __('قائمة الجوال', 'distributor-connect')
    ));
}
add_action('init', 'distributor_connect_menus');

/**
 * تسجيل مناطق الودجات
 */
function distributor_connect_widgets() {
    register_sidebar(array(
        'name' => __('الشريط الجانبي الرئيسي', 'distributor-connect'),
        'id' => 'main-sidebar',
        'description' => __('منطقة ودجات الشريط الجانبي', 'distributor-connect'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => __('ودجات الفوتر', 'distributor-connect'),
        'id' => 'footer-widgets',
        'description' => __('منطقة ودجات الفوتر', 'distributor-connect'),
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>'
    ));
    register_sidebar(array(
        'name' => __('الشريط الجانبي للأرشيف', 'distributor-connect'),
        'id' => 'archive-sidebar',
        'description' => __('منطقة ودجات أرشيف الموزعين', 'distributor-connect'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}
add_action('widgets_init', 'distributor_connect_widgets');

/**
 * Handle Registration Form Submission
 */
function handle_distributor_registration() {
    // Check if form was submitted
    if (!isset($_POST['distributor_nonce']) || !isset($_POST['action'])) {
        wp_die(__('Invalid form submission', 'distributor-connect'));
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['distributor_nonce'], 'distributor_registration')) {
        wp_die(__('Security check failed', 'distributor-connect'));
    }
    
    // Sanitize input data
    $distributor_type = sanitize_text_field($_POST['distributor_type']);
    $governorate = sanitize_text_field($_POST['governorate']);
    $company_name = sanitize_text_field($_POST['company_name']);
    $contact_name = sanitize_text_field($_POST['contact_name']);
    $phone = sanitize_text_field($_POST['phone']);
    $whatsapp = sanitize_text_field($_POST['whatsapp']);
    $email = sanitize_email($_POST['email']);
    $location = sanitize_textarea_field($_POST['location']);
    $description = sanitize_textarea_field($_POST['description']);
    
    // Validate required fields
    if (empty($distributor_type) || empty($governorate) || empty($company_name) || empty($contact_name) || empty($phone) || empty($email) || empty($location)) {
        wp_redirect(add_query_arg('registration', 'missing_fields', wp_get_referer()));
        exit;
    }
    
    // Validate email
    if (!is_email($email)) {
        wp_redirect(add_query_arg('registration', 'invalid_email', wp_get_referer()));
        exit;
    }
    
    // Create new post
    $post_data = array(
        'post_title' => $company_name,
        'post_content' => $description,
        'post_type' => $distributor_type,
        'post_status' => 'pending',
        'post_author' => 0, // Set to 0 for guest submissions
        'meta_input' => array(
            'contact_name' => $contact_name,
            'phone' => $phone,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'location' => $location,
            'submission_date' => current_time('mysql'),
            'submission_ip' => $_SERVER['REMOTE_ADDR']
        )
    );
    
    $post_id = wp_insert_post($post_data);
    
    if ($post_id && !is_wp_error($post_id)) {
        // Set taxonomy term
        wp_set_object_terms($post_id, $governorate, 'governorate');
        
        // Send notification email to admin
        $admin_email = get_option('admin_email');
        $subject = sprintf(__('New Distributor Registration: %s', 'distributor-connect'), $company_name);
        $message = sprintf(
            __('A new distributor has registered on your website.\n\nCompany: %s\nContact: %s\nPhone: %s\nEmail: %s\nType: %s\nGovernorate: %s\n\nPlease review and approve: %s', 'distributor-connect'),
            $company_name,
            $contact_name,
            $phone,
            $email,
            $distributor_type,
            $governorate,
            admin_url('post.php?post=' . $post_id . '&action=edit')
        );
        
        wp_mail($admin_email, $subject, $message);
        
        // Redirect with success message
        wp_redirect(add_query_arg('registration', 'success', wp_get_referer()));
        exit;
    } else {
        wp_redirect(add_query_arg('registration', 'error', wp_get_referer()));
        exit;
    }
}
add_action('admin_post_distributor_registration', 'handle_distributor_registration');
add_action('admin_post_nopriv_distributor_registration', 'handle_distributor_registration');

/**
 * Modify main query for archive pages
 */
function distributor_modify_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Set posts per page for distributor archives
        if (is_post_type_archive(array('wholesale', 'mixed', 'retail'))) {
            $query->set('posts_per_page', 12);
        }
        
        // Handle governorate filtering
        if (isset($_GET['governorate']) && !empty($_GET['governorate'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'governorate',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['governorate'])
                )
            ));
        }
    }
}
add_action('pre_get_posts', 'distributor_modify_main_query');

/**
 * Add custom body classes
 */
function distributor_body_classes($classes) {
    if (is_post_type_archive(array('wholesale', 'mixed', 'retail'))) {
        $classes[] = 'distributor-archive';
        $classes[] = 'distributor-' . get_query_var('post_type');
    }
    
    if (is_singular(array('wholesale', 'mixed', 'retail'))) {
        $classes[] = 'distributor-single';
        $classes[] = 'distributor-' . get_post_type();
    }
    
    if (is_tax('governorate')) {
        $classes[] = 'governorate-archive';
    }
    
    return $classes;
}
add_filter('body_class', 'distributor_body_classes');

/**
 * Customize excerpt length and more text
 */
function distributor_excerpt_length($length) {
    if (is_post_type_archive(array('wholesale', 'mixed', 'retail'))) {
        return 20;
    }
    return $length;
}
add_filter('excerpt_length', 'distributor_excerpt_length');

function distributor_excerpt_more($more) {
    if (is_post_type_archive(array('wholesale', 'mixed', 'retail'))) {
        return '...';
    }
    return $more;
}
add_filter('excerpt_more', 'distributor_excerpt_more');

/**
 * Add theme customizer support
 */
function distributor_customize_register($wp_customize) {
    // Add Distributor section
    $wp_customize->add_section('distributor_options', array(
        'title' => __('Distributor Options', 'distributor-connect'),
        'priority' => 130,
    ));
    
    // Contact info display setting
    $wp_customize->add_setting('show_contact_info', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('show_contact_info', array(
        'label' => __('Show Contact Info on Cards', 'distributor-connect'),
        'section' => 'distributor_options',
        'type' => 'checkbox',
    ));
    
    // Archive page layout
    $wp_customize->add_setting('archive_layout', array(
        'default' => 'grid',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('archive_layout', array(
        'label' => __('Archive Page Layout', 'distributor-connect'),
        'section' => 'distributor_options',
        'type' => 'select',
        'choices' => array(
            'grid' => __('Grid Layout', 'distributor-connect'),
            'list' => __('List Layout', 'distributor-connect'),
        ),
    ));
}
add_action('customize_register', 'distributor_customize_register');

/**
 * Remove unwanted actions from parent theme if needed
 */
function distributor_remove_parent_actions() {
    // Example: Remove parent theme's custom post types if they conflict
    // remove_action('init', 'parent_theme_post_types');
}
add_action('after_setup_theme', 'distributor_remove_parent_actions', 11);

/**
 * Add custom search functionality
 */
function distributor_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && is_search()) {
        // Include custom post types in search
        $query->set('post_type', array('post', 'page', 'wholesale', 'mixed', 'retail'));
    }
}
add_action('pre_get_posts', 'distributor_search_filter');

/**
 * Load text domain for translations
 */
function distributor_load_textdomain() {
    load_child_theme_textdomain('distributor-connect', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'distributor_load_textdomain');

/**
 * Flush rewrite rules on theme activation
 */
function distributor_activate_theme() {
    // Trigger CPT registration
    do_action('init');
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'distributor_activate_theme');

/**
 * Theme deactivation cleanup
 */
function distributor_deactivate_theme() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'distributor_deactivate_theme');