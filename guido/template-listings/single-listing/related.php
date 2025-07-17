<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$related_columns = guido_get_config('listing_related_columns', 4);

$relate_count = apply_filters('wp_listings_directory_number_listing_related', guido_get_config('listing_related_number', 4));

$tax_query = array();
$terms = get_the_terms( $post->ID, 'listing_category' );
if ($terms) {
    $termids = array();
    foreach($terms as $term) {
        $termids[] = $term->term_id;
    }
    $tax_query[] = array(
        'taxonomy' => 'listing_category',
        'field' => 'id',
        'terms' => $termids,
        'operator' => 'IN'
    );
}

if ( empty($tax_query) ) {
    return;
}
$args = array(
    'post_type' => 'listing',
    'posts_per_page' => $relate_count,
    'post__not_in' => array( $post->ID ),
    'tax_query' => array_merge(array( 'relation' => 'AND' ), $tax_query)
);
$relates = new WP_Query( $args );
if( $relates->have_posts() ):
?>
    <div class="wrapper-listings-related">
        <div class="container">
            <div class="related-listings">
                <h4 class="title text-center">
                    <?php esc_html_e( 'Similar Listing', 'guido' ); ?>
                </h4>
                <div class="widget-content">
                    <div class="slick-carousel" data-carousel="slick"
                        data-items="<?php echo esc_attr($related_columns); ?>"
                            data-large="<?php echo esc_attr($related_columns); ?>"
                            data-medium="2"
                            data-small="1"
                            data-pagination="false" data-nav="true">
                        <?php while ( $relates->have_posts() ) : $relates->the_post(); ?>
                            <div class="item">
                                <?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-grid' ); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>