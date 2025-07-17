<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Guido
 * @since Guido 1.0
 */
/*
*Template Name: 404 Page
*/
get_header();

$icon_url = guido_get_config('404_icon_img');
$bg_img = guido_get_config('404_bg_img');

$style = '';
if ( !empty($bg_img) ) {
	$style = 'style="background-image: url('.$bg_img.');"';
}

?>
<section class="page-404 justify-content-center flex-middle" <?php echo trim($style); ?>>
	<div id="main-container" class="inner">
		<div id="main-content" class="main-page">
			<section class="error-404 not-found clearfix">
				<div class="container">
					<div class="content-inner text-center">
						<div class="top-image">
							<?php if( !empty($icon_url) ) { ?>
								<img src="<?php echo esc_url( $icon_url); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php }else{ ?>
								<img src="<?php echo esc_url( get_template_directory_uri().'/images/error.jpg'); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</div>
						<div class="slogan">
							<h4 class="title-big">
								<?php
								$title = guido_get_config('404_title');
								if ( !empty($title) ) {
									echo esc_html($title);
								} else {
									esc_html_e('Oh! Page Not Found', 'guido');
								}
								?>
							</h4>
						</div>
						<div class="description">
							<?php
							$description = guido_get_config('404_description');
							if ( !empty($description) ) {
								echo esc_html($description);
							} else {
								esc_html_e('We can’t seem to find the page you’re looking for', 'guido');
							}
							?>
						</div>
						<div class="page-content">
							<?php get_search_form(); ?>
							<div class="return">
								<a class="btn-underline text-theme" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Back to Home','guido') ?></a>
							</div>
						</div><!-- .page-content -->
					</div>
				</div>
			</section><!-- .error-404 -->
		</div><!-- .content-area -->
	</div>
</section>
<?php get_footer(); ?>