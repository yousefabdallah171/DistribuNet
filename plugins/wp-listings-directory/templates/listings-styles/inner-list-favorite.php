<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$types = get_the_terms( $post->ID, 'listing_type' );
$address = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'address', true );
$home_area = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'home_area', true );
$beds = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'beds', true );
$baths = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'baths', true );

$price = WP_Listings_Directory_Listing::get_price_html($post->ID);

?>

<?php do_action( 'wp_listings_directory_before_listing_content', $post->ID ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('listing-favorite-wrapper'); ?>>

	<?php if ( has_post_thumbnail() ) { ?>
        <div class="agent-thumbnail">
            <?php echo get_the_post_thumbnail( $post, 'thumbnail' ); ?>

            <?php if ( $types ) { ?>
                <?php foreach ($types as $term) { ?>
                    <a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="listing-information">
    	
		<?php the_title( sprintf( '<h2 class="entry-title listing-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

        <?php if ( $address ) { ?>
            <div class="listing-location"><?php echo $address; ?></div>
        <?php } ?>
        <div class="listing-date">
            <?php echo sprintf( __('%s ago', 'wp-listings-directory'), human_time_diff(get_the_time('U'), current_time('timestamp')) ); ?> 
        </div>
        
        <div class="listing-metas">
            <?php
                if ( $home_area ) {
                    echo sprintf(__('%d Home Area', 'wp-listings-directory'), $home_area);
                }
                if ( $beds ) {
                    echo sprintf(__('%d Beds', 'wp-listings-directory'), $beds);
                }
                if ( $baths ) {
                    echo sprintf(__('%d Baths', 'wp-listings-directory'), $baths);
                }
            ?>
        </div>

        <div class="listing-metas-bottom">
            <div class="listing-date-author">
                <?php
                    $userdata = get_userdata($post->post_author);
                    echo $userdata->display_name;
                ?>
            </div>
            <?php if ( $price ) { ?>
                <div class="listing-price"><?php echo $price; ?></div>
            <?php } ?>
        </div>

        <a href="javascript:void(0)" class="btn-remove-listing-favorite" data-listing_id="<?php echo esc_attr($post->ID); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-listings-directory-remove-listing-favorite-nonce' )); ?>"><?php esc_html_e('Remove', 'wp-listings-directory'); ?></a>

	</div>
</article><!-- #post-## -->

<?php do_action( 'wp_listings_directory_after_listing_content', $post->ID ); ?>