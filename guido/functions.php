<?php
/**
 * guido functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Guido
 * @since Guido 1.0.35
 */

define( 'GUIDO_THEME_VERSION', '1.0.35' );
define( 'GUIDO_DEMO_MODE', false );

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'guido_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Guido 1.0
 */
function guido_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on guido, use a find and replace
	 * to change 'guido' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'guido', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );
	add_image_size( 'guido-listing-list', 448, 352, true );
	add_image_size( 'guido-listing-grid', 630, 396, true );

	add_image_size( 'guido-gallery-large', 1920, 650, true );
	add_image_size( 'guido-gallery-medium', 640, 500, true );
	add_image_size( 'guido-gallery-xl', 810, 530, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'guido' ),
		'mobile-primary' => esc_html__( 'Primary Mobile Menu', 'guido' ),
		'user-menu' => esc_html__( 'User Account Menu', 'guido' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce", array('gallery_thumbnail_image_width' => 410) );
	
	add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = guido_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'guido_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Enqueue editor styles.
	add_editor_style('css/style-editor.css');
}
endif; // guido_setup
add_action( 'after_setup_theme', 'guido_setup' );

/**
 * Load Google Front
 */
function guido_get_fonts_url() {
    $fonts_url = '';

    $main_font = guido_get_config('main-font');
	$main_font = !empty($main_font) ? json_decode($main_font, true) : array();
	if (  !empty($main_font['fontfamily']) ) {
		$main_font_family = $main_font['fontfamily'];
		$main_font_weight = !empty($main_font['fontweight']) ? $main_font['fontweight'] : '400,500,600,700,800,900';
		$main_font_subsets = !empty($main_font['subsets']) ? $main_font['subsets'] : 'latin,latin-ext';
	} else {
		$main_font_family = 'Jost';
		$main_font_weight = '400,500,600,700,800,900';
		$main_font_subsets = 'latin,latin-ext';
	}

	$heading_font = guido_get_config('heading-font');
	$heading_font = !empty($heading_font) ? json_decode($heading_font, true) : array();
	if (  !empty($heading_font['fontfamily']) ) {
		$heading_font_family = $heading_font['fontfamily'];
		$heading_font_weight = !empty($heading_font['fontweight']) ? $heading_font['fontweight'] : '400,500,600,700,800,900';
		$heading_font_subsets = !empty($heading_font['subsets']) ? $heading_font['subsets'] : 'latin,latin-ext';
	} else {
		$heading_font_family = 'Jost';
		$heading_font_weight = '400,500,600,700,800,900';
		$heading_font_subsets = 'latin,latin-ext';
	}

	if ( $main_font_family == $heading_font_family ) {
		$font_weight = $main_font_weight.','.$heading_font_weight;
		$font_subsets = $main_font_subsets.','.$heading_font_subsets;
		$fonts = array(
			$main_font_family => array(
				'weight' => $font_weight,
				'subsets' => $font_subsets,
			),
		);
	} else {
		$fonts = array(
			$main_font_family => array(
				'weight' => $main_font_weight,
				'subsets' => $main_font_subsets,
			),
			$heading_font_family => array(
				'weight' => $heading_font_weight,
				'subsets' => $heading_font_subsets,
			),
		);
	}

	$font_families = array();
	$subset = array();

	foreach ($fonts as $key => $opt) {
		$font_families[] = $key.':'.$opt['weight'];
		$subset[] = $opt['subsets'];
	}



    $query_args = array(
        'family' => implode( '|', $font_families ),
        'subset' => urlencode( implode( ',', $subset ) ),
    );
		
		$protocol = is_ssl() ? 'https:' : 'http:';
    $fonts_url = add_query_arg( $query_args, $protocol .'//fonts.googleapis.com/css' );
    
 
    return esc_url( $fonts_url );
}

/**
 * Enqueue styles.
 *
 * @since Guido 1.0
 */
function guido_enqueue_styles() {
	
	// load font
	wp_enqueue_style( 'guido-theme-fonts', guido_get_fonts_url(), array(), null );

	//load font awesome
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );

	//load font flaticon
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '1.0.0' );

	// load font themify icon
	wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );
			
	// load animate version 3.6.0
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '3.6.0' );

	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap-rtl', get_template_directory_uri() . '/css/bootstrap.rtl.css', array(), '5.0.2' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '5.0.2' );
	}
	// slick
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.8.0' );
	// magnific-popup
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '1.1.0' );
	// perfect scrollbar
	wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri() . '/css/perfect-scrollbar.css', array(), '0.6.12' );
	
	// mobile menu
	wp_enqueue_style( 'sliding-menu', get_template_directory_uri() . '/css/sliding-menu.min.css', array(), '0.3.0' );

	// main style
	if( is_rtl() ){
		wp_enqueue_style( 'guido-template', get_template_directory_uri() . '/css/template.rtl.css', array(), '1.0' );
	} else {
		wp_enqueue_style( 'guido-template', get_template_directory_uri() . '/css/template.css', array(), '1.0' );
	}
	
	$custom_style = guido_custom_styles();
	if ( !empty($custom_style) ) {
		wp_add_inline_style( 'guido-template', $custom_style );
	}
	wp_enqueue_style( 'guido-style', get_template_directory_uri() . '/style.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'guido_enqueue_styles', 100 );

function guido_admin_enqueue_styles() {

	// load font
	wp_enqueue_style( 'guido-theme-fonts', guido_get_fonts_url(), array(), null );

	//load font awesome
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );

	//load font flaticon
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '1.0.0' );

	// load font themify icon
	wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'guido_admin_enqueue_styles', 100 );

function guido_login_enqueue_styles() {
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.5.0' );
	wp_enqueue_style( 'guido-login-style', get_template_directory_uri() . '/css/login-style.css', array(), '1.0' );
}
add_action( 'login_enqueue_scripts', 'guido_login_enqueue_styles', 10 );
/**
 * Enqueue scripts.
 *
 * @since Guido 1.0
 */
function guido_enqueue_scripts() {
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// bootstrap
	wp_enqueue_script( 'bootstrap-bundle', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.0.2', true );
	// slick
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	// countdown
	wp_register_script( 'countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), '20150315', true );
	wp_localize_script( 'countdown', 'guido_countdown_opts', array(
		'days' => esc_html__('Days', 'guido'),
		'hours' => esc_html__('Hrs', 'guido'),
		'mins' => esc_html__('Mins', 'guido'),
		'secs' => esc_html__('Secs', 'guido'),
	));
	
	// popup
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	// unviel
	wp_enqueue_script( 'jquery-unveil', get_template_directory_uri() . '/js/jquery.unveil.js', array( 'jquery' ), '1.1.0', true );
	
	// perfect scrollbar
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.min.js', array( 'jquery' ), '1.5.0', true );
	
	if ( guido_get_config('keep_header') ) {
		wp_enqueue_script( 'jquery-waypoints', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '4.0.1', true );
		wp_enqueue_script( 'sticky', get_template_directory_uri() . '/js/sticky.min.js', array( 'jquery', 'jquery-waypoints' ), '4.0.1', true );
	}

	// mobile menu script
	wp_enqueue_script( 'sliding-menu', get_template_directory_uri() . '/js/sliding-menu.min.js', array( 'jquery' ), '0.3.0', true );

	// main script
	wp_register_script( 'guido-functions', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
	wp_localize_script( 'guido-functions', 'guido_opts', array(
		'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
		'previous' => esc_html__('Previous', 'guido'),
		'next' => esc_html__('Next', 'guido'),
		'menu_back_text' => esc_html__('Back', 'guido')
	));
	wp_enqueue_script( 'guido-functions' );
	
	wp_add_inline_script( 'guido-functions', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'guido_enqueue_scripts', 1 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Guido 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function guido_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'guido_search_form_modify' );


function guido_get_config($name, $default = '') {
	global $guido_theme_options;
	
	if ( empty($guido_theme_options) ) {
		$guido_theme_options = get_option('guido_theme_options');
	}

    if ( isset($guido_theme_options[$name]) ) {
        return $guido_theme_options[$name];
    }
    return $default;
}

function guido_set_exporter_ocdi_settings_option_keys($option_keys) {
	return array(
		'guido_theme_options',
		'elementor_disable_color_schemes',
		'elementor_disable_typography_schemes',
		'elementor_allow_tracking',
		'elementor_cpt_support',
		'wp_listings_directory_settings',
		'wp_listings_directory_fields_data',
	);
}
add_filter( 'apus_exporter_ocdi_settings_option_keys', 'guido_set_exporter_ocdi_settings_option_keys' );

function guido_disable_one_click_import() {
	return false;
}
add_filter('apus_frammework_enable_one_click_import', 'guido_disable_one_click_import');

function guido_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'guido' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Listings filter sidebar', 'guido' ),
		'id'            => 'listings-filter',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Listings filter Top', 'guido' ),
		'id'            => 'listings-filter-top',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Listings filter Top 2', 'guido' ),
		'id'            => 'listings-filter-top2',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Listings filter Top Map', 'guido' ),
		'id'            => 'listings-filter-top-map',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Listings filter Half Map', 'guido' ),
		'id'            => 'listings-filter-half-map',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Listing single sidebar', 'guido' ),
		'id'            => 'listing-single-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );


	register_sidebar( array(
		'name'          => esc_html__( 'User Profile sidebar', 'guido' ),
		'id'            => 'user-profile-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Blog sidebar', 'guido' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Shop sidebar', 'guido' ),
		'id'            => 'shop-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'guido' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

}
add_action( 'widgets_init', 'guido_widgets_init' );

function guido_get_load_plugins() {
	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Framework For Themes', 'guido' ),
        'slug'                     => 'apus-frame',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-frame.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Elementor Page Builder', 'guido' ),
	    'slug'                     => 'elementor',
	    'required'                 => true,
	);
	
	$plugins[] = array(
		'name'                     => esc_html__( 'Cmb2', 'guido' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'guido' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Contact Form 7', 'guido' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	);

	// woocommerce plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'Woocommerce', 'guido' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	);
	
	// Listing plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'WP Listings Directory', 'guido' ),
        'slug'                     => 'wp-listings-directory',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/wp-listings-directory.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'WP Listings Directory - WooCommerce Paid Listings', 'guido' ),
        'slug'                     => 'wp-listings-directory-wc-paid-listings',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/wp-listings-directory-wc-paid-listings.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'WP Private Message', 'guido' ),
        'slug'                     => 'wp-private-message',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/wp-private-message.zip'
	);
	
	$plugins[] = array(
        'name'                  => esc_html__( 'One Click Demo Import', 'guido' ),
        'slug'                  => 'one-click-demo-import',
        'required'              => false,
    );

	$plugins[] = array(
        'name'                  => esc_html__( 'SVG Support', 'guido' ),
        'slug'                  => 'easy-svg',
        'required'              => false,
        'force_activation'      => false,
        'force_deactivation'    => false,
    );

	$config = array(
        'id'           => 'guido', 
        'default_path' => '', 
        'menu'         => 'tgmpa-install-plugins', 
        'has_notices'  => true, 
        'dismissable'  => true, 
        'dismiss_msg'  => '',  
        'is_automatic' => false, 
        'message'      => '', 
        'strings'      => array(
            'bulk_install' => esc_html__( 'Install Selected Plugins', 'guido' ),
        ),
    );

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'guido_get_load_plugins' );

require get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';
get_template_part( '/inc/functions-helper' );
get_template_part( '/inc/functions-frontend' );

/**
 * Implement the Custom Header feature.
 *
 */
get_template_part( '/inc/custom-header' );
get_template_part( '/inc/classes/megamenu' );
get_template_part( '/inc/classes/mobilemenu' );

/**
 * Custom template tags for this theme.
 *
 */
get_template_part( '/inc/template-tags' );

/**
 * Customizer additions.
 *
 */
get_template_part( '/inc/customizer/font/custom-controls' );
get_template_part( '/inc/customizer/customizer-custom-control' );
get_template_part( '/inc/customizer/customizer' );


if( guido_is_cmb2_activated() ) {
	get_template_part( '/inc/vendors/cmb2/page' );
}

if( guido_is_woocommerce_activated() ) {
	get_template_part( '/inc/vendors/woocommerce/functions' );
	get_template_part( '/inc/vendors/woocommerce/customizer' );
}

if( guido_is_wp_listings_directory_activated() ) {
	get_template_part( '/inc/vendors/wp-listings-directory/customizer' );
	get_template_part( '/inc/vendors/wp-listings-directory/functions' );

	get_template_part( '/inc/vendors/wp-listings-directory/functions-listing-display' );
}

if ( guido_is_wp_listings_directory_wc_paid_listings_activated() ) {
	get_template_part( '/inc/vendors/wp-listings-directory-wc-paid-listings/functions' );
}

function guido_register_load_widget() {
	get_template_part( '/inc/widgets/custom_menu' );
	get_template_part( '/inc/widgets/recent_post' );
	get_template_part( '/inc/widgets/search' );
	
	get_template_part( '/inc/widgets/elementor-template' );
	
	if ( guido_is_wp_listings_directory_activated() ) {
		
		get_template_part( '/inc/widgets/listing-list' );
		get_template_part( '/inc/widgets/user-short-profile' );
		
		get_template_part( '/inc/widgets/contact-form' );

		// listing details
		get_template_part( '/inc/widgets/listing-contact-form' );
		get_template_part( '/inc/widgets/listing-author' );
		get_template_part( '/inc/widgets/listing-categories' );
		get_template_part( '/inc/widgets/listing-contact-info' );
		get_template_part( '/inc/widgets/listing-hours' );
		get_template_part( '/inc/widgets/listing-price' );
		
		get_template_part( '/inc/widgets/author-contact-info' );
		get_template_part( '/inc/widgets/author-description' );
		
		if ( guido_is_wp_private_message() ) {
			get_template_part( '/inc/widgets/private-message-form' );
		}
	}
}
add_action( 'widgets_init', 'guido_register_load_widget' );

if ( guido_is_wp_private_message() ) {
	get_template_part( '/inc/vendors/wp-private-message/functions' );
}

get_template_part( '/inc/vendors/elementor/functions' );
get_template_part( '/inc/vendors/one-click-demo-import/functions' );


/**
 * Custom Styles
 *
 */
get_template_part( '/inc/custom-styles' );