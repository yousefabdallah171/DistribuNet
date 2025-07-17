<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$listing_layout = guido_get_listing_layout_type();
$listing_layout = !empty($listing_layout) ? $listing_layout : 'v1';

?>

<section id="primary" class="content-area inner">
	<div id="main" class="site-main content" role="main">
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post();
				global $post;
				$latitude = WP_Listings_Directory_Listing::get_post_meta( $post->ID, 'map_location_latitude', true );
				$longitude = WP_Listings_Directory_Listing::get_post_meta( $post->ID, 'map_location_longitude', true );
			?>
				<div class="single-listing-wrapper single-listing-wrapper" data-latitude="<?php echo esc_attr($latitude); ?>" data-longitude="<?php echo esc_attr($longitude); ?>">
					<?php
						if ( $listing_layout !== 'v1' ) {
							echo WP_Listings_Directory_Template_Loader::get_template_part( 'content-single-listing-'.$listing_layout );
						} else {
							echo WP_Listings_Directory_Template_Loader::get_template_part( 'content-single-listing' );
						}
					?>
				</div>
			<?php endwhile; ?>

			<?php the_posts_pagination( array(
				'prev_text'          => esc_html__( 'Previous page', 'guido' ),
				'next_text'          => esc_html__( 'Next page', 'guido' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'guido' ) . ' </span>',
			) ); ?>
		<?php else : ?>
			<div class="<?php echo apply_filters('guido_listing_content_class', 'container');?>">
				<?php get_template_part( 'content', 'none' ); ?>
			</div>
		<?php endif; ?>
	</div><!-- .site-main -->
</section><!-- .content-area -->
<?php get_footer(); ?>