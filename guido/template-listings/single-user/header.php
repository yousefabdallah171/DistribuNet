<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$args = array(
    'post_type' => 'listing',
    'posts_per_page' => -1,
    'author' => $author_obj->ID,
    'fields' => 'ids'
);
$listings = new WP_Query( $args );

$total_nb_reviews = 0;
$total_rating = 0;
if ( $listings->posts ) {
    foreach ($listings->posts as $post_id) {
        $nb_reviews = get_post_meta( $post_id, '_nb_reviews', true );
        $rating = get_post_meta( $post_id, '_average_rating', true );
        $total_nb_reviews = !empty($nb_reviews) ? $total_nb_reviews + $nb_reviews : $total_nb_reviews;
        $total_rating = !empty($rating) ? $total_rating + $rating : $total_rating;
    }
}
?>
<div class="listing-user-header">
    <div class="<?php echo apply_filters('guido_user_content_class', 'container');?>">
        <div class="d-sm-flex align-items-center">
            <div class="left-info d-flex align-items-center">
                <div class="listing-user-avarta d-flex align-items-center justify-content-center">
                    <?php echo get_avatar($author_obj->ID, 90); ?>
                </div>
                <div class="listing-user-info">
                    <h1 class="title"><?php echo trim($author_obj->display_name); ?></h1>

                    <div class="listing-user-metas">
                        <?php WP_Listings_Directory_Review::print_review($total_rating, 'list', $total_nb_reviews); ?>
                    </div>

                </div>
            </div>
            <div class="listing-action-user ms-auto">
                <?php
                if ( guido_is_wp_private_message() ) {
                    guido_author_private_message_form($author_obj->ID);
                }
                ?>
            </div>
        </div>
    </div>
</div>