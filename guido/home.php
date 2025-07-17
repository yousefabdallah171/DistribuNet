<?php
get_header();
$sidebar_configs = guido_get_blog_layout_configs();
$columns = guido_get_config('blog_columns', 1);
$layout = guido_get_config( 'blog_display_mode', 'list' );
guido_render_breadcrumbs();

$thumbsize = !isset($thumbsize) ? guido_get_config( 'blog_item_thumbsize', 'full' ) : $thumbsize;
if (!empty($_GET['style']) && ($_GET['style'] =='gridsidebar') ){
    $columns = 2;
    $layout = 'grid';
} elseif (!empty($_GET['style']) && ($_GET['style'] =='full') ){
	$columns = 3;
    $layout = 'grid';
    $sidebar_configs['main'] = array( 'class' => 'col-md-12 col-sm-12 col-xs-12' );
    $sidebar_configs['right']['class'] = $sidebar_configs['left']['class'] = 'hidden';
} elseif (!empty($_GET['style']) && ($_GET['style'] =='list2') ){
	$columns = 1;
    $layout = 'list';
    $thumbsize = 'full';
}

?>
<section id="main-container" class="main-content home-page-default <?php echo apply_filters('guido_blog_content_class', 'container');?> inner">
	<?php guido_before_content( $sidebar_configs ); ?>
	<div class="row responsive-medium">
		<?php guido_display_sidebar_left( $sidebar_configs ); ?>

		<div id="main-content" class="col-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
			<div id="main" class="site-main layout-blog" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header d-none">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php
				get_template_part( 'template-posts/layouts/'.$layout, null, array('columns' => $columns, 'thumbsize' => $thumbsize) );

				// Previous/next page navigation.
				guido_paging_nav();

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-posts/content', 'none' );

			endif;
			?>

			</div><!-- .site-main -->
		</div><!-- .content-area -->
		
		<?php guido_display_sidebar_right( $sidebar_configs ); ?>
		
	</div>
</section>
<?php get_footer(); ?>