<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="listings-pagination-wrapper">
	<?php
		WP_Listings_Directory_Mixes::custom_pagination( array(
			'max_num_pages' => $listings->max_num_pages,
			'prev_text'     => esc_html__( 'Previous page', 'wp-listings-directory' ),
			'next_text'     => esc_html__( 'Next page', 'wp-listings-directory' ),
			'wp_query' 		=> $listings
		));
	?>
</div>
