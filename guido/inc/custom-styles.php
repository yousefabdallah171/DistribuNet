<?php
if ( !function_exists ('guido_custom_styles') ) {
	function guido_custom_styles() {
		global $post;	
		
		ob_start();	
		?>
		
			<?php if ( guido_get_config('main_color') != "" ) {
				$main_color = guido_get_config('main_color');
			} else {
				$main_color = '#234DD4';
			}
			if ( guido_get_config('second_color') != "" ) {
				$second_color = guido_get_config('second_color');
			} else {
				$second_color = '#F5C34B';
			}

			if ( guido_get_config('main_hover_color') != "" ) {
				$main_hover_color = guido_get_config('main_hover_color');
			} else {
				$main_hover_color = '#1c3da8';
			}

			if ( guido_get_config('second_hover_color') != "" ) {
				$second_hover_color = guido_get_config('second_hover_color');
			} else {
				$second_hover_color = '#0A2357';
			}

			if ( guido_get_config('text_color') != "" ) {
				$text_color = guido_get_config('text_color');
			} else {
				$text_color = '#717171';
			}

			if ( guido_get_config('link_color') != "" ) {
				$link_color = guido_get_config('link_color');
			} else {
				$link_color = '#222222';
			}

			if ( guido_get_config('link_hover_color') != "" ) {
				$link_hover_color = guido_get_config('link_hover_color');
			} else {
				$link_hover_color = '#234DD4';
			}

			if ( guido_get_config('heading_color') != "" ) {
				$heading_color = guido_get_config('heading_color');
			} else {
				$heading_color = '#222222';
			}

			$main_color_rgb = guido_hex2rgb($main_color);
			$second_color_rgb = guido_hex2rgb($second_color);
			
			// font
			$main_font = guido_get_config('main-font');
			$main_font = !empty($main_font) ? json_decode($main_font, true) : array();
			$main_font_family = !empty($main_font['fontfamily']) ? $main_font['fontfamily'] : 'Jost';
			$main_font_weight = !empty($main_font['fontweight']) ? $main_font['fontweight'] : 400;
			$main_font_size = !empty(guido_get_config('main-font-size')) ? guido_get_config('main-font-size').'px' : '15px';

			$main_font_arr = explode(',', $main_font_family);
			if ( count($main_font_arr) == 1 ) {
				$main_font_family = "'".$main_font_family."'";
			}

			$heading_font = guido_get_config('heading-font');
			$heading_font = !empty($heading_font) ? json_decode($heading_font, true) : array();
			$heading_font_family = !empty($heading_font['fontfamily']) ? $heading_font['fontfamily'] : 'Jost';
			$heading_font_weight = !empty($heading_font['fontweight']) ? $heading_font['fontweight'] : 500;

			$heading_font_arr = explode(',', $heading_font_family);
			if ( count($heading_font_arr) == 1 ) {
				$heading_font_family = "'".$heading_font_family."'";
			}
			?>
			:root {
			  --guido-theme-color: <?php echo trim($main_color); ?>;
			  --guido-second-color: <?php echo trim($second_color); ?>;
			  --guido-text-color: <?php echo trim($text_color); ?>;
			  --guido-link-color: <?php echo trim($link_color); ?>;
			  --guido-link_hover_color: <?php echo trim($link_hover_color); ?>;
			  --guido-heading-color: <?php echo trim($heading_color); ?>;
			  --guido-theme-hover-color: <?php echo trim($main_hover_color); ?>;
			  --guido-second-hover-color: <?php echo trim($second_hover_color); ?>;

			  --guido-main-font: <?php echo trim($main_font_family); ?>;
			  --guido-main-font-size: <?php echo trim($main_font_size); ?>;
			  --guido-main-font-weight: <?php echo trim($main_font_weight); ?>;
			  --guido-heading-font: <?php echo trim($heading_font_family); ?>;
			  --guido-heading-font-weight: <?php echo trim($heading_font_weight); ?>;

			  --guido-theme-color-005: <?php echo guido_generate_rgba($main_color_rgb, 0.05); ?>
			  --guido-theme-color-007: <?php echo guido_generate_rgba($main_color_rgb, 0.07); ?>
			  --guido-theme-color-010: <?php echo guido_generate_rgba($main_color_rgb, 0.1); ?>
			  --guido-theme-color-015: <?php echo guido_generate_rgba($main_color_rgb, 0.15); ?>
			  --guido-theme-color-020: <?php echo guido_generate_rgba($main_color_rgb, 0.2); ?>
			  --guido-second-color-050: <?php echo guido_generate_rgba($second_color_rgb, 0.5); ?>
			}
			
			<?php if (  guido_get_config('header_mobile_color') != "" ) : ?>
				#apus-header-mobile {
					background-color: <?php echo esc_html( guido_get_config('header_mobile_color') ); ?>;
				}
			<?php endif; ?>

	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		return implode($new_lines);
	}
}