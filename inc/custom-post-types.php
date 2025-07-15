<?php
// تسجيل أنواع المنشورات المخصصة للموزعين
function distributor_register_post_types() {
    $types = array(
        'wholesale' => 'موزع جملة',
        'retail'    => 'موزع قطاعي',
        'mixed'     => 'موزع مختلط',
    );
    foreach ($types as $type => $label) {
        $labels = array(
            'name'               => $label . 'ين',
            'singular_name'      => $label,
            'menu_name'          => $label . 'ين',
            'name_admin_bar'     => $label,
            'add_new'            => 'إضافة جديد',
            'add_new_item'       => 'إضافة ' . $label . ' جديد',
            'edit_item'          => 'تعديل ' . $label,
            'new_item'           => $label . ' جديد',
            'view_item'          => 'عرض ' . $label,
            'search_items'       => 'بحث عن ' . $label . 'ين',
            'not_found'          => 'لا يوجد ' . $label . 'ين',
            'not_found_in_trash' => 'لا يوجد ' . $label . 'ين في سلة المهملات',
            'all_items'          => 'كل ' . $label . 'ين',
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'rewrite'            => array('slug' => $type),
            'supports'           => array('title'),
            'menu_icon'          => 'dashicons-store',
            'show_in_rest'       => true,
        );
        register_post_type($type, $args);
    }
}
add_action('init', 'distributor_register_post_types');

/**
 * Customize post type messages
 */
function distributor_post_updated_messages($messages) {
    $post = get_post();
    
    $messages['wholesale'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => 'تم تحديث موزع الجملة.',
        2  => 'تم تحديث الحقل المخصص.',
        3  => 'تم حذف الحقل المخصص.',
        4  => 'تم تحديث موزع الجملة.',
        5  => isset($_GET['revision']) ? sprintf('تم استرجاع موزع الجملة من المراجعة المؤرخة %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => 'تم نشر موزع الجملة.',
        7  => 'تم حفظ موزع الجملة.',
        8  => 'تم إرسال موزع الجملة للمراجعة.',
        9  => sprintf('تم جدولة موزع الجملة للنشر في: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
        10 => 'تم حفظ مسودة موزع الجملة.',
    );
    
    $messages['mixed'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => 'تم تحديث موزع الجملة المختلطة.',
        2  => 'تم تحديث الحقل المخصص.',
        3  => 'تم حذف الحقل المخصص.',
        4  => 'تم تحديث موزع الجملة المختلطة.',
        5  => isset($_GET['revision']) ? sprintf('تم استرجاع موزع الجملة المختلطة من المراجعة المؤرخة %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => 'تم نشر موزع الجملة المختلطة.',
        7  => 'تم حفظ موزع الجملة المختلطة.',
        8  => 'تم إرسال موزع الجملة المختلطة للمراجعة.',
        9  => sprintf('تم جدولة موزع الجملة المختلطة للنشر في: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
        10 => 'تم حفظ مسودة موزع الجملة المختلطة.',
    );
    
    $messages['retail'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => 'تم تحديث موزع القطاعي.',
        2  => 'تم تحديث الحقل المخصص.',
        3  => 'تم حذف الحقل المخصص.',
        4  => 'تم تحديث موزع القطاعي.',
        5  => isset($_GET['revision']) ? sprintf('تم استرجاع موزع القطاعي من المراجعة المؤرخة %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => 'تم نشر موزع القطاعي.',
        7  => 'تم حفظ موزع القطاعي.',
        8  => 'تم إرسال موزع القطاعي للمراجعة.',
        9  => sprintf('تم جدولة موزع القطاعي للنشر في: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
        10 => 'تم حفظ مسودة موزع القطاعي.',
    );
    
    return $messages;
}
add_filter('post_updated_messages', 'distributor_post_updated_messages');

/**
 * Set default content for new distributors
 */
function distributor_default_content($content, $post) {
    if (in_array($post->post_type, array('wholesale', 'mixed', 'retail')) && empty($content)) {
        $type_names = array(
            'wholesale' => 'الجملة',
            'mixed' => 'الجملة المختلطة',
            'retail' => 'القطاعي'
        );
        
        $type_name = isset($type_names[$post->post_type]) ? $type_names[$post->post_type] : '';
        
        $content = sprintf(
            'أهلاً وسهلاً بكم في %s - موزع %s معتمد.

نحن نقدم خدماتنا بأعلى مستويات الجودة والاحترافية.

للتواصل والاستفسار، يرجى الاتصال بنا على الأرقام المذكورة أدناه.',
            $post->post_title,
            $type_name
        );
    }
    
    return $content;
}
add_filter('default_content', 'distributor_default_content', 10, 2);

/**
 * Auto-set featured image for distributors without one
 */
function distributor_auto_featured_image($post_id) {
    $post_type = get_post_type($post_id);
    
    if (in_array($post_type, array('wholesale', 'mixed', 'retail'))) {
        if (!has_post_thumbnail($post_id)) {
            // You can set default images based on post type
            $default_images = array(
                'wholesale' => 'wholesale-default.jpg',
                'mixed' => 'mixed-default.jpg',
                'retail' => 'retail-default.jpg'
            );
            
            // This is where you'd set a default featured image
            // For now, we'll just add a filter hook for customization
            do_action('distributor_set_default_featured_image', $post_id, $post_type);
        }
    }
}
add_action('save_post', 'distributor_auto_featured_image');

/**
 * Flush rewrite rules on theme activation
 */
function distributor_connect_flush_rewrites() {
    distributor_register_post_types();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'distributor_connect_flush_rewrites');