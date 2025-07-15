<?php
/**
 * Advanced Custom Fields Configuration - Arabic Version
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF Field Groups for Distributors
 */
function register_distributor_acf_fields() {
    if (function_exists('acf_add_local_field_group')) {
        
        // Main Contact Information
        acf_add_local_field_group(array(
            'key' => 'group_distributor_contact',
            'title' => 'معلومات الاتصال',
            'fields' => array(
                array(
                    'key' => 'field_full_name',
                    'label' => 'الاسم الكامل',
                    'name' => 'full_name',
                    'type' => 'text',
                    'required' => 1,
                    'placeholder' => 'أدخل الاسم الكامل للمسؤول',
                ),
                array(
                    'key' => 'field_phone',
                    'label' => 'رقم الهاتف',
                    'name' => 'phone',
                    'type' => 'text',
                    'required' => 1,
                    'placeholder' => '01xxxxxxxxx',
                    'instructions' => 'رقم الهاتف المحمول (سيتم استخدامه للواتساب أيضاً)',
                ),
                array(
                    'key' => 'field_whatsapp',
                    'label' => 'رقم الواتساب',
                    'name' => 'whatsapp',
                    'type' => 'text',
                    'placeholder' => '01xxxxxxxxx',
                    'instructions' => 'إذا كان مختلف عن رقم الهاتف',
                ),
                array(
                    'key' => 'field_email',
                    'label' => 'البريد الإلكتروني',
                    'name' => 'email',
                    'type' => 'email',
                    'required' => 1,
                    'placeholder' => 'example@domain.com',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wholesale',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mixed',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'retail',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
        ));
        
        // Location and Address Information
        acf_add_local_field_group(array(
            'key' => 'group_distributor_location',
            'title' => 'معلومات الموقع والعنوان',
            'fields' => array(
                array(
                    'key' => 'field_full_address',
                    'label' => 'العنوان الكامل',
                    'name' => 'full_address',
                    'type' => 'textarea',
                    'required' => 1,
                    'rows' => 3,
                    'placeholder' => 'العنوان التفصيلي بما في ذلك المدينة والشارع ورقم المبنى',
                ),
                array(
                    'key' => 'field_map_location',
                    'label' => 'موقع الخريطة',
                    'name' => 'map_location',
                    'type' => 'text',
                    'placeholder' => 'رابط خرائط جوجل أو الإحداثيات',
                    'instructions' => 'اختياري - يمكنك إدخال رابط خرائط جوجل أو الإحداثيات',
                ),
                array(
                    'key' => 'field_landmarks',
                    'label' => 'معالم مميزة',
                    'name' => 'landmarks',
                    'type' => 'text',
                    'placeholder' => 'أقرب معلم أو نقطة دالة',
                    'instructions' => 'مثل: بجوار مسجد الرحمن، أمام مدرسة النور',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wholesale',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mixed',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'retail',
                    ),
                ),
            ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
        ));
        
        // Business Information
        acf_add_local_field_group(array(
            'key' => 'group_distributor_business',
            'title' => 'معلومات العمل والنشاط',
            'fields' => array(
                array(
                    'key' => 'field_product_types',
                    'label' => 'أنواع المنتجات',
                    'name' => 'product_types',
                    'type' => 'textarea',
                    'rows' => 4,
                    'placeholder' => 'اذكر أنواع المنتجات التي تتعامل بها',
                    'instructions' => 'مثل: منتجات غذائية، مشروبات، مواد تنظيف، إلخ',
                ),
                array(
                    'key' => 'field_business_description',
                    'label' => 'وصف النشاط التجاري',
                    'name' => 'business_description',
                    'type' => 'textarea',
                    'rows' => 5,
                    'placeholder' => 'وصف مختصر عن نشاطك التجاري وخدماتك',
                ),
                array(
                    'key' => 'field_working_hours',
                    'label' => 'ساعات العمل',
                    'name' => 'working_hours',
                    'type' => 'textarea',
                    'rows' => 3,
                    'placeholder' => 'مثل: من السبت إلى الخميس، من 9 صباحاً إلى 9 مساءً',
                ),
                array(
                    'key' => 'field_delivery_service',
                    'label' => 'خدمة التوصيل',
                    'name' => 'delivery_service',
                    'type' => 'radio',
                    'choices' => array(
                        'available' => 'متوفرة',
                        'not_available' => 'غير متوفرة',
                        'partial' => 'متوفرة لمناطق معينة',
                    ),
                    'default_value' => 'not_available',
                    'layout' => 'horizontal',
                ),
                array(
                    'key' => 'field_minimum_order',
                    'label' => 'الحد الأدنى للطلب',
                    'name' => 'minimum_order',
                    'type' => 'text',
                    'placeholder' => 'مثل: 500 جنيه أو 10 قطع',
                    'instructions' => 'اختياري - الحد الأدنى للطلب أو الكمية',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wholesale',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mixed',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'retail',
                    ),
                ),
            ),
            'menu_order' => 2,
            'position' => 'normal',
            'style' => 'default',
        ));
        
        // Social Media and Website Links
        acf_add_local_field_group(array(
            'key' => 'group_distributor_social',
            'title' => 'وسائل التواصل الاجتماعي والموقع الإلكتروني',
            'fields' => array(
                array(
                    'key' => 'field_website',
                    'label' => 'الموقع الإلكتروني',
                    'name' => 'website',
                    'type' => 'url',
                    'placeholder' => 'https://example.com',
                ),
                array(
                    'key' => 'field_facebook',
                    'label' => 'صفحة الفيسبوك',
                    'name' => 'facebook',
                    'type' => 'url',
                    'placeholder' => 'https://facebook.com/yourpage',
                ),
                array(
                    'key' => 'field_instagram',
                    'label' => 'حساب الإنستغرام',
                    'name' => 'instagram',
                    'type' => 'url',
                    'placeholder' => 'https://instagram.com/youraccount',
                ),
                array(
                    'key' => 'field_twitter',
                    'label' => 'حساب تويتر',
                    'name' => 'twitter',
                    'type' => 'url',
                    'placeholder' => 'https://twitter.com/youraccount',
                ),
                array(
                    'key' => 'field_youtube',
                    'label' => 'قناة اليوتيوب',
                    'name' => 'youtube',
                    'type' => 'url',
                    'placeholder' => 'https://youtube.com/yourchannel',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wholesale',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mixed',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'retail',
                    ),
                ),
            ),
            'menu_order' => 3,
            'position' => 'normal',
            'style' => 'default',
        ));
        
        // Additional Options
        acf_add_local_field_group(array(
            'key' => 'group_distributor_options',
            'title' => 'خيارات إضافية',
            'fields' => array(
                array(
                    'key' => 'field_featured',
                    'label' => 'موزع مميز',
                    'name' => 'featured',
                    'type' => 'true_false',
                    'instructions' => 'تحديد هذا الخيار سيجعل الموزع يظهر في المقدمة',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_verified',
                    'label' => 'موزع موثق',
                    'name' => 'verified',
                    'type' => 'true_false',
                    'instructions' => 'الموزعون الموثقون يحصلون على علامة تميز',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_join_date',
                    'label' => 'تاريخ الانضمام',
                    'name' => 'join_date',
                    'type' => 'date_picker',
                    'instructions' => 'تاريخ انضمام الموزع للدليل',
                    'display_format' => 'd/m/Y',
                    'return_format' => 'Y-m-d',
                ),
                array(
                    'key' => 'field_rating',
                    'label' => 'التقييم',
                    'name' => 'rating',
                    'type' => 'select',
                    'choices' => array(
                        '5' => '5 نجوم - ممتاز',
                        '4' => '4 نجوم - جيد جداً',
                        '3' => '3 نجوم - جيد',
                        '2' => '2 نجوم - مقبول',
                        '1' => '1 نجمة - ضعيف',
                    ),
                    'default_value' => '3',
                    'allow_null' => 1,
                ),
                array(
                    'key' => 'field_notes',
                    'label' => 'ملاحظات إدارية',
                    'name' => 'admin_notes',
                    'type' => 'textarea',
                    'rows' => 3,
                    'placeholder' => 'ملاحظات للإدارة فقط (غير مرئية للزوار)',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wholesale',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'mixed',
                    ),
                ),
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'retail',
                    ),
                ),
            ),
            'menu_order' => 4,
            'position' => 'side',
            'style' => 'default',
        ));
    }
}
add_action('acf/init', 'register_distributor_acf_fields');

/**
 * Auto-populate fields for logged-in users during frontend submission
 */
function distributor_acf_load_field_defaults($field) {
    if (is_user_logged_in() && !is_admin()) {
        $user = wp_get_current_user();
        
        switch($field['name']) {
            case 'email':
                if (empty($field['value'])) {
                    $field['value'] = $user->user_email;
                }
                break;
            case 'full_name':
                if (empty($field['value'])) {
                    $field['value'] = $user->display_name;
                }
                break;
        }
    }
    
    return $field;
}
add_filter('acf/load_field', 'distributor_acf_load_field_defaults');

/**
 * Add custom validation for ACF fields
 */
function distributor_acf_validate_values($valid, $value, $field, $input) {
    if (!$valid) {
        return $valid;
    }
    
    switch($field['name']) {
        case 'phone':
        case 'whatsapp':
            // Egyptian phone number validation
            if (!empty($value) && !preg_match('/^(\+20|0)?1[0-2,5]\d{8}$/', preg_replace('/[\s-]/', '', $value))) {
                $valid = 'يرجى إدخال رقم هاتف مصري صحيح';
            }
            break;
            
        case 'website':
        case 'facebook':
        case 'instagram':
        case 'twitter':
        case 'youtube':
            // URL validation
            if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                $valid = 'يرجى إدخال رابط صحيح';
            }
            break;
    }
    
    return $valid;
}
add_filter('acf/validate_value', 'distributor_acf_validate_values', 10, 4);

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_distributor_fields',
    'title' => 'بيانات الموزع',
    'fields' => array(
        array(
            'key' => 'field_full_name',
            'label' => 'اسم الموزع',
            'name' => 'full_name',
            'type' => 'text',
            'required' => 1,
        ),
        array(
            'key' => 'field_phone',
            'label' => 'رقم الهاتف',
            'name' => 'phone',
            'type' => 'text',
            'required' => 1,
        ),
        array(
            'key' => 'field_whatsapp',
            'label' => 'رقم واتساب',
            'name' => 'whatsapp',
            'type' => 'text',
            'instructions' => 'اتركه فارغًا لاستخدام رقم الهاتف',
        ),
        array(
            'key' => 'field_email',
            'label' => 'البريد الإلكتروني',
            'name' => 'email',
            'type' => 'email',
        ),
        array(
            'key' => 'field_full_address',
            'label' => 'العنوان الكامل',
            'name' => 'full_address',
            'type' => 'textarea',
        ),
        array(
            'key' => 'field_governorate',
            'label' => 'المحافظة',
            'name' => 'governorate',
            'type' => 'taxonomy',
            'taxonomy' => 'governorate',
            'field_type' => 'select',
            'required' => 1,
        ),
        array(
            'key' => 'field_location',
            'label' => 'الموقع على الخريطة',
            'name' => 'location',
            'type' => 'google_map',
            'instructions' => 'حدد الموقع على الخريطة أو اتركه فارغًا لعرض خريطة المحافظة فقط',
        ),
        array(
            'key' => 'field_social_links',
            'label' => 'روابط التواصل الاجتماعي',
            'name' => 'social_links',
            'type' => 'repeater',
            'sub_fields' => array(
                array(
                    'key' => 'field_social_type',
                    'label' => 'نوع الرابط',
                    'name' => 'type',
                    'type' => 'select',
                    'choices' => array(
                        'facebook' => 'فيسبوك',
                        'instagram' => 'انستجرام',
                        'website' => 'موقع إلكتروني',
                    ),
                ),
                array(
                    'key' => 'field_social_url',
                    'label' => 'الرابط',
                    'name' => 'url',
                    'type' => 'url',
                ),
            ),
        ),
        array(
            'key' => 'field_business_description',
            'label' => 'وصف النشاط',
            'name' => 'business_description',
            'type' => 'textarea',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => 'IN',
                'value' => array('wholesale', 'retail', 'mixed'),
            ),
        ),
    ),
    'style' => 'seamless',
    'position' => 'acf_after_title',
));

endif;