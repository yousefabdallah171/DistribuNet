<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Guido
 * @since Guido 1.0
 */
$footer = apply_filters( 'guido_get_footer_layout', 'default' );
global $post;
?>
	</div><!-- .site-content -->
		<?php if ( !empty($footer) ): ?>
			<?php guido_display_footer_builder($footer); ?>
		<?php else: ?>
			<footer id="apus-footer" class="apus-footer " role="contentinfo">
				<div class="footer-default">
					<div class="apus-footer-inner">
						<div class="apus-copyright">
							<div class="container">
								<div class="copyright-content clearfix">
									<div class="text-copyright text-center">
										<?php
											
											$allowed_html_array = array( 'a' => array('href' => array()) );
											echo wp_kses(sprintf(__('&copy; %s - Guido. All Rights Reserved. <br/> Powered by <a href="//themeforest.net/user/apustheme">ApusTheme</a>', 'guido'), date("Y")), $allowed_html_array);
										?>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</footer><!-- .site-footer -->
		<?php endif; ?>
	<?php
	if ( guido_get_config('back_to_top') ) { ?>
		<a href="#" id="back-to-top" class="add-fix-top">
			<i class="flaticon-arrow-pointing-to-up"></i>
		</a>
	<?php
	}
	?>
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>