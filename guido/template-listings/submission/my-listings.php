<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
guido_load_select2();
?>

<?php $my_listings_page_id = wp_listings_directory_get_option('my_listings_page_id'); ?>

<h1 class="title-profile"><?php esc_html_e( 'My Listings', 'guido' ) ; ?></h1>

<?php
	$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
	$query_vars = array(
		'post_type'     => 'listing',
		'post_status'   => apply_filters('wp-listings-directory-my-listings-post-statuses', array( 'publish', 'expired', 'pending', 'pending_approve', 'pending_payment', 'draft', 'preview' )),
		'paged'         => $paged,
		'author'        => get_current_user_id(),
		'orderby'		=> 'date',
		'order'			=> 'DESC',
	);
	if ( isset($_GET['search']) ) {
		$query_vars['s'] = $_GET['search'];
	}
	if ( isset($_GET['orderby']) ) {
		switch ($_GET['orderby']) {
			case 'menu_order':
				$query_vars['orderby'] = array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
					'ID'         => 'DESC',
				);
				break;
			case 'newest':
				$query_vars['orderby'] = 'date';
				$query_vars['order'] = 'DESC';
				break;
			case 'oldest':
				$query_vars['orderby'] = 'date';
				$query_vars['order'] = 'ASC';
				break;
		}
	}
	$listings = new WP_Query($query_vars);

?>
<div class="box-white-dashboard">

	<div class="space-30">
		<div class="d-sm-flex align-items-center top-dashboard-search">
			<div class="search-my-listings-form widget-search search-listings-form">
				<form action="<?php echo esc_url(get_permalink( $my_listings_page_id )); ?>" method="get">
					<div class="input-group">
						<input placeholder="<?php esc_attr_e('Search ...', 'guido'); ?>" class="form-control" type="text" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
						<button class="search-submit btn btn-search" name="submit">
							<i class="flaticon-loupe"></i>
						</button>
					</div>
				</form>
			</div>
			<div class="sort-my-listings-form sortby-form ms-auto">
				<?php
					$orderby_options = apply_filters( 'wp_listings_directory_my_listings_orderby', array(
						'menu_order'	=> esc_html__( 'Default', 'guido' ),
						'newest' 		=> esc_html__( 'Newest', 'guido' ),
						'oldest'     	=> esc_html__( 'Oldest', 'guido' ),
					) );

					$orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : 'newest'; 
				?>

				<div class="orderby-wrapper d-flex align-items-center">
					<span class="text-sort">
						<?php echo esc_html__('Sort by: ','guido'); ?>
					</span>
					<form class="my-listings-ordering" method="get">
						<select name="orderby" class="orderby">
							<?php foreach ( $orderby_options as $id => $name ) : ?>
								<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="paged" value="1" />
						<?php WP_Listings_Directory_Mixes::query_string_form_fields( null, array( 'orderby', 'submit', 'paged' ) ); ?>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="inner border-my-listings">
		<div class="layout-my-listings d-flex align-items-center header-layout">
			<div class="listing-thumbnail-wrapper">
				<?php echo esc_html__('Image','guido') ?>
			</div>
			<div class="layout-left d-flex align-items-center inner-info">
				<div class="inner-info-left">
					<?php echo esc_html__('Information','guido') ?>
				</div>
				<div class="d-none d-md-block">
					<?php echo esc_html__('Expiry','guido') ?>
				</div>
				<div class="d-none d-md-block">
					<?php echo esc_html__('Status','guido') ?>
				</div>
				<div class="d-none d-lg-block">
					<?php echo esc_html__('View','guido') ?>
				</div>
				<div>
					<?php echo esc_html__('Action','guido') ?>
				</div>
			</div>
		</div>
		<?php if ( $listings->have_posts() ) : ?>
			<?php while ( $listings->have_posts() ) : $listings->the_post(); global $post; ?>
				<?php $is_featured = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'featured', true ); ?>
				<div class="my-listings-item listing-item">
					<div class="d-flex align-items-center layout-my-listings">
						<div class="listing-thumbnail-wrapper">
							<?php guido_listing_display_image( $post, 'thumbnail' ); ?>
						</div>
						<div class="inner-info d-flex align-items-center layout-left">
							<div class="inner-info-left">
								<?php if ( $is_featured ) : ?>
									<span class="text-info featured"><?php echo esc_html__('Featured','guido') ?></span>
								<?php endif; ?>
								<h3 class="listing-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	            				<?php guido_listing_display_price($post, 'no-icon-title', true); ?>
							</div>
							<div class="listing-info-date-expiry d-none d-md-block">
								<div class="listing-table-info-content-expiry">
									<?php
										$expires = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'expiry_date', true);
										if ( $expires ) {
											echo '<span>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ) . '</span>';
										} else {
											echo '--';
										}
									?>
								</div>
							</div>
							<div class="status-listing-wrapper d-none d-md-block">
								<span class="status-listing <?php echo esc_attr($post->post_status); ?>">
									<?php
										$post_status = get_post_status_object( $post->post_status );
										if ( !empty($post_status->label) ) {
											echo esc_html($post_status->label);
										} else {
											echo esc_html($post->post_status);
										}
									?>
								</span>
							</div>
							<div class="view-listing-wrapper d-none d-lg-block">
								<?php
									$views = get_post_meta($post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'views', true);
									echo WP_Listings_Directory_Mixes::format_number($views);
								?>
							</div>
							<div class="warpper-action-listing">

								<?php
								$my_listings_page_url = get_permalink( $my_listings_page_id );
								$my_listings_page_url = add_query_arg( 'listing_id', $post->ID, remove_query_arg( 'listing_id', $my_listings_page_url ) );
								switch ( $post->post_status ) {
									case 'publish' :
										$edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_listings_page_url ) );
										
										$edit_able = wp_listings_directory_get_option('user_edit_published_submission');
										if ( $edit_able !== 'no' ) {
										?>
											<a data-bs-toggle="tooltip" href="<?php echo esc_url($edit_url); ?>" class="edit-btn btn-action-icon edit  job-table-action" title="<?php esc_attr_e('Edit', 'guido'); ?>">
												<i class="flaticon-edit"></i>
											</a>
										<?php } ?>
										<?php
										break;
									case 'expired' :
										$relist_url = add_query_arg( 'action', 'relist', remove_query_arg( 'action', $my_listings_page_url ) );
										?>
										<a data-bs-toggle="tooltip" href="<?php echo esc_url($relist_url); ?>" class="btn-action-icon view  job-table-action" title="<?php esc_attr_e('Relist', 'guido'); ?>">
											<i class="ti-reload"></i>
										</a>
										<?php
										break;
									case 'pending_payment':
										$order_id = get_post_meta($post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX.'order_id', true);
										if ( $order_id ) {
											$edit_able = wp_listings_directory_get_option('user_edit_published_submission');
											if ( $edit_able !== 'no' ) {
												$edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_listings_page_url ) );
												?>
												<a data-bs-toggle="tooltip" href="<?php echo esc_url($edit_url); ?>" class="edit-btn btn-action-icon edit  job-table-action" title="<?php esc_attr_e('Edit', 'guido'); ?>">
													<i class="flaticon-edit"></i>
												</a>
												<?php
											}
										} else {
											$continue_url = add_query_arg( 'action', 'continue', remove_query_arg( 'action', $my_listings_page_url ) );
											?>
											<a data-bs-toggle="tooltip" href="<?php echo esc_url($continue_url); ?>" class="edit-btn btn-action-icon edit  job-table-action" title="<?php esc_attr_e('Continue', 'guido'); ?>">
												<i class="flaticon-arrow-pointing-to-right"></i>
											</a>
											<?php
										}
										break;
									case 'pending_approve':
									case 'pending' :
										$edit_able = wp_listings_directory_get_option('user_edit_published_submission');
										if ( $edit_able !== 'no' ) {
											$edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $my_listings_page_url ) );
											?>
											<a data-bs-toggle="tooltip" href="<?php echo esc_url($edit_url); ?>" class="edit-btn btn-action-icon edit  job-table-action" title="<?php esc_attr_e('Edit', 'guido'); ?>">
												<i class="flaticon-edit"></i>
											</a>
											<?php
										}
									break;
									case 'draft' :
									case 'preview' :
										$continue_url = add_query_arg( 'action', 'continue', remove_query_arg( 'action', $my_listings_page_url ) );
										?>
										<a data-bs-toggle="tooltip" href="<?php echo esc_url($continue_url); ?>" class="edit-btn btn-action-icon edit  job-table-action" title="<?php esc_attr_e('Continue', 'guido'); ?>">
											<i class="flaticon-arrow-pointing-to-right"></i>
										</a>
										<?php
										break;
								}
								?>

								<a data-bs-toggle="tooltip" class="remove-btn btn-action-icon listing-table-action listing-button-delete" href="javascript:void(0)" data-listing_id="<?php echo esc_attr($post->ID); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-listings-directory-delete-listing-nonce' )); ?>" title="<?php esc_attr_e('Remove', 'guido'); ?>">
									<i class="flaticon-delete"></i>
								</a>
							</div>
						</div>
					</div>

				</div>
			<?php endwhile; ?>
			<?php
				WP_Listings_Directory_Mixes::custom_pagination( array(
					'max_num_pages' => $listings->max_num_pages,
					'prev_text'     => '<i class="ti-angle-left"></i>',
					'next_text'     => '<i class="ti-angle-right"></i>',
					'wp_query' 		=> $listings
				));
				
				wp_reset_postdata();
			?>
		<?php else : ?>
			<div class="alert alert-warning">
				<p><?php esc_html_e( 'You don\'t have any listings yet. Start by creating new one.', 'guido' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>