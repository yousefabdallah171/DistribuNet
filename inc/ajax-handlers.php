<?php
/**
 * AJAX Handlers for Search and Filter Functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main AJAX search handler
 */
function handle_distributor_search() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'distributor_nonce')) {
        wp_die(json_encode(array('success' => false, 'data' => 'فشل التحقق الأمني')));
    }
    
    // Get search parameters
    $search_query = sanitize_text_field($_POST['search'] ?? '');
    $governorate = sanitize_text_field($_POST['governorate'] ?? '');
    $type = sanitize_text_field($_POST['type'] ?? '');
    $sort = sanitize_text_field($_POST['sort'] ?? 'date');
    $delivery = sanitize_text_field($_POST['delivery'] ?? '');
    $verified = sanitize_text_field($_POST['verified'] ?? '');
    $featured = sanitize_text_field($_POST['featured'] ?? '');
    $page = intval($_POST['page'] ?? 1);
    $per_page = intval($_POST['per_page'] ?? 12);
    
    // Build query arguments
    $args = array(
        'post_type' => array('wholesale', 'mixed', 'retail'),
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'meta_query' => array(),
        'tax_query' => array(),
    );
    
    // Add search query
    if (!empty($search_query)) {
        $args['s'] = $search_query;
        
        // Also search in custom fields
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array(
                'key' => 'full_name',
                'value' => $search_query,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'phone',
                'value' => $search_query,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'full_address',
                'value' => $search_query,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'product_types',
                'value' => $search_query,
                'compare' => 'LIKE'
            )
        );
    }
    
    // Filter by post type
    if (!empty($type) && in_array($type, array('wholesale', 'mixed', 'retail'))) {
        $args['post_type'] = $type;
    }
    
    // Filter by governorate
    if (!empty($governorate)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'governorate',
            'field' => 'slug',
            'terms' => $governorate
        );
    }
    
    // Filter by delivery service
    if (!empty($delivery)) {
        $args['meta_query'][] = array(
            'key' => 'delivery_service',
            'value' => $delivery,
            'compare' => '='
        );
    }
    
    // Filter by verified status
    if ($verified === '1') {
        $args['meta_query'][] = array(
            'key' => 'verified',
            'value' => '1',
            'compare' => '='
        );
    }
    
    // Filter by featured status
    if ($featured === '1') {
        $args['meta_query'][] = array(
            'key' => 'featured',
            'value' => '1',
            'compare' => '='
        );
    }
    
    // Handle sorting
    switch ($sort) {
        case 'name':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'rating':
            $args['meta_key'] = 'rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'verified':
            $args['meta_key'] = 'verified';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'DESC';
            break;
        case 'featured':
            $args['meta_key'] = 'featured';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
    }
    
    // Perform the query
    $query = new WP_Query($args);
    
    // Generate HTML output
    $html = '';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('template-parts/distributor-card');
            $html .= ob_get_clean();
        }
        wp_reset_postdata();
    }
    
    // Prepare response
    $response = array(
        'success' => true,
        'data' => array(
            'html' => $html,
            'total' => $query->found_posts,
            'total_pages' => $query->max_num_pages,
            'current_page' => $page,
            'has_more' => $page < $query->max_num_pages
        )
    );
    
    wp_die(json_encode($response));
}
add_action('wp_ajax_search_distributors', 'handle_distributor_search');
add_action('wp_ajax_nopriv_search_distributors', 'handle_distributor_search');

/**
 * Quick search handler for popup/widget
 */
function handle_quick_search() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'distributor_nonce')) {
        wp_die(json_encode(array('success' => false, 'data' => 'فشل التحقق الأمني')));
    }
    
    $search_query = sanitize_text_field($_POST['search'] ?? '');
    
    if (strlen($search_query) < 2) {
        wp_die(json_encode(array(
            'success' => true,
            'data' => array('html' => '<p class="no-results">أدخل على الأقل حرفين للبحث</p>')
        )));
    }
    
    // Quick search query
    $args = array(
        'post_type' => array('wholesale', 'mixed', 'retail'),
        'post_status' => 'publish',
        'posts_per_page' => 5,
        's' => $search_query,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'full_name',
                'value' => $search_query,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'phone',
                'value' => $search_query,
                'compare' => 'LIKE'
            )
        )
    );
    
    $query = new WP_Query($args);
    
    $html = '';
    if ($query->have_posts()) {
        $html .= '<div class="quick-search-results">';
        while ($query->have_posts()) {
            $query->the_post();
            
            $phone = get_field('phone');
            $governorates = get_the_terms(get_the_ID(), 'governorate');
            $gov_name = $governorates && !is_wp_error($governorates) ? $governorates[0]->name : '';
            
            $html .= sprintf(
                '<div class="quick-result-item">
                    <a href="%s">
                        <h4>%s</h4>
                        <div class="quick-meta">
                            %s%s
                        </div>
                    </a>
                </div>',
                get_permalink(),
                get_the_title(),
                $phone ? '<span class="phone">📞 ' . esc_html($phone) . '</span>' : '',
                $gov_name ? '<span class="gov">📍 ' . esc_html($gov_name) . '</span>' : ''
            );
        }
        $html .= '</div>';
        
        if ($query->found_posts > 5) {
            $html .= sprintf('<div class="view-all-results">
                <a href="%s">عرض جميع النتائج (%d)</a>
            </div>', 
            home_url('/search/?s=' . urlencode($search_query)), 
            $query->found_posts);
        }
        
        wp_reset_postdata();
    } else {
        $html = '<p class="no-quick-results">لم يتم العثور على نتائج</p>';
    }
    
    wp_die(json_encode(array(
        'success' => true,
        'data' => array('html' => $html)
    )));
}
add_action('wp_ajax_quick_search_distributors', 'handle_quick_search');
add_action('wp_ajax_nopriv_quick_search_distributors', 'handle_quick_search');

/**
 * Get distributors by governorate (for AJAX)
 */
function get_distributors_by_governorate() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'distributor_nonce')) {
        wp_die(json_encode(array('success' => false, 'data' => 'فشل التحقق الأمني')));
    }
    
    $governorate = sanitize_text_field($_POST['governorate'] ?? '');
    $type = sanitize_text_field($_POST['type'] ?? '');
    $limit = intval($_POST['limit'] ?? 6);
    
    if (empty($governorate)) {
        wp_die(json_encode(array('success' => false, 'data' => 'محافظة غير محددة')));
    }
    
    $args = array(
        'post_type' => !empty($type) ? $type : array('wholesale', 'mixed', 'retail'),
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'tax_query' => array(
            array(
                'taxonomy' => 'governorate',
                'field' => 'slug',
                'terms' => $governorate
            )
        ),
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
    
    $html = '';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('template-parts/distributor-card');
            $html .= ob_get_clean();
        }
        wp_reset_postdata();
    }
    
    wp_die(json_encode(array(
        'success' => true,
        'data' => array(
            'html' => $html,
            'count' => $query->found_posts
        )
    )));
}
add_action('wp_ajax_get_distributors_by_governorate', 'get_distributors_by_governorate');
add_action('wp_ajax_nopriv_get_distributors_by_governorate', 'get_distributors_by_governorate');

/**
 * Get featured distributors
 */
function get_featured_distributors() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'distributor_nonce')) {
        wp_die(json_encode(array('success' => false, 'data' => 'فشل التحقق الأمني')));
    }
    
    $limit = intval($_POST['limit'] ?? 4);
    $type = sanitize_text_field($_POST['type'] ?? '');
    
    $args = array(
        'post_type' => !empty($type) ? $type : array('wholesale', 'mixed', 'retail'),
        'post_status' => 'publish',
        'posts_per_page' => $limit,
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
    
    $html = '';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('template-parts/distributor-card');
            $html .= ob_get_clean();
        }
        wp_reset_postdata();
    }
    
    wp_die(json_encode(array(
        'success' => true,
        'data' => array(
            'html' => $html,
            'count' => $query->found_posts
        )
    )));
}
add_action('wp_ajax_get_featured_distributors', 'get_featured_distributors');
add_action('wp_ajax_nopriv_get_featured_distributors', 'get_featured_distributors');

/**
 * Enhanced form submission handler with better validation
 */
function handle_enhanced_distributor_registration() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['distributor_nonce'], 'distributor_registration')) {
        wp_die(__('فشل التحقق الأمني', 'distributor-connect'));
    }
    
    // Sanitize and validate all fields
    $distributor_type = sanitize_text_field($_POST['distributor_type']);
    $governorate = sanitize_text_field($_POST['governorate']);
    $company_name = sanitize_text_field($_POST['company_name']);
    $full_name = sanitize_text_field($_POST['full_name']);
    $phone = sanitize_text_field($_POST['phone']);
    $whatsapp = sanitize_text_field($_POST['whatsapp']) ?: $phone;
    $email = sanitize_email($_POST['email']);
    $full_address = sanitize_textarea_field($_POST['full_address']);
    $map_location = esc_url_raw($_POST['map_location']);
    $landmarks = sanitize_text_field($_POST['landmarks']);
    $product_types = sanitize_textarea_field($_POST['product_types']);
    $business_description = sanitize_textarea_field($_POST['business_description']);
    $working_hours = sanitize_textarea_field($_POST['working_hours']);
    $delivery_service = sanitize_text_field($_POST['delivery_service']);
    $minimum_order = sanitize_text_field($_POST['minimum_order']);
    $website = esc_url_raw($_POST['website']);
    $facebook = esc_url_raw($_POST['facebook']);
    $instagram = esc_url_raw($_POST['instagram']);
    $youtube = esc_url_raw($_POST['youtube']);
    
    // Enhanced validation
    $errors = array();
    
    if (empty($distributor_type) || !in_array($distributor_type, array('wholesale', 'mixed', 'retail'))) {
        $errors[] = 'نوع النشاط مطلوب';
    }
    
    if (empty($governorate)) {
        $errors[] = 'المحافظة مطلوبة';
    }
    
    if (empty($company_name)) {
        $errors[] = 'اسم الشركة مطلوب';
    }
    
    if (empty($full_name)) {
        $errors[] = 'اسم المسؤول مطلوب';
    }
    
    if (empty($phone) || !preg_match('/^(\+20|0)?1[0-2,5]\d{8}$/', preg_replace('/[\s-]/', '', $phone))) {
        $errors[] = 'رقم هاتف صحيح مطلوب';
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = 'بريد إلكتروني صحيح مطلوب';
    }
    
    if (empty($full_address)) {
        $errors[] = 'العنوان الكامل مطلوب';
    }
    
    if (empty($product_types)) {
        $errors[] = 'أنواع المنتجات مطلوبة';
    }
    
    if (!empty($errors)) {
        wp_redirect(add_query_arg('registration', 'missing_fields', wp_get_referer()));
        exit;
    }
    
    // Create new post
    $post_data = array(
        'post_title' => $company_name,
        'post_content' => $business_description,
        'post_type' => $distributor_type,
        'post_status' => 'pending',
        'post_author' => 0,
        'meta_input' => array(
            'full_name' => $full_name,
            'phone' => $phone,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'full_address' => $full_address,
            'map_location' => $map_location,
            'landmarks' => $landmarks,
            'product_types' => $product_types,
            'business_description' => $business_description,
            'working_hours' => $working_hours,
            'delivery_service' => $delivery_service,
            'minimum_order' => $minimum_order,
            'website' => $website,
            'facebook' => $facebook,
            'instagram' => $instagram,
            'youtube' => $youtube,
            'submission_date' => current_time('mysql'),
            'submission_ip' => $_SERVER['REMOTE_ADDR'],
            'featured' => 0,
            'verified' => 0,
            'join_date' => current_time('Y-m-d')
        )
    );
    
    $post_id = wp_insert_post($post_data);
    
    if ($post_id && !is_wp_error($post_id)) {
        // Set taxonomy term
        wp_set_object_terms($post_id, $governorate, 'governorate');
        
        // Send enhanced notification email
        send_registration_notification($post_id, array(
            'company_name' => $company_name,
            'full_name' => $full_name,
            'phone' => $phone,
            'email' => $email,
            'distributor_type' => $distributor_type,
            'governorate' => $governorate
        ));
        
        // Redirect with success
        wp_redirect(add_query_arg('registration', 'success', wp_get_referer()));
        exit;
    } else {
        wp_redirect(add_query_arg('registration', 'error', wp_get_referer()));
        exit;
    }
}
add_action('admin_post_distributor_registration', 'handle_enhanced_distributor_registration');
add_action('admin_post_nopriv_distributor_registration', 'handle_enhanced_distributor_registration');

/**
 * Send enhanced notification email
 */
function send_registration_notification($post_id, $data) {
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $type_names = array(
        'wholesale' => 'جملة',
        'mixed' => 'جملة مختلطة',
        'retail' => 'قطاعي'
    );
    
    $type_name = isset($type_names[$data['distributor_type']]) ? $type_names[$data['distributor_type']] : $data['distributor_type'];
    
    $subject = sprintf('[%s] طلب تسجيل موزع جديد: %s', $site_name, $data['company_name']);
    
    $message = sprintf("
تم تلقي طلب تسجيل موزع جديد في موقع %s

تفاصيل الطلب:
==================
الشركة: %s
المسؤول: %s
النوع: %s
المحافظة: %s
الهاتف: %s
البريد الإلكتروني: %s

لمراجعة الطلب والموافقة عليه:
%s

لحذف الطلب:
%s

تم الإرسال تلقائياً من موقع %s
",
        $site_name,
        $data['company_name'],
        $data['full_name'],
        $type_name,
        $data['governorate'],
        $data['phone'],
        $data['email'],
        admin_url('post.php?post=' . $post_id . '&action=edit'),
        admin_url('post.php?post=' . $post_id . '&action=trash'),
        $site_name
    );
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    wp_mail($admin_email, $subject, $message, $headers);
}

add_action('wp_ajax_distributor_ajax_filter', 'distributor_ajax_filter');
add_action('wp_ajax_nopriv_distributor_ajax_filter', 'distributor_ajax_filter');
function distributor_ajax_filter() {
    check_ajax_referer('distributor_nonce', 'nonce');
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
    $governorate = isset($_POST['governorate']) ? intval($_POST['governorate']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $posts_per_page = 6;
    $args = array(
        'post_type' => array('wholesale', 'retail', 'mixed'),
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        's' => $search,
    );
    if ($type) {
        $args['post_type'] = $type;
    }
    if ($governorate) {
        $args['tax_query'] = array(array(
            'taxonomy' => 'governorate',
            'field' => 'term_id',
            'terms' => $governorate,
        ));
    }
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        echo '<div class="distributors-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/distributor-card');
        }
        echo '</div>';
        if ($query->found_posts > $offset + $posts_per_page) {
            echo '<button id="load-more-distributors" data-offset="' . ($offset + $posts_per_page) . '">تحميل المزيد</button>';
        }
    } else {
        echo '<div class="no-results">لا توجد نتائج مطابقة.</div>';
    }
    wp_die();
}