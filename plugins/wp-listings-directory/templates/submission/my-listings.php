<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_script('wpld-select2');
wp_enqueue_style('wpld-select2');
?>

<?php $my_listings_page_id = wp_listings_directory_get_option('my_listings_page_id'); ?>


<div class="search-orderby-wrapper">
	<div class="search-my-listings-form">
		<form action="<?php echo esc_url(get_permalink( $my_listings_page_id )); ?>" method="get">
			<div class="form-group">
				<input type="text" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
			</div>
			<div class="submit-wrapper">
				<button class="search-submit" name="submit">
					<?php esc_html_e( 'Search ...', 'wp-listings-directory' ); ?>
				</button>
			</div>
		</form>
	</div>
	<div class="sort-my-listings-form sortby-form">
		<?php
			$orderby_options = apply_filters( 'wp_listings_directory_my_listings_orderby', array(
				'menu_order'	=> esc_html__( 'Default', 'wp-listings-directory' ),
				'newest' 		=> esc_html__( 'Newest', 'wp-listings-directory' ),
				'oldest'     	=> esc_html__( 'Oldest', 'wp-listings-directory' ),
			) );

			$orderby = isset( $_GET['orderby'] ) ? wp_unslash( $_GET['orderby'] ) : 'newest'; 
		?>

		<div class="orderby-wrapper">
			<span>
				<?php echo esc_html__('Sort by: ','wp-listings-directory'); ?>
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

<?php
	$paged = (get_query_var( 'paged' )) ? get_query_var( 'paged' ) : 1;
	$query_vars = array(
		'post_type'     => 'listing',
		'post_status'   => 'any',
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
	// query_posts($query_vars);
	$listings = new WP_Query($query_vars);

	if ( $listings->have_posts() ) : ?>
	<table class="listing-table">
		<thead>
			<tr>
				<th class="listing-title"><?php esc_html_e('Listing Title', 'wp-listings-directory'); ?></th>
				<th class="listing-status"><?php esc_html_e('Status', 'wp-listings-directory'); ?></th>
				<th class="listing-actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php while ( $listings->have_posts() ) : $listings->the_post(); global $post; ?>
			<tr class="my-listings-item">
				<td class="listing-table-info">
					
					<div class="listing-table-info-content">
						<div class="listing-table-info-content-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

							<?php $is_urgent = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'urgent', true ); ?>
							<?php if ( $is_urgent ) : ?>
								<span class="urgent-lable"><?php esc_html_e( 'Urgent', 'wp-listings-directory' ); ?></span>
							<?php endif; ?>

							<?php $is_featured = get_post_meta( $post->ID, WP_LISTINGS_DIRECTORY_LISTING_PREFIX . 'featured', true ); ?>
							<?php if ( $is_featured ) : ?>
								<span class="featured-lable"><?php esc_html_e( 'Featured', 'wp-listings-directory' ); ?></span>
							<?php endif; ?>

						</div>

						<?php $location = WP_Listings_Directory_Query::get_listing_location_name(); ?>
						<?php if ( ! empty( $location ) ) : ?>
							<div class="listing-table-info-content-location">
								<?php echo wp_kses( $location, wp_kses_allowed_html( 'post' ) ); ?>
							</div>
						<?php endif; ?>
						
						<div class="listing-table-info-content-date-expiry">
							<div class="listing-table-info-content-date">
								<?php esc_html_e('Created: ', 'wp-listings-directory'); ?>
								<span><?php the_time( get_option('date_format') ); ?></span>
							</div>
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
					</div>
				</td>

				<td class="listing-table-status min-width nowrap">
					<div class="listing-table-actions-inner <?php echo esc_attr($post->post_status); ?>">
						<?php echo get_post_status(); ?>
					</div>
				</td>

				<td class="listing-table-actions min-width nowrap">
					<a class="view-btn" href="<?php the_permalink(); ?>" title="<?php esc_attr_e('View', 'wp-listings-directory'); ?>"><?php esc_html_e('View', 'wp-listings-directory'); ?></a>

					<?php if ( ! empty( $my_listings_page_id ) ) :
						$edit_url = get_permalink( $my_listings_page_id );
						$edit_url = add_query_arg( 'listing_id', $post->ID, remove_query_arg( 'listing_id', $edit_url ) );
						$edit_url = add_query_arg( 'action', 'edit', remove_query_arg( 'action', $edit_url ) );
					?>
						<a class="edit-btn" href="<?php echo esc_url($edit_url); ?>" class="listing-table-action" title="<?php esc_attr_e('Edit', 'wp-listings-directory'); ?>">
							<?php esc_html_e( 'Edit', 'wp-listings-directory' ); ?>
						</a>
					<?php endif; ?>

					<a class="remove-btn listing-table-action listing-button-delete" href="javascript:void(0)" data-listing_id="<?php echo esc_attr($post->ID); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'wp-listings-directory-delete-listing-nonce' )); ?>" title="<?php esc_attr_e('Remove', 'wp-listings-directory'); ?>">
						<?php esc_html_e( 'Remove', 'wp-listings-directory' ); ?>
					</a>

				</td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>

	<?php
		WP_Listings_Directory_Mixes::custom_pagination( array(
			'max_num_pages' => $listings->max_num_pages,
			'prev_text'     => '<i class="flaticon-left-arrow"></i>',
			'next_text'     => '<i class="flaticon-right-arrow"></i>',
			'wp_query' 		=> $listings
		));
		
		wp_reset_postdata();
	?>
<?php else : ?>
	<div class="alert alert-warning">
		<p><?php esc_html_e( 'You don\'t have any listings, yet. Start by creating new one.', 'wp-listings-directory' ); ?></p>
	</div>
<?php endif; ?>
