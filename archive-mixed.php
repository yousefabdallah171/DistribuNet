<?php get_header(); ?>
<div class="container" style="display:flex; flex-direction:row-reverse; gap:2rem; margin:2rem auto; max-width:1200px;">
    <main style="flex:2;">
        <h1 class="text-center">موزعو الجملة المختلطة</h1>
        <?php if (have_posts()): ?>
            <div class="distributors-grid">
                <?php while (have_posts()): the_post();
                    get_template_part('template-parts/distributor-card');
                endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">لا توجد موزعين جملة مختلطة حالياً.</div>
        <?php endif; ?>
    </main>
    <aside style="flex:1; min-width:280px;">
        <?php get_sidebar(); ?>
    </aside>
</div>
<?php get_footer(); ?>