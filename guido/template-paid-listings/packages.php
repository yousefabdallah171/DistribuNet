<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( $packages ) : ?>
	<div class="widget-packages widget-subwoo woocommerce">
		<h2 class="title-profile text-center"><?php esc_html_e( 'Packages', 'guido' ); ?></h2>
		<div class="row">
			<?php foreach ( $packages as $key => $package ) :
				$product = wc_get_product( $package );
				if ( ! $product->is_type( array( 'listing_package', 'listing_package_subscription' ) ) || ! $product->is_purchasable() ) {
					continue;
				}
				?>
				<div class="col-sm-6 col-lg-4 col-12">
					<div class="subwoo-inner <?php echo esc_attr($product->is_featured()?'is_featured':''); ?>">
						<div class="item">
							<div class="header-sub">
								<h3 class="title"><?php echo trim($product->get_title()); ?></h3>
								<div class="price">
									<?php echo (!empty($product->get_price())) ? $product->get_price_html() : esc_html__('Free', 'guido'); ?>
								</div>
							</div>
							<div class="bottom-sub">
								<div class="short-des"><?php echo apply_filters( 'the_excerpt', get_post_field('post_excerpt', $product->get_id()) ) ?></div>
								<div class="button-action">
									<div class="add-cart">
										<button class="button" type="submit" name="wpldwpl_listing_package" value="<?php echo esc_attr($product->get_id()); ?>" id="package-<?php echo esc_attr($product->get_id()); ?>">
											<i class="flaticon-shopping-bag"></i><?php esc_html_e('Get Started', 'guido') ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach;
				wp_reset_postdata();
			?>
		</div>
		<div class="bottom-packages" style="text-align:center; margin-top: 30px;">
			<button class="btn btn-theme" type="submit">
				<?php esc_html_e('ADD LISTING', 'guido'); ?>
			</button>
		</div>
	</div>
<?php endif; ?>