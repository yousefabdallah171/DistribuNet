<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wp_query;

if ( get_query_var( 'paged' ) ) {
    $paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
    $paged = get_query_var( 'page' );
} else {
    $paged = 1;
}

$query_args = array(
	'post_type' => 'listing',
    'post_status' => 'publish',
    'post_per_page' => wp_listings_directory_get_option('number_listings_per_page', 10),
    'paged' => $paged,
);
$params = array();
$taxs = ['type', 'category', 'feature', 'location'];
foreach ($taxs as $tax) {
	if ( is_tax('listing_'.$tax) ) {
		$term = $wp_query->queried_object;
		if ( isset( $term->term_id) ) {
			$params['filter-'.$tax] = $term->term_id;
		}
	}
}

if ( WP_Listings_Directory_Abstract_Filter::has_filter() ) {
	$params = array_merge($params, $_GET);
}
$listings = WP_Listings_Directory_Query::get_posts($query_args, $params);


if ( isset( $_REQUEST['load_type'] ) && WP_Listings_Directory_Mixes::is_ajax_request() ) {
	if ( 'items' !== $_REQUEST['load_type'] ) {
        echo WP_Listings_Directory_Template_Loader::get_template_part('archive-listing-ajax-full', array('listings' => $listings));
	} else {
		echo WP_Listings_Directory_Template_Loader::get_template_part('archive-listing-ajax-listings', array('listings' => $listings));
	}

} else {
	get_header();

	$layout_type = guido_get_listings_layout_type();

	if ( $layout_type == 'half-map' ) {

		$first_class = 'col-xl-5 col-lg-6 col-12 first_class p-0';
		$second_class = 'col-xl-7 col-lg-6 col-12 second_class p-0';
		
		$filter_type = guido_get_listings_half_map_filter_type();

		if ( $filter_type == 'filter-top') {
			$sidebar = 'listings-filter-half-map';
			$sidebar_wrapper_class = 'listings-filter-half-map';
		} else {
			$sidebar = 'listings-filter';
			$sidebar_wrapper_class = 'offcanvas-filter-half-map';
		}
	?>
		<section id="main-container" class="inner layout-type-<?php echo esc_attr($layout_type); ?>">

   			<div class="mobile-groups-button d-block d-lg-none clearfix text-center">
   				<?php
			    if ( $filter_type == 'offcanvas' && is_active_sidebar( $sidebar ) ) {
			        ?>
			        <span class="filter-in-sidebar btn btn-sm btn-theme"><i class="fa fa-filter pre"></i><span class="text"><?php esc_html_e('Open Filter', 'guido'); ?></span></span>
			        <?php
			    } else {
			    	?>
			        <span class="filter-in-half-map-top btn btn-sm btn-theme"><i class="fa fa-filter pre"></i><span class="text"><?php esc_html_e('Open Filter', 'guido'); ?></span></span>
			        <?php
			    }
			    ?>

				<button class=" btn btn-sm btn-theme btn-view-map" type="button"><i class="fas fa-map pre" aria-hidden="true"></i> <?php esc_html_e( 'Map View', 'guido' ); ?></button>
				<button class=" btn btn-sm btn-theme btn-view-listing" type="button"><i class="fas fa-list pre" aria-hidden="true"></i> <?php esc_html_e( 'Listings View', 'guido' ); ?></button>
			</div>

			<div class="row m-0">

				<div id="main-content" class="<?php echo esc_attr($first_class); ?>">
					<div class="inner-left">
						<?php if( is_active_sidebar( $sidebar ) ) { ?>
				   			<div class=" <?php echo esc_attr($sidebar_wrapper_class); ?>">
				   				<div class="inner">
						   			<?php dynamic_sidebar( $sidebar ); ?>
						   		</div>
						   	</div>
						   	<div class="over-dark-filter"></div>
					   	<?php } ?>
					   	<div class="content-listing">
					   		
							<?php
								echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/archive-inner', array('listings' => $listings));

								echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/pagination', array('listings' => $listings));
							?>

						</div>
					</div>
				</div><!-- .content-area -->

				<div class="<?php echo esc_attr($second_class); ?>">
					<div id="listings-google-maps" class="fix-map d-none d-lg-block"></div>
				</div>
			</div>
		</section>
	<?php
	} else {
		$sidebar_configs = guido_get_listings_layout_configs();
		$filter_top_sidebar = guido_get_listings_filter_top_sidebar();
	?>
		
		<section id="main-container" class="inner layout-type-<?php echo esc_attr($layout_type); ?> <?php echo esc_attr(guido_get_listings_show_filter_top()?'has-filter-top':''); ?>">
			
			<div class="mobile-groups-button d-block d-lg-none clearfix text-center">
				<?php if ( is_active_sidebar( $filter_top_sidebar ) && guido_get_listings_show_filter_top() ) { ?>
					<span class="filter-in-sidebar-top btn btn-sm btn-theme"><i class="fa fa-filter"></i><span class="text"><?php esc_html_e('Open Filter', 'guido'); ?></span></span>
				<?php } ?>

				<?php if ( $layout_type == 'top-map' ) { ?>
					<button class=" btn btn-sm btn-theme btn-view-map" type="button"><i class="fas fa-map" aria-hidden="true"></i> <?php esc_html_e( 'Map View', 'guido' ); ?></button>
					<button class=" btn btn-sm btn-theme btn-view-listing d-none d-lg-block" type="button"><i class="fas fa-list" aria-hidden="true"></i> <?php esc_html_e( 'Listings View', 'guido' ); ?></button>
				<?php } ?>
			</div>

			<?php if ( $layout_type == 'top-map' ) { ?>
				<div id="listings-google-maps" class="d-none d-lg-block top-map"></div>
			<?php } ?>

			<?php
			if ( is_active_sidebar( $filter_top_sidebar ) && guido_get_listings_show_filter_top() ) { ?>
				<div class="listings-filter-top-sidebar-wrapper">
					<div class="inner">
				   		<?php dynamic_sidebar( $filter_top_sidebar ); ?>
				   	</div>
			   	</div>
			<?php } ?>

			<?php
				guido_render_breadcrumbs();
			?>

			<div class="main-content <?php echo apply_filters('guido_listing_content_class', 'container');?> inner">
				
				<?php guido_before_content( $sidebar_configs ); ?>
				
				<div class="row">
					<?php guido_display_sidebar_left( $sidebar_configs ); ?>

					<div id="main-content" class="col-sm-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?>">
						<main id="main" class="site-main layout-type-<?php echo esc_attr($layout_type); ?>" role="main">

							<?php
								echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/archive-inner', array('listings' => $listings));

								echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/pagination', array('listings' => $listings));
							?>

						</main><!-- .site-main -->
					</div><!-- .content-area -->
					
					<?php guido_display_sidebar_right( $sidebar_configs ); ?>
				</div>

			</div>
		</section>
	<?php
	}

	get_footer();
}