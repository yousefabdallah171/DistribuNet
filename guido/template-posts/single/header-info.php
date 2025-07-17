<?php
$post_format = get_post_format();
global $post;
?>
<div class="text-center entry-content-detail header-info-blog">
    <?php if(has_post_thumbnail()) { ?>
        <div class="entry-thumb">
            <?php
                $thumb = guido_post_thumbnail();
                echo trim($thumb);
            ?>
        </div>
    <?php } ?>
    <div class="header-info-blog-inner <?php echo esc_attr( (has_post_thumbnail()) ? '':'position-static' ); ?>">
        <?php guido_post_categories($post); ?>
        <?php if (get_the_title()) { ?>
            <h1 class="detail-title">
                <?php the_title(); ?>
            </h1>
        <?php } ?>
        <div class="top-detail-info clearfix">
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                <i class="flaticon-avatar"></i><?php echo get_the_author(); ?>
            </a>
            <span class="date">
                <i class="flaticon-date"></i><?php the_time( get_option('date_format', 'd M, Y') ); ?>
            </span>
        </div>
    </div>
</div>