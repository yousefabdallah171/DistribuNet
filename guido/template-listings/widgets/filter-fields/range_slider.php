<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style = isset($field['slider_style']) ? $field['slider_style'] : '';
$suffix = isset($field['suffix']) ? $field['suffix'] : '';

?>
<div class="form-group form-group-<?php echo esc_attr($key); ?> <?php echo esc_attr($style); ?>">
	
    <div class="form-group-inner">
    	<?php
			$min_val = ! empty( $_GET[$name.'-from'] ) ? esc_attr( $_GET[$name.'-from'] ) : $min;
			$max_val = ! empty( $_GET[$name.'-to'] ) ? esc_attr( $_GET[$name.'-to'] ) : $max;
		?>
    	<?php if ( $style == 'text' ) { ?>
			<div class="from-to-wrapper from-to-text-wrapper">
				<?php if ( !isset($field['show_title']) || $field['show_title'] ) { ?>
			    	<label class="heading-label">
			    		<?php echo trim($field['name']); ?>
			    	</label>
			    <?php } ?>
				<div class="heading-filter-price">
					<div class="inner">
				  		<?php if ( !empty($field['placeholder']) ) { ?>
				    		<?php echo trim($field['placeholder']); ?>
					    <?php } elseif ( !empty($field['name']) ) { ?>
					    	<?php echo trim($field['name']); ?>
					    <?php } ?>

					    <span class="price-text">
					    	
					    	<span class="from-text"><?php echo esc_html($min_val); ?></span>
							<span class="space">-</span>
							<span class="to-text"><?php echo esc_html($max_val); ?></span>
							<?php echo trim(!empty($suffix) ? '<span class="suffix">'.$suffix.'</span>' :''); ?>
					    </span>
				    </div>
			    </div>
			    <div class="price-input-wrapper">
			    	<div class="row row-20">
			    		<div class="col-xs-6">
							<input type="number" name="<?php echo esc_attr($name.'-from'); ?>" class="form-control filter-from" value="<?php echo esc_attr($min_val); ?>" placeholder="<?php echo esc_attr(!empty($field['min_placeholder']) ? $field['min_placeholder'] : ''); ?>">
						</div>
						<div class="col-xs-6">
					  		<input type="number" name="<?php echo esc_attr($name.'-to'); ?>" class="form-control filter-to" value="<?php echo esc_attr($max_val); ?>" placeholder="<?php echo esc_attr(!empty($field['max_placeholder']) ? $field['max_placeholder'] : ''); ?>">
					  	</div>
			  		</div>
		  		</div>
			</div>
			
		<?php } else { ?>

			<div class="from-to-wrapper">
				<?php if ( !isset($field['show_title']) || $field['show_title'] ) { ?>
			    	<label class="heading-label">
			    		<?php echo trim($field['name']); ?>
			    	</label>
			    <?php } ?>
				
			</div>
		  	<div class="main-range-slider" data-max="<?php echo esc_attr($max); ?>" data-min="<?php echo esc_attr($min); ?>"></div>
		  	<div class="inner">
		  		<span class="from-text-wrapper">
					<span class="from-text">
						<?php echo esc_html($min_val); ?>
					</span>
					<?php echo trim(!empty($suffix) ? '<span class="suffix">'.$suffix.'</span>' :''); ?>
				</span>
				<span class="to-text-wrapper float-end">
					<span class="to-text">
						<?php echo esc_html($max_val); ?>
					</span>
					<?php echo trim(!empty($suffix) ? '<span class="suffix">'.$suffix.'</span>' :''); ?>
				</span>
			</div>
		  	<input type="hidden" name="<?php echo esc_attr($name.'-from'); ?>" class="filter-from" value="<?php echo esc_attr($min_val); ?>">
		  	<input type="hidden" name="<?php echo esc_attr($name.'-to'); ?>" class="filter-to" value="<?php echo esc_attr($max_val); ?>">
	  <?php } ?>
	</div>
</div><!-- /.form-group -->