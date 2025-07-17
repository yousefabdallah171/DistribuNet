<?php
/**
 * Fields Manager
 *
 * @package    wp-listings-directory
 * @author     Habq
 * @license    GNU General Public License, version 3
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class WP_Listings_Directory_Fields_Manager {

	public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'register_page' ), 1 );
        add_action( 'init', array(__CLASS__, 'init_hook'), 10 );
	}

    public static function register_page() {
        add_submenu_page( 'edit.php?post_type=listing', __( 'Fields Manager', 'wp-listings-directory' ), __( 'Fields Manager', 'wp-listings-directory' ), 'manage_options', 'listing-manager-fields-manager', array( __CLASS__, 'output' ) );
    }

    public static function init_hook() {
        // Ajax endpoints.
        add_action( 'wpld_ajax_wp_listings_directory_custom_field_html', array( __CLASS__, 'custom_field_html' ) );

        add_action( 'wpld_ajax_wp_listings_directory_custom_field_available_html', array( __CLASS__, 'custom_field_available_html' ) );

        // compatible handlers.
        // custom fields
        add_action( 'wp_ajax_wp_listings_directory_custom_field_html', array( __CLASS__, 'custom_field_html' ) );
        add_action( 'wp_ajax_nopriv_wp_listings_directory_custom_field_html', array( __CLASS__, 'custom_field_html' ) );

        add_action( 'wp_ajax_wp_listings_directory_custom_field_available_html', array( __CLASS__, 'custom_field_available_html' ) );
        add_action( 'wp_ajax_nopriv_wp_listings_directory_custom_field_available_html', array( __CLASS__, 'custom_field_available_html' ) );

        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'scripts' ), 1 );
    }

    public static function scripts() {
        wp_enqueue_style('font-awesome', WP_LISTINGS_DIRECTORY_PLUGIN_URL . 'assets/css/font-awesome.css');

        // icon
        wp_enqueue_style('jquery-fonticonpicker', WP_LISTINGS_DIRECTORY_PLUGIN_URL. 'assets/admin/jquery.fonticonpicker.min.css', array(), '1.0');
        wp_enqueue_style('jquery-fonticonpicker-bootstrap', WP_LISTINGS_DIRECTORY_PLUGIN_URL. 'assets/admin/jquery.fonticonpicker.bootstrap.min.css', array(), '1.0');
        wp_enqueue_script('jquery-fonticonpicker', WP_LISTINGS_DIRECTORY_PLUGIN_URL. 'assets/admin/jquery.fonticonpicker.min.js', array(), '1.0', true);

        wp_enqueue_style('wp-listings-directory-custom-field-css', WP_LISTINGS_DIRECTORY_PLUGIN_URL . 'assets/admin/style.css');
        wp_register_script('wp-listings-directory-custom-field', WP_LISTINGS_DIRECTORY_PLUGIN_URL.'assets/admin/functions.js', array('jquery', 'wp-color-picker'), '', true);

        $args = array(
            'plugin_url' => WP_LISTINGS_DIRECTORY_PLUGIN_URL,
            'ajax_url' => admin_url('admin-ajax.php'),
        );
        wp_localize_script('wp-listings-directory-custom-field', 'wp_listings_directory_customfield_common_vars', $args);
        wp_enqueue_script('wp-listings-directory-custom-field');

        wp_enqueue_script('jquery-ui-sortable');
    }

    public static function output() {
        $prefix = WP_LISTINGS_DIRECTORY_LISTING_PREFIX;
        self::save();
        ?>
        <h1><?php echo esc_html__('Fields manager', 'wp-listings-directory'); ?></h1>

        <form class="listing-manager-options" method="post" action="">
            
            <button type="submit" class="button button-primary" name="updateListingFieldManager"><?php esc_html_e('Update', 'wp-listings-directory'); ?></button>
            
            <?php

            $rand_id = rand(123, 9878787);
            $default_fields = self::get_all_field_types();

            $available_fields = self::get_all_types_fields_available();
            $required_types = self::get_all_types_fields_required();

            $custom_all_fields_saved_data = self::get_custom_fields_data();

            ?>
            <div class="custom-fields-wrapper clearfix">
                            
                <div class="wp-listings-directory-custom-field-form" id="wp-listings-directory-custom-field-form-<?php echo esc_attr($rand_id); ?>">
                    <div class="box-wrapper">
                        <h3 class="title"><?php echo esc_html('List of Fields', 'wp-listings-directory'); ?></h3>
                        <ul id="foo<?php echo esc_attr($rand_id); ?>" class="block__list block__list_words"> 
                            <?php

                            $count_node = 1000;
                            $output = '';
                            $all_fields_name_count = 0;
                            $disabled_fields = array();

                            if (is_array($custom_all_fields_saved_data) && sizeof($custom_all_fields_saved_data) > 0) {
                                $field_names_counter = 0;
                                $types = self::get_all_field_type_keys();
                                foreach ($custom_all_fields_saved_data as $key => $custom_field_saved_data) {
                                    $all_fields_name_count++;
                                    
                                    $li_rand_id = rand(454, 999999);

                                    $output .= '<li class="custom-field-class-' . $li_rand_id . '">';

                                    $fieldtype = $custom_field_saved_data['type'];

                                    $delete = true;
                                    $drfield_values = self::get_field_id($fieldtype, $required_types);
                                    $dvfield_values = self::get_field_id($fieldtype, $available_fields);
                                    if ( !empty($drfield_values) ) {
                                        $count_node ++;
                                        
                                        $delete = false;
                                        $field_values = wp_parse_args( $custom_field_saved_data, $drfield_values);
                                        if ( in_array( $fieldtype, array( $prefix.'title', $prefix.'expiry_date', $prefix.'featured' ) ) ) {
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_simple_html', $fieldtype, $count_node, $field_values);
                                        } elseif ( in_array( $fieldtype, array( $prefix.'description' ) ) ) {
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_description_html', $fieldtype, $count_node, $field_values);
                                        } else {
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_'.$fieldtype.'_html', $fieldtype, $count_node, $field_values);
                                        }
                                    } elseif ( !empty($dvfield_values) ) {
                                        $count_node ++;
                                        $field_values = wp_parse_args( $custom_field_saved_data, $dvfield_values);

                                        $dtypes = apply_filters( 'wp_listings_directory_list_simple_type', array( $prefix.'featured', $prefix.'year', $prefix.'address', $prefix.'map_location', $prefix.'price_from', $prefix.'price_to', $prefix.'price_range', $prefix.'tagline', $prefix.'hours', $prefix.'menu_prices', $prefix.'faq', $prefix.'socials', $prefix.'website', $prefix.'email', $prefix.'location', $prefix.'phone', $prefix.'video' ) );

                                        if ( in_array( $fieldtype, $dtypes) ) {
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_simple_html', $fieldtype, $count_node, $field_values);

                                        } elseif ( in_array( $fieldtype, apply_filters( 'wp_listings_directory_list_tax_type', array( $prefix.'category', $prefix.'type', $prefix.'location', $prefix.'feature' ) ) ) ) {
                                            
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_tax_html', $fieldtype, $count_node, $field_values);

                                        } elseif ( in_array($fieldtype, apply_filters( 'wp_listings_directory_list_files_type', array( $prefix.'featured_image', $prefix.'logo', $prefix.'gallery', $prefix.'attachments' ) ) )) {
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_files_html', $fieldtype, $count_node, $field_values);
                                        } else {
                                            $output .= apply_filters('wp_listings_directory_custom_field_available_'.$fieldtype.'_html', $fieldtype, $count_node, $field_values);
                                        }
                                        $disabled_fields[] = $fieldtype;
                                    } elseif ( in_array($fieldtype, $types) ) {
                                        $count_node ++;
                                        if ( in_array( $fieldtype, array('text', 'textarea', 'wysiwyg', 'number', 'url', 'email', 'checkbox') ) ) {
                                            $output .= apply_filters('wp_listings_directory_custom_field_text_html', $fieldtype, $count_node, $custom_field_saved_data);
                                        } elseif ( in_array( $fieldtype, array('select', 'multiselect', 'radio') ) ) {
                                            $output .= apply_filters('wp_listings_directory_custom_field_opts_html', $fieldtype, $count_node, $custom_field_saved_data);
                                        } else {
                                            $output .= apply_filters('wp_listings_directory_custom_field_'.$fieldtype.'_html', $fieldtype, $count_node, $custom_field_saved_data);
                                        }
                                    }

                                    $output .= apply_filters('wp_listings_directory_custom_field_actions_html', $li_rand_id, $count_node, $fieldtype, $delete);
                                    $output .= '</li>';
                                }
                            } else {
                                foreach ($required_types as $field_values) {
                                    $count_node ++;
                                    $li_rand_id = rand(454, 999999);
                                    $output .= '<li class="custom-field-class-' . $li_rand_id . '">';
                                    $output .= apply_filters('wp_listings_directory_custom_field_available_simple_html', $field_values['id'], $count_node, $field_values);

                                    $output .= apply_filters('wp_listings_directory_custom_field_actions_html', $li_rand_id, $count_node, $field_values['id'], false);
                                    $output .= '</li>';
                                }
                            }
                            echo force_balance_tags($output);
                            ?>
                        </ul>

                        <button type="submit" class="button button-primary" name="updateListingFieldManager"><?php esc_html_e('Update', 'wp-listings-directory'); ?></button>

                        <div class="input-field-types">
                            <h3><?php esc_html_e('Create a custom field', 'wp-listings-directory'); ?></h3>
                            <div class="input-field-types-wrapper">
                                <select name="field-types" class="wp-listings-directory-field-types">
                                    <?php foreach ($default_fields as $group) { ?>
                                        <optgroup label="<?php echo esc_attr($group['title']); ?>">
                                            <?php foreach ($group['fields'] as $value => $label) { ?>
                                                <option value="<?php echo esc_attr($value); ?>"><?php echo $label; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                                <button type="button" class="button btn-add-field" data-randid="<?php echo esc_attr($rand_id); ?>"><?php esc_html_e('Create', 'wp-listings-directory'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wp-listings-directory-form-field-list wp-listings-directory-list">
                    <h3 class="title"><?php esc_html_e('Available Fields', 'wp-listings-directory'); ?></h3>
                    <?php if ( !empty($available_fields) ) { ?>
                        <ul>
                            <?php foreach ($available_fields as $field) { ?>
                                <li class="<?php echo esc_attr($field['id']); ?> <?php echo esc_attr(in_array($field['id'], $disabled_fields) ? 'disabled' : ''); ?>">
                                    <a class="wp-listings-directory-custom-field-add-available-field" data-fieldtype="<?php echo esc_attr($field['id']); ?>" data-randid="<?php echo esc_attr($rand_id); ?>" href="javascript:void(0);" data-fieldlabel="<?php echo esc_attr($field['name']); ?>">
                                        <span class="icon-wrapper">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <?php echo esc_html($field['name']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="clearfix" style="clear: both;"></div>
            </div>

            <script>
                var global_custom_field_counter = <?php echo intval($all_fields_name_count); ?>;
                jQuery(document).ready(function () {
                    
                    jQuery('#foo<?php echo esc_attr($rand_id); ?>').sortable({
                        group: "words",
                        animation: 150,
                        handle: ".field-intro",
                        cancel: ".form-group-wrapper"
                    });
                });
            </script>
        </form>
        <?php
    }

    public static function get_field_id($id, $fields) {
        if ( !empty($fields) && is_array($fields) ) {
            foreach ($fields as $field) {
                if ( $field['id'] == $id ) {
                    return $field;
                }
            }
        }
        return array();
    }

    public static function get_all_field_types() {
        $fields = apply_filters( 'wp_listings_directory_get_default_field_types', array(
            array(
                'title' => esc_html__('Direct Input', 'wp-listings-directory'),
                'fields' => array(
                    'text' => esc_html__('Text', 'wp-listings-directory'),
                    'textarea' => esc_html__('Textarea', 'wp-listings-directory'),
                    'wysiwyg' => esc_html__('WP Editor', 'wp-listings-directory'),
                    'date' => esc_html__('Date', 'wp-listings-directory'),
                    'number' => esc_html__('Number', 'wp-listings-directory'),
                    'url' => esc_html__('Url', 'wp-listings-directory'),
                    'email' => esc_html__('Email', 'wp-listings-directory'),
                )
            ),
            array(
                'title' => esc_html__('Choices', 'wp-listings-directory'),
                'fields' => array(
                    'select' => esc_html__('Select', 'wp-listings-directory'),
                    'multiselect' => esc_html__('Multiselect', 'wp-listings-directory'),
                    'checkbox' => esc_html__('Checkbox', 'wp-listings-directory'),
                    'radio' => esc_html__('Radio Buttons', 'wp-listings-directory'),
                )
            ),
            array(
                'title' => esc_html__('Form UI', 'wp-listings-directory'),
                'fields' => array(
                    'heading' => esc_html__('Heading', 'wp-listings-directory')
                )
            ),
            array(
                'title' => esc_html__('Others', 'wp-listings-directory'),
                'fields' => array(
                    'file' => esc_html__('File', 'wp-listings-directory')
                )
            ),
        ));
        
        return $fields;
    }

    public static function get_all_field_type_keys() {
        $fields = self::get_all_field_types();
        $return = array();
        foreach ($fields as $group) {
            foreach ($group['fields'] as $key => $value) {
                $return[] = $key;
            }
        }

        return apply_filters( 'wp_listings_directory_get_all_field_types', $return );
    }

    public static function get_all_types_fields_required() {
        $prefix = WP_LISTINGS_DIRECTORY_LISTING_PREFIX;
        $fields = array(
            array(
                'name'              => __( 'Listing Title', 'wp-listings-directory' ),
                'id'                => $prefix . 'title',
                'type'              => 'text',
                'disable_check' => true,
                'required' => true,
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_input'),
                'show_compare'      => true
            ),
            array(
                'name'              => __( 'Listing Description', 'wp-listings-directory' ),
                'id'                => $prefix . 'description',
                'type'              => 'textarea',
                'options'           => array(
                    'media_buttons' => false,
                    'textarea_rows' => 8,
                    'wpautop' => true,
                    'tinymce'       => array(
                        'plugins'                       => 'lists,paste,tabfocus,wplink,wordpress',
                        'paste_as_text'                 => true,
                        'paste_auto_cleanup_on_paste'   => true,
                        'paste_remove_spans'            => true,
                        'paste_remove_styles'           => true,
                        'paste_remove_styles_if_webkit' => true,
                        'paste_strip_class_attributes'  => true,
                    ),
                ),
                'disable_check' => true,
                'required' => true,
                'show_compare'      => true
            ),
            array(
                'name'              => __( 'Expiry Date', 'wp-listings-directory' ),
                'id'                => $prefix . 'expiry_date',
                'type'              => 'text_date',
                'date_format'       => 'Y-m-d',
                'disable_check' => true,
                'show_in_submit_form' => false,
                'show_in_admin_edit' => true,
            ),
            array(
                'name'              => __( 'Featured', 'wp-listings-directory' ),
                'id'                => $prefix . 'featured',
                'type'              => 'checkbox',
                'description'       => __( 'Featured listings will be sticky during searches, and can be styled differently.', 'wp-listings-directory' ),
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_checkbox'),
                'show_compare'      => true
            ),
        );
        return apply_filters( 'wp-listings-directory-type-required-fields', $fields );
    }

    public static function get_all_types_fields_available() {

        $socials = WP_Listings_Directory_Mixes::get_socials_network();
        $opt_socials = [];
        foreach ($socials as $key => $value) {
            $opt_socials[$key] = $value['title'];
        }

        $prefix = WP_LISTINGS_DIRECTORY_LISTING_PREFIX;
        $fields = array(
            array(
                'name'              => __( 'Tagline', 'wp-listings-directory' ),
                'id'                => $prefix . 'tagline',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Featured Image', 'wp-listings-directory' ),
                'id'                => $prefix . 'featured_image',
                'type'              => 'wp_listings_directory_file',
                'ajax'              => true,
                'multiple_files'    => false,
                'mime_types'        => array( 'gif', 'jpeg', 'jpg', 'jpg|jpeg|jpe', 'png' ),
            ),
            array(
                'name'              => __( 'Logo', 'wp-listings-directory' ),
                'id'                => $prefix . 'logo',
                'type'              => 'wp_listings_directory_file',
                'ajax'              => true,
                'multiple_files'    => false,
                'mime_types'        => array( 'gif', 'jpeg', 'jpg', 'jpg|jpeg|jpe', 'png' ),
            ),
            array(
                'name'              => __( 'Gallery', 'wp-listings-directory' ),
                'id'                => $prefix . 'gallery',
                'type'              => 'wp_listings_directory_file',
                'ajax'              => true,
                'multiple_files'          => true,
                'mime_types'        => array( 'gif', 'jpeg', 'jpg', 'jpg|jpeg|jpe', 'png' ),
            ),

            // location
            array(
                'name'              => __( 'Friendly Address', 'wp-listings-directory' ),
                'id'                => $prefix . 'address',
                'type'              => 'text',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_input'),
                'show_compare'      => true
            ),
            array(
                'id'                => $prefix . 'map_location',
                'name'              => __( 'Map Location', 'wp-listings-directory' ),
                'type'              => 'pw_map',
                'sanitization_cb'   => 'pw_map_sanitise',
                'split_values'      => true,
            ),

            // price
            array(
                'name'              => __( 'Price Range', 'wp-listings-directory' ),
                'id'                => $prefix . 'price_range',
                'type'              => 'select',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_listing_price_range'),
                'show_compare'      => true,
                'options'           => WP_Listings_Directory_Mixes::price_range_icons()
            ),
            array(
                'name'              => __( 'Price From', 'wp-listings-directory' ),
                'id'                => $prefix . 'price_from',
                'type'              => 'text',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_listing_price'),
                'show_compare'      => true
            ),
            array(
                'name'              => __( 'Price To', 'wp-listings-directory' ),
                'id'                => $prefix . 'price_to',
                'type'              => 'text',
                'show_compare'      => true
            ),

            // listing detail
            array(
                'name'              => __( 'Hours', 'wp-listings-directory' ),
                'id'                => $prefix . 'hours',
                'type'              => 'wpld_hours',
            ),
            array(
                'name'              => __( 'Menu Prices', 'wp-listings-directory' ),
                'id'                => $prefix . 'menu_prices',
                'type'              => 'group',
                'options'           => array(
                    'group_title'       => __( 'Menu {#}', 'wp-listings-directory' ),
                    'add_button'        => __( 'Add Another Menu', 'wp-listings-directory' ),
                    'remove_button'     => __( 'Remove Menu', 'wp-listings-directory' ),
                    'sortable'          => false,
                    'closed'         => true,
                ),
                'fields'            => array(
                    array(
                        'name'      => __( 'Profile Image', 'wp-listings-directory' ),
                        'id'        => 'profile_image',
                        'type'      => 'file',
                        'options' => array(
                            'url' => false, // Hide the text input for the url
                        ),
                        'text'    => array(
                            'add_upload_file_text' => __( 'Add Image', 'wp-listings-directory' ),
                        ),
                        'query_args' => array(
                            'type' => array(
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                            ),
                        ),
                        'file_multiple'         => false,
                        'ajax'              => true,
                        'mime_types'        => array( 'gif', 'jpeg', 'jpg', 'png' ),
                    ),
                    array(
                        'name'      => __( 'Title', 'wp-listings-directory' ),
                        'id'        => 'title',
                        'type'      => 'text',
                    ),
                    array(
                        'name'      => __( 'Price', 'wp-listings-directory' ),
                        'id'        => 'price',
                        'type'      => 'text',
                    ),
                    array(
                        'name'      => __( 'Description', 'wp-listings-directory' ),
                        'id'        => 'description',
                        'type'      => 'textarea',
                    ),
                ),
            ),
            array(
                'name'              => __( 'FAQ', 'wp-listings-directory' ),
                'id'                => $prefix . 'faq',
                'type'              => 'group',
                'options'           => array(
                    'group_title'       => __( 'FAQ {#}', 'wp-listings-directory' ),
                    'add_button'        => __( 'Add Another FAQ', 'wp-listings-directory' ),
                    'remove_button'     => __( 'Remove FAQ', 'wp-listings-directory' ),
                    'sortable'          => false,
                    'closed'         => true,
                ),
                'fields'            => array(
                    array(
                        'name'      => __( 'Question', 'wp-listings-directory' ),
                        'id'        => 'question',
                        'type'      => 'text',
                    ),
                    array(
                        'name'      => __( 'Answer', 'wp-listings-directory' ),
                        'id'        => 'answer',
                        'type'      => 'textarea',
                    ),
                ),
            ),
            array(
                'name'              => __( 'Socials', 'wp-listings-directory' ),
                'id'                => $prefix . 'socials',
                'type'              => 'group',
                'options'           => array(
                    'group_title'       => __( 'Network {#}', 'wp-listings-directory' ),
                    'add_button'        => __( 'Add Another Network', 'wp-listings-directory' ),
                    'remove_button'     => __( 'Remove Network', 'wp-listings-directory' ),
                    'sortable'          => false,
                    'closed'         => true,
                ),
                'fields'            => array(
                    array(
                        'name'      => __( 'Network', 'wp-listings-directory' ),
                        'id'        => 'network',
                        'type'      => 'select',
                        'options'   => $opt_socials
                    ),
                    array(
                        'name'      => __( 'Url', 'wp-listings-directory' ),
                        'id'        => 'url',
                        'type'      => 'text',
                    ),
                ),
            ),
            array(
                'name'              => __( 'Website', 'wp-listings-directory' ),
                'id'                => $prefix . 'website',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Email', 'wp-listings-directory' ),
                'id'                => $prefix . 'email',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Phone', 'wp-listings-directory' ),
                'id'                => $prefix . 'phone',
                'type'              => 'text',
            ),
            array(
                'name'              => __( 'Video link', 'wp-listings-directory' ),
                'id'                => $prefix . 'video',
                'type'              => 'text',
                'description'       => __( 'Enter Youtube or Vimeo url.', 'wp-listings-directory' ),
            ),


            // Taxonomies
            array(
                'name'              => __( 'Category', 'wp-listings-directory' ),
                'id'                => $prefix . 'category',
                'type'              => 'pw_taxonomy_select',
                'taxonomy'          => 'listing_category',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_select'),
                'show_compare'      => true
            ),
            array(
                'name'              => __( 'Type', 'wp-listings-directory' ),
                'id'                => $prefix . 'type',
                'type'              => 'pw_taxonomy_multiselect',
                'taxonomy'          => 'listing_type',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_select'),
                'show_compare'      => true
            ),
            array(
                'name'              => __( 'Features', 'wp-listings-directory' ),
                'id'                => $prefix . 'feature',
                'type'              => 'pw_taxonomy_multiselect',
                'taxonomy'          => 'listing_feature',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_check_list'),
                'show_compare'      => true
            ),
            array(
                'name'              => __( 'Location', 'wp-listings-directory' ),
                'id'                => $prefix . 'location',
                'type'              => 'pw_taxonomy_multiselect',
                'taxonomy'          => 'listing_location',
                'field_call_back' => array( 'WP_Listings_Directory_Abstract_Filter', 'filter_field_taxonomy_hierarchical_check_list'),
                'show_compare'      => true
            ),
        );
        return apply_filters( 'wp-listings-directory-type-available-fields', $fields );
    }

    public static function get_custom_fields_data() {
        $meta_key = self::get_custom_fields_key();
        return apply_filters( 'wp-listings-directory-get-custom-fields-data', get_option($meta_key, array()) );
    }

    public static function get_custom_fields_key() {
        return apply_filters( 'wp-listings-directory-get-custom-fields-key', 'wp_listings_directory_fields_data' );
    }

    public static function get_display_hooks() {
        $hooks = array(
            '' => esc_html__('Choose a position', 'wp-listings-directory'),
            'wp-listings-directory-single-listing-description' => esc_html__('Single Listing - Description', 'wp-listings-directory'),
            'wp-listings-directory-single-listing-details' => esc_html__('Single Listing - Details', 'wp-listings-directory'),
            'wp-listings-directory-single-listing-amenities' => esc_html__('Single Listing - Features', 'wp-listings-directory'),
            'wp-listings-directory-single-listing-contact-form' => esc_html__('Single Listing - Contact Form', 'wp-listings-directory'),
            'wp-listings-directory-single-listing-video' => esc_html__('Single Listing - Video', 'wp-listings-directory'),
            'wp-listings-directory-single-listing-attachments' => esc_html__('Single Listing - Attachments', 'wp-listings-directory'),
        );
        return apply_filters( 'wp-listings-directory-get-custom-fields-display-hooks', $hooks );
    }

    public static function save() {
        if ( isset( $_POST['updateListingFieldManager'] ) ) {

            $custom_field_final_array = $counts = array();
            $field_index = 0;

            foreach ($_POST['wp-listings-directory-custom-fields-type'] as $field_type) {
                $custom_fields_id = isset($_POST['wp-listings-directory-custom-fields-id'][$field_index]) ? $_POST['wp-listings-directory-custom-fields-id'][$field_index] : '';
                $counter = 0;
                if ( isset($counts[$field_type]) ) {
                    $counter = $counts[$field_type];
                }
                $custom_field_final_array[] = self::custom_field_ready_array($counter, $field_type, $custom_fields_id);
                $counter++;
                $counts[$field_type] = $counter;
                $field_index++;
            }
            
            $meta_key = self::get_custom_fields_key();
            update_option($meta_key, $custom_field_final_array);
            
        }
    }

    public static function custom_field_ready_array($array_counter = 0, $field_type = '', $custom_fields_id = '') {
        $custom_field_element_array = array();
        $custom_field_element_array['type'] = $field_type;
        if ( !empty($_POST["wp-listings-directory-custom-fields-{$field_type}"]) ) {
            foreach ($_POST["wp-listings-directory-custom-fields-{$field_type}"] as $field => $value) {
                if ( isset($value[$custom_fields_id]) ) {
                    $custom_field_element_array[$field] = $value[$custom_fields_id];
                } elseif ( isset($value[$array_counter]) ) {
                    $custom_field_element_array[$field] = $value[$array_counter];
                }
            }
        }
        return $custom_field_element_array;
    }

    public static function custom_field_html() {
        $fieldtype = $_POST['fieldtype'];
        $global_custom_field_counter = $_REQUEST['global_custom_field_counter'];
        $li_rand_id = rand(454, 999999);
        $html = '<li class="custom-field-class-' . $li_rand_id . '">';
        $types = self::get_all_field_type_keys();
        if ( in_array($fieldtype, $types) ) {
            if ( in_array( $fieldtype, array('text', 'textarea', 'wysiwyg', 'number', 'url', 'email', 'checkbox') ) ) {
                $html .= apply_filters( 'wp_listings_directory_custom_field_text_html', $fieldtype, $global_custom_field_counter, '' );
            } elseif ( in_array( $fieldtype, array('select', 'multiselect', 'radio') ) ) {
                $html .= apply_filters( 'wp_listings_directory_custom_field_opts_html', $fieldtype, $global_custom_field_counter, '' );
            } else {
                $html .= apply_filters('wp_listings_directory_custom_field_'.$fieldtype.'_html', $fieldtype, $global_custom_field_counter, '');
            }
        }
        // action btns
        $html .= apply_filters('wp_listings_directory_custom_field_actions_html', $li_rand_id, $global_custom_field_counter, $fieldtype);
        $html .= '</li>';
        echo json_encode( array('html' => $html) );
        wp_die();
    }

    public static function custom_field_available_html() {
        $prefix = WP_LISTINGS_DIRECTORY_LISTING_PREFIX;
        $fieldtype = $_POST['fieldtype'];
        $global_custom_field_counter = $_REQUEST['global_custom_field_counter'];
        $li_rand_id = rand(454, 999999);
        $html = '<li class="custom-field-class-' . $li_rand_id . '">';
        $types = self::get_all_types_fields_available();

        $dfield_values = self::get_field_id($fieldtype, $types);
        if ( !empty($dfield_values) ) {

            $dtypes = apply_filters( 'wp_listings_directory_list_simple_type', array( $prefix.'featured', $prefix.'year', $prefix.'address', $prefix.'map_location', $prefix.'price_from', $prefix.'price_to', $prefix.'price_range', $prefix.'tagline', $prefix.'hours', $prefix.'menu_prices', $prefix.'faq', $prefix.'socials', $prefix.'website', $prefix.'email', $prefix.'phone', $prefix.'location', $prefix.'video' ) );

            if ( in_array( $fieldtype, $dtypes ) ) {
                $html .= apply_filters( 'wp_listings_directory_custom_field_available_simple_html', $fieldtype, $global_custom_field_counter, $dfield_values );
                
            } elseif ( in_array( $fieldtype, apply_filters( 'wp_listings_directory_list_tax_type', array($prefix.'category', $prefix.'type', $prefix.'location', $prefix.'feature') ) ) ) {
                
                $html .= apply_filters( 'wp_listings_directory_custom_field_available_tax_html', $fieldtype, $global_custom_field_counter, $dfield_values );

            } elseif ( in_array( $fieldtype, apply_filters( 'wp_listings_directory_list_file_type', array($prefix.'featured_image', $prefix.'logo', $prefix.'gallery', $prefix.'attachments') ) ) ) {
                
                $html .= apply_filters( 'wp_listings_directory_custom_field_available_file_html', $fieldtype, $global_custom_field_counter, $dfield_values );
            
            } else {
                $html .= apply_filters( 'wp_listings_directory_custom_field_available_'.$fieldtype.'_html', $fieldtype, $global_custom_field_counter, $dfield_values );
            }
        }

        // action btns
        $html .= apply_filters('wp_listings_directory_custom_field_actions_html', $li_rand_id, $global_custom_field_counter, $fieldtype);
        $html .= '</li>';
        echo json_encode(array('html' => $html));
        wp_die();
    }

}

WP_Listings_Directory_Fields_Manager::init();


