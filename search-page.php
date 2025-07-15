<?php
/**
 * Template Name: صفحة البحث في الموزعين
 * Dedicated search page for distributors
 */

get_header(); ?>

<div class="container">
    <div class="search-page-header">
        <h1 class="page-title">دليل الموزعين</h1>
        <p class="page-description">ابحث واعثر على أفضل الموزعين في جميع أنحاء مصر</p>
    </div>
    
    <!-- Search Statistics -->
    <div class="search-stats">
        <?php
        $total_distributors = wp_count_posts('wholesale')->publish + 
                             wp_count_posts('mixed')->publish + 
                             wp_count_posts('retail')->publish;
        
        $total_governorates = wp_count_terms(array(
            'taxonomy' => 'governorate',
            'hide_empty' => true
        ));
        ?>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?php echo number_format($total_distributors); ?></div>
                <div class="stat-label">موزع مسجل</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_governorates; ?></div>
                <div class="stat-label">محافظة</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">3</div>
                <div class="stat-label">أنواع توزيع</div>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Search Form -->
    <div class="main-search-section">
        <?php 
        get_template_part('template-parts/search-filter-form', null, array(
            'show_search' => true,
            'show_governorate' => true,
            'show_type' => true,
            'show_delivery' => true,
            'show_advanced' => true,
            'layout' => 'horizontal'
        )); 
        ?>
    </div>
    
    <!-- Search Results -->
    <div class="search-results-section">
        <div class="results-header">
            <div class="results-count"></div>
            <div class="view-options">
                <button class="view-toggle active" data-view="grid" title="عرض شبكي">
                    <span>⊞</span>
                </button>
                <button class="view-toggle" data-view="list" title="عرض قائمة">
                    <span>☰</span>
                </button>
            </div>
        </div>
        
        <div class="search-results" data-view="grid"></div>
        <div class="loading" style="display: none;">
            <div class="loading-spinner"></div>
            <p>جاري البحث...</p>
        </div>
    </div>
    
    <!-- Popular Searches -->
    <div class="popular-searches">
        <h3>عمليات بحث شائعة</h3>
        <div class="popular-tags">
            <?php
            $popular_terms = get_terms(array(
                'taxonomy' => 'governorate',
                'orderby' => 'count',
                'order' => 'DESC',
                'number' => 8
            ));
            
            foreach ($popular_terms as $term):
            ?>
                <a href="<?php echo get_term_link($term); ?>" class="popular-tag">
                    <?php echo esc_html($term->name); ?>
                    <span class="count">(<?php echo $term->count; ?>)</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Quick Category Access -->
    <div class="quick-categories">
        <h3>تصفح حسب النوع</h3>
        <div class="category-grid">
            
            <div class="category-card wholesale">
                <div class="category-icon">🏪</div>
                <h4>موزعين الجملة</h4>
                <p>للبيع بالكميات الكبيرة والجملة</p>
                <div class="category-count">
                    <?php echo wp_count_posts('wholesale')->publish; ?> موزع
                </div>
                <a href="<?php echo get_post_type_archive_link('wholesale'); ?>" class="category-link">
                    تصفح الجملة
                </a>
            </div>
            
            <div class="category-card mixed">
                <div class="category-icon">🛒</div>
                <h4>الجملة المختلطة</h4>
                <p>للبيع بالجملة والقطاعي معاً</p>
                <div class="category-count">
                    <?php echo wp_count_posts('mixed')->publish; ?> موزع
                </div>
                <a href="<?php echo get_post_type_archive_link('mixed'); ?>" class="category-link">
                    تصفح المختلط
                </a>
            </div>
            
            <div class="category-card retail">
                <div class="category-icon">🛍️</div>
                <h4>موزعين القطاعي</h4>
                <p>للبيع بالتجزئة والقطع الصغيرة</p>
                <div class="category-count">
                    <?php echo wp_count_posts('retail')->publish; ?> موزع
                </div>
                <a href="<?php echo get_post_type_archive_link('retail'); ?>" class="category-link">
                    تصفح القطاعي
                </a>
            </div>
            
        </div>
    </div>
    
    <!-- Register CTA -->
    <div class="register-cta">
        <div class="cta-content">
            <h3>هل أنت موزع؟</h3>
            <p>انضم إلى دليلنا واعرض منتجاتك لآلاف العملاء</p>
            <a href="<?php echo home_url('/register'); ?>" class="cta-button">
                سجل الآن مجاناً
            </a>
        </div>
    </div>
    
</div>

<style>
.search-page-header {
    text-align: center;
    padding: 3rem 0;
    background: linear-gradient(135deg, var(--hover-color) 0%, #fff 100%);
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.search-stats {
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    background: var(--background-color);
    border-radius: var(--border-radius);
    border: 1px solid var(--border-color);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--secondary-color);
    font-size: 0.9rem;
}

.main-search-section {
    background: var(--background-color);
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 2rem;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.view-options {
    display: flex;
    gap: 0.5rem;
}

.view-toggle {
    background: var(--hover-color);
    border: 1px solid var(--border-color);
    padding: 8px 12px;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s;
}

.view-toggle.active,
.view-toggle:hover {
    background: var(--primary-color);
    color: white;
}

.search-results[data-view="list"] .distributor-card {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1rem;
}

.popular-searches {
    margin: 3rem 0;
    text-align: center;
}

.popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
    margin-top: 1rem;
}

.popular-tag {
    background: var(--hover-color);
    color: var(--primary-color);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    text-decoration: none;
    transition: all 0.3s;
    border: 1px solid var(--border-color);
}

.popular-tag:hover {
    background: var(--primary-color);
    color: white;
}

.popular-tag .count {
    font-size: 0.8rem;
    opacity: 0.7;
}

.quick-categories {
    margin: 3rem 0;
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.category-card {
    background: var(--background-color);
    padding: 2rem;
    border-radius: var(--border-radius);
    text-align: center;
    border: 2px solid var(--border-color);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 4px;
    height: 100%;
}

.category-card.wholesale::before { background: var(--success-color); }
.category-card.mixed::before { background: var(--warning-color); }
.category-card.retail::before { background: var(--info-color); }

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--box-shadow);
}

.category-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.category-card h4 {
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.category-card p {
    color: var(--secondary-color);
    margin-bottom: 1rem;
}

.category-count {
    background: var(--hover-color);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    display: inline-block;
}

.category-link {
    background: var(--primary-color);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    transition: all 0.3s;
    display: inline-block;
}

.category-link:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.register-cta {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 3rem 2rem;
    border-radius: var(--border-radius);
    text-align: center;
    margin: 3rem 0;
}

.cta-content h3,
.cta-content p {
    color: white;
}

.cta-content h3 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-button {
    background: white;
    color: var(--primary-color);
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1rem;
    transition: all 0.3s;
    display: inline-block;
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255,255,255,0.3);
}

@media (max-width: 768px) {
    .results-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .category-grid {
        grid-template-columns: 1fr;
    }
    
    .register-cta {
        padding: 2rem 1rem;
    }
    
    .cta-content h3 {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewToggles = document.querySelectorAll('.view-toggle');
    const searchResults = document.querySelector('.search-results');
    
    viewToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            // Remove active class from all toggles
            viewToggles.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked toggle
            this.classList.add('active');
            
            // Update results view
            const view = this.dataset.view;
            searchResults.setAttribute('data-view', view);
        });
    });
    
    // Initial search if URL has parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('s') || urlParams.has('governorate') || urlParams.has('type')) {
        // Populate form from URL params
        const searchInput = document.querySelector('.search-input');
        const governorateSelect = document.querySelector('select[name="governorate"]');
        const typeSelect = document.querySelector('select[name="type"]');
        
        if (searchInput && urlParams.has('s')) {
            searchInput.value = urlParams.get('s');
        }
        if (governorateSelect && urlParams.has('governorate')) {
            governorateSelect.value = urlParams.get('governorate');
        }
        if (typeSelect && urlParams.has('type')) {
            typeSelect.value = urlParams.get('type');
        }