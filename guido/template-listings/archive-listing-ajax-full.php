<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = array(
	'listings' => $listings
);

$layout_type = guido_get_listings_layout_type();
if ( $layout_type == 'top-map' ) {
	$listings_page = WP_Listings_Directory_Mixes::get_listings_page_url();
	$display_mode = guido_get_listings_display_mode();
	?>
	<div class="listings-display-mode-wrapper-ajax">
		<?php echo guido_display_mode_form($display_mode, $listings_page); ?>
	</div>
	<?php
}
?>

<?php
	echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/archive-inner', $args);
?>

<?php echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/pagination', array('listings' => $listings) ); ?>

