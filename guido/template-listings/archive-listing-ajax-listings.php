<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout_type = guido_get_listings_layout_type();

$listings_display_mode = guido_get_listings_display_mode();
$listing_inner_style = guido_get_listings_inner_style();


$total = $listings->found_posts;
$per_page = $listings->query_vars['posts_per_page'];
$current = max( 1, $listings->get( 'paged', 1 ) );
$last  = min( $total, $per_page * $current );

$pre_page  = max( 0, ($listings->get( 'paged', 1 ) - 1 ) );
$i =  $per_page * $pre_page;

?>
<div class="results-count">
	<span class="last"><?php echo esc_html($last); ?></span>
</div>

<div class="items-wrapper">
	<?php if ( $listings_display_mode == 'grid' ) {
		$columns = guido_get_listings_columns();
		$bcol = $columns ? 12/$columns : 4;

		if( $layout_type == 'half-map' ) {
			$ct = ($columns && $columns >= 2) ? 6 : 1;
		} else {
			$ct = '12';
		}
	?>
			<?php while ( $listings->have_posts() ) : $listings->the_post(); ?>
				<div class="col-sm-6 col-md-6 col-lg-<?php echo esc_attr($bcol); ?> col-ct-<?php echo esc_attr($ct); ?> col-12 <?php echo esc_attr(($i%$columns == 0)?'lg-clearfix':'') ?> <?php echo esc_attr(($i%2 == 0)?'md-clearfix sm-clearfix':'') ?>">
					<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-'.$listing_inner_style ); ?>
				</div>
			<?php $i++; endwhile; ?>
	<?php } else { ?>
		<?php while ( $listings->have_posts() ) : $listings->the_post(); ?>
			<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-'.$listing_inner_style ); ?>
		<?php endwhile; ?>
	<?php } ?>
</div>

<div class="apus-pagination-next-link"><?php next_posts_link( '&nbsp;', $listings->max_num_pages ); ?></div>