<?php get_header(); ?>
<style>
.single-distributor-page {
    max-width: 800px;
    margin: 2rem auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 16px rgba(26,35,126,0.07);
    padding: 2.5rem 1.5rem;
    direction: rtl;
    font-family: 'Cairo', 'Tajawal', Tahoma, Arial, sans-serif;
}
.dist-header {
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    gap: 1rem;
}
.dist-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #1a237e;
    margin: 0;
}
.dist-type-badge {
    background: #ffb300;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 700;
    border-radius: 18px;
    padding: 0.4rem 1.2rem;
    margin-right: 0.5rem;
    display: inline-block;
}
.dist-contact-card {
    background: #f5f7fa;
    border-radius: 14px;
    box-shadow: 0 1px 6px rgba(26,35,126,0.05);
    padding: 1.5rem 1rem;
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
}
.dist-contact-row {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    font-size: 1.1rem;
    color: #222;
}
.dist-contact-row .icon {
    font-size: 1.3rem;
    color: #3949ab;
    min-width: 1.7em;
    text-align: center;
}
.dist-social {
    display: flex;
    gap: 0.7rem;
    align-items: center;
}
.dist-social a {
    background: #ffb300;
    color: #fff;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    transition: background 0.2s;
}
.dist-social a:hover {
    background: #1a237e;
}
.dist-desc-card {
    background: #f9fbe7;
    border-radius: 14px;
    padding: 1.5rem 1rem;
    margin-bottom: 2rem;
    color: #333;
    font-size: 1.08rem;
}
.dist-map-section {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 6px rgba(26,35,126,0.05);
    padding: 1.5rem 1rem;
    margin-bottom: 1rem;
}
@media (max-width: 600px) {
    .single-distributor-page { padding: 1.2rem 0.3rem; }
    .dist-header { flex-direction: column; align-items: flex-end; gap: 0.5rem; }
    .dist-title { font-size: 1.3rem; }
    .dist-type-badge { font-size: 0.95rem; padding: 0.3rem 0.8rem; }
}
</style>
<div class="single-distributor-page">
    <?php
    $post_id = get_the_ID();
    $full_name = get_field('full_name', $post_id);
    $phone = get_field('phone', $post_id);
    $whatsapp = get_field('whatsapp', $post_id) ?: $phone;
    $email = get_field('email', $post_id);
    $full_address = get_field('full_address', $post_id);
    $governorate = get_field('governorate', $post_id);
    $location = get_field('location', $post_id);
    $social_links = get_field('social_links', $post_id);
    $business_description = get_field('business_description', $post_id);
    $governorate_term = $governorate ? get_term($governorate, 'governorate') : false;
    $type = get_post_type();
    $type_badge = ($type === 'wholesale') ? __('توزيع جملة', 'distributor-connect') : (($type === 'retail') ? __('توزيع تجزئة', 'distributor-connect') : __('مختلط', 'distributor-connect'));
    ?>
    <!-- Header Section -->
    <div class="dist-header">
        <span class="dist-type-badge"><?php echo esc_html($type_badge); ?></span>
        <h1 class="dist-title"><?php echo esc_html(get_the_title()); ?></h1>
    </div>
    <!-- Contact Details Block -->
    <div class="dist-contact-card">
        <?php if ($phone): ?>
            <div class="dist-contact-row"><span class="icon">📞</span><span><?php _e('رقم الهاتف:', 'distributor-connect'); ?></span> <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $whatsapp)); ?>" target="_blank" style="color:#1a237e; font-weight:700; text-decoration:none;"><?php echo esc_html($phone); ?> <span style="font-size:1.1em;">واتساب</span></a></div>
        <?php endif; ?>
        <?php if ($full_address): ?>
            <div class="dist-contact-row"><span class="icon">📍</span><span><?php _e('العنوان الكامل:', 'distributor-connect'); ?></span> <?php echo esc_html($full_address); ?></div>
        <?php endif; ?>
        <?php if ($governorate_term): ?>
            <div class="dist-contact-row"><span class="icon">🗺️</span><span><?php _e('المحافظة:', 'distributor-connect'); ?></span> <a href="<?php echo esc_url(get_term_link($governorate_term)); ?>" style="color:#3949ab; text-decoration:none; font-weight:700;"> <?php echo esc_html($governorate_term->name); ?></a></div>
        <?php endif; ?>
        <?php if ($social_links): ?>
            <div class="dist-contact-row dist-social">
                <span class="icon">🌐</span>
                <?php foreach ($social_links as $link):
                    $icon = ($link['type'] === 'facebook') ? '' : (($link['type'] === 'instagram') ? '' : '🌐');
                    $icon_html = ($link['type'] === 'facebook') ? '<span style="font-family:Arial,sans-serif;">&#xf09a;</span>' : (($link['type'] === 'instagram') ? '<span style="font-family:Arial,sans-serif;">&#xf16d;</span>' : '🌐');
                    $label = ($link['type'] === 'facebook') ? 'فيسبوك' : (($link['type'] === 'instagram') ? 'انستجرام' : 'موقع');
                ?>
                    <a href="<?php echo esc_url($link['url']); ?>" target="_blank" title="<?php echo esc_attr($label); ?>">
                        <?php echo $icon_html; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <!-- Distributor Description -->
    <?php if ($business_description): ?>
        <div class="dist-desc-card">
            <div style="font-weight:700; color:#1a237e; margin-bottom:0.7rem;">نبذة عن النشاط</div>
            <div><?php echo nl2br(esc_html($business_description)); ?></div>
        </div>
    <?php endif; ?>
    <!-- Map Section -->
    <div class="dist-map-section">
        <div style="font-weight:700; color:#1a237e; margin-bottom:0.7rem;">الموقع على الخريطة</div>
        <?php if ($location): ?>
            <button class="show-map-btn" onclick="var btn=this;var map=btn.nextElementSibling;map.style.display='block';btn.style.display='none';return false;" style="background:#ffb300; color:#fff; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-size:1rem; margin-bottom:1rem; cursor:pointer;">عرض الخريطة</button>
            <div class="map-iframe" style="display:none;">
                <iframe width="100%" height="300" frameborder="0" style="border:0; border-radius:12px;" allowfullscreen loading="lazy"
                    src="https://www.google.com/maps?q=<?php echo esc_attr($location['lat']); ?>,<?php echo esc_attr($location['lng']); ?>&hl=ar&z=15&output=embed"></iframe>
            </div>
        <?php elseif ($governorate_term): ?>
            <button class="show-map-btn" onclick="var btn=this;var map=btn.nextElementSibling;map.style.display='block';btn.style.display='none';return false;" style="background:#ffb300; color:#fff; border:none; border-radius:8px; padding:0.5rem 1.5rem; font-size:1rem; margin-bottom:1rem; cursor:pointer;">عرض الخريطة</button>
            <div class="map-iframe" style="display:none;">
                <iframe width="100%" height="300" frameborder="0" style="border:0; border-radius:12px;" allowfullscreen loading="lazy"
                    src="https://www.google.com/maps?q=<?php echo urlencode($governorate_term->name); ?>&hl=ar&z=10&output=embed"></iframe>
            </div>
        <?php else: ?>
            <div style="color:#888;">لا يوجد موقع محدد لهذا الموزع.</div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>