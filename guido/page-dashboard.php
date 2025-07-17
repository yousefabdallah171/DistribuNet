<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Guido
 * @since Guido 1.0
 */
/*
*Template Name: Dashboard Template
*/
get_header();
global $post;
$sidebar_configs = guido_get_page_layout_configs();
$page_config = get_post_meta( $post->ID, 'apus_page_layout', true );
?>
<section class="page-dashboard">
	<?php if(get_post_meta( $post->ID, 'apus_page_show_breadcrumb', true )){ ?>
		<div class="apus-breadscrumb-dashboard">
			<?php guido_render_breadcrumbs(); ?>
		</div>
	<?php } ?>
	<?php  if ( has_nav_menu( 'user-menu' ) ) { ?>
		<div class="wrapper-menu-dashboard">
			<div class="container">
				<?php
	                if ( !empty('user-menu') && has_nav_menu( 'user-menu' ) ) {
	                    $args = array(
	                        'theme_location' => 'user-menu',
	                        'container_class' => false,
	                        'menu_class' => 'nav nav-fill menu-dashboard flex-nowrap',
	                        'fallback_cb' => '',
	                        'menu_id' => '',
	                        'walker' => new Guido_Nav_Menu()
	                    );
	                    wp_nav_menu($args);
	                }
	            ?>
			</div>
		</div>
	<?php } ?>
	<section id="main-container" class="<?php echo apply_filters('guido_page_content_class', 'container');?> <?php echo esc_attr(get_post_meta( $post->ID, 'apus_page_layout', true )); ?> ">
		<div class="inner-dashboard">
			<?php guido_before_content( $sidebar_configs ); ?>
			<div class="<?php echo esc_attr(( $page_config == 'main' ) ? 'clearfix':'row'); ?>">
				<?php guido_display_sidebar_left( $sidebar_configs ); ?>
				<div id="main-content" class="main-page <?php echo esc_attr($sidebar_configs['main']['class']); ?> <?php echo esc_attr(( $page_config == 'main' ) ? 'p-0':''); ?>">
					<div id="main" class="site-main clearfix" role="main">

						<?php
						// Start the loop.
						while ( have_posts() ) : the_post();
							
							// Include the page content template.
							the_content();

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						// End the loop.
						endwhile;
						?>
					</div><!-- .site-main -->
					<?php
		    		wp_link_pages( array(
		    			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'guido' ) . '</span>',
		    			'after'       => '</div>',
		    			'link_before' => '<span>',
		    			'link_after'  => '</span>',
		    			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'guido' ) . ' </span>%',
		    			'separator'   => '',
		    		) );
		    		?>
				</div><!-- .content-area -->
				<?php guido_display_sidebar_right( $sidebar_configs ); ?>
			</div>
		</div>
	</section>
</section>
<?php get_footer(); ?>