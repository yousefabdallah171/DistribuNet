<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
extract( $args );

global $post;
if ( empty($post->post_type) || $post->post_type != 'listing' ) {
    return;
}

extract( $args );
extract( $instance );
echo trim($before_widget);

$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

$author_id = $post->post_author;
$userdata = get_userdata($author_id);

$a_title_html = $userdata->display_name;
$a_phone = get_user_meta($author_id, '_user_phone', true);
$a_phone = guido_user_display_phone($a_phone, 'no-title', false);

?>
<div class="listing-detail-author">
	<div class="author-content-wrapper d-flex align-items-center">
        <div class="author-thumbnail d-flex align-items-center">
            <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                <?php echo guido_get_avatar($post->post_author, 180); ?>
            </a>
        </div>
        <div class="author-content">
            <h3 class="name">
                <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php echo trim($a_title_html); ?></a>
            </h3>
            <?php if($a_phone){ ?>
                <div class="author-phone"><?php echo trim($a_phone); ?></div>
            <?php } ?>
        </div>
    </div>
</div>
<?php echo trim($after_widget);