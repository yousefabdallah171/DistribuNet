<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

extract( $args );
if ( !is_user_logged_in() ) {
    return;
}

$title = apply_filters('widget_title', $instance['title']);
if ( $title ) {
    echo trim($before_title)  . trim( $title ) . $after_title;
}

if ($nav_menu_user) {
    $term = get_term_by( 'slug', $nav_menu_user, 'nav_menu' );
    if ( !empty($term) ) {
        $nav_menu_id = $term->term_id;
    }
}
?>

<?php if ( !empty($nav_menu_id) ) {
    echo trim($before_widget);
?>
    <div class="user_short_profile">
        <?php
            $args = array(
                'menu'        => $nav_menu_id,
                'container_class' => 'navbar-collapse no-padding',
                'menu_class' => 'menu_short_profile',
                'fallback_cb' => '',
                'walker' => new Guido_Nav_Menu()
            );
            wp_nav_menu($args);
        ?>
    </div>
    <?php echo trim($after_widget);
}