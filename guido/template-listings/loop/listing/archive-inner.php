<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helper: Get city image (from term meta _icon_image)
function guido_get_city_image_url($term_id, $size = 'medium') {
    $image_id = get_term_meta($term_id, '_icon_image', true);
    if ($image_id) {
        $img = wp_get_attachment_image_src($image_id, $size);
        if (!empty($img[0])) return $img[0];
    }
    // fallback image (optional: change to your default city image)
    return get_template_directory_uri() . '/assets/img/city-placeholder.png';
}

// RTL-friendly responsive styles for city cards and search/filter UI
add_action('wp_head', function() {
    if (is_tax('listing_category')) {
        echo '<style>
        .pxl-archive-wrapper { font-family: inherit; }
        .pxl-search-filter-row { display: flex; flex-wrap: wrap; gap: 1em; align-items: center; margin-bottom: 2em; }
        .pxl-search-input { flex: 1 1 200px; padding: 0.7em 1em; border: 1px solid #ddd; border-radius: 8px; font-size: 1.1em; direction: rtl; }
        .pxl-sort-dropdown { flex: 0 0 180px; padding: 0.7em 1em; border: 1px solid #ddd; border-radius: 8px; font-size: 1.1em; direction: rtl; }
        .pxl-search-btn { padding: 0.7em 1.2em; border-radius: 8px; background: #0073aa; color: #fff; border: none; font-size: 1.1em; display: flex; align-items: center; gap: 0.5em; cursor: pointer; margin-right: 0.5em; margin-bottom: 0; transition: background 0.2s; }
        .pxl-search-btn:hover { background: #005c8a; }
        .pxl-city-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5em; }
        .pxl-city-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden; text-align: center; transition: box-shadow 0.2s, transform 0.2s; cursor: pointer; border: 1px solid #f0f0f0; }
        .pxl-city-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.13); transform: translateY(-4px) scale(1.03); }
        .pxl-city-card-img { width: 100%; height: 140px; object-fit: cover; background: #f7f7f7; }
        .pxl-city-card-title { font-size: 1.25em; font-weight: 700; margin: 1em 0 1em 0; color: #1a1a1a; letter-spacing: 0.5px; }
        @media (max-width: 600px) {
            .pxl-search-filter-row { flex-direction: column; gap: 0.7em; }
            .pxl-city-grid { grid-template-columns: 1fr 1fr; }
            .pxl-city-card-img { height: 100px; }
            .pxl-search-btn { width: 100%; margin: 0; margin-top: 0.5em; justify-content: center; }
        }
        @media (max-width: 400px) {
            .pxl-city-grid { grid-template-columns: 1fr; }
        }
        </style>';
    }
});

// Detect if we are on a listing_category archive
if (is_tax('listing_category')) {
    $category = get_queried_object();
    $location_slug = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : false;
    echo '<div class="pxl-archive-wrapper" dir="rtl" style="text-align:right;">';
    if (!$location_slug) {
        // --- Search & Sort UI ---
        echo '<form class="pxl-search-filter-row" method="get" action="">';
        echo '<input type="text" name="city_search" class="pxl-search-input" placeholder="ابحث عن مدينة..." value="' . esc_attr($_GET['city_search'] ?? '') . '">';
        echo '<button type="submit" class="pxl-search-btn" aria-label="بحث"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M10.5 3a7.5 7.5 0 1 1 0 15 7.5 7.5 0 0 1 0-15Zm0 2a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11Zm7.03 11.97 3.25 3.25a1 1 0 0 1-1.32 1.5l-.1-.08-3.25-3.25a1 1 0 0 1 1.32-1.5l.1.08Z"/></svg> بحث</button>';
        echo '<select name="sort_by" class="pxl-sort-dropdown" aria-label="التصنيف حسب">';
        echo '<option value="">التصنيف حسب</option>';
        echo '<option value="name"' . (($_GET['sort_by'] ?? '')=='name' ? ' selected' : '') . '>الاسم</option>';
        echo '<option value="count"' . (($_GET['sort_by'] ?? '')=='count' ? ' selected' : '') . '>عدد الموزعين</option>';
        echo '<option value="active"' . (($_GET['sort_by'] ?? '')=='active' ? ' selected' : '') . '>الأكثر نشاطًا</option>';
        echo '</select>';
        // preserve other query args
        foreach ($_GET as $k=>$v) { if (!in_array($k,['city_search','sort_by'])) echo '<input type="hidden" name="'.esc_attr($k).'" value="'.esc_attr($v).'">'; }
        echo '</form>';

        // --- Fetch and filter/sort cities ---
        $locations_with_listings = [];
        $all_locations = get_terms(array(
            'taxonomy' => 'listing_location',
            'hide_empty' => false,
        ));
        $city_search = isset($_GET['city_search']) ? trim($_GET['city_search']) : '';
        foreach ($all_locations as $location) {
            // Check if there are listings with this category and this location
            $listing_query = new WP_Query([
                'post_type' => 'listing',
                'posts_per_page' => 1,
                'tax_query' => [
                    [
                        'taxonomy' => 'listing_category',
                        'field'    => 'term_id',
                        'terms'    => $category->term_id,
                    ],
                    [
                        'taxonomy' => 'listing_location',
                        'field'    => 'term_id',
                        'terms'    => $location->term_id,
                    ],
                ],
            ]);
            if ($listing_query->have_posts()) {
                // Filter by search
                if ($city_search && stripos($location->name, $city_search) === false) continue;
                // Count listings for sorting
                $location->_listing_count = $listing_query->found_posts;
                $locations_with_listings[] = $location;
            }
            wp_reset_postdata();
        }
        // Sort
        $sort_by = $_GET['sort_by'] ?? '';
        if ($sort_by === 'name') {
            usort($locations_with_listings, function($a, $b) { return strnatcasecmp($b->name, $a->name); });
        } elseif ($sort_by === 'count') {
            usort($locations_with_listings, function($a, $b) { return ($b->_listing_count ?? 0) - ($a->_listing_count ?? 0); });
        } elseif ($sort_by === 'active') {
            // Placeholder: same as count for now
            usort($locations_with_listings, function($a, $b) { return ($b->_listing_count ?? 0) - ($a->_listing_count ?? 0); });
        }
        // --- City Cards Grid ---
        echo '<h2 class="pxl-section-title">اختر المدينة</h2>';
        if (!empty($locations_with_listings)) {
            echo '<div class="pxl-city-grid">';
            foreach ($locations_with_listings as $city) {
                $city_url = add_query_arg('location', $city->slug, get_term_link($category));
                $img_url = guido_get_city_image_url($city->term_id);
                echo '<a href="' . esc_url($city_url) . '" class="pxl-city-card">';
                echo '<img src="' . esc_url($img_url) . '" class="pxl-city-card-img" alt="' . esc_attr($city->name) . '">';
                echo '<div class="pxl-city-card-title">' . esc_html($city->name) . '</div>';
                echo '</a>';
            }
            echo '</div>';
        } else {
            echo '<p>لا توجد مدن بها موزعين في هذه الفئة.</p>';
        }
    } else {
        // Show listings for selected category + location
        $location = get_term_by('slug', $location_slug, 'listing_location');
        if ($location) {
            // Back to cities link
            echo '<a href="' . esc_url(get_term_link($category)) . '" class="pxl-back-link" style="display:inline-block;margin-bottom:1.5em;color:#0073aa;text-decoration:underline;">عودة إلى المدن</a>';
            echo '<h2 class="pxl-section-title">الموزعين في ' . esc_html($location->name) . ' - ' . esc_html($category->name) . '</h2>';
            $listings = new WP_Query([
                'post_type' => 'listing',
                'posts_per_page' => 12,
                'tax_query' => [
                    [
                        'taxonomy' => 'listing_category',
                        'field'    => 'term_id',
                        'terms'    => $category->term_id,
                    ],
                    [
                        'taxonomy' => 'listing_location',
                        'field'    => 'term_id',
                        'terms'    => $location->term_id,
                    ],
                ],
            ]);
            if ($listings->have_posts()) {
                echo '<div class="pxl-listings-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5em;">';
                while ($listings->have_posts()) {
                    $listings->the_post();
                    // Use the same template part as Apus Listings Tabs (Elementor)
                    echo WP_Listings_Directory_Template_Loader::get_template_part('listings-styles/inner-grid');
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p>لا توجد موزعين في هذه المدينة ضمن هذه الفئة.</p>';
            }
        } else {
            echo '<p>المدينة غير موجودة.</p>';
        }
    }
    echo '</div>';
    return;
}


$listings_display_mode = guido_get_listings_display_mode();
$listing_inner_style = guido_get_listings_inner_style();
$layout_type = guido_get_listings_layout_type();
$filter_type = guido_get_listings_half_map_filter_type();

$filter_sidebar = 'listings-filter';
if ( $layout_type == 'half-map' && $filter_type == 'offcanvas' && is_active_sidebar( $filter_sidebar ) ) {
	add_action( 'wp_listings_directory_before_listing_archive', 'guido_listing_display_filter_btn', 2 );
	
}
?>
<div class="listings-listing-wrapper main-items-wrapper" data-display_mode="<?php echo esc_attr($listings_display_mode); ?>">
	<?php
	/**
	 * wp_listings_directory_before_listing_archive
	 */
	do_action( 'wp_listings_directory_before_listing_archive', $listings );
	?>

	<?php if ( !empty($listings) && !empty($listings->posts) ) : ?>
		<?php
		/**
		 * wp_listings_directory_before_loop_listing
		 */
		do_action( 'wp_listings_directory_before_loop_listing', $listings );
		?>
		<div class="listings-wrapper items-wrapper clearfix">
			<?php
				$addclass = '';
				if ( $listings_display_mode == 'grid' ) {
					$columns = guido_get_listings_columns();
					$addclass = 'col-sm-6 col-md-6 col-12';
				} else {
					$columns = guido_get_listings_list_columns();
					$addclass = 'col-12';
				}
				$bcol = $columns ? 12/$columns : 3;
				if($layout_type == 'half-map'){
					$ct = ($columns && $columns >= 2) ? 6 : 1;
				}else{
					$ct = '12';
				}
				$i = 0;
			?>
			<div class="row">
				<?php while ( $listings->have_posts() ) : $listings->the_post(); ?>
					<div class="<?php echo esc_attr($addclass); ?> col-lg-<?php echo esc_attr($bcol); ?>">
						<?php echo WP_Listings_Directory_Template_Loader::get_template_part( 'listings-styles/inner-'.$listing_inner_style ); ?>
					</div>
				<?php $i++; endwhile; ?>
			</div>

		</div>

		<?php
		/**
		 * wp_listings_directory_after_loop_listing
		 */
		do_action( 'wp_listings_directory_after_loop_listing', $listings );
		
		wp_reset_postdata();
		?>

	<?php else : ?>
		<div class="not-found text-center"><?php esc_html_e('No listing found.', 'guido'); ?></div>
	<?php endif; ?>

	<?php
	/**
	 * wp_listings_directory_after_listing_archive
	 */
	do_action( 'wp_listings_directory_after_listing_archive', $listings );
	?>
</div>