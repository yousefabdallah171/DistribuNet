<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

global $post;
if ( empty($post->post_type) || $post->post_type != 'listing' ) {
    return;
}
$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
$terms = get_the_terms($post, 'listing_category');
if ( !$meta_obj->check_post_meta_exist('category') || empty($terms) || is_wp_error( $terms ) ) {
    return;
}

extract( $args );
extract( $instance );
echo trim($before_widget);

$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}
?>
<div id="listing-detail-categories" class="listing-detail-categories">
    <ul class="list list-detail-categories">
        <?php foreach ($terms as $term) { ?>
            <?php $addclass = (!empty(guido_listing_term_icon($term)))?'has-icon':''; ?>
            <li class="<?php echo esc_attr($addclass); ?>">
                <a href="<?php echo esc_url(get_term_link($term)); ?>">
                    <?php echo trim(guido_listing_term_icon($term)); ?>
                    <?php echo esc_html($term->name); ?>
                </a>
            </li>
        <?php } ?>
        <?php do_action('wp-listings-directory-single-listing-categories', $post); ?>
    </ul>
</div>
<?php echo trim($after_widget);