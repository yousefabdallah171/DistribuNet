<?php

function guido_listing_display_image($post, $size = 'thumbnail') {
	if ( has_post_thumbnail($post->ID) ) {
		?>
	    <div class="image-thumbnail">
	        <a class="listing-image" href="<?php echo esc_url( get_permalink($post) ); ?>">
	        	<?php
        		$post_thumbnail_id = get_post_thumbnail_id($post->ID);
        		echo guido_get_attachment_thumbnail( $post_thumbnail_id, $size );
	        	?>
	        </a>
	    </div>
	    <?php
	}
}

function guido_listing_display_logo($post, $size = 'thumbnail') {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
	if ( $meta_obj->check_post_meta_exist('logo') && ($logo = $meta_obj->get_post_meta('logo')) ) {
		$logo_id = $meta_obj->get_post_meta('logo_id');
		if ( $logo_id ) {
			?>
		    <div class="image-thumbnail image-logo d-flex align-items-center justify-content-center">
	        	<?php echo guido_get_attachment_thumbnail( $logo_id, $size ); ?>
		    </div>
		    <?php
		} else {
			?>
		    <div class="image-thumbnail image-logo d-flex align-items-center justify-content-center">
	        	<img src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr_e('Image', 'guido'); ?>">
		    </div>
		    <?php
		}
	}
}

function guido_listing_display_category($post, $echo = true) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
	ob_start();
	if ( $meta_obj->check_post_meta_exist('category') ) {
		$terms = get_the_terms( $post->ID, 'listing_category' );
		$i = 1;
		if ( $terms && ! is_wp_error( $terms ) ) {
			?>
			<div class="listing-meta listing-category">
				<div class="listing-tax-inner">
					<?php foreach ($terms as $term) { ?>
		            	<a href="<?php echo esc_url(get_term_link($term)); ?>">
		            		<?php echo trim(guido_listing_term_icon($term)); ?>
		            		<?php echo esc_html($term->name); ?>
	            		</a><?php if($i < count($terms)) echo trim(', ');?>
				    <?php $i++; } ?>
		    	</div>
		    </div>
	    	<?php
	    }
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_category_first($post, $echo = true) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
	ob_start();
	if ( $meta_obj->check_post_meta_exist('category') ) {
		$terms = get_the_terms( $post->ID, 'listing_category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			?>
			<div class="listing-meta listing-category">
				<div class="listing-tax-inner">
					<?php foreach ($terms as $term) { ?>
		            	<a href="<?php echo esc_url(get_term_link($term)); ?>">
		            		<?php echo trim(guido_listing_term_icon($term)); ?>
		            		<?php echo esc_html($term->name); ?>
	            		</a>
				    <?php break; } ?>
		    	</div>
		    </div>
	    	<?php
	    }
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_term_icon( $term ) {
	$html = '';

	$icon_type_value = get_term_meta( $term->term_id, '_icon_type', true );
	$icon_font_value = get_term_meta( $term->term_id, '_icon_font', true );
	$icon_image_value = get_term_meta( $term->term_id, '_icon_image', true );
	if ( $icon_type_value == 'font' && !empty($icon_font_value) ) {
		$html = '<i class="'.esc_attr($icon_font_value).'"></i>';
	} elseif ( $icon_type_value == 'image' && !empty($icon_image_value) ) {
		$image_url = wp_get_attachment_image_src($icon_image_value, 'full');
		if ( !empty($image_url[0]) ) {
			$html = '<img src="'.esc_url($image_url[0]).'" alt="'.esc_attr__( 'icon', 'guido' ).'" />';
		}
	}
	$color_value = get_term_meta( $term->term_id, '_color', true );
	$style = '';
	if ( !empty($color_value) ) {
		$style = ' style="background: '.$color_value.';"';
	}
	if($html){
		$html = '<span class="icon-cate '.$icon_type_value.'"'.$style.'>'.$html.'</span>';
	}
	return $html;
}

function guido_listing_display_tax($post, $taxonomy_key, $icon = '', $show_title = false, $echo = true) {
	$terms = get_the_terms( $post->ID, 'listing_'.$taxonomy_key );
	ob_start();
	$number = 1;
	if ( $terms && ! is_wp_error( $terms ) ) {
		?>
		<div class="listing-meta listing-tax <?php echo esc_attr($taxonomy_key); ?>">
			<div class="listing-tax-inner">
			<?php
				if ($icon) {
					?>
						<span class="icon"><i class="<?php echo esc_attr($icon); ?>"></i></span>
					<?php
				}
				if ( $show_title) {
					$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);
					$title = $meta_obj->get_post_meta_title( $taxonomy_key );
					?>
						<span class="title"><?php echo trim($title); ?>:</span>
					<?php
				}
			
				foreach ($terms as $term) {
					$color = get_term_meta( $term->term_id, '_color', true );
					$style = '';
					if ( $color ) {
						$style = 'color: '.$color;
					}
					?>
		            	<a href="<?php echo esc_url(get_term_link($term)); ?>" style="<?php echo esc_attr($style); ?>"><?php echo esc_html($term->name); ?></a><?php if($number < count($terms)) echo trim(', ');?>
		        	<?php  $number++;
		    	}
	    	?>
	    	</div>
	    </div>
    	<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_short_location($post, $display_type = 'icon', $echo = true) {
	$locations = get_the_terms( $post->ID, 'listing_location' );
	ob_start();
	if ( $locations && ! is_wp_error( $locations ) ) {
		$terms = array();
        guido_locations_walk($locations, 0, $terms);
        if ( empty($terms) ) {
        	$terms = $locations;
        } else {
        	$terms = array_reverse($terms, true);
        }
		?>
		<div class="job-location">
			<?php if ($display_type == 'icon') { ?>
	            <i class="flaticon-pin"></i><?php } ?><?php $i=1; foreach ($terms as $term) { ?><a href="<?php echo get_term_link($term); ?>"><?php echo trim($term->name); ?></a><?php echo esc_html( $i < count($terms) ? ', ' : '' ); ?>
            <?php $i++; } ?>
        </div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_full_location($post, $display_type = 'no-icon-title', $echo = true) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	$location = $meta_obj->get_post_meta( 'address' );
	if ( empty($location) ) {
		$location = $meta_obj->get_post_meta( 'map_location_address' );
	}
	ob_start();
	if ( $location ) {
		if ( $display_type == 'icon' ) {
			?>
			<div class="listing-location with-icon"><i class="flaticon-pin"></i><a href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php echo esc_html($location); ?></a></div>
			<?php
		} elseif ( $display_type == 'title' ) {
			?>
			<div class="listing-location with-title">
				<strong><?php esc_html_e('Location:', 'guido'); ?></strong><a href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php echo esc_html($location); ?></a>
			</div>
			<?php
		} else {
			?>
			<div class="listing-location"><a href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php echo esc_html($location); ?></a></div>
			<?php
		}
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_location_btn($post) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	$location = $meta_obj->get_post_meta( 'map_location_address' );
	
	if ( empty($location) ) {
		$location = $meta_obj->get_post_meta( 'address' );
	}
	if ( $location ) {
		?>
		<a class="btn-readmore" href="<?php echo esc_url( '//maps.google.com/maps?q=' . urlencode( strip_tags( $location ) ) . '&zoom=14&size=512x512&maptype=roadmap&sensor=false' ); ?>" target="_blank"><?php esc_html_e('Get Direction', 'guido'); ?></a>
		<?php
    }
}

function guido_listing_display_tageline($post, $display_type = 'no-icon-title', $echo = true) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_post_meta_exist('tagline') && ($tagline = $meta_obj->get_post_meta( 'tagline' )) ) {
		?>
		<div class="listing-tagline"><?php echo esc_html($tagline); ?></div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_phone($post, $display_type = 'no-title', $echo = true, $always_show_phone = false) {
	
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	$phone = $meta_obj->get_post_meta( 'phone' );
	$a_phone = guido_user_display_phone($phone, $display_type, false, $always_show_phone);

	if ( $echo ) {
		echo trim($a_phone);
	} else {
		return $a_phone;
	}
}

function guido_listing_display_price($post_id, $display_type = 'no-icon-title', $echo = true) {
	if ( is_object($post_id) ) {
		$post_id = $post_id->ID;
	}
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post_id);
	$price = $meta_obj->get_price_html();
	ob_start();
	if ( $price ) {
		if ( $display_type == 'icon' ) {
			?>
			<div class="listing-price with-icon"><i class="ti-credit-card"></i> <?php echo trim($price); ?></div>
			<?php
		} elseif ( $display_type == 'title' ) {
			$title = $meta_obj->get_post_meta_title( 'price' );
			?>
			<div class="listing-price with-title">
				<strong><?php echo trim($title); ?>:</strong> <span><?php echo trim($price); ?></span>
			</div>
			<?php
		} else {
			?>
			<div class="listing-price"><?php echo trim($price); ?></div>
			<?php
		}
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_price_range($post_id, $display_type = 'no-icon-title', $echo = true) {
	if ( is_object($post_id) ) {
		$post_id = $post_id->ID;
	}
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post_id);
	ob_start();
	$all_price_range = WP_Listings_Directory_Mixes::price_range_icons();
	if ( $meta_obj->check_post_meta_exist('price_range') && ($price_range = $meta_obj->get_post_meta('price_range')) && !empty($all_price_range[$price_range]) ) {
		$current_currency = WP_Listings_Directory_Price::get_current_currency();
		$multi_currencies = WP_Listings_Directory_Price::get_currencies_settings();

		if ( !empty($multi_currencies) && !empty($multi_currencies[$current_currency]) ) {
			$currency_args = $multi_currencies[$current_currency];
		}

		if ( !empty($currency_args) ) {
			$currency_symbol = !empty($currency_args['custom_symbol']) ? $currency_args['custom_symbol'] : '';
			if ( empty($currency_symbol) ) {
				$currency = !empty($currency_args['currency']) ? $currency_args['currency'] : 'USD';
				$currency_symbol = WP_Listings_Directory_Price::currency_symbol($currency);
			}
		}

		if ( empty($currency_symbol) ) {
			$currency_symbol = '$';
		}

	    $price_range_display =  str_repeat( $currency_symbol, $all_price_range[$price_range]['icon'] );
        
		if ( $display_type == 'icon' ) {
			?>
			<div class="listing-price with-icon"><i class="ti-credit-card"></i> <?php echo trim($price_range_display); ?></div>
			<?php
		} elseif ( $display_type == 'title' ) {
			$title = $meta_obj->get_post_meta_title( 'price' );
			?>
			<div class="listing-price with-title">
				<strong><?php echo trim($title); ?>:</strong> <span><?php echo trim($price_range_display); ?></span>
			</div>
			<?php
		} else {
			?>
			<div class="listing-price"><?php echo trim($price_range_display); ?></div>
			<?php
		}
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_postdate($post, $display_type = 'no-icon-title', $format = 'normal', $echo = true) {
	ob_start();
	if ( $format == 'ago' ) {
		$post_date = sprintf(esc_html__('%s ago', 'guido'), human_time_diff(get_the_time('U'), current_time('timestamp')) );
	} else {
		$post_date = get_the_time(get_option('date_format'));
	}
	if ( $display_type == 'icon' ) {
		?>
		<div class="listing-postdate with-icon"><i class="flaticon-clock-1"></i> <?php echo trim($post_date); ?></div>
		<?php
	} elseif ( $display_type == 'title' ) {
		?>
		<div class="listing-postdate with-title">
			<strong><?php esc_html_e('Date:', 'guido'); ?></strong> <?php echo trim($post_date); ?>
		</div>
		<?php
	} else {
		?>
		<div class="listing-postdate"><?php echo trim($post_date); ?></div>
		<?php
	}
	$output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_views($post, $display_type = 'no-icon-title', $echo = true) {
	ob_start();
	
	$views = WP_Listings_Directory_Listing::get_post_meta($post->ID, 'views');

	if ( $display_type == 'icon' ) {
		?>
		<div class="listing-views with-icon"><i class="flaticon-eye"></i> <?php echo trim($views); ?></div>
		<?php
	} elseif ( $display_type == 'title' ) {
		?>
		<div class="listing-views with-title">
			<strong><?php esc_html_e('Views:', 'guido'); ?></strong> <?php echo trim($views); ?>
		</div>
		<?php
	} else {
		?>
		<div class="listing-views"><?php echo trim($views); ?></div>
		<?php
	}
	$output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_featured_icon($post, $echo = true, $add_class = '') {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	$featured = $meta_obj->get_post_meta( 'featured' );
	ob_start();
	if ( $featured ) {
		?>
        <span class="featured-listing <?php echo esc_attr($add_class); ?>"><?php esc_html_e('Featured', 'guido'); ?></span>
	    <?php
	}

    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_item_map_meta($post) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	$latitude = $meta_obj->get_post_meta( 'map_location_latitude' );
	$longitude = $meta_obj->get_post_meta( 'map_location_longitude' );

	$thumbnail_url = '';
	if ( has_post_thumbnail($post->ID) ) {
		$thumbnail_url = get_the_post_thumbnail_url( $post, 'thumbnail' );
	}
	
	$logo_url = '';
	if ( $meta_obj->check_post_meta_exist('logo') && ($logo = $meta_obj->get_post_meta('logo')) ) {
		$logo_id = $meta_obj->get_post_meta('logo_id');
		if ( $logo_id ) {
			$logo_url = wp_get_attachment_image_url($logo_id, 'thumbnail');
		} else {
			$logo_url = $logo;
		}
	}

	echo 'data-latitude="'.esc_attr($latitude).'" data-longitude="'.esc_attr($longitude).'" data-img="'.esc_url($thumbnail_url).'" data-logo="'.esc_url($logo_url).'"';
}

function guido_listing_display_email($post, $icon = '', $show_title = false, $link = true, $echo = false) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_post_meta_exist('email') && ($value = $meta_obj->get_post_meta( 'email' )) ) {
		?>
		<div class="listing-meta with-<?php echo esc_attr($show_title ? 'icon-title' : 'icon'); ?>">
			<?php if ( !empty($icon) ) { ?>
				<i class="<?php echo esc_attr($icon); ?>"></i>
			<?php } ?>

			<?php if ( !empty($show_title) ) {
				$title = $meta_obj->get_post_meta_title( 'email' );
			?>
				<span class="title-meta"><?php echo esc_html($title); ?>:</span>
			<?php } ?>

			<span class="value-suffix">
				<?php if ( $link ) { ?>
					<a href="mailto:<?php echo esc_attr($value); ?>">
				<?php } ?>
				<?php echo esc_html($value); ?>
				<?php if ( $link ) { ?>
					</a>
				<?php } ?>
			</span>

		</div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_website($post, $icon = '', $show_title = false, $link = true, $echo = false) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_post_meta_exist('website') && ($value = $meta_obj->get_post_meta( 'website' )) ) {
		?>
		<div class="listing-meta with-<?php echo esc_attr($show_title ? 'icon-title' : 'icon'); ?>">
			<?php if ( !empty($icon) ) { ?>
				<i class="<?php echo esc_attr($icon); ?>"></i>
			<?php } ?>

			<?php if ( !empty($show_title) ) {
				$title = $meta_obj->get_post_meta_title( 'website' );
			?>
				<span class="title-meta"><?php echo esc_html($title); ?>:</span>
			<?php } ?>

			<span class="value-suffix">
				<?php if ( $link ) { ?>
					<a href="<?php echo esc_url($value); ?>">
				<?php } ?>
				<?php echo esc_html($value); ?>
				<?php if ( $link ) { ?>
					</a>
				<?php } ?>
			</span>

		</div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_meta($post, $meta_key, $icon = '', $show_title = false, $suffix = '', $echo = false) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_post_meta_exist($meta_key) && ($value = $meta_obj->get_post_meta( $meta_key )) ) {
		?>
		<div class="listing-meta with-<?php echo esc_attr($show_title ? 'icon-title' : 'icon'); ?>">
			<?php if ( !empty($icon) ) { ?>
				<i class="<?php echo esc_attr($icon); ?>"></i>
			<?php } ?>

			<?php if ( !empty($show_title) ) {
				$title = $meta_obj->get_post_meta_title( $meta_key );
			?>
				<span class="title-meta"><?php echo esc_html($title); ?>:</span>
			<?php } ?>

			<span class="value-suffix">
				<?php echo esc_html($value); ?>
				<?php echo trim($suffix); ?>
			</span>

		</div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_custom_field_meta($post, $meta_key, $icon = '', $show_title = false, $suffix = '', $echo = false) {
	$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

	ob_start();
	if ( $meta_obj->check_custom_post_meta_exist($meta_key) && ($value = $meta_obj->get_custom_post_meta( $meta_key )) ) {
		?>
		<div class="listing-meta with-<?php echo esc_attr($show_title ? 'icon-title' : 'icon'); ?>">

			<div class="listing-meta">

				<?php if ( !empty($show_title) ) {
					$title = $meta_obj->get_custom_post_meta_title( $meta_key );
				?>
					<span class="title-meta">
						<?php echo esc_html($title); ?>
					</span>
				<?php } ?>

				<?php if ( !empty($icon) ) { ?>
					<i class="<?php echo esc_attr($icon); ?>"></i>
				<?php } ?>
				<span class="value-suffix">
					<?php echo esc_html($value); ?>
					<?php echo trim($suffix); ?>
				</span>
			</div>

		</div>
		<?php
    }
    $output = ob_get_clean();
    if ( $echo ) {
    	echo trim($output);
    } else {
    	return $output;
    }
}

function guido_listing_display_rating($post) {
    if ( WP_Listings_Directory_Review::review_enable($post->ID) ) {
        $average_rating = get_post_meta( $post->ID, '_average_rating', true );
        $nb_reviews = get_post_meta( $post->ID, '_nb_reviews', true );
        if ( $average_rating > 0 && $nb_reviews ) { ?>
            <div class="star-rating">
                <?php WP_Listings_Directory_Review::print_review($average_rating, 'list', $nb_reviews); ?>
            </div>
        <?php }
    }
}

function guido_listing_print_btn($post, $show_title = false) {
	if ( guido_get_config('listing_enable_printer', true) ) {
        ?>
        <a href="javascript:void(0);" class="btn-print-listing" data-listing_id="<?php echo esc_attr($post->ID); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce( 'guido-printer-listing-nonce' )); ?>" data-toggle="tooltip" title="<?php esc_attr_e('Print', 'guido'); ?>"><i class=" flaticon-printer"></i>
        	<?php if ( $show_title ) { ?>
        		<span><?php esc_html_e('Print', 'guido'); ?></span>
        	<?php } ?>
        </a>
        <?php
    }
}

function guido_listing_display_filter_btn() {
    $layout_type = guido_get_listings_layout_type();
    $filter_type = guido_get_listings_half_map_filter_type();

	$filter_sidebar = 'listings-filter';
	
    if ( $layout_type == 'half-map' && $filter_type == 'offcanvas' && is_active_sidebar( $filter_sidebar ) ) {
        ?>
        <div class="filter-in-sidebar-wrapper">
            <span class="filter-in-sidebar filter btn d-none d-lg-block"><i class="fa fa-filter"></i><span class="text"><?php esc_html_e('Open Filter', 'guido'); ?></span></span>
        </div>
        <?php
        //remove_action( 'wp_listings_directory_before_listing_archive', array( 'WP_Listings_Directory_Listing', 'display_listings_count_results' ), 10 );
        remove_action( 'wp_listings_directory_before_listing_archive','guido_listings_display_mode_form', 30 );
    }
}