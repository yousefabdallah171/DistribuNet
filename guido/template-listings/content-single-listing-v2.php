<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;

wp_enqueue_script( 'sticky-kit' );
?>

<?php do_action( 'wp_listings_directory_before_listing_detail', $post->ID ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('listing-single-layout listing-single-v2'); ?>>
	<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/gallery-v2' ); ?>
	<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/header-v2' ); ?>
	<div class="<?php echo apply_filters('guido_listing_content_class', 'container');?>">		
		<!-- Main content -->
		<div class="content-listing-detail">

			<div class="row row-40 listing-v-wrapper">
				<div class="col-12 listing-detail-main col-lg-<?php echo esc_attr( is_active_sidebar( 'listing-single-sidebar' ) ? 8 : 12); ?>">

					<?php do_action( 'wp_listings_directory_before_listing_content', $post->ID ); ?>

					<?php
					if ( guido_get_config('show_listing_description', true) ) {
						echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/description' );
					}
					?>

					<?php
					if ( guido_get_config('show_listing_feature', true) ) {
						echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/features' );
					}
					?>

					<?php
					if ( guido_get_config('show_listing_menu_prices', true) ) {
						echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/menu-prices' );
					}
					?>

					<?php
					if ( guido_get_config('show_listing_faq', true) ) {
						echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/faq' );
					}
					?>
					
			   		<?php
					if ( guido_get_config('show_listing_video', true) ) {
						echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/video' );
					}
					?>
					<?php if ( WP_Listings_Directory_Review::review_enable() ) { ?>
						<div class="d-none d-lg-block">
							<?php comments_template(); ?>
						</div>
					<?php } ?>

					<?php do_action( 'wp_listings_directory_after_listing_content', $post->ID ); ?>
				</div>
				
				<?php if ( is_active_sidebar( 'listing-single-sidebar' ) ): ?>
					<div class="col-12 col-lg-4 sidebar-wrapper">
				   		<div class="sidebar sidebar-listing-inner sidebar-right">
					   		<?php dynamic_sidebar( 'listing-single-sidebar' ); ?>
				   		</div>
				   	</div>
			   	<?php endif; ?>
			   	<div class="col-12 listing-detail-main col-lg-<?php echo esc_attr( is_active_sidebar( 'listing-single-sidebar' ) ? 8 : 12); ?>">
			   		
					<?php if ( WP_Listings_Directory_Review::review_enable() ) { ?>
						<div class="d-block d-lg-none">
							<?php comments_template(); ?>
						</div>
					<?php } ?>

			   	</div>
			</div>
		</div>
	</div>
	<?php
	if ( guido_get_config('show_listing_related', true) ) {
		echo WP_Listings_Directory_Template_Loader::get_template_part( 'single-listing/related' );
	}
	?>
</article><!-- #post-## -->

<?php do_action( 'wp_listings_directory_after_listing_detail', $post->ID ); ?>