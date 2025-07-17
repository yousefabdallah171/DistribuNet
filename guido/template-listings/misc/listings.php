<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = array(
	'listings' => $listings
);

echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/archive-inner', $args);

echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/pagination', array('listings' => $listings));
