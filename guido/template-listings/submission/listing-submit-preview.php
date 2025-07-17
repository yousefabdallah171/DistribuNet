<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post, $listing_preview;
$listing_preview = $post;

$listing_layout = guido_get_listing_layout_type();
$listing_layout = !empty($listing_layout) ? $listing_layout : 'v1';
?>
<div class="listing-submission-preview-form-wrapper">
	<?php if ( sizeof($form_obj->errors) ) : ?>
			<?php foreach ( $form_obj->errors as $message ) { ?>
				<div class="alert alert-danger margin-bottom-15">
					<?php echo trim( $message ); ?>
				</div>
			<?php
			}
			?>
	<?php endif; ?>
	<form action="<?php echo esc_url($form_obj->get_form_action());?>" class="cmb-form" method="post" enctype="multipart/form-data" encoding="multipart/form-data">
		<input type="hidden" name="<?php echo esc_attr($form_obj->get_form_name()); ?>" value="<?php echo esc_attr($form_obj->get_form_name()); ?>">
		<input type="hidden" name="listing_id" value="<?php echo esc_attr($listing_id); ?>">
		<input type="hidden" name="submit_step" value="<?php echo esc_attr($step); ?>">
		<input type="hidden" name="object_id" value="<?php echo esc_attr($listing_id); ?>">
		<?php wp_nonce_field('wp-listings-directory-listing-submit-preview-nonce', 'security-listing-submit-preview'); ?>
		<div class="wrapper-action-listing">
			<button class="button btn btn-theme" name="continue-submit-listing"><?php esc_html_e('Submit Listing', 'guido'); ?></button>
			<button class="button btn btn-danger" name="continue-edit-listing"><?php esc_html_e('Edit Listing', 'guido'); ?></button>
		</div>
		

	</form>

	<?php
	$latitude = WP_Listings_Directory_Listing::get_post_meta( $post->ID, 'map_location_latitude', true );
	$longitude = WP_Listings_Directory_Listing::get_post_meta( $post->ID, 'map_location_longitude', true );
	?>
	<div class="single-listing-wrapper single-listing-wrapper" data-latitude="<?php echo esc_attr($latitude); ?>" data-longitude="<?php echo esc_attr($longitude); ?>">
		<?php
			if ( $listing_layout !== 'v1' ) {
				echo WP_Listings_Directory_Template_Loader::get_template_part( 'content-single-listing-'.$listing_layout );
			} else {
				echo WP_Listings_Directory_Template_Loader::get_template_part( 'content-single-listing' );
			}
		?>
	</div>
</div>
