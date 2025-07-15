<aside class="sidebar" style="direction:rtl; text-align:right; padding:2rem 1rem; background:#fafbfc; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <label for="sidebar-search">بحث:</label>
        <input type="search" id="sidebar-search" class="search-field" placeholder="ابحث..." value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit">بحث</button>
    </form>
    <hr>
    <h3>الأقسام</h3>
    <ul style="list-style:none; padding:0;">
        <li><a href="<?php echo get_post_type_archive_link('wholesale'); ?>">موزعو الجملة</a></li>
        <li><a href="<?php echo get_post_type_archive_link('mixed'); ?>">موزعو الجملة المختلطة</a></li>
        <li><a href="<?php echo get_post_type_archive_link('retail'); ?>">موزعو القطاعي</a></li>
    </ul>
    <hr>
    <h3>المحافظات</h3>
    <ul style="list-style:none; padding:0; max-height:200px; overflow:auto;">
        <?php
        $governorates = get_terms(array('taxonomy' => 'governorate', 'hide_empty' => false));
        foreach ($governorates as $gov) {
            echo '<li><a href="' . esc_url(get_term_link($gov)) . '">' . esc_html($gov->name) . '</a></li>';
        }
        ?>
    </ul>
</aside>