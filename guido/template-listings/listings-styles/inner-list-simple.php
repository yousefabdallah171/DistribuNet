<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;
?>
<article <?php post_class('listing-list-simple'); ?>>
    <div class="d-flex">
        <?php if ( has_post_thumbnail() ) { ?>
            <div class="listing-thumbnail-wrapper">
                <?php guido_listing_display_image( $post, 'thumbnail' ); ?>
            </div>
        <?php } ?>
        <div class="listing-information">
            <?php guido_listing_display_price($post, 'no-icon-title', true); ?>
            <?php the_title( sprintf( '<h2 class="listing-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        </div>
    </div>
</article><!-- #post-## -->