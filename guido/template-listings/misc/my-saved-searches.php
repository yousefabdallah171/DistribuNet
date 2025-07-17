<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
guido_load_select2();
?>
<div class="clearfix">
	
	<h3 class="title-profile"><?php echo esc_html__('My Saved Search','guido') ?></h3>
	<?php if ( !empty($alerts) && !empty($alerts->posts) ) {
		$email_frequency_default = WP_Listings_Directory_Saved_Search::get_email_frequency(); ?>
		<div class="box-white-dashboard">

			<div class="space-30 d-sm-flex align-items-center top-dashboard-search">
					<div class="search-listings-saved-search-form search-listings-form">
						<form action="" method="get">
							<div class="input-group">
								<input type="text" placeholder="<?php esc_attr_e( 'Search ...', 'guido' ); ?>" class="form-control" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
								<button class="search-submit btn btn-search" name="submit">
									<i class="ti-search"></i>
								</button>
							</div>
							<input type="hidden" name="paged" value="1" />
						</form>
					</div>
					<div class="sort-listings-saved-search-form sortby-form ms-auto sort-my-listings-form">
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

			<div class="wrapper-save-search">
			<div class="table-responsive">
				<table class="listing-table user-transactions">
					<thead>
						<tr>
							<td class="title"><?php esc_html_e('Title', 'guido'); ?></td>
							<td class="alert-query"><?php esc_html_e('Saved Search Query', 'guido'); ?></td>
							<td class="listing-number"><?php esc_html_e('Number Listings', 'guido'); ?></td>
							<td class="listing-times"><?php esc_html_e('Times', 'guido'); ?></td>
							<td class="listing-actions"><?php esc_html_e('Actions', 'guido'); ?></td>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($alerts->posts as $saved_search_id) {
						
						$email_frequency = get_post_meta($saved_search_id, WP_LISTINGS_DIRECTORY_LISTING_SAVED_SEARCH_PREFIX . 'email_frequency', true);
						if ( !empty($email_frequency_default[$email_frequency]['label']) ) {
							$email_frequency = $email_frequency_default[$email_frequency]['label'];
						}

						$params = get_post_meta($saved_search_id, WP_LISTINGS_DIRECTORY_LISTING_SAVED_SEARCH_PREFIX . 'saved_search_query', true);

						$query_args = array(
							'post_type' => 'listing',
						    'post_status' => 'publish',
						    'post_per_page' => 1,
						    'fields' => 'ids'
						);
						$listings = WP_Listings_Directory_Query::get_posts($query_args, $params);
						$count_listings = $listings->found_posts;

						$listings_saved_search_url = WP_Listings_Directory_Mixes::get_listings_page_url();
						if ( !empty($params) ) {
							foreach ($params as $key => $value) {
								if ( is_array($value) ) {
									$listings_saved_search_url = remove_query_arg( $key.'[]', $listings_saved_search_url );
									foreach ($value as $val) {
										$listings_saved_search_url = add_query_arg( $key.'[]', $val, $listings_saved_search_url );
									}
								} else {
									$listings_saved_search_url = add_query_arg( $key, $value, remove_query_arg( $key, $listings_saved_search_url ) );
								}
							}
						}
						?>

						<?php do_action( 'wp_listings_directory_before_saved_search_content', $saved_search_id ); ?>
						<tr <?php post_class('saved-search-wrapper'); ?>>
							<td>
								<div class="listing-table-info-content-title">
						        	<a href="<?php echo esc_url($listings_saved_search_url); ?>" rel="bookmark"><?php echo get_the_title($saved_search_id); ?></a>
						        </div>
							</td>
							<td>
								<div class="alert-query">
						        	<?php
						        	$params = WP_Listings_Directory_Abstract_Filter::get_filters($params);
						        	if ( $params ) {
						        		?>
						        		<ul class="list">
						        			<?php
							        			foreach ($params as $key => $value) {
							        				WP_Listings_Directory_Listing_Filter::display_filter_value_simple($key, $value, $params);
							        			}
						        			?>
						        		</ul>
						        	<?php } ?>
						        </div>
							</td>
							<td>
								<div class="listing-found">
						            <?php echo sprintf(esc_html__('Listings found %d', 'guido'), intval($count_listings) ); ?>
						        </div>
							</td>
							<td>
								<div class="listing-metas">
						            <?php echo trim($email_frequency); ?>
						        </div>
							</td>
							<td>
								<a href="javascript:void(0)" data-bs-toggle="tooltip" class="btn-remove-saved-search btn-action-icon deleted" data-saved_search_id="<?php echo esc_attr($saved_search_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-listings-directory-remove-saved-search-nonce' )); ?>" title="<?php esc_attr_e('Remove', 'guido'); ?>"><i class="flaticon-delete"></i></a>
							</td>
						</tr>
						
						<?php do_action( 'wp_listings_directory_after_saved_search_content', $saved_search_id );
					}

					?>
					</tbody>
				</table>
			</div>
			</div>

			<?php WP_Listings_Directory_Mixes::custom_pagination( array(
				'wp_query' => $alerts,
				'max_num_pages' => $alerts->max_num_pages,
				'prev_text'     => '<i class="ti-angle-left"></i>',
				'next_text'     => '<i class="ti-angle-right"></i>',
			)); ?>
		</div>

	<?php } else { ?>
		<div class="not-found alert alert-warning"><?php esc_html_e('No listing alert found.', 'guido'); ?></div>
	<?php } ?>
</div>