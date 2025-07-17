<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo WP_Listings_Directory_Template_Loader::get_template_part('loop/listing/archive-inner', array('listings' => $listings));
