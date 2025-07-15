<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="pxl-header" dir="rtl">
    <div class="pxl-header__topbar">
        <button class="pxl-header__burger" aria-label="افتح القائمة" type="button">
            <!-- SVG or Font Awesome burger icon -->
            <span aria-hidden="true">&#9776;</span>
        </button>
        <div class="pxl-header__logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php if (has_custom_logo()) { the_custom_logo(); } else { bloginfo('name'); } ?>
            </a>
        </div>
        <button class="pxl-header__search" aria-label="بحث" type="button">
            <!-- SVG or Font Awesome search icon -->
            <span aria-hidden="true">&#128269;</span>
        </button>
    </div>
    <!-- Off-canvas Burger Menu -->
    <nav class="pxl-burger-menu" aria-label="القائمة الجانبية" tabindex="-1">
        <button class="pxl-burger-menu__close" aria-label="إغلاق القائمة" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul class="pxl-burger-menu__links">
            <li><a href="#">توزيع جملة</a></li>
            <li><a href="#">توزيع تجزئة</a></li>
            <li><a href="#">توزيع مختلط</a></li>
        </ul>
        <div class="pxl-burger-menu__social">
            <a href="#" aria-label="Facebook" class="pxl-social-icon"><!-- Facebook SVG --></a>
            <a href="#" aria-label="Instagram" class="pxl-social-icon"><!-- Instagram SVG --></a>
            <a href="#" aria-label="WhatsApp" class="pxl-social-icon"><!-- WhatsApp SVG --></a>
        </div>
    </nav>
    <!-- Search Overlay -->
    <div class="pxl-search-overlay" tabindex="-1" aria-modal="true" role="dialog">
        <button class="pxl-search-overlay__close" aria-label="إغلاق البحث" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="pxl-search-overlay__content">
            <form class="pxl-search-form" role="search">
                <input type="search" placeholder="ابحث عن موزع..." aria-label="بحث" />
                <!-- AJAX filter form fields will be injected here -->
                <div class="pxl-search-form__filters">
                    <?php echo do_shortcode('[distributor_search_filter]'); ?>
                </div>
            </form>
        </div>
    </div>
</header>

<main class="main-content">