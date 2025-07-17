<?php
extract( $args );

extract( $args );
extract( $instance );


$style = !empty($style) ? $style : 'carousel';
$args = array(
    'limit' => $number_post,
    'get_listings_by' => $get_listings_by,
    'orderby' => $orderby,
    'order' => $order,
    'style' => $style,
);

$loop = guido_get_listings($args);
if ( $loop->have_posts() ):
    echo trim($before_widget);
    $title = apply_filters('widget_title', $instance['title']);

    if ( $title ) {
        echo trim($before_title)  . trim( $title ) . $after_title;
    }
?>
    
    <div class="listings-list-simple">
    	<?php while ( $loop->have_posts() ): $loop->the_post(); ?>
			<?php get_template_part( 'template-listings/listings-styles/inner', 'list-simple'); ?>
    	<?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    <?php echo trim($after_widget);
endif;