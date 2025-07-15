<?php
/**
 * Shortcode System for Distributor Directory
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main distributor search shortcode
 * Usage: [distributor_search layout="full" show_filters="true"]
 */
function distributor_search_shortcode($atts) {
    $atts = shortcode_atts(array(
        'layout' => 'full', // full, compact, mini
        'show_filters' => 'true',
        'show_sort' => 'true',
        'per_page' => '12',
        'columns' => '3',
        'governorate' => '',
        'type' => '',
        'class' => ''
    ), $atts);
    
    // Enqueue required scripts
    wp_enqueue_script('distributor-search-filter');
    
    ob_start();
    ?>
    <div class="distributor-search-container <?php echo esc_attr($atts['class']); ?>" data-layout="<?php echo esc_attr($atts['layout']); ?>">
        
        <?php if ($atts['layout'] !== 'mini'): ?>
            <div class="search-header">
                <h2>البحث في دليل الموزعين</h2>
                <p>ابحث عن الموزعين حسب المحافظة ونوع النشاط</p>
            </div>
        <?php endif; ?>
        
        <div class="distributor-search-filter">
            <form class="search-form">
                <div class="search-form-grid <?php echo $atts['layout']; ?>">
                    
                    <div class="search-field">
                        <input type="text" 
                               class="search-input" 
                               name="search" 
                               placeholder="ابحث بالاسم، الهاتف، أو نوع المنتج..."
                               value="<?php echo esc_attr($_GET['s'] ?? ''); ?>">
                    </div>
                    
                    <?php if ($atts['show_filters'] === 'true'): ?>
                        
                        <div class="filter-field">
                            <select name="governorate" class="filter-select">
                                <option value="">جميع المحافظات</option>
                                <?php
                                $governorates = get_terms(array(
                                    'taxonomy' => 'governorate',
                                    'hide_empty' => false,
                                    'orderby' => 'name'
                                ));
                                
                                $selected_gov = $atts['governorate'] ?: ($_GET['governorate'] ?? '');
                                
                                foreach ($governorates as $gov):
                                ?>
                                    <option value="<?php echo esc_attr($gov->slug); ?>" 
                                            <?php selected($selected_gov, $gov->slug); ?>>
                                        <?php echo esc_html($gov->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-field">
                            <select name="type" class="filter-select">
                                <option value="">جميع الأنواع</option>
                                <option value="wholesale" <?php selected($atts['type'] ?: ($_GET['type'] ?? ''), 'wholesale'); ?>>جملة</option>
                                <option value="mixed" <?php selected($atts['type'] ?: ($_GET['type'] ?? ''), 'mixed'); ?>>جملة مختلطة</option>
                                <option value="retail" <?php selected($atts['type'] ?: ($_GET['type'] ?? ''), 'retail'); ?>>قطاعي</option>
                            </select>
                        </div>
                        
                        <?php if ($atts['layout'] === 'full'): ?>
                            <div class="filter-field">
                                <select name="delivery" class="filter-select">
                                    <option value="">خدمة التوصيل</option>
                                    <option value="available">متوفرة</option>
                                    <option value="partial">مناطق معينة</option>
                                    <option value="not_available">غير متوفرة</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                    <?php endif; ?>
                    
                    <?php if ($atts['show_sort'] === 'true' && $atts['layout'] === 'full'): ?>
                        <div class="sort-field">
                            <select name="sort" class="filter-select">
                                <option value="date">الأحدث</option>
                                <option value="name">الاسم</option>
                                <option value="rating">التقييم</option>
                                <option value="verified">الموثقين أولاً</option>
                                <option value="featured">المميزين أولاً</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="search-button">
                        <button type="submit" class="search-btn">
                            <span class="search-icon">🔍</span>
                            <span class="search-text">بحث</span>
                        </button>
                    </div>
                    
                </div>
                
                <?php if ($atts['layout'] === 'full'): ?>
                    <div class="advanced-filters">
                        <label class="filter-checkbox">
                            <input type="checkbox" name="verified" value="1">
                            <span class="checkmark"></span>
                            موثقين فقط
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="featured" value="1">
                            <span class="checkmark"></span>
                            مميزين فقط
                        </label>
                    </div>
                <?php endif; ?>
                
            </form>
        </div>
        
        <div class="search-results" data-columns="<?php echo esc_attr($atts['columns']); ?>"></div>
        <div class="loading" style="display: none;">
            <div class="loading-spinner"></div>
            <p>جاري البحث...</p>
        </div>
        
    </div>
    
    <style>
    .search-form-grid.mini {
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
    }
    .search-form-grid.compact {
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 1rem;
    }
    .search-form-grid.full {
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
        gap: 1rem;
    }
    .loading {
        text-align: center;
        padding: 2rem;
    }
    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
    
    <?php
    return ob_get_clean();
}
add_shortcode('distributor_search', 'distributor_search_shortcode');

/**
 * Mini search widget shortcode
 * Usage: [mini_search placeholder="ابحث..."]
 */
function mini_search_shortcode($atts) {
    $atts = shortcode_atts(array(
        'placeholder' => 'ابحث عن موزع...',
        'show_button' => 'true',
        'redirect_page' => ''
    ), $atts);
    
    $redirect_url = !empty($atts['redirect_page']) ? get_permalink($atts['redirect_page']) : home_url('/search/');
    
    ob_start();
    ?>
    <div class="mini-search-widget">
        <form class="mini-search-form" method="get" action="<?php echo esc_url($redirect_url); ?>">
            <div class="mini-search-input-group">
                <input type="text" 
                       name="s" 
                       class="mini-search-input" 
                       placeholder="<?php echo esc_attr($atts['placeholder']); ?>"
                       value="<?php echo esc_attr($_GET['s'] ?? ''); ?>">
                <?php if ($atts['show_button'] === 'true'): ?>
                    <button type="submit" class="mini-search-btn">🔍</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <style>
    .mini-search-widget {
        margin: 1rem 0;
    }
    .mini-search-input-group {
        display: flex;
        border: 2px solid var(--border-color);
        border-radius: var(--border-radius);
        overflow: hidden;
    }
    .mini-search-input {
        flex: 1;
        padding: 0.75rem;
        border: none;
        outline: none;
        font-size: 1rem;
        direction: rtl;
    }
    .mini-search-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background 0.3s;
    }
    .mini-search-btn:hover {
        background: var(--secondary-color);
    }
    </style>
    
    <?php
    return ob_get_clean();
}
add_shortcode('mini_search', 'mini_search_shortcode');

/**
 * Show distributors by governorate
 * Usage: [distributors_by_governorate gov="cairo" type="wholesale" limit="6"]
 */
function distributors_by_governorate_shortcode($atts) {
    $atts = shortcode_atts(array(
        'gov' => '',
        'type' => '',
        'limit' => '6',
        'columns' => '3',
        'show_title' => 'true',
        'show_more_link' => 'true',
        'orderby' => 'date',
        'order' => 'DESC'
    ), $atts);
    
    if (empty($atts['gov'])) {
        return '<p>يرجى تحديد المحافظة في الشورت كود</p>';
    }
    
    // Get governorate term
    $governorate = get_term_by('slug', $atts['gov'], 'governorate');
    if (!$governorate) {
        return '<p>محافظة غير موجودة</p>';
    }
    
    // Build query
    $args = array(
        'post_type' => !empty($atts['type']) ? $atts['type'] : array('wholesale', 'mixed', 'retail'),
        'post_status' => 'publish',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'tax_query' => array(
            array(
                'taxonomy' => 'governorate',
                'field' => 'slug',
                'terms' => $atts['gov']
            )
        )
    );
    
    $query = new WP_Query($args);
    
    if (!$query->have_posts()) {
        return '<p>لا يوجد موزعين في هذه المحافظة حالياً</p>';
    }
    
    ob_start();
    ?>
    <div class="distributors-by-governorate">
        
        <?php if ($atts['show_title'] === 'true'): ?>
            <div class="section-header">
                <h3>موزعين محافظة <?php echo esc_html($governorate->name); ?></h3>
                <?php if ($query->found_posts > intval($atts['limit']) && $atts['show_more_link'] === 'true'): ?>
                    <a href="<?php echo get_term_link($governorate); ?>" class="view-all-link">
                        عرض الكل (<?php echo $query->found_posts; ?>)
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="distributors-grid" style="grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr);">
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <?php get_template_part('template-parts/distributor-card'); ?>
            <?php endwhile; ?>
        </div>
        
    </div>
    
    <style>
    .distributors-by-governorate .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
    }
    .distributors-by-governorate .view-all-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border: 2px solid var(--primary-color);
        border-radius: var(--border-radius);
        transition: all 0.3s;
    }
    .distributors-by-governorate .view-all-link:hover {
        background: var(--primary-color);
        color: white;
    }
    </style>
    
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('distributors_by_governorate', 'distributors_by_governorate_shortcode');

/**
 * Featured distributors shortcode
 * Usage: [featured_distributors limit="4" show_carousel="true"]
 */
function featured_distributors_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => '4',
        'type' => '',
        'columns' => '4',
        'show_title' => 'true',
        'show_carousel' => 'false',
        'autoplay' => 'true',
        'autoplay_speed' => '3000'
    ), $atts);
    
    $args = array(
        'post_type' => !empty($atts['type']) ? $atts['type'] : array('wholesale', 'mixed', 'retail'),
        'post_status' => 'publish',
        'posts_per_page' => intval($atts['limit']),
        'meta_query' => array(
            array(
                'key' => 'featured',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'rand'
    );
    
    $query = new WP_Query($args);
    
    if (!$query->have_posts()) {
        return '<p>لا يوجد موزعين مميزين حالياً</p>';
    }
    
    $carousel_class = $atts['show_carousel'] === 'true' ? 'featured-carousel' : 'featured-grid';
    
    ob_start();
    ?>
    <div class="featured-distributors">
        
        <?php if ($atts['show_title'] === 'true'): ?>
            <div class="section-header">
                <h3>⭐ الموزعين المميزين</h3>
            </div>
        <?php endif; ?>
        
        <div class="<?php echo $carousel_class; ?>" 
             <?php if ($atts['show_carousel'] === 'true'): ?>
                data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
                data-speed="<?php echo esc_attr($atts['autoplay_speed']); ?>"
             <?php else: ?>
                style="grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr);"
             <?php endif; ?>>
            
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <?php get_template_part('template-parts/distributor-card'); ?>
            <?php endwhile; ?>
            
        </div>
        
        <?php if ($atts['show_carousel'] === 'true'): ?>
            <div class="carousel-controls">
                <button class="carousel-prev">‹</button>
                <button class="carousel-next">›</button>
            </div>
        <?php endif; ?>
        
    </div>
    
    <?php if ($atts['show_carousel'] === 'true'): ?>
        <script>
        jQuery(document).ready(function($) {
            const carousel = $('.featured-carousel');
            const autoplay = carousel.data('autoplay');
            const speed = carousel.data('speed');
            
            // Simple carousel implementation
            if (autoplay === 'true') {
                setInterval(function() {
                    // Add simple auto-scroll logic here
                }, speed);
            }
        });
        </script>
    <?php endif; ?>
    
    <style>
    .featured-distributors .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .featured-distributors .section-header h3 {
        background: linear-gradient(45deg, #ffd700, #ffed4e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.5rem;
    }
    .featured-grid {
        display: grid;
        gap: 2rem;
    }
    .featured-carousel {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 1rem;
    }
    .featured-carousel .distributor-card {
        flex: 0 0 300px;
    }
    .carousel-controls {
        text-align: center;
        margin-top: 1rem;
    }
    .carousel-controls button {
        background: var(--primary-color);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 0.5rem;
        cursor: pointer;
        font-size: 1.2rem;
    }
    </style>
    
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('featured_distributors', 'featured_distributors_shortcode');

/**
 * Governorate list shortcode
 * Usage: [governorate_list show_count="true" layout="grid"]
 */
function governorate_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_count' => 'true',
        'layout' => 'grid', // grid, list, dropdown
        'columns' => '4',
        'hide_empty' => 'true',
        'show_region' => 'false'
    ), $atts);
    
    $args = array(
        'taxonomy' => 'governorate',
        'hide_empty' => $atts['hide_empty'] === 'true',
        'orderby' => 'name',
        'order' => 'ASC'
    );
    
    $governorates = get_terms($args);
    
    if (empty($governorates)) {
        return '<p>لا توجد محافظات</p>';
    }
    
    ob_start();
    
    if ($atts['layout'] === 'dropdown') {
        ?>
        <div class="governorate-dropdown">
            <select onchange="window.location.href=this.value">
                <option value="">اختر المحافظة</option>
                <?php foreach ($governorates as $gov): ?>
                    <option value="<?php echo get_term_link($gov); ?>">
                        <?php echo esc_html($gov->name); ?>
                        <?php if ($atts['show_count'] === 'true'): ?>
                            (<?php echo $gov->count; ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    } else {
        $layout_class = $atts['layout'] === 'grid' ? 'governorate-grid' : 'governorate-list';
        ?>
        <div class="<?php echo $layout_class; ?>" 
             <?php if ($atts['layout'] === 'grid'): ?>
                style="grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr);"
             <?php endif; ?>>
            
            <?php foreach ($governorates as $gov): 
                $region = $atts['show_region'] === 'true' ? get_term_meta($gov->term_id, 'region', true) : '';
            ?>
                <div class="governorate-item">
                    <a href="<?php echo get_term_link($gov); ?>" class="governorate-link">
                        <h4><?php echo esc_html($gov->name); ?></h4>
                        <?php if ($region): ?>
                            <span class="region"><?php echo esc_html($region); ?></span>
                        <?php endif; ?>
                        <?php if ($atts['show_count'] === 'true'): ?>
                            <span class="count"><?php echo $gov->count; ?> موزع</span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
            
        </div>
        
        <style>
        .governorate-grid {
            display: grid;
            gap: 1rem;
        }
        .governorate-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .governorate-item {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: all 0.3s;
        }
        .governorate-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }
        .governorate-link {
            display: block;
            padding: 1rem;
            text-decoration: none;
            color: inherit;
        }
        .governorate-link h4 {
            margin: 0 0 0.5rem 0;
            color: var(--primary-color);
        }
        .governorate-link .region {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        .governorate-link .count {
            color: var(--info-color);
            font-weight: 600;
            font-size: 0.9rem;
        }
        </style>
        <?php
    }
    
    return ob_get_clean();
}
add_shortcode('governorate_list', 'governorate_list_shortcode');

/**
 * Add shortcode button to editor
 */
function add_distributor_shortcode_button() {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_buttons', 'register_distributor_shortcode_button');
        add_filter('mce_external_plugins', 'add_distributor_shortcode_plugin');
    }
}
add_action('admin_init', 'add_distributor_shortcode_button');

function register_distributor_shortcode_button($buttons) {
    array_push($buttons, 'distributor_shortcodes');
    return $buttons;
}

function add_distributor_shortcode_plugin($plugin_array) {
    $plugin_array['distributor_shortcodes'] = get_stylesheet_directory_uri() . '/assets/js/shortcode-button.js';
    return $plugin_array;
}

function distributor_search_filter_shortcode() {
    ob_start();
    get_template_part('template-parts/search-filter-form');
    return ob_get_clean();
}
add_shortcode('distributor_search_filter', 'distributor_search_filter_shortcode');