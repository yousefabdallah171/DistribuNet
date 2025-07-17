<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main content" role="main">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'content-single-listing' ); ?>
				<?php endwhile; ?>

				<?php the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'wp-listings-directory' ),
					'next_text'          => __( 'Next page', 'wp-listings-directory' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'wp-listings-directory' ) . ' </span>',
				) ); ?>
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
