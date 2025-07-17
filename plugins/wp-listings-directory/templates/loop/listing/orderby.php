<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$orderby_options = apply_filters( 'wp-listings-directory-listings-orderby', array(
	'menu_order' => esc_html__('Default', 'wp-listings-directory'),
	'newest' => esc_html__('Newest', 'wp-listings-directory'),
	'oldest' => esc_html__('Oldest', 'wp-listings-directory'),
	'price-lowest' => esc_html__('Lowest Price', 'wp-listings-directory'),
	'price-highest' => esc_html__('Highest Price', 'wp-listings-directory'),
	'random' => esc_html__('Random', 'wp-listings-directory'),
));
$orderby = isset( $_GET['filter-orderby'] ) ? wp_unslash( $_GET['filter-orderby'] ) : 'menu_order';
if ( !WP_Listings_Directory_Mixes::is_ajax_request() ) {
	wp_enqueue_script('wpld-select2');
	wp_enqueue_style('wpld-select2');
}
?>
<div class="listings-ordering">
	<form class="listings-ordering" method="get" action="<?php echo WP_Listings_Directory_Mixes::get_listings_page_url(); ?>">
		<div class="label"><?php esc_html_e('Sort by:', 'wp-listings-directory'); ?></div>
		<select name="filter-orderby" class="orderby" data-placeholder="<?php esc_attr_e('Sort by', 'wp-listings-directory'); ?>">
			<?php foreach ( $orderby_options as $id => $name ) : ?>
				<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
		<input type="hidden" name="paged" value="1" />
		<?php WP_Listings_Directory_Mixes::query_string_form_fields( null, array( 'filter-orderby', 'submit', 'paged' ) ); ?>
	</form>
</div>