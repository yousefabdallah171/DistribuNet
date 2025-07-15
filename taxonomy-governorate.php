<?php
get_header(); ?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">
            <?php printf(__('Distributors in %s', 'distributor-connect'), single_term_title('', false)); ?>
        </h1>
        <?php if (term_description()) : ?>
            <p class="page-description"><?php echo term_description(); ?></p>
        <?php endif; ?>
    </div>
    
    <div class="content-area">
        <div class="main-content-area">
            <!-- Post Type Filter -->
            <div class="archive-filters">
                <div class="filter-group">
                    <label><?php _e('Filter by Type:', 'distributor-connect'); ?></label>
                    <a href="<?php echo get_term_link(get_queried_object()); ?>" class="btn btn-outline <?php echo !isset($_GET['post_type']) ? 'active' : ''; ?>">
                        <?php _e('All', 'distributor-connect'); ?>
                    </a>
                    <a href="<?php echo add_query_arg('post_type', 'wholesale', get_term_link(get_queried_object())); ?>" class="btn btn-outline <?php echo ($_GET['post_type'] ?? '') === 'wholesale' ? 'active' : ''; ?>">
                        <?php _e('Wholesale', 'distributor-connect'); ?>
                    </a>
                    <a href="<?php echo add_query_arg('post_type', 'mixed', get_term_link(get_queried_object())); ?>" class="btn btn-outline <?php echo ($_GET['post_type'] ?? '') === 'mixed' ? 'active' : ''; ?>">
                        <?php _e('Mixed', 'distributor-connect'); ?>
                    </a>
                    <a href="<?php echo add_query_arg('post_type', 'retail', get_term_link(get_queried_object())); ?>" class="btn btn-outline <?php echo ($_GET['post_type'] ?? '') === 'retail' ? 'active' : ''; ?>">
                        <?php _e('Retail', 'distributor-connect'); ?>
                    </a>
                </div>
            </div>
            
            <?php if (have_posts()) : ?>
                <div class="distributors-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/distributor-card'); ?>
                    <?php endwhile; ?>
                </div>
                
                <div class="pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('&laquo; Previous', 'distributor-connect'),
                        'next_text' => __('Next &raquo;', 'distributor-connect'),
                    ));
                    ?>
                </div>
            <?php else : ?>
                <p><?php _e('No distributors found in this governorate.', 'distributor-connect'); ?></p>
            <?php endif; ?>
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>