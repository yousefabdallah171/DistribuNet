<?php
/**
 * Admin Customizations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize Admin Columns for Custom Post Types
 */
function customize_distributor_admin_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        
        // Add custom columns after title
        if ($key === 'title') {
            $new_columns['contact_name'] = __('Contact Person', 'distributor-connect');
            $new_columns['phone'] = __('Phone', 'distributor-connect');
            $new_columns['governorate'] = __('Governorate', 'distributor-connect');
            $new_columns['status'] = __('Status', 'distributor-connect');
        }
    }
    
    return $new_columns;
}
add_filter('manage_wholesale_posts_columns', 'customize_distributor_admin_columns');
add_filter('manage_mixed_posts_columns', 'customize_distributor_admin_columns');
add_filter('manage_retail_posts_columns', 'customize_distributor_admin_columns');

/**
 * Populate Custom Admin Columns
 */
function populate_distributor_admin_columns($column, $post_id) {
    switch ($column) {
        case 'contact_name':
            $contact_name = get_post_meta($post_id, 'contact_name', true);
            echo $contact_name ? esc_html($contact_name) : '—';
            break;
            
        case 'phone':
            $phone = get_post_meta($post_id, 'phone', true);
            if ($phone) {
                echo '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
            } else {
                echo '—';
            }
            break;
            
        case 'governorate':
            $terms = get_the_terms($post_id, 'governorate');
            if ($terms && !is_wp_error($terms)) {
                $term_names = array_map(function($term) use ($post_id) {
                    return '<a href="' . admin_url('edit.php?post_type=' . get_post_type($post_id) . '&governorate=' . $term->slug) . '">' . esc_html($term->name) . '</a>';
                }, $terms);
                echo implode(', ', $term_names);
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $status = get_post_status($post_id);
            $status_labels = array(
                'publish' => '<span style="color: green;">✓ Published</span>',
                'pending' => '<span style="color: orange;">⏳ Pending Review</span>',
                'draft' => '<span style="color: gray;">📝 Draft</span>'
            );
            echo isset($status_labels[$status]) ? $status_labels[$status] : esc_html($status);
            break;
    }
}
add_action('manage_wholesale_posts_custom_column', 'populate_distributor_admin_columns', 10, 2);
add_action('manage_mixed_posts_custom_column', 'populate_distributor_admin_columns', 10, 2);
add_action('manage_retail_posts_custom_column', 'populate_distributor_admin_columns', 10, 2);

/**
 * Add Admin Filters
 */
function add_distributor_admin_filters() {
    global $typenow;
    
    if (in_array($typenow, array('wholesale', 'mixed', 'retail'))) {
        // Governorate filter
        $selected_governorate = isset($_GET['governorate']) ? $_GET['governorate'] : '';
        $governorates = get_terms(array(
            'taxonomy' => 'governorate',
            'hide_empty' => true
        ));
        
        if (!empty($governorates)) {
            echo '<select name="governorate">';
            echo '<option value="">' . __('All Governorates', 'distributor-connect') . '</option>';
            foreach ($governorates as $governorate) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    $governorate->slug,
                    selected($selected_governorate, $governorate->slug, false),
                    $governorate->name
                );
            }
            echo '</select>';
        }
    }
}
add_action('restrict_manage_posts', 'add_distributor_admin_filters');

/**
 * Add Dashboard Widget for Pending Distributors
 */
function add_pending_distributors_dashboard_widget() {
    wp_add_dashboard_widget(
        'pending_distributors_widget',
        __('Pending Distributor Registrations', 'distributor-connect'),
        'display_pending_distributors_widget'
    );
}
add_action('wp_dashboard_setup', 'add_pending_distributors_dashboard_widget');

/**
 * Display Pending Distributors Widget
 */
function display_pending_distributors_widget() {
    $pending_posts = get_posts(array(
        'post_type' => array('wholesale', 'mixed', 'retail'),
        'post_status' => 'pending',
        'numberposts' => 10,
        'meta_query' => array(
            array(
                'key' => 'contact_name',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    if (empty($pending_posts)) {
        echo '<p>' . __('No pending registrations.', 'distributor-connect') . '</p>';
        return;
    }
    
    echo '<ul>';
    foreach ($pending_posts as $post) {
        $contact_name = get_post_meta($post->ID, 'contact_name', true);
        $phone = get_post_meta($post->ID, 'phone', true);
        $post_type_label = get_post_type_object($post->post_type)->labels->singular_name;
        
        echo '<li>';
        echo '<strong><a href="' . get_edit_post_link($post->ID) . '">' . esc_html($post->post_title) . '</a></strong><br>';
        echo '<small>' . esc_html($post_type_label) . ' • ' . esc_html($contact_name);
        if ($phone) {
            echo ' • ' . esc_html($phone);
        }
        echo ' • ' . human_time_diff(strtotime($post->post_date)) . ' ago</small>';
        echo '</li>';
    }
    echo '</ul>';
    
    echo '<p><a href="' . admin_url('edit.php?post_type=wholesale&post_status=pending') . '" class="button">' . __('View All Pending', 'distributor-connect') . '</a></p>';
}

/**
 * Add Custom Admin Notices
 */
function distributor_admin_notices() {
    $screen = get_current_screen();
    
    if (in_array($screen->post_type, array('wholesale', 'mixed', 'retail'))) {
        // Check if ACF is active
        if (!class_exists('ACF')) {
            echo '<div class="notice notice-warning"><p>';
            echo __('<strong>Advanced Custom Fields (ACF) is not active.</strong> Some features of the Distributor theme may not work properly. Please install and activate ACF.', 'distributor-connect');
            echo '</p></div>';
        }
    }
}
add_action('admin_notices', 'distributor_admin_notices');