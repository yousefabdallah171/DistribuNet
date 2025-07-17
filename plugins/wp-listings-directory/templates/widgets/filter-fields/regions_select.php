<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



$first_regions = get_terms('listing_location', array(
    'orderby' => 'count',
    'hide_empty' => 0,
    'parent' => 0,
    'orderby' => 'title',
    'order'   => 'ASC',
));

if ( !empty( $first_regions ) && !is_wp_error( $first_regions ) ) {

    $selected_region = '';
    if ( ! empty( $selected ) ) {
        if( !is_array($selected)) {
            if ( is_numeric( $selected ) ) {
                $selected_region = intval( $selected );
            } else {
                $term = get_term_by( 'slug', $selected, 'listing_location' );
                $selected_region = $term->term_id;
            }
        } else {
            $selected_region = $selected;
        }
    } elseif ( is_tax('listing_location') ) {
        global $wp_query;
        $term = $wp_query->queried_object;
        if ( isset( $term->term_id) ) {
            $selected_region = $term->term_id;
        }
    }

    $selected_regions = [];

    $parent = $parent_second = $parent_third = 0;
    $first_select = $second_select = $third_select = $fourth_select = 0;

    if ( !empty($selected_region) ) {
        if ( !is_array($selected_region) ) {
            $level = WP_Listings_Directory_Abstract_Filter::get_the_level($selected_region);
            if ( $level == 0 ) {
                $parent = $selected_region;
                $selected_regions[] = $selected_region;
            } elseif ( $level == 1 ) {
                $term = get_term($selected_region);
                if ( $term ) {
                    $parent_term = get_term($term->parent);
                    $parent = $parent_term->term_id;
                    $parent_second = $term->term_id;
                    $selected_regions[] = $parent_term->term_id;
                    $selected_regions[] = $term->term_id;
                }
            } elseif ( $level == 2 ) {
                $term = get_term($selected_region);
                if ( $term ) {
                    // second
                    $second_parent_term = get_term($term->parent);
                    $parent_second = $second_parent_term->term_id;
                    
                    // first
                    $first_parent_term = get_term($second_parent_term->parent);
                    $parent = $first_parent_term->term_id;


                    $selected_regions[] = $term->term_id;
                    $selected_regions[] = $second_parent_term->term_id;
                    $selected_regions[] = $first_parent_term->term_id;
                }
            } elseif ( $level == 3 ) {
                $term = get_term($selected_region);
                if ( $term ) {
                    $fourth_select = $term->term_id;
                    // third
                    $third_parent_term = get_term($term->parent);
                    $parent_third = $third_parent_term->term_id;
                    // second
                    $second_parent_term = get_term($third_parent_term->parent);
                    $parent_second = $second_parent_term->term_id;
                    // first
                    $first_parent_term = get_term($second_parent_term->parent);
                    $parent = $first_parent_term->term_id;

                    $selected_regions[] = $term->term_id;
                    $selected_regions[] = $third_parent_term->term_id;
                    $selected_regions[] = $second_parent_term->term_id;
                    $selected_regions[] = $first_parent_term->term_id;
                }
            }
        } else {
            $selected_regions = $selected_region;
        }
    }
    
    $nb_fields = apply_filters('wp_listings_directory_cmb2_field_taxonomy_location_number', 4);

    $region1_text = apply_filters('wp_listings_directory_cmb2_field_taxonomy_location_field_name_1', 'Country');


    $placeholder = !empty($field['placeholder']) ? $field['placeholder'] : __('Filter by %s', 'wp-listings-directory');

    $placeholder1 = sprintf($placeholder, $region1_text);
?>
    <div class="form-group form-group-<?php echo esc_attr($key); ?> tax-select-field">
        <?php if ( !isset($field['show_title']) || $field['show_title'] ) { ?>
            <label class="heading-label">
                <?php echo wp_kses_post($field['name']); ?>
            </label>
        <?php } ?>


        <div class="form-group-inner inner field-region field-region1">
            <select class="select-field-region select-field-region1 form-control" data-next="2" autocomplete="off" name="<?php echo esc_attr($name); ?>[]" data-placeholder="<?php echo esc_attr($placeholder1); ?>"  data-taxonomy="listing_location">
                <option value=""><?php echo esc_html($placeholder1); ?></option>
                <?php
                if ( ! empty( $first_regions ) && ! is_wp_error( $first_regions ) ) {
                    foreach ($first_regions as $region) {
                        $selected_attr = '';
                        if ( in_array($region->term_id, $selected_regions) ) {
                            $selected_attr = 'selected="selected"';
                            $parent = $region->term_id;
                        }
                        ?>
                        <option value="<?php echo esc_attr($region->term_id); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
                        <?php  
                    }
                }
                ?>
            </select>
        </div>
    

        <?php if ( $nb_fields == '2' || $nb_fields == '3' || $nb_fields == '4' ) {
            $region2_text = apply_filters('wp_listings_directory_cmb2_field_taxonomy_location_field_name_2', 'State');
            $placeholder2 = sprintf($placeholder, $region2_text);
        ?>

            <div class="field-region field-region2">
                <select class="select-field-region select-field-region2 form-control" data-next="3" autocomplete="off" name="<?php echo esc_attr($name); ?>[]" data-placeholder="<?php echo esc_attr($placeholder2); ?>"  data-taxonomy="listing_location">
                    <option value=""><?php echo esc_html($placeholder2); ?></option>
                    <?php
                    if ( !empty($parent) ) {
                        $second_regions = get_terms('listing_location', array(
                            'orderby' => 'count',
                            'hide_empty' => 0,
                            'parent' => $parent,
                            'orderby' => 'title',
                            'order'   => 'ASC',
                        ));
                        if ( ! empty( $second_regions ) && ! is_wp_error( $second_regions ) ) {
                            foreach ($second_regions as $region) {
                                $selected_attr = '';
                                if ( in_array($region->term_id, $selected_regions) ) {
                                    $selected_attr = 'selected="selected"';
                                    $parent_second = $region->term_id;
                                }
                                ?>
                                <option value="<?php echo esc_attr($region->term_id); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
                                <?php  
                            }
                        }
                    }
                    ?>
                </select>
            </div>

        <?php } ?>
        <?php if ( $nb_fields == '3' || $nb_fields == '4' ) {
            $region3_text = apply_filters('wp_listings_directory_cmb2_field_taxonomy_location_field_name_3', 'City');
            $placeholder3 = sprintf($placeholder, $region3_text);
        ?>

            <div class="field-region field-region3">
                <select class="select-field-region select-field-region3 form-control" data-next="4" autocomplete="off" name="<?php echo esc_attr($name); ?>[]" data-placeholder="<?php echo esc_attr($placeholder3); ?>"  data-taxonomy="listing_location">
                    <option value=""><?php echo esc_html($placeholder3); ?></option>
                    <?php
                    if ( !empty($parent_second) ) {
                        $third_regions = get_terms('listing_location', array(
                            'orderby' => 'count',
                            'hide_empty' => 0,
                            'parent' => $parent_second,
                            'orderby' => 'title',
                            'order'   => 'ASC',
                        ));
                        if ( ! empty( $third_regions ) && ! is_wp_error( $third_regions ) ) {
                            foreach ($third_regions as $region) {
                                $selected_attr = '';
                                if ( in_array($region->term_id, $selected_regions) ) {
                                    $selected_attr = 'selected="selected"';
                                    $third_parent_term = $region->term_id;
                                }
                                ?>
                                <option value="<?php echo esc_attr($region->term_id); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
                                <?php  
                            }
                        }
                    }
                    ?>
                </select>
            </div>

        <?php } ?>
        <?php if ( $nb_fields == '4' ) {
            $region4_text = apply_filters('wp_listings_directory_cmb2_field_taxonomy_location_field_name_4', 'District');
            $placeholder4 = sprintf($placeholder, $region4_text);
        ?>
            <div class="field-region field-region4">
                <select class="select-field-region select-field-region4 form-control" data-next="5" autocomplete="off" name="<?php echo esc_attr($name); ?>[]" data-placeholder="<?php echo esc_attr($placeholder4); ?>" data-taxonomy="listing_location">
                    <option value=""><?php echo esc_html($placeholder4); ?></option>
                    <?php
                    if ( !empty($parent_third) ) {
                        $fourth_regions = get_terms('listing_location', array(
                            'orderby' => 'count',
                            'hide_empty' => 0,
                            'parent' => $parent_third,
                            'orderby' => 'title',
                            'order'   => 'ASC',
                        ));
                        if ( ! empty( $fourth_regions ) && ! is_wp_error( $fourth_regions ) ) {
                            foreach ($fourth_regions as $region) {
                                $selected_attr = '';
                                if ( in_array($region->term_id, $selected_regions) ) {
                                    $selected_attr = 'selected="selected"';
                                }
                                ?>
                                <option value="<?php echo esc_attr($region->term_id); ?>" <?php echo trim($selected_attr); ?>><?php echo esc_html($region->name); ?></option>
                                <?php  
                            }
                        }
                    }
                    ?>
                </select>
            </div>

        <?php } ?>


    </div><!-- /.form-group -->
<?php }