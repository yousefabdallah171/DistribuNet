<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
guido_load_select2();
?>
<h1 class="title-profile"><?php esc_html_e( 'Favorite', 'guido' ) ; ?></h1>
<?php
if ( !empty($listing_ids) && is_array($listing_ids) ) {
	if ( get_query_var( 'paged' ) ) {
	    $paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
	    $paged = get_query_var( 'page' );
	} else {
	    $paged = 1;
	}
	$query_args = array(
		'post_type'         => 'listing',
		'posts_per_page'    => get_option('posts_per_page'),
		'paged'    			=> $paged,
		'post_status'       => 'publish',
		'post__in'       	=> $listing_ids,
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

	$listings = new WP_Query($query_args);
	if ( $listings->have_posts() ) { ?>
		<div class="box-white-dashboard pb-0">
			<div class="space-30">
					<div class="d-sm-flex align-items-center top-dashboard-search">
						<div class="search-listings-favorite-form widget-search search-listings-form">
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
						<div class="sort-listings-favorite-form sortby-form ms-auto">
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

			<div class="row">
				<?php while ( $listings->have_posts() ) : $listings->the_post();
					?>
					<div class="col-12 col-md-6 col-lg-4 listing-favorite-wrapper">
						<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-list-favorite' ); ?>
					</div>
					<?php
				endwhile; ?>

			</div>

			<?php wp_reset_postdata();

			WP_Listings_Directory_Mixes::custom_pagination( array(
				'max_num_pages' => $listings->max_num_pages,
				'prev_text'     => '<i class="ti-angle-left"></i>',
				'next_text'     => '<i class="ti-angle-right"></i>',
				'wp_query' 		=> $listings
			)); ?>
		</div>
		<?php
	}
?>

<?php } else { ?>
	<div class="not-found alert alert-warning"><?php esc_html_e('No listings found.', 'guido'); ?></div>
<?php } ?>