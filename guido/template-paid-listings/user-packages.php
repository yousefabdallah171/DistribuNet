<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( $user_packages ) : ?>
	<div class="widget-your-packages">
		<h2 class="title-profile"><?php esc_html_e( 'Your Packages', 'guido' ); ?></h2>
		<div class="box-white-dashboard">
			<div class="user-listing-packaged row">
				<?php
					$prefix = WP_LISTINGS_DIRECTORY_WC_PAID_LISTINGS_PREFIX;
					$checked = 1; foreach ( $user_packages as $key => $package ) :
					$package_count = get_post_meta($package->ID, $prefix.'package_count', true);
					$listing_limit = get_post_meta($package->ID, $prefix.'listing_limit', true);
					$listing_duration = get_post_meta($package->ID, $prefix.'listing_duration', true);
				?>
						<div class="col-xs-6 col-md-3">
							<div class="inner-user-listing-packaged">
								
								<input type="radio" <?php checked( $checked, 1 ); ?> name="wpldwpl_listing_package" value="user-<?php echo esc_attr($package->ID); ?>" id="user-package-<?php echo esc_attr($package->ID); ?>" />

								<label for="user-package-<?php echo esc_attr($package->ID); ?>">
									<span class="value">
										<?php echo trim($package->post_title); ?>
									</span>
									<span class="des-package">
										<?php
											if ( $listing_limit ) {
												printf( _n( '%s listing posted out of %d', '%s listings posted out of %d', $package_count, 'guido' ), $package_count, $listing_limit );
											} else {
												printf( _n( '%s listing posted', '%s listings posted', $package_count, 'guido' ), $package_count );
											}

											if ( $listing_duration ) {
												printf(  ', ' . _n( 'listed for %s day', 'listed for %s days', $listing_duration, 'guido' ), $listing_duration );
											}

											$checked = 0;
										?>
									</span>
								</label>
							</div>
						</div>
				<?php endforeach; ?>
			</div>
			<div class="bottom-packages">
				<button class="btn btn-theme" type="submit">
					<?php esc_html_e('ADD LISTING', 'guido') ?>
				</button>
			</div>
		</div>
	</div>
<?php endif; ?>