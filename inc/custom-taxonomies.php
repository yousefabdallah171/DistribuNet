<?php
/**
 * Register Custom Taxonomies - Arabic Version
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Governorate Taxonomy
 */
function distributor_register_governorate_taxonomy() {
    $labels = array(
        'name'              => 'المحافظات',
        'singular_name'     => 'المحافظة',
        'search_items'      => 'بحث في المحافظات',
        'all_items'         => 'كل المحافظات',
        'parent_item'       => 'المحافظة الرئيسية',
        'parent_item_colon' => 'المحافظة الرئيسية:',
        'edit_item'         => 'تعديل المحافظة',
        'update_item'       => 'تحديث المحافظة',
        'add_new_item'      => 'إضافة محافظة جديدة',
        'new_item_name'     => 'اسم المحافظة الجديدة',
        'menu_name'         => 'المحافظات',
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'governorate'),
        'show_in_rest'      => true,
    );
    register_taxonomy('governorate', array('wholesale', 'retail', 'mixed'), $args);
}
add_action('init', 'distributor_register_governorate_taxonomy');

/**
 * Add default Egyptian governorates with Arabic names
 */
function add_default_governorates() {
    $governorates = array(
        'cairo' => array(
            'name' => 'القاهرة',
            'description' => 'العاصمة المصرية ومركز الأعمال الرئيسي'
        ),
        'alexandria' => array(
            'name' => 'الإسكندرية',
            'description' => 'عروس البحر المتوسط والمركز التجاري الثاني'
        ),
        'giza' => array(
            'name' => 'الجيزة',
            'description' => 'محافظة الأهرامات والمركز الصناعي المهم'
        ),
        'dakahlia' => array(
            'name' => 'الدقهلية',
            'description' => 'قلب الدلتا والمركز الزراعي المهم'
        ),
        'sharqia' => array(
            'name' => 'الشرقية',
            'description' => 'بوابة مصر الشرقية ومركز زراعي مهم'
        ),
        'qalyubia' => array(
            'name' => 'القليوبية',
            'description' => 'محافظة صناعية مهمة شمال القاهرة'
        ),
        'kafr-el-sheikh' => array(
            'name' => 'كفر الشيخ',
            'description' => 'مركز زراعي مهم في شمال الدلتا'
        ),
        'gharbia' => array(
            'name' => 'الغربية',
            'description' => 'مركز النسيج والصناعات التقليدية'
        ),
        'monufia' => array(
            'name' => 'المنوفية',
            'description' => 'مركز زراعي وصناعي في الدلتا'
        ),
        'beheira' => array(
            'name' => 'البحيرة',
            'description' => 'مركز زراعي مهم غرب الدلتا'
        ),
        'ismailia' => array(
            'name' => 'الإسماعيلية',
            'description' => 'مدينة قناة السويس والتجارة العالمية'
        ),
        'suez' => array(
            'name' => 'السويس',
            'description' => 'مدينة القناة والصناعات البترولية'
        ),
        'port-said' => array(
            'name' => 'بورسعيد',
            'description' => 'المدينة الباسلة ومركز التجارة الحرة'
        ),
        'damietta' => array(
            'name' => 'دمياط',
            'description' => 'مدينة الأثاث والصناعات الخشبية'
        ),
        'north-sinai' => array(
            'name' => 'شمال سيناء',
            'description' => 'البوابة الشرقية لمصر'
        ),
        'south-sinai' => array(
            'name' => 'جنوب سيناء',
            'description' => 'أرض الفيروز والسياحة الدينية'
        ),
        'fayoum' => array(
            'name' => 'الفيوم',
            'description' => 'واحة مصر الجميلة والمركز الزراعي'
        ),
        'beni-suef' => array(
            'name' => 'بني سويف',
            'description' => 'مركز صناعي وزراعي مهم'
        ),
        'minya' => array(
            'name' => 'المنيا',
            'description' => 'عروس الصعيد والمركز الزراعي'
        ),
        'assiut' => array(
            'name' => 'أسيوط',
            'description' => 'قلب الصعيد والمركز التجاري'
        ),
        'sohag' => array(
            'name' => 'سوهاج',
            'description' => 'مركز الحضارة الفرعونية في الصعيد'
        ),
        'qena' => array(
            'name' => 'قنا',
            'description' => 'مركز صناعي وزراعي في الصعيد'
        ),
        'luxor' => array(
            'name' => 'الأقصر',
            'description' => 'متحف العالم المفتوح ومركز السياحة'
        ),
        'aswan' => array(
            'name' => 'أسوان',
            'description' => 'بوابة مصر الجنوبية ومركز الطاقة'
        ),
        'red-sea' => array(
            'name' => 'البحر الأحمر',
            'description' => 'مركز السياحة البحرية والتعدين'
        ),
        'new-valley' => array(
            'name' => 'الوادي الجديد',
            'description' => 'أكبر محافظات مصر مساحة'
        ),
        'matrouh' => array(
            'name' => 'مطروح',
            'description' => 'ساحل البحر المتوسط الجميل'
        )
    );
    
    foreach ($governorates as $slug => $gov_data) {
        if (!term_exists($gov_data['name'], 'governorate')) {
            $term = wp_insert_term(
                $gov_data['name'],
                'governorate',
                array(
                    'description' => $gov_data['description'],
                    'slug' => $slug
                )
            );
            
            if (!is_wp_error($term)) {
                // Add custom meta for governorate if needed
                update_term_meta($term['term_id'], 'governorate_code', strtoupper($slug));
                update_term_meta($term['term_id'], 'region', get_governorate_region($slug));
            }
        }
    }
}
add_action('init', 'add_default_governorates');

/**
 * Get governorate region (for grouping purposes)
 */
function get_governorate_region($slug) {
    $regions = array(
        // Greater Cairo
        'cairo' => 'القاهرة الكبرى',
        'giza' => 'القاهرة الكبرى',
        'qalyubia' => 'القاهرة الكبرى',
        
        // Alexandria
        'alexandria' => 'الإسكندرية',
        'beheira' => 'الإسكندرية',
        'matrouh' => 'الإسكندرية',
        
        // Delta
        'dakahlia' => 'الدلتا',
        'sharqia' => 'الدلتا',
        'kafr-el-sheikh' => 'الدلتا',
        'gharbia' => 'الدلتا',
        'monufia' => 'الدلتا',
        'damietta' => 'الدلتا',
        
        // Canal
        'ismailia' => 'المنطقة القناة',
        'suez' => 'المنطقة القناة',
        'port-said' => 'المنطقة القناة',
        
        // Sinai
        'north-sinai' => 'سيناء',
        'south-sinai' => 'سيناء',
        
        // Upper Egypt
        'fayoum' => 'الصعيد',
        'beni-suef' => 'الصعيد',
        'minya' => 'الصعيد',
        'assiut' => 'الصعيد',
        'sohag' => 'الصعيد',
        'qena' => 'الصعيد',
        'luxor' => 'الصعيد',
        'aswan' => 'الصعيد',
        
        // Others
        'red-sea' => 'مناطق أخرى',
        'new-valley' => 'مناطق أخرى',
    );
    
    return isset($regions[$slug]) ? $regions[$slug] : 'مناطق أخرى';
}

/**
 * Add custom columns to governorate admin
 */
function governorate_custom_columns($columns) {
    $columns['region'] = 'المنطقة';
    $columns['distributors_count'] = 'عدد الموزعين';
    return $columns;
}
add_filter('manage_edit-governorate_columns', 'governorate_custom_columns');

/**
 * Populate custom columns
 */
function governorate_custom_column_content($content, $column_name, $term_id) {
    switch ($column_name) {
        case 'region':
            $region = get_term_meta($term_id, 'region', true);
            return $region ? $region : 'غير محدد';
            
        case 'distributors_count':
            $term = get_term($term_id, 'governorate');
            $counts = array();
            
            // Count distributors by type
            $post_types = array('wholesale', 'mixed', 'retail');
            $total = 0;
            
            foreach ($post_types as $post_type) {
                $query = new WP_Query(array(
                    'post_type' => $post_type,
                    'post_status' => 'publish',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'governorate',
                            'field' => 'term_id',
                            'terms' => $term_id,
                        ),
                    ),
                    'posts_per_page' => -1,
                    'fields' => 'ids'
                ));
                
                $count = $query->found_posts;
                if ($count > 0) {
                    $type_names = array(
                        'wholesale' => 'جملة',
                        'mixed' => 'مختلط',
                        'retail' => 'قطاعي'
                    );
                    $counts[] = $count . ' ' . $type_names[$post_type];
                    $total += $count;
                }
            }
            
            if ($total > 0) {
                return sprintf('<strong>%d</strong><br><small>%s</small>', $total, implode(' | ', $counts));
            }
            return '0';
    }
    
    return $content;
}
add_filter('manage_governorate_custom_column', 'governorate_custom_column_content', 10, 3);

/**
 * Add governorate meta fields for admin
 */
function governorate_add_form_fields() {
    ?>
    <div class="form-field">
        <label for="governorate_code">كود المحافظة</label>
        <input type="text" name="governorate_code" id="governorate_code" value="" />
        <p class="description">كود المحافظة باللغة الإنجليزية (اختياري)</p>
    </div>
    
    <div class="form-field">
        <label for="region">المنطقة</label>
        <select name="region" id="region">
            <option value="">اختر المنطقة</option>
            <option value="القاهرة الكبرى">القاهرة الكبرى</option>
            <option value="الإسكندرية">الإسكندرية</option>
            <option value="الدلتا">الدلتا</option>
            <option value="المنطقة القناة">المنطقة القناة</option>
            <option value="سيناء">سيناء</option>
            <option value="الصعيد">الصعيد</option>
            <option value="مناطق أخرى">مناطق أخرى</option>
        </select>
        <p class="description">اختر المنطقة الجغرافية للمحافظة</p>
    </div>
    <?php
}
add_action('governorate_add_form_fields', 'governorate_add_form_fields');

/**
 * Edit governorate form fields
 */
function governorate_edit_form_fields($term) {
    $governorate_code = get_term_meta($term->term_id, 'governorate_code', true);
    $region = get_term_meta($term->term_id, 'region', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="governorate_code">كود المحافظة</label></th>
        <td>
            <input type="text" name="governorate_code" id="governorate_code" value="<?php echo esc_attr($governorate_code); ?>" />
            <p class="description">كود المحافظة باللغة الإنجليزية (اختياري)</p>
        </td>
    </tr>
    
    <tr class="form-field">
        <th scope="row"><label for="region">المنطقة</label></th>
        <td>
            <select name="region" id="region">
                <option value="">اختر المنطقة</option>
                <option value="القاهرة الكبرى" <?php selected($region, 'القاهرة الكبرى'); ?>>القاهرة الكبرى</option>
                <option value="الإسكندرية" <?php selected($region, 'الإسكندرية'); ?>>الإسكندرية</option>
                <option value="الدلتا" <?php selected($region, 'الدلتا'); ?>>الدلتا</option>
                <option value="المنطقة القناة" <?php selected($region, 'المنطقة القناة'); ?>>المنطقة القناة</option>
                <option value="سيناء" <?php selected($region, 'سيناء'); ?>>سيناء</option>
                <option value="الصعيد" <?php selected($region, 'الصعيد'); ?>>الصعيد</option>
                <option value="مناطق أخرى" <?php selected($region, 'مناطق أخرى'); ?>>مناطق أخرى</option>
            </select>
            <p class="description">اختر المنطقة الجغرافية للمحافظة</p>
        </td>
    </tr>
    <?php
}
add_action('governorate_edit_form_fields', 'governorate_edit_form_fields');

/**
 * Save governorate meta fields
 */
function save_governorate_meta_fields($term_id) {
    if (isset($_POST['governorate_code'])) {
        update_term_meta($term_id, 'governorate_code', sanitize_text_field($_POST['governorate_code']));
    }
    
    if (isset($_POST['region'])) {
        update_term_meta($term_id, 'region', sanitize_text_field($_POST['region']));
    }
}
add_action('created_governorate', 'save_governorate_meta_fields');
add_action('edited_governorate', 'save_governorate_meta_fields');

/**
 * Get governorates by region
 */
function get_governorates_by_region($region = '') {
    $args = array(
        'taxonomy' => 'governorate',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    );
    
    if (!empty($region)) {
        $args['meta_query'] = array(
            array(
                'key' => 'region',
                'value' => $region,
                'compare' => '='
            )
        );
    }
    
    return get_terms($args);
}

/**
 * Custom taxonomy archive title
 */
function governorate_archive_title($title) {
    if (is_tax('governorate')) {
        $term = get_queried_object();
        $title = sprintf('موزعين محافظة %s', $term->name);
        
        // Add region info if available
        $region = get_term_meta($term->term_id, 'region', true);
        if ($region) {
            $title .= sprintf(' - منطقة %s', $region);
        }
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'governorate_archive_title');