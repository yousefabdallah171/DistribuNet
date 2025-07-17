(function ($) {
    "use strict";

    if (!$.apusThemeExtensions)
        $.apusThemeExtensions = {};
    
    function ApusThemeCore() {
        var self = this;
        // self.init();
    };

    ApusThemeCore.prototype = {
        /**
         *  Initialize
         */
        init: function() {
            var self = this;
            
            self.preloadSite();

            self.activeAccordion();
            
            // slick init
            self.initSlick($("[data-carousel=slick]"));

            // isoto
            self.initIsotope();

            // Unveil init
            setTimeout(function(){
                self.layzyLoadImage();
            }, 200);

            self.initHeaderSticky('main-sticky-header');

            // back to top
            self.backToTop();
            
            // popup image
            self.popupImage();

            $('[data-bs-toggle="tooltip"]').tooltip();

            self.initMobileMenu();

            self.mainMenuInit();

            self.changePaddingTopContent();

            $(window).resize(function(){
                setTimeout(function(){
                    self.changePaddingTopContent();
                }, 50);
            });
            
            $(document.body).on('click', '.nav [data-toggle="dropdown"]', function(e){
                e.preventDefault();
                if ( this.href && this.href != '#' ){
                    if ( this.target && this.target == '_blank' ) {
                        window.open(this.href, '_blank');
                    } else {
                        window.location.href = this.href;
                    }
                }
            });

            $('.navbar-wrapper .show-navbar-sidebar').on('click', function(){
                $(this).closest('.navbar-wrapper').find('.navbar-sidebar-wrapper').addClass('active');
                $(this).closest('.navbar-wrapper').find('.navbar-sidebar-overlay').addClass('active');
            });
            
            $('.close-navbar-sidebar, .navbar-sidebar-overlay').on('click', function(){
                $(this).closest('.navbar-wrapper').find('.navbar-sidebar-wrapper').removeClass('active');
                $(this).closest('.navbar-wrapper').find('.navbar-sidebar-overlay').removeClass('active');
            });

            self.loadExtension();
        },
        /**
         *  Extensions: Load scripts
         */
        loadExtension: function() {
            var self = this;
            
            if ($.apusThemeExtensions.quantity_increment) {
                $.apusThemeExtensions.quantity_increment.call(self);
            }

            if ($.apusThemeExtensions.shop) {
                $.apusThemeExtensions.shop.call(self);
            }

            if ($.apusThemeExtensions.listing_map) {
                $.apusThemeExtensions.listing_map.call(self);
            }

            if ($.apusThemeExtensions.listing) {
                $.apusThemeExtensions.listing.call(self);
            }
        },
        initSlick: function(element) {
            var self = this;
            element.each( function(){
                var config = {
                    infinite: false,
                    arrows: $(this).data( 'nav' ),
                    dots: $(this).data( 'pagination' ),
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    prevArrow:"<button type='button' class='slick-arrow slick-prev'><i class='flaticon-arrow-pointing-to-left'></i></span><span class='textnav'>"+ guido_opts.previous +"</span></button>",
                    nextArrow:"<button type='button' class='slick-arrow slick-next'><span class='textnav'>"+ guido_opts.next +"</span><i class='flaticon-arrow-pointing-to-right'></i></button>",
                };
            
                var slick = $(this);
                if( $(this).data('items') ){
                    config.slidesToShow = $(this).data( 'items' );
                    var slidestoscroll = $(this).data( 'items' );
                }
                if( $(this).data('infinite') ){
                    config.infinite = true;
                }
                if( $(this).data('autoplay') ){
                    config.autoplay = true;
                    config.autoplaySpeed = 2500;
                }
                if( $(this).data('disable_draggable') ){
                    config.touchMove = false;
                    config.draggable = false;
                    config.swipe = false;
                    config.swipeToSlide = false;
                }
                if( $(this).data('centermode') ){
                    config.centerMode = true;
                }
                if( $(this).data('vertical') ){
                    config.vertical = true;
                }
                if( $(this).data('rows') ){
                    config.rows = $(this).data( 'rows' );
                }
                if( $(this).data('asnavfor') ){
                    config.asNavFor = $(this).data( 'asnavfor' );
                }
                if( $(this).data('slidestoscroll') ){
                    var slidestoscroll = $(this).data( 'slidestoscroll' );
                }
                if( $(this).data('focusonselect') ){
                    config.focusOnSelect = $(this).data( 'focusonselect' );
                }
                config.slidesToScroll = slidestoscroll;


                if ($(this).data('smalldesktop')) {
                    var smalldesktop = $(this).data('smalldesktop');
                } else {
                    var smalldesktop = config.items;
                }
                if ($(this).data('large')) {
                    var large = $(this).data('large');
                } else {
                    var large = 4;
                }
                if ($(this).data('medium')) {
                    var medium = $(this).data('medium');
                } else {
                    var medium = 3;
                }
                if ($(this).data('small')) {
                    var small = $(this).data('small');
                } else {
                    var small = 2;
                }
                if ($(this).data('smallest')) {
                    var smallest = $(this).data('smallest');
                } else {
                    if ($(this).data('small')) {
                        var smallest = $(this).data('small');
                    } else{
                        var smallest = 2;
                    }
                }


                if ($(this).data('slidestoscroll_smalldesktop')) {
                    var slidestoscroll_smalldesktop = $(this).data('slidestoscroll_smalldesktop');
                } else {
                    var slidestoscroll_smalldesktop = config.items;
                }
                if ($(this).data('slidestoscroll_large')) {
                    var slidestoscroll_large = $(this).data('slidestoscroll_large');
                } else {
                    var slidestoscroll_large = large;
                }
                if ($(this).data('slidestoscroll_medium')) {
                    var slidestoscroll_medium = $(this).data('slidestoscroll_medium');
                } else {
                    var slidestoscroll_medium = medium;
                }
                if ($(this).data('slidestoscroll_small')) {
                    var slidestoscroll_small = $(this).data('slidestoscroll_small');
                } else {
                    var slidestoscroll_small = small;
                }
                if ($(this).data('slidestoscroll_smallest')) {
                    var slidestoscroll_smallest = $(this).data('slidestoscroll_smallest');
                } else {
                    var slidestoscroll_smallest = smallest;
                }

                config.responsive = [

                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: smallest,
                            slidesToScroll: slidestoscroll_smallest,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: small,
                            slidesToScroll: slidestoscroll_small
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: medium,
                            slidesToScroll: slidestoscroll_medium
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: large,
                            slidesToScroll: slidestoscroll_large
                        }
                    },
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: smalldesktop,
                            slidesToScroll: slidestoscroll_smalldesktop
                        }
                    }
                ];

                if ( $('html').attr('dir') == 'rtl' ) {
                    config.rtl = true;
                }

                $(this).slick( config );

            } );

            // Fix owl in bootstrap tabs
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                var $slick = $(".slick-carousel", target);

                if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                    $slick.slick('refresh');
                }
                self.layzyLoadImage();
            });

            // Fix owl in bootstrap 5 tabs
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                var $slick = $(".slick-carousel", target);

                if ($slick.length > 0 && $slick.hasClass('slick-initialized')) {
                    $slick.slick('refresh');
                }
                self.layzyLoadImage();
            });
        },
        layzyLoadImage: function() {
            $(window).off('scroll.unveil resize.unveil lookup.unveil');
            var $images = $('.image-wrapper:not(.image-loaded) .unveil-image'); // Get un-loaded images only
            if ($images.length) {
                $images.unveil(1, function() {
                    $(this).load(function() {
                        $(this).parents('.image-wrapper').first().addClass('image-loaded');
                        $(this).removeAttr('data-src');
                        $(this).removeAttr('data-srcset');
                        $(this).removeAttr('data-sizes');
                    });
                });
            }

            var $images = $('.product-image:not(.image-loaded) .unveil-image'); // Get un-loaded images only
            if ($images.length) {
                $images.unveil(1, function() {
                    $(this).load(function() {
                        $(this).parents('.product-image').first().addClass('image-loaded');
                    });
                });
            }
        },
        initIsotope: function() {
            $('.isotope-items').each(function(){  
                var $container = $(this);
                
                $container.imagesLoaded( function(){
                    $container.isotope({
                        itemSelector : '.isotope-item',
                        transformsEnabled: true,         // Important for videos
                        masonry: {
                            columnWidth: $container.data('columnwidth')
                        }
                    }); 
                });
            });

            /*---------------------------------------------- 
             *    Apply Filter        
             *----------------------------------------------*/
            $('.isotope-filter li a').on('click', function(){
               
                var parentul = $(this).parents('ul.isotope-filter').data('related-grid');
                $(this).parents('ul.isotope-filter').find('li a').removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter'); 
                $('#'+parentul).isotope({ filter: selector }, function(){ });
                
                return(false);
            });
        },
        initHeaderSticky: function(main_sticky_class) {
            if ( $('.' + main_sticky_class).length ) {
                if ( typeof Waypoint !== 'undefined' ) {
                    if ( $('.' + main_sticky_class) && typeof Waypoint.Sticky !== 'undefined' ) {
                        var sticky = new Waypoint.Sticky({
                            element: $('.' + main_sticky_class)[0],
                            wrapper: '<div class="main-sticky-header-wrapper">',
                            offset: '-10px',
                            stuckClass: 'sticky-header'
                        });
                    }
                }
            }
        },
        backToTop: function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 400) {
                    $('#back-to-top').addClass('active');
                } else {
                    $('#back-to-top').removeClass('active');
                }
            });
            $('#back-to-top').on('click', function () {
                $('html, body').animate({scrollTop: '0px'}, 800);
                return false;
            });
        },
            
        popupImage: function() {
            // popup
            $(".popup-image").magnificPopup({type:'image'});
            $('.popup-video').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });

            $('.widget-gallery').each(function(){
                var tagID = $(this).attr('id');
                $('#' + tagID).magnificPopup({
                    delegate: '.popup-image-gallery',
                    type: 'image',
                    tLoading: 'Loading image #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                    }
                });
            });
        },
        preloadSite: function() {
            // preload page
            setTimeout(function(){
                if ( $('body').hasClass('apus-body-loading') ) {
                    $('body').removeClass('apus-body-loading');
                    $('.apus-page-loading').fadeOut(100);
                }
            }, 100);
        },
        
        activeAccordion: function() {
            $('.panel-collapse').on('show.bs.collapse', function () {
                $(this).siblings('.panel-heading').addClass('active');
            });

            $('.panel-collapse').on('hide.bs.collapse', function () {
                $(this).siblings('.panel-heading').removeClass('active');
            });
        },

        initMobileMenu: function() {

            // stick mobile
            var self = this;

            // mobile menu
            $('.btn-toggle-canvas,.btn-showmenu').on('click', function (e) {
                e.stopPropagation();
                $('.apus-offcanvas').toggleClass('active');           
                $('.over-dark').toggleClass('active');

                $("#mobile-menu-container").slidingMenu({
                    backLabel: guido_opts.menu_back_text
                });
            });
            $('body').on('click', function() {
                if ($('.apus-offcanvas').hasClass('active')) {
                    $('.apus-offcanvas').toggleClass('active');
                    $('.over-dark').toggleClass('active');
                }
            });
            $('.apus-offcanvas').on('click', function(e) {
                e.stopPropagation();
            });
            
            if ($(window).width() < 992) {
                if ( $('.apus-offcanvas-body').length ) {
                    var ps = new PerfectScrollbar('.apus-offcanvas-body', {
                        wheelPropagation: true
                    });
                }
            }

            // sidebar mobile       
            $('body').on('click', '.mobile-sidebar-btn.btn-left', function(){
                $('.sidebar-left').toggleClass('active');
            });
            $('body').on('click', '.mobile-sidebar-btn.btn-right', function(){
                $('.sidebar-right').toggleClass('active');
            });

            $('body').on('click', '.mobile-sidebar-btn', function(){
                $('.mobile-sidebar-panel-overlay').toggleClass('active');
                $('.mobile-sidebar-btn i').toggleClass('ti-menu-alt ti-close');
            });
            $('body').on('click', '.mobile-sidebar-panel-overlay, .close-sidebar-btn', function(){
                $('.sidebar').removeClass('active');
                $('.mobile-sidebar-panel-overlay').removeClass('active');
                $('.mobile-sidebar-btn i').toggleClass('ti-menu-alt ti-close');
            });


            if ($(window).width() < 992) {
                if ( $('.sidebar-wrapper > .sidebar').length ) {
                    var ps = new PerfectScrollbar('.sidebar-wrapper > .sidebar', {
                        wheelPropagation: true
                    });
                }
            }

            $(window).scroll(function () {
                if ($(window).width() <= 600) {
                    if ( $('#wpadminbar').length ) {
                        var admin_bar_h = $('#wpadminbar').outerHeight();
                        var mobile = $('.header-mobile').outerHeight();
                        var scroll_h = $(this).scrollTop();
                        if (scroll_h > admin_bar_h) {
                            $('.admin-bar .header-mobile').css({'top': 0});
                            $('.admin-bar .wrapper-menu-dashboard').css({'top': mobile });
                        } else {
                            var top = admin_bar_h - scroll_h;
                            $('.admin-bar .header-mobile').css({'top': top});
                            $('.admin-bar .wrapper-menu-dashboard').css({'top': top + mobile });
                        }
                    }
                }
            });
        },
        mainMenuInit: function() {
            $('.apus-megamenu .megamenu .has-mega-menu.aligned-fullwidth').each(function(e){
                var $this = $(this),
                    i = $this.closest(".elementor-container"),
                    a = $this.closest('.apus-megamenu');
                $this.on('hover', function(){
                    var m = $(this).find('> .dropdown-menu .dropdown-menu-inner'),
                        w = i.width();

                    m.css({
                        width: w,
                        marginLeft: i.offset().left - a.offset().left
                    });
                });

                $this.find('.elementor-element').addClass('no-transparent');
            });
        },

        changePaddingTopContent: function() {
            var admin_bar_h = 0;
            var header_h = 0;
            var menu_dashboard = 0;
            var header_main_content_h = header_h - admin_bar_h;
            if ( $('#wpadminbar').length ){
                var admin_bar_h = $('#wpadminbar').outerHeight();
            }

            if ( $('.wrapper-menu-dashboard').length ){
                var menu_dashboard = $('.wrapper-menu-dashboard').outerHeight();
            }

            if ($(window).width() >= 992) {
                if ( $('#apus-header').length ) {
                    var header_h = $('#apus-header').outerHeight();
                }
                $('#apus-main-content').css({ 'padding-top': 0 });
                
                // header fix
                $('body.fix-header #apus-main-content, body.page-template-page-dashboard #apus-main-content').css({ 'padding-top': header_h + menu_dashboard });

                if ( $('#listings-google-maps').is('.fix-map') ) {
                    $('#apus-main-content').css({ 'padding-top': header_h });
                }

            } else {
                if ( $('#apus-header-mobile').length ) {
                    var header_h = $('#apus-header-mobile').outerHeight();
                }
                $('#apus-main-content').css({ 'padding-top': header_h + menu_dashboard });
            }
            
            if ($('#listings-google-maps').is('.fix-map')) {
                var header_h = header_h + admin_bar_h;
                if ( $('.offcanvas-filter-half-map').length ) {
                    $('.offcanvas-filter-half-map').css({ 'top': admin_bar_h, 'height': 'calc(100vh - ' + admin_bar_h+ 'px)' });
                }

                $('#listings-google-maps').css({ 'top': header_h, 'height': 'calc(100vh - ' + header_h+ 'px)' });
                
            }

            // header fix
            $('body.page-template-page-dashboard #apus-header, body.fix-header #apus-header').css({ 'top': admin_bar_h });

            if ( $('.wrapper-menu-dashboard').length ) {
                $('.wrapper-menu-dashboard').css({ 'top': header_h + admin_bar_h });
            }

            // fix for half map
            $('.layout-type-half-map .filter-sidebar').css({ 'padding-top': header_h + 30 });
            if ( $('.layout-type-half-map .filter-scroll').length ) {
                var ps = new PerfectScrollbar('.layout-type-half-map .filter-scroll', {
                    wheelPropagation: true
                });
            }
            // offcanvas-filter-sidebar 
            $('.offcanvas-filter-sidebar').css({ 'padding-top': header_h + 10 });
        },

        setCookie: function(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires+";path=/";
        },
        getCookie: function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        }
    }

    $.apusThemeCore = ApusThemeCore.prototype;

    $(document).ready(function() {
        // Initialize script
        var apusthemecore_init = new ApusThemeCore();
        apusthemecore_init.init();
    });

    jQuery(window).on("elementor/frontend/init", function() {
        
        var apusthemecore_init = new ApusThemeCore();

        // General element
        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_brands.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_features_box.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_posts.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_testimonials.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_banners.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );

        // Listings elements
        
        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_listings.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
                apusthemecore_init.layzyLoadImage();
            }
        );

        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_listings_tabs.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );
        
        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_listing_categories.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );
        
        elementorFrontend.hooks.addAction( "frontend/element_ready/apus_element_listing_locations.default",
            function($scope) {
                apusthemecore_init.initSlick($scope.find('.slick-carousel'));
            }
        );
    });

})(jQuery);

