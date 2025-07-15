<?php get_header(); ?>
<div class="single-distributor container">
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
    ?>
    <div class="distributor-header">
        <h1 class="distributor-title"><?php echo esc_html(get_the_title()); ?></h1>
        <?php if ($full_name): ?>
            <div class="meta-item"><strong>اسم المسؤول:</strong> <?php echo esc_html($full_name); ?></div>
        <?php endif; ?>
        <?php if ($phone): ?>
            <div class="meta-item"><strong>الهاتف:</strong> <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $whatsapp)); ?>" target="_blank"><?php echo esc_html($phone); ?> <span>واتساب</span></a></div>
        <?php endif; ?>
        <?php if ($email): ?>
            <div class="meta-item"><strong>البريد الإلكتروني:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div>
        <?php endif; ?>
        <?php if ($full_address): ?>
            <div class="meta-item"><strong>العنوان:</strong> <?php echo esc_html($full_address); ?></div>
        <?php endif; ?>
        <?php if ($governorate_term): ?>
            <div class="meta-item"><strong>المحافظة:</strong> <a href="<?php echo esc_url(get_term_link($governorate_term)); ?>"><?php echo esc_html($governorate_term->name); ?></a></div>
        <?php endif; ?>
    </div>
    <div class="distributor-map" style="margin:2rem 0;">
        <?php if ($location): ?>
            <button class="show-map-btn" onclick="var btn=this;var map=btn.nextElementSibling;map.style.display='block';btn.style.display='none';return false;">عرض الخريطة</button>
            <div class="map-iframe" style="display:none;">
                <iframe width="100%" height="300" frameborder="0" style="border:0" allowfullscreen loading="lazy"
                    src="https://www.google.com/maps?q=<?php echo esc_attr($location['lat']); ?>,<?php echo esc_attr($location['lng']); ?>&hl=ar&z=15&output=embed"></iframe>
            </div>
        <?php elseif ($governorate_term): ?>
            <button class="show-map-btn" onclick="var btn=this;var map=btn.nextElementSibling;map.style.display='block';btn.style.display='none';return false;">عرض الخريطة</button>
            <div class="map-iframe" style="display:none;">
                <iframe width="100%" height="300" frameborder="0" style="border:0" allowfullscreen loading="lazy"
                    src="https://www.google.com/maps?q=<?php echo urlencode($governorate_term->name); ?>&hl=ar&z=10&output=embed"></iframe>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($business_description): ?>
        <div class="distributor-description">
            <strong>وصف النشاط:</strong>
            <div><?php echo nl2br(esc_html($business_description)); ?></div>
        </div>
    <?php endif; ?>
    <?php if ($social_links): ?>
        <div class="distributor-social">
            <strong>روابط التواصل:</strong>
            <ul style="list-style:none; padding:0; display:flex; gap:1rem;">
                <?php foreach ($social_links as $link): ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html($link['type']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
