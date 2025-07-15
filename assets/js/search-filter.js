(function($) {
    'use strict';
    
    // Distributor Search and Filter System
    class DistributorSearch {
        constructor(container) {
            this.container = $(container);
            this.searchForm = this.container.find('.search-form');
            this.resultsContainer = this.container.find('.search-results');
            this.loadingEl = this.container.find('.loading');
            this.currentPage = 1;
            this.totalPages = 1;
            this.isLoading = false;
            
            this.init();
        }
        
        init() {
            this.bindEvents();
            this.setupInfiniteScroll();
        }
        
        bindEvents() {
            // Search form submission
            this.searchForm.on('submit', (e) => {
                e.preventDefault();
                this.performSearch();
            });
            
            // Real-time search
            this.container.find('.search-input').on('input', 
                this.debounce(() => this.performSearch(), 500)
            );
            
            // Filter changes
            this.container.find('.filter-select').on('change', () => {
                this.performSearch();
            });
            
            // Clear search
            this.container.find('.clear-search').on('click', () => {
                this.clearSearch();
            });
            
            // Sort options
            this.container.find('.sort-select').on('change', () => {
                this.performSearch();
            });
        }
        
        setupInfiniteScroll() {
            $(window).on('scroll', this.debounce(() => {
                if (this.shouldLoadMore()) {
                    this.loadMore();
                }
            }, 100));
        }
        
        shouldLoadMore() {
            if (this.isLoading || this.currentPage >= this.totalPages) {
                return false;
            }
            
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
            
            return scrollTop + windowHeight >= documentHeight - 500;
        }
        
        performSearch(loadMore = false) {
            if (this.isLoading) return;
            
            if (!loadMore) {
                this.currentPage = 1;
                this.resultsContainer.empty();
            }
            
            this.isLoading = true;
            this.showLoading();
            
            const formData = this.getFormData();
            formData.page = this.currentPage;
            formData.action = 'search_distributors';
            formData.nonce = distributor_ajax.nonce;
            
            $.ajax({
                url: distributor_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: (response) => {
                    this.handleSearchResponse(response, loadMore);
                },
                error: () => {
                    this.showError('حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.');
                },
                complete: () => {
                    this.isLoading = false;
                    this.hideLoading();
                }
            });
        }
        
        loadMore() {
            this.currentPage++;
            this.performSearch(true);
        }
        
        handleSearchResponse(response, loadMore) {
            if (response.success) {
                const data = response.data;
                
                if (loadMore) {
                    this.resultsContainer.append(data.html);
                } else {
                    this.resultsContainer.html(data.html);
                    this.updateResultsCount(data.total);
                }
                
                this.totalPages = data.total_pages;
                this.animateResults();
                
                if (data.total === 0 && !loadMore) {
                    this.showNoResults();
                }
            } else {
                this.showError(response.data || 'حدث خطأ غير متوقع');
            }
        }
        
        getFormData() {
            return {
                search: this.container.find('.search-input').val(),
                governorate: this.container.find('select[name="governorate"]').val(),
                type: this.container.find('select[name="type"]').val(),
                sort: this.container.find('select[name="sort"]').val(),
                delivery: this.container.find('select[name="delivery"]').val(),
                verified: this.container.find('input[name="verified"]').is(':checked') ? '1' : '',
                featured: this.container.find('input[name="featured"]').is(':checked') ? '1' : ''
            };
        }
        
        clearSearch() {
            this.container.find('input, select').val('');
            this.container.find('input[type="checkbox"]').prop('checked', false);
            this.performSearch();
        }
        
        showLoading() {
            this.loadingEl.show();
        }
        
        hideLoading() {
            this.loadingEl.hide();
        }
        
        showError(message) {
            this.resultsContainer.html(`
                <div class="search-error">
                    <div class="error-icon">⚠️</div>
                    <p>${message}</p>
                    <button class="btn retry-search">المحاولة مرة أخرى</button>
                </div>
            `);
            
            this.resultsContainer.find('.retry-search').on('click', () => {
                this.performSearch();
            });
        }
        
        showNoResults() {
            this.resultsContainer.html(`
                <div class="no-results">
                    <div class="no-results-icon">🔍</div>
                    <h3>لم يتم العثور على نتائج</h3>
                    <p>جرب تعديل معايير البحث أو <a href="#" class="clear-search">امسح الفلاتر</a></p>
                </div>
            `);
        }
        
        updateResultsCount(total) {
            let countEl = this.container.find('.results-count');
            if (countEl.length === 0) {
                countEl = $('<div class="results-count"></div>');
                this.resultsContainer.before(countEl);
            }
            
            const countText = total === 0 ? 'لا توجد نتائج' : 
                             total === 1 ? 'نتيجة واحدة' :
                             total <= 10 ? `${total} نتائج` : `${total} نتيجة`;
            
            countEl.html(`<span>تم العثور على ${countText}</span>`);
        }
        
        animateResults() {
            this.resultsContainer.find('.distributor-card').each(function(index) {
                $(this).css({
                    opacity: 0,
                    transform: 'translateY(20px)'
                }).delay(index * 100).animate({
                    opacity: 1
                }, 300).css('transform', 'translateY(0)');
            });
        }
        
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }
    
    // Initialize search when document is ready
    $(document).ready(function() {
        // Initialize all search containers
        $('.distributor-search-filter').each(function() {
            new DistributorSearch(this);
        });
        
        // Add smooth scrolling to search results
        $('.search-results').on('click', 'a[href^="#"]', function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
        
        // Add click tracking for analytics
        $('.search-results').on('click', '.distributor-card a', function() {
            const cardTitle = $(this).closest('.distributor-card').find('h3').text();
            
            // Send analytics event if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'distributor_click', {
                    'distributor_name': cardTitle,
                    'search_query': $('.search-input').val()
                });
            }
        });
    });
    
    // Export for use in other scripts
    window.DistributorSearch = DistributorSearch;
    
})(jQuery);

// Additional utility functions
function initQuickSearch() {
    const quickSearchHTML = `
        <div class="quick-search-popup" id="quickSearchPopup">
            <div class="popup-overlay"></div>
            <div class="popup-content">
                <div class="popup-header">
                    <h3>البحث السريع</h3>
                    <button class="close-popup">×</button>
                </div>
                <div class="popup-body">
                    <input type="text" class="quick-search-input" placeholder="ابحث عن موزع...">
                    <div class="quick-results"></div>
                </div>
            </div>
        </div>
    `;
    
    if (!document.getElementById('quickSearchPopup')) {
        document.body.insertAdjacentHTML('beforeend', quickSearchHTML);
        
        // Bind events
        document.querySelector('.close-popup').addEventListener('click', closeQuickSearch);
        document.querySelector('.popup-overlay').addEventListener('click', closeQuickSearch);
        
        const quickInput = document.querySelector('.quick-search-input');
        quickInput.addEventListener('input', debounce(performQuickSearch, 300));
        
        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeQuickSearch();
        });
    }
}

function openQuickSearch() {
    initQuickSearch();
    document.getElementById('quickSearchPopup').style.display = 'block';
    document.querySelector('.quick-search-input').focus();
}

function closeQuickSearch() {
    const popup = document.getElementById('quickSearchPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

function performQuickSearch() {
    const query = document.querySelector('.quick-search-input').value;
    const resultsContainer = document.querySelector('.quick-results');
    
    if (query.length < 2) {
        resultsContainer.innerHTML = '';
        return;
    }
    
    // Perform AJAX search
    jQuery.ajax({
        url: distributor_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'quick_search_distributors',
            search: query,
            nonce: distributor_ajax.nonce
        },
        success: function(response) {
            if (response.success) {
                resultsContainer.innerHTML = response.data.html;
            }
        }
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}