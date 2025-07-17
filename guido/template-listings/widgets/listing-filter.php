<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !empty($args['before_widget']) ) {
	echo trim( $args['before_widget'] );
}

if ( ! empty( $instance['title'] ) ) {
	echo trim( $args['before_title'] );
	echo esc_attr( $instance['title'] );
	echo trim( $args['after_title'] );
}
guido_load_select2();
?>

<form method="get" action="<?php echo WP_Listings_Directory_Mixes::get_listings_page_url(); ?>" class="filter-listing-form filter-listing-form">
	<?php $fields = $fields_adv = WP_Listings_Directory_Listing_Filter::get_fields(); ?>
	<?php if ( ! empty( $instance['sort'] ) ) : ?>
		<?php
			$filtered_keys = array_filter( explode( ',', $instance['sort'] ) );
			$fields = array_merge( array_flip( $filtered_keys ), $fields );
		?>
	<?php endif; ?>
	<div class="main-filter clearfix">
		<?php foreach ( $fields as $key => $field ) : ?>
			<?php
				if ( empty( $instance['hide_'.$key] ) && !empty($field['field_call_back']) && is_callable($field['field_call_back']) ) {
					call_user_func( $field['field_call_back'], $instance, $args, $key, $field );
				}
			?>
		<?php endforeach; ?>

		
	</div>	
	<?php if ( ! empty( $instance['show_adv_fields'] ) ) : ?>
		<div class="more-advanced clearfix">
			<a href="#toggle_adv" class="filter-toggle-adv visiable-line">
				<i class="flaticon-more"></i> <span><?php esc_html_e('Advanced Search', 'guido'); ?></span>
			</a>
		</div>
		<div class="filter-advance-fields clearfix">
			<?php if ( ! empty( $instance['sort_adv'] ) ) : ?>
				<?php
				$filtered_keys = array_filter( explode( ',', $instance['sort_adv'] ) );
				$fields_adv = array_merge( array_flip( $filtered_keys ), $fields_adv );
				?>
			<?php endif; ?>

			<?php foreach ( $fields_adv as $key => $field ) : ?>
				
				<?php
					if ( empty( $instance['hide_adv_'.$key] ) && !empty($field['field_call_back']) && is_callable($field['field_call_back']) ) {
						call_user_func( $field['field_call_back'], $instance, $args, $key, $field );
					}
				?>

			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	
	<?php if ( ! empty( $instance['button_text'] ) ) : ?>
		<div class="form-group no-margin">
			<button class="button btn"><?php echo esc_attr( $instance['button_text'] ); ?></button>
		</div><!-- /.form-group -->
	<?php endif; ?>
</form>

<?php
if ( !empty($args['after_widget']) ) {
	echo trim( $args['after_widget'] );
}
?>

