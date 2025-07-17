<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !empty($filters) ) {
	?>
	<div class="results-filter-wrapper">
		<h3 class="title"><?php esc_html_e('Your Selected', 'wp-listings-directory'); ?></h3>
		<div class="inner">
			<ul class="results-filter">
				<?php foreach ($filters as $key => $value) { ?>
					<?php WP_Listings_Directory_Listing_Filter::display_filter_value($key, $value, $filters); ?>
				<?php } ?>
			</ul>
			<a href="<?php echo esc_url(WP_Listings_Directory_Mixes::get_listings_page_url()); ?>"><?php esc_html_e('Clear all', 'wp-listings-directory'); ?></a>
		</div>
	</div>
<?php }