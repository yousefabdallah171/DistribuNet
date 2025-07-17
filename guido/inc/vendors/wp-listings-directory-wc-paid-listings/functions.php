<?php

function guido_listing_paid_listing_template_folder_name($folder) {
	$folder = 'template-paid-listings';
	return $folder;
}
add_filter( 'wp-listings-directory-wc-paid-listings-theme-folder-name', 'guido_listing_paid_listing_template_folder_name', 10 );

