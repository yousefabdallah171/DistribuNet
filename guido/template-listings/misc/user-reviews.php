<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user = wp_get_current_user();
$post_ids = array();

$query_vars = array(
	'post_type'     => 'listing',
	'post_status'   => 'publish',
	'author'        => $user->ID,
	'fields'		=> 'ids',
	'posts_per_page' => -1
);
$loop = new WP_Query($query_vars);
if ( !empty($loop->posts) ) {
	$post_ids = $loop->posts;
}
$args = array(
	'post_type' => array('listing'),
	'status' => 'approve',
	'meta_query' => array(
        array(
           'key' => '_rating_avg',
           'value' => 0,
           'compare' => '>',
        )
    )
);
$comments = null;
if ( !empty($post_ids) ) {
	$comments = WP_Listings_Directory_Review::get_comments( $args, $post_ids );
}

?>

<div class="user-reviews">
	<h1 class="title-profile"><?php esc_html_e( 'Reviews', 'guido' ) ; ?></h1>
	<div class="box-white-dashboard">
		<h1 class="title"><?php esc_html_e( 'All Reviews', 'guido' ) ; ?></h1>
		<?php
		if ( $comments ) {
			$number = apply_filters( 'wp-listings-directory-get-my-reviews-limit', get_option('posts_per_page'));
			$max_page = ceil(count($comments)/$number);
			$page = !empty($_GET['cpage']) ? $_GET['cpage'] : 1;

			echo '<ul class="list-reviews comment-list">';
				wp_list_comments(array(
					'per_page' => $number,
					'page' => $page,
					'reverse_top_level' => false,
					'callback' => array('WP_Listings_Directory_Review', 'user_reviews')
				), $comments);
			echo '</ul>';

			$pargs = array(
				'base' => add_query_arg( 'cpage', '%#%' ),
				'format' => '',
				'total' => $max_page,
				'current' => $page,
				'echo' => true,
				'add_fragment' => '',
				'prev_text'     => '<i class="ti-angle-left"></i>',
				'next_text'     => '<i class="ti-angle-right"></i>',
			);
			if($max_page > 1){
				WP_Listings_Directory_Mixes::paginate_links( $pargs );
			}
		} else { ?>
			<div class="not-found"><?php esc_html_e('No reviews found.', 'guido'); ?></div>
		<?php } ?>
	</div>
</div>