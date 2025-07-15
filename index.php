<?php get_header(); ?>

<?php get_template_part('template-parts/hero'); ?>

<section id="filter-section" style="margin:3rem 0;">
    <div class="container">
        <?php echo do_shortcode('[distributor_search_filter]'); ?>
    </div>
</section>

<section id="distributor-grid-section" style="margin-bottom:3rem;">
    <div class="container">
        <h2 class="text-center" style="margin-bottom:2rem;">أحدث الموزعين</h2>
        <div id="homepage-distributors-grid">
            <!-- AJAX grid will be loaded here -->
        </div>
        <div class="text-center" id="homepage-load-more-wrap">
            <button id="homepage-load-more" style="display:none;" class="submit-btn">تحميل المزيد</button>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/distributor-map'); ?>

<?php get_template_part('template-parts/why-us'); ?>

<?php get_template_part('template-parts/registration-cta'); ?>

<script>
jQuery(document).ready(function($){
    var offset = 0;
    var perPage = 6;
    function loadDistributors(reset) {
        if (reset) offset = 0;
        $('#homepage-load-more').hide();
        $.post(distributor_ajax.ajax_url, {
            action: 'distributor_ajax_filter',
            nonce: distributor_ajax.nonce,
            offset: offset,
            posts_per_page: perPage
        }, function(response){
            if (reset) $('#homepage-distributors-grid').html(response);
            else $('#homepage-distributors-grid').append(response);
            if ($('.distributor-card').length % perPage === 0 && $('.distributor-card').length > 0) {
                $('#homepage-load-more').show();
            }
        });
    }
    loadDistributors(true);
    $('#homepage-load-more').on('click', function(){
        offset += perPage;
        loadDistributors(false);
    });
});
</script>

<?php get_footer(); ?>