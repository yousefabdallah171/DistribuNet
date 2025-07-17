<?php 
global $post;
$thumbsize = !isset($args['thumbsize']) ? guido_get_config( 'blog_item_thumbsize', 'full' ) : $args['thumbsize'];
$thumb = guido_display_post_thumb($thumbsize);
?>
<article <?php post_class('post post-layout post-list-item'); ?>>
    <?php
        if ( !empty($thumb) ) {
            ?>
            <div class="top-image">
                <?php guido_post_categories_first($post); ?>
                <?php
                    echo trim($thumb);
                ?>
             </div>
            <?php
        }
    ?>
    <div class="col-content">
        <?php if( empty($thumb) ) { ?>
            <?php guido_post_categories_first($post); ?>
        <?php } ?>
        <div class="top-detail-info">
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                <i class="flaticon-avatar"></i><?php echo get_the_author(); ?>
            </a>
            <span class="date">
                <i class="flaticon-date"></i><?php the_time( get_option('date_format', 'd M, Y') ); ?>
            </span>
        </div>
        <?php if (get_the_title()) { ?>
            <h4 class="entry-title">
                <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
                    <div class="stick-icon text-theme"><i class="ti-pin2"></i></div>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
        <?php } ?>
        <div class="description"><?php echo guido_substring( get_the_excerpt(),25, '...' ); ?></div>
        <div class="more-bottom">
            <a href="<?php the_permalink(); ?>" class="btn-readmore"><?php echo esc_html__( 'Read More', 'guido' )?></a>
        </div>
    </div>
</article>