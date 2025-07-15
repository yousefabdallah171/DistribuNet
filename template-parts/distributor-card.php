<?php
/**
 * Template part for displaying distributor cards - Enhanced Arabic Version
 *
 * @package Distributor Connect
 */

// Get distributor data
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
<div class="distributor-card" style="direction:rtl; text-align:right;">
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
    <?php if ($location): ?>
        <div class="distributor-map" style="margin:1rem 0;">
            <button class="show-map-btn" onclick="var btn=this;var map=btn.nextElementSibling;map.style.display='block';btn.style.display='none';return false;">عرض الخريطة</button>
            <div class="map-iframe" style="display:none;">
                <iframe width="100%" height="180" frameborder="0" style="border:0" allowfullscreen loading="lazy"
                    src="https://www.google.com/maps?q=<?php echo esc_attr($location['lat']); ?>,<?php echo esc_attr($location['lng']); ?>&hl=ar&z=15&output=embed"></iframe>
            </div>
        </div>
    <?php endif; ?>
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

<style>
/* Additional CSS for enhanced card features */
.distributor-card.featured {
    border: 2px solid #ffd700;
    position: relative;
}

.featured-badge {
    position: absolute;
    top: -10px;
    right: 15px;
    background: #ffd700;
    color: #000;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
    z-index: 10;
}

.verified-badge {
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    margin-right: 5px;
    vertical-align: middle;
}

.rating {
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 2px;
}

.star {
    color: #ddd;
    font-size: 1rem;
}

.star.filled {
    color: #ffd700;
}

.rating-text {
    margin-right: 0.5rem;
    font-size: 0.85rem;
    color: var(--secondary-color);
}

.delivery-status.available {
    color: #28a745;
    font-weight: 600;
}

.delivery-status.not_available {
    color: #dc3545;
}

.delivery-status.partial {
    color: #ffc107;
    font-weight: 600;
}

.contact-btn.email {
    background: #17a2b8;
    color: white;
}

.last-updated {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    text-align: center;
    color: var(--secondary-color);
}

.address-preview,
.product-preview,
.hours-preview {
    font-size: 0.9rem;
    color: var(--secondary-color);
}
</style>