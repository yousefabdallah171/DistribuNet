(function($) {
    "use strict";
    
    $.extend($.apusThemeCore, {
        /**
         *  Initialize scripts
         */
        listing_init: function() {
            var self = this;

            self.select2Init();

            self.searchAjaxInit();
            
            self.submitListing();

            self.listingDetail();

            self.filterListingFnc();

            self.userLoginRegister();

            self.listingBtnFilter();

            self.dashboardChartInit();

            if ( $('.listings-listing-wrapper.main-items-wrapper').length ) {
                $(document).on('change', 'form.filter-listing-form input, form.filter-listing-form select', function (e) {
                    var form = $(this).closest('form.filter-listing-form');
                    setTimeout(function(){
                        form.trigger('submit');
                    }, 200);
                });

                $(document).on('submit', 'form.filter-listing-form', function (e) {
                    e.preventDefault();
                    var url = $(this).attr('action');

                    var formData = $(this).find(":input").filter(function(index, element) {
                            return $(element).val() != '';
                        }).serialize();

                    if( url.indexOf('?') != -1 ) {
                        url = url + '&' + formData;
                    } else{
                        url = url + '?' + formData;
                    }
                    
                    self.listingsGetPage( url );
                    return false;
                });

                // Sort Action
                $(document).on('change', 'form.listings-ordering select.orderby', function(e) {
                    e.preventDefault();
                    $('form.listings-ordering').trigger('submit');
                });
                
                $(document).on('submit', 'form.listings-ordering', function (e) {
                    var url = $(this).attr('action');

                    var formData = $(this).find(":input").filter(function(index, element) {
                            return $(element).val() != '';
                        }).serialize();
                    
                    if( url.indexOf('?') != -1 ) {
                        url = url + '&' + formData;
                    } else{
                        url = url + '?' + formData;
                    }
                    self.listingsGetPage( url );
                    return false;
                });

                // display mode
                $(document).on('change', 'form.listings-display-mode input', function(e) {
                    e.preventDefault();
                    $('form.listings-display-mode').trigger('submit');
                });

                $(document).on('submit', 'form.listings-display-mode', function (e) {
                    var url = $(this).attr('action');

                    if( url.indexOf('?') != -1 ) {
                        url = url + '&' + $(this).serialize();
                    } else{
                        url = url + '?' + $(this).serialize();
                    }
                    self.listingsGetPage( url );
                    return false;
                });
            }

            $(document).on('click', '.close-magnific-popup', function(e) {
                e.preventDefault();
                $.magnificPopup.close();
            });

            // ajax pagination
            if ( $('.ajax-pagination').length ) {
                self.ajaxPaginationLoad();
            }

            $(document).on('click', '.advance-search-btn', function(e) {
                e.preventDefault();
                $(this).closest('.search-form-inner').find('.advance-search-wrapper-fields').removeClass('overflow-visible').slideToggle('fast', 'swing', function(){
                    if ( !$(this).hasClass('overflow-visible') ) {
                        $(this).addClass('overflow-visible');
                    }
                });
            });

            // filter fixed
            if ( $('.offcanvas-filter-half-map').length ) {
                var ps = new PerfectScrollbar('.offcanvas-filter-half-map', {
                    wheelPropagation: true
                });
            }
            self.galleryPropery();


            $(document).on( "after_add_listing_favorite",function( e, $element, data) {
                if ( $element.find('span').length ) {
                    $element.find('span').text(data.text);
                }
                var attr = $(this).attr('data-bs-toggle');
                if (typeof attr !== 'undefined' && attr !== false) {

                    $element.attr('title', data.text_tooltip);
                    $element.attr('data-original-title', data.text_tooltip);
                    $element.attr('data-bs-original-title', data.text_tooltip);
                    $element.tooltip('update');
                    $element.tooltip('show');
                }
            });
            $(document).on( "after_remove_listing_favorite",function( e, $element, data) {
                if ( $element.find('span').length ) {
                    $element.find('span').text(data.text);
                }
                var attr = $(this).attr('data-bs-toggle');
                if (typeof attr !== 'undefined' && attr !== false) {
                    $element.attr('title', data.text_tooltip);
                    $element.attr('data-original-title', data.text_tooltip);
                    $element.attr('data-bs-original-title', data.text_tooltip);
                    $element.tooltip('update');
                    $element.tooltip('show');
                }
            });
        },
        select2Init: function() {
            // select2
            if ( $.isFunction( $.fn.select2 ) && typeof wp_listings_directory_select2_opts !== 'undefined' ) {
                var select2_args = wp_listings_directory_select2_opts;
                select2_args['allowClear']              = true;
                select2_args['minimumResultsForSearch'] = 10;
                
                if ( typeof wp_listings_directory_select2_opts.language_result !== 'undefined' ) {
                    select2_args['language'] = {
                        noResults: function(){
                            return wp_listings_directory_select2_opts.language_result;
                        }
                    };
                }
                var filter_select2_args = select2_args;
                
                select2_args['allowClear'] = false;
                select2_args['theme'] = 'default';

                var register_select2_args = select2_args;
                register_select2_args['minimumResultsForSearch'] = -1;
                // filter
                
                $('select[name=email_frequency]').select2( select2_args );
                $('.register-form select').select2( register_select2_args );
                
                filter_select2_args['allowClear'] = true;
                $('.filter-listing-form select').select2( filter_select2_args );
            }
        },
        searchAjaxInit: function() {
            if ( $.isFunction( $.fn.typeahead ) ) {
                $('.apus-autocompleate-input').each(function(){
                    var $this = $(this);
                    $this.typeahead({
                            'hint': true,
                            'highlight': true,
                            'minLength': 2,
                            'limit': 10
                        }, {
                            name: 'search',
                            source: function (query, processSync, processAsync) {
                                processSync([guido_listing_opts.empty_msg]);
                                $this.closest('.twitter-typeahead').addClass('loading');

                                var values = {};
                                $.each($this.closest('form').serializeArray(), function (i, field) {
                                    values[field.name] = field.value;
                                });

                                var ajaxurl = guido_listing_opts.ajaxurl;
                                if ( typeof wp_listings_directory_opts.ajaxurl_endpoint !== 'undefined' ) {
                                    var ajaxurl =  wp_listings_directory_opts.ajaxurl_endpoint.toString().replace( '%%endpoint%%', 'guido_autocomplete_search_listings' );
                                }

                                return $.ajax({
                                    url: ajaxurl,
                                    type: 'GET',
                                    data: {
                                        'search': query,
                                        'action': 'guido_autocomplete_search_listings',
                                        'data': values
                                    },
                                    dataType: 'json',
                                    success: function (json) {
                                        $this.closest('.twitter-typeahead').removeClass('loading');
                                        $this.closest('.has-suggestion').removeClass('active');
                                        return processAsync(json);
                                    }
                                });
                            },
                            templates: {
                                empty : [
                                    '<div class="empty-message">',
                                    guido_listing_opts.empty_msg,
                                    '</div>'
                                ].join('\n'),
                                // suggestion: Handlebars.compile( guido_listing_opts.template ),
                                suggestion: function(data) {
                                    return '<a href="'+data.url+'" class="d-flex align-items-center autocompleate-media"><div class="wrapper-img flex-shrink-0"><img src="'+data.image+'" class="media-object" height="50" width="50"></div><div class="info-body flex-grow-1"><h4>'+data.title+'</h4>'+data.price+'</div></a>';
                                }
                            },
                        }
                    );
                    $this.on('typeahead:selected', function (e, data) {
                        e.preventDefault();
                        setTimeout(function(){
                            $('.apus-autocompleate-input').val(data.title);    
                        }, 5);
                        
                        return false;
                    });
                });
            }

            $('.form-search .has-suggestion').on('click', function(e) {
                e.stopPropagation();
            });
            $(".form-search .has-suggestion").on('click', function(){
                var search_val = $(this).find('input[name=filter-title]').val();
                if ( search_val === '' ) {
                    $(this).toggleClass("active");
                } else {
                    $(this).removeClass("active");
                }
            });
            $('body').on('click', function() {
                if ($('.form-search .has-suggestion').hasClass('active')) {
                    $('.form-search .has-suggestion').removeClass('active');
                }
            });

        },
        submitListing: function() {
            $(document).on('click', 'ul.submit-listing-heading li a', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                if ( $(href).length ) {
                    $('ul.submit-listing-heading li').removeClass('active');
                    $(this).closest('li').addClass('active');
                    $('.before-group-row').removeClass('active');
                    $(href).addClass('active');

                    $( "input" ).trigger( "pxg:simplerefreshmap" );
                }
            });

            $(document).on('click', '.job-submission-previous-btn, .job-submission-next-btn', function(e) {
                e.preventDefault();
                var index = $(this).data('index');
                if ( $('.before-group-row-'+index).length ) {
                    $('.before-group-row').removeClass('active');
                    $('.before-group-row-'+index).addClass('active');

                    $('.submit-listing-heading li').removeClass('active');
                    $('.submit-listing-heading-'+index).addClass('active');

                    $( "input" ).trigger( "pxg:simplerefreshmap" );
                }
            });
        },
        listingDetail: function() {
            var self = this;
            
            var adjustheight = 145;
            $('.show-more-less-wrapper').each(function(){
                var desc_height = $(this).closest('.description-inner').find('.description-inner-wrapper').height();
                if ( desc_height > adjustheight ) {
                    $(this).closest('.description-inner').addClass('show-more');
                    $(this).closest('.description-inner').find('.description-inner-wrapper').css({
                        'height': adjustheight,
                        'overflow': 'hidden',
                    });
                }
                $(this).find('.show-more').on('click', function(){
                    $(this).closest('.description-inner').removeClass('show-more').addClass('show-less');
                    $(this).closest('.description-inner').find('.description-inner-wrapper').css({
                        'height': 'auto',
                        'overflow': 'visible',
                    });
                });
                $(this).find('.show-less').on('click', function(){
                    $(this).closest('.description-inner').removeClass('show-less').addClass('show-more');
                    $(this).closest('.description-inner').find('.description-inner-wrapper').css({
                        'height': adjustheight,
                        'overflow': 'hidden',
                    });
                });
            });

            $('.contact-form-popup-btn').magnificPopup({
                mainClass: 'apus-mfp-zoom-in contact-form-popup',
                type:'inline',
                midClick: true,
                modal: false
            });

            $('.send-private-message-btn').magnificPopup({
                mainClass: 'apus-mfp-zoom-in private-message-form-popup',
                type:'inline',
                midClick: true,
                modal: false
            });

            // claim-listing-form
            $('.claim-this-business-btn').magnificPopup({
                mainClass: 'apus-mfp-zoom-in claim-form-popup',
                type:'inline',
                midClick: true,
                modal: false
            });

            
        },
        dashboardChartInit: function() {
            var self = this;
            var $this = $('#dashboard_listing_chart_wrapper');
            if( $this.length <= 0 ) {
                return;
            }

            // select2
            if ( $.isFunction( $.fn.select2 ) && typeof wp_listings_directory_select2_opts !== 'undefined' ) {
                var select2_args = wp_listings_directory_select2_opts;
                select2_args['allowClear']              = false;
                select2_args['minimumResultsForSearch'] = 10;
                
                if ( typeof wp_listings_directory_select2_opts.language_result !== 'undefined' ) {
                    select2_args['language'] = {
                        noResults: function(){
                            return wp_listings_directory_select2_opts.language_result;
                        }
                    };
                }
                
                select2_args['width'] = '100%';

                $('.stats-graph-search-form select').select2( select2_args );
            }


            var listing_id = $this.data('listing_id');
            var nb_days = $this.data('nb_days');
            self.dashboardChartAjaxInit($this, listing_id, nb_days);

            $('form.stats-graph-search-form select[name="listing_id"]').on('change', function(){
                $('form.stats-graph-search-form').trigger('submit');
            });

            $('form.stats-graph-search-form select[name="nb_days"]').on('change', function(){
                $('form.stats-graph-search-form').trigger('submit');
            });

            $('form.stats-graph-search-form').on('submit', function(e){
                e.preventDefault();
                var listing_id = $('form.stats-graph-search-form select[name="listing_id"]').val();
                var nb_days = $('form.stats-graph-search-form select[name="nb_days"]').val();
                self.dashboardChartAjaxInit($this, listing_id, nb_days);
                return false;
            });
        },
        dashboardChartAjaxInit: function($this, listing_id, nb_days) {
            var self = this;
            if( $this.length <= 0 ) {
                return;
            }
            var parent_div = $this.parent();
            if ( parent_div.hasClass('loading') ) {
                return;
            }
            parent_div.addClass('loading');

            var ajaxurl = guido_listing_opts.ajaxurl;
            if ( typeof wp_listings_directory_opts.ajaxurl_endpoint !== 'undefined' ) {
                ajaxurl =  wp_listings_directory_opts.ajaxurl_endpoint.toString().replace( '%%endpoint%%', 'wp_listings_directory_get_listing_chart' );
            }

            $.ajax({
                url: ajaxurl,
                type:'POST',
                dataType: 'json',
                data: {
                    action: 'wp_listings_directory_get_listing_chart',
                    listing_id: listing_id,
                    nb_days: nb_days,
                    nonce: $this.data('nonce'),
                }
            }).done(function(response) {
                if (response.status == 'error') {
                    $this.remove();
                } else {
                    var ctx = $this.get(0).getContext("2d");

                    var data = {
                        labels: response.stats_labels,
                        datasets: [
                            {
                                label: response.stats_view,
                                backgroundColor: response.bg_color,
                                borderColor: response.border_color,
                                borderWidth: 1,
                                data: response.stats_values
                            },
                        ]
                    };

                    var options = {
                        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                        scaleBeginAtZero : true,
                        //Boolean - Whether grid lines are shown across the chart
                        scaleShowGridLines : false,
                        //String - Colour of the grid lines
                        scaleGridLineColor : "rgba(0,0,0,.05)",
                        //Number - Width of the grid lines
                        scaleGridLineWidth : 1,
                        //Boolean - Whether to show horizontal lines (except X axis)
                        scaleShowHorizontalLines: true,
                        //Boolean - Whether to show vertical lines (except Y axis)
                        scaleShowVerticalLines: true,
                        //Boolean - If there is a stroke on each bar
                        barShowStroke : false,
                        //Number - Pixel width of the bar stroke
                        barStrokeWidth : 2,
                        //Number - Spacing between each of the X value sets
                        barValueSpacing : 5,
                        //Number - Spacing between data sets within X values
                        barDatasetSpacing : 1,
                        legend: { display: false },

                        tooltips: {
                            enabled: true,
                            mode: 'x-axis',
                            cornerRadius: 4
                        },
                    }

                    if (typeof self.myBarChart !== 'undefined') {
                        self.myBarChart.destroy();
                    }

                    self.myBarChart = new Chart(ctx, {
                        type: response.chart_type,
                        data: data,
                        options: options
                    });
                }
                parent_div.removeClass('loading');
            });
        },
        listingBtnFilter: function(){
            $('.btn-view-map').on('click', function(e){
                e.preventDefault();
                $('#listings-google-maps').removeClass('d-none').removeClass('d-lg-block');
                $('.content-listing .listings-listing-wrapper').addClass('d-none');
                $('.btn-view-listing').removeClass('d-none').removeClass('d-lg-block');
                $(this).addClass('d-none');
                $('.listings-pagination-wrapper, .agencies-pagination-wrapper').addClass('p-fix-pagination');
                setTimeout(function() {
                    $(window).trigger('pxg:refreshmap');
                }, 100);
            });
            $('.btn-view-listing').on('click', function(e){
                e.preventDefault();
                $('#listings-google-maps').addClass('d-none').addClass('d-lg-block');
                $('.content-listing .listings-listing-wrapper').removeClass('d-none');
                $('.btn-view-map').removeClass('d-none');
                $(this).addClass('d-none');
                $('.listings-pagination-wrapper, .agencies-pagination-wrapper').removeClass('p-fix-pagination');
            });

            // $('.show-filter-listings').on('click', function(e){
            //     e.stopPropagation();
            //     $('.layout-type-half-map .filter-sidebar').toggleClass('active');
            //     $('.filter-sidebar + .over-dark').toggleClass('active');
            // });
            
            // $(document).on('click', '.filter-sidebar + .over-dark', function(){
            //     $('.layout-type-half-map .filter-sidebar').removeClass('active');
            //     $('.filter-sidebar + .over-dark').removeClass('active');
            // });

            // filter sidebar fixed
            $(document).on('click', '.offcanvas-filter-half-map .close-filter, .btn-show-filter, .filter-in-sidebar, .offcanvas-filter-half-map + .over-dark-filter', function(){
                $('.offcanvas-filter-half-map').toggleClass('active');
            });

            // filter show top
            $(document).on('click', '.filter-in-half-map-top', function(){
                $('.listings-filter-half-map').toggle(150);
            });

            // filter top
            $(document).on('click', '.filter-in-sidebar-top', function(){
                $('.listings-filter-top-sidebar-wrapper').toggle(150);
            });
        },
        filterListingFnc: function(){
            var self = this;
            $('body').on('click', '.btn-show-filter, .offcanvas-filter-sidebar + .over-dark', function(){
                $('.offcanvas-filter-sidebar, .offcanvas-filter-sidebar + .over-dark').toggleClass('active');
                if ( $('.offcanvas-filter-sidebar').length ) {
                    var ps = new PerfectScrollbar('.offcanvas-filter-sidebar', {
                        wheelPropagation: true
                    });
                }
                
            });

            $(document).on('after_add_listing_favorite', function(e, $this, data) {
                $this.attr('data-original-title', guido_listing_opts.favorite_added_tooltip_title);
            });
            $(document).on('after_remove_listing_favorite', function( event, $this, data ) {
                $this.attr('data-original-title', guido_listing_opts.favorite_add_tooltip_title);
            });

            // $('body').on('click', function() {
            //     if ( $(this).find('.price-input-wrapper').length ) {
            //         $(this).find('.price-input-wrapper').slideUp();
            //     }
            // });

            // $('body').on('click', '.form-group-price.text, .form-group-price.list, .form-group-home_area.text, .form-group-lot_area.text, .form-group-year_built.text', function(e){
            //     e.stopPropagation();
            // });
            // $('body').on('click', '.heading-filter-price', function(){
            //     $(this).closest('.from-to-wrapper').find('.price-input-wrapper').slideToggle();
            // });
            $('body').on('keyup', '.price-input-wrapper input', function(){
                var $from_val = $(this).closest('.price-input-wrapper').find('.filter-from').val();
                var $to_val = $(this).closest('.price-input-wrapper').find('.filter-to').val();
                var $wrapper = $(this).closest('.from-to-text-wrapper');

                if ( $wrapper.hasClass('price') ) {
                    if ( wp_listings_directory_opts.enable_multi_currencies === 'yes' ) {
                        $from_val = self.shortenNumber($from_val);
                        $to_val = self.shortenNumber($to_val);
                    } else {
                        $from_val = self.addCommas($from_val);
                        $to_val = self.addCommas($to_val);
                    }
                    $wrapper.find('.from-text .price-text').text( $from_val );
                    $wrapper.find('.to-text .price-text').text( $to_val );
                } else {
                    $wrapper.find('.from-text').text( $from_val );
                    $wrapper.find('.to-text').text( $to_val );
                }
            });
            $('body').on('change', '.price-input-wrapper input', function(){
                var $from_val = $(this).closest('.price-input-wrapper').find('.filter-from').val();
                var $to_val = $(this).closest('.price-input-wrapper').find('.filter-to').val();
                var $wrapper = $(this).closest('.from-to-text-wrapper');
                if ( $wrapper.hasClass('price') ) {
                    if ( wp_listings_directory_opts.enable_multi_currencies === 'yes' ) {
                        $from_val = self.shortenNumber($from_val);
                        $to_val = self.shortenNumber($to_val);
                    } else {
                        $from_val = self.addCommas($from_val);
                        $to_val = self.addCommas($to_val);
                    }

                    $wrapper.find('.from-text .price-text').text( $from_val );
                    $wrapper.find('.to-text .price-text').text( $to_val );
                } else {
                    $wrapper.find('.from-text').text( $from_val );
                    $wrapper.find('.to-text').text( $to_val );
                }
            });
            $('body').on('click', '.from-to-wrapper.price-list .price-filter li', function(){
                var $parent = $(this).closest('.from-to-wrapper');
                var $min = $(this).data('min');
                var $max = $(this).data('max');
                $parent.find('input.filter-from').val($min);
                $parent.find('input.filter-to').val($max);
                $(this).closest('.from-to-wrapper').find('.heading-filter-price .price-text').html($(this).find('.price-text').html());
                $(this).closest('.price-input-wrapper').slideUp();
                $(this).closest('form').trigger('submit');
            });

            $('body').on('click', '.reset-search-btn', function(e){
                e.preventDefault();
                var $form = $( this ).closest( 'form' );

                $('.terms-list.circle-check', $form).find(':checked').each(function(i, obj) {
                    $(obj).attr('checked', false);
                    $(obj).prop( "checked", false );
                });

                $form.find(':input').not( ':input[type="hidden"]' ).not( ':input[type="checkbox"]' ).not( ':input[type="radio"]' ).val( '' ).trigger( 'change.select2' );
                $('.main-range-slider').each(function(){
                    var $this = $(this);
                    $this.slider("values", 0, $this.data('min'));
                    $this.slider("values", 1, $this.data('max'));
                    $this.closest('.form-group-inner').find('.filter-from').val($this.data('min'));
                    $this.closest('.form-group-inner').find('.filter-to').val($this.data('max'));

                    $this.closest('.form-group-inner').find('.from-text').val($this.data('min'));
                    $this.closest('.form-group-inner').find('.to-text').val($this.data('max'));
                });

                $('.price-range-slider').each(function(){
                    var $this = $(this);
                    var $from_price = $this.data('min');
                    var $to_price = $this.data('max');

                    $this.slider("values", 0, $this.data('min'));
                    $this.slider("values", 1, $this.data('max'));

                    if ( wp_listings_directory_opts.enable_multi_currencies === 'yes' ) {
                        $from_price = self.shortenNumber($from_price);
                        $to_price = self.shortenNumber($to_price);
                    } else {
                        $from_price = self.addCommas($from_price);
                        $to_price = self.addCommas($to_price);
                    }

                    $this.closest('.form-group-inner').find('.from-text .price-text').text( $from_price );
                    $this.closest('.form-group-inner').find('.filter-from').val( $this.data('min') )
                    $this.closest('.form-group-inner').find('.to-text .price-text').text( $to_price );
                    $this.closest('.form-group-inner').find('.filter-to').val( $this.data('max') );

                });

                

                if ( $('.listings-listing-wrapper.main-items-wrapper').length ) {
                    $form.trigger('submit');
                }
            });
        },

        userLoginRegister: function(){
            var self = this;
            // login/register
            $('.user-login-form, .must-log-in').on('click', function(e){
                e.preventDefault();
                if ( $('.btn-login-register-popup-btn').length ) {
                    $('.btn-login-register-popup-btn').trigger('click');
                }
            });
            $('.btn-login-register-popup-btn').magnificPopup({
                mainClass: 'apus-mfp-zoom-in login-popup',
                type:'inline',
                midClick: true,
                closeBtnInside:false,
                showCloseBtn:false,
                callbacks: {
                    open: function() {
                        self.layzyLoadImage();
                    }
                }
            });
              
        },

        listingsGetPage: function(pageUrl, isBackButton){
            var self = this;
            if (self.filterAjax) { return false; }

            self.listingsSetCurrentUrl();

            if (pageUrl) {
                // Show 'loader' overlay
                self.listingsShowLoader();
                
                // Make sure the URL has a trailing-slash before query args (301 redirect fix)
                pageUrl = pageUrl.replace(/\/?(\?|#|$)/, '/$1');
                
                if (!isBackButton) {
                    self.setPushState(pageUrl);
                }

                self.filterAjax = $.ajax({
                    url: pageUrl,
                    data: {
                        load_type: 'full'
                    },
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    
                    method: 'POST', // Note: Using "POST" method for the Ajax request to avoid "load_type" query-string in pagination links
                    
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Apus: AJAX error - listingsGetPage() - ' + errorThrown);
                        
                        // Hide 'loader' overlay (after scroll animation)
                        self.listingsHideLoader();
                        
                        self.filterAjax = false;
                    },
                    success: function(response) {
                        // Update listings content
                        self.listingsUpdateContent(response);
                        
                        self.filterAjax = false;
                    }
                });
                
            }
        },
        listingsHideLoader: function(){
            $('body').find('.main-items-wrapper').removeClass('loading');
        },
        listingsShowLoader: function(){
            $('body').find('.main-items-wrapper').addClass('loading');
        },
        setPushState: function(pageUrl) {
            window.history.pushState({apusShop: true}, '', pageUrl);
        },
        listingsSetCurrentUrl: function() {
            var self = this;
            
            // Set current page URL
            self.searchAndTagsResetURL = window.location.href;
        },
        /**
         *  Listings: Update listings content with AJAX HTML
         */
        listingsUpdateContent: function(ajaxHTML) {
            var self = this,
                $ajaxHTML = $('<div>' + ajaxHTML + '</div>');

            var $listings = $ajaxHTML.find('.main-items-wrapper'),
                $display_mode = $ajaxHTML.find('.listings-display-mode-wrapper-ajax .listings-display-mode-wrapper'),
                $pagination = $ajaxHTML.find('.main-pagination-wrapper');

            // Replace listings
            if ($listings.length) {
                $('.main-items-wrapper').replaceWith($listings);
            }
            if ($display_mode.length) {
                $('.listings-display-mode-wrapper').replaceWith($display_mode);
            }
            // Replace pagination
            if ($pagination.length) {
                $('.main-pagination-wrapper').replaceWith($pagination);
            }
            
            // Load images (init Unveil)
            self.layzyLoadImage();

            // pagination
            if ( $('.ajax-pagination').length ) {
                self.infloadScroll = false;
                self.ajaxPaginationLoad();
            }

            if ( $.isFunction( $.fn.select2 ) && typeof wp_listings_directory_select2_opts !== 'undefined' ) {
                var select2_args = wp_listings_directory_select2_opts;
                select2_args['allowClear']              = false;
                select2_args['minimumResultsForSearch'] = 10;
                select2_args['width'] = 'auto';
                
                if ( typeof wp_listings_directory_select2_opts.language_result !== 'undefined' ) {
                    select2_args['language'] = {
                        noResults: function(){
                            return wp_listings_directory_select2_opts.language_result;
                        }
                    };
                }
                
                $('select.orderby').select2( select2_args );
            }
            
            self.updateMakerCards('listings-google-maps', true);
            setTimeout(function() {
                // Hide 'loader'
                self.listingsHideLoader();
            }, 100);
        },

        /**
         *  Shop: Initialize infinite load
         */
        ajaxPaginationLoad: function() {
            var self = this,
                $infloadControls = $('body').find('.ajax-pagination'),                   
                nextPageUrl;

            self.infloadScroll = ($infloadControls.hasClass('infinite-action')) ? true : false;
            
            if (self.infloadScroll) {
                self.infscrollLock = false;
                
                var pxFromWindowBottomToBottom,
                    pxFromMenuToBottom = Math.round($(document).height() - $infloadControls.offset().top);
                
                /* Bind: Window resize event to re-calculate the 'pxFromMenuToBottom' value (so the items load at the correct scroll-position) */
                var to = null;
                $(window).resize(function() {
                    if (to) { clearTimeout(to); }
                    to = setTimeout(function() {
                        var $infloadControls = $('.ajax-pagination'); // Note: Don't cache, element is dynamic
                        pxFromMenuToBottom = Math.round($(document).height() - $infloadControls.offset().top);
                    }, 100);
                });
                
                $(window).scroll(function(){
                    if (self.infscrollLock) {
                        return;
                    }
                    
                    pxFromWindowBottomToBottom = 0 + $(document).height() - ($(window).scrollTop()) - $(window).height();
                    
                    // If distance remaining in the scroll (including buffer) is less than the pagination element to bottom:
                    if (pxFromWindowBottomToBottom < pxFromMenuToBottom) {
                        self.ajaxPaginationGet();
                    }
                });
            } else {
                var $productsWrap = $('body');
                /* Bind: "Load" button */
                $productsWrap.on('click', '.main-pagination-wrapper .apus-loadmore-btn', function(e) {
                    e.preventDefault();
                    self.ajaxPaginationGet();
                });
                
            }
            
            if (self.infloadScroll) {
                $(window).trigger('scroll'); // Trigger scroll in case the pagination element (+buffer) is above the window bottom
            }
        },
        /**
         *  Shop: AJAX load next page
         */
        ajaxPaginationGet: function() {
            var self = this;
            
            if (self.filterAjax) return false;
            
            // Get elements (these can be replaced with AJAX, don't pre-cache)
            var $nextPageLink = $('.apus-pagination-next-link').find('a'),
                $infloadControls = $('.ajax-pagination'),
                nextPageUrl = $nextPageLink.attr('href');
            
            if (nextPageUrl) {
                // Show 'loader'
                $infloadControls.addClass('apus-loader');
                
                // self.setPushState(nextPageUrl);

                self.filterAjax = $.ajax({
                    url: nextPageUrl,
                    data: {
                        load_type: 'items'
                    },
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    method: 'GET',
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('APUS: AJAX error - ajaxPaginationGet() - ' + errorThrown);
                    },
                    complete: function() {
                        // Hide 'loader'
                        $infloadControls.removeClass('apus-loader');
                    },
                    success: function(response) {
                        var $response = $('<div>' + response + '</div>'), // Wrap the returned HTML string in a dummy 'div' element we can get the elements
                            $gridItemElement = $('.items-wrapper', $response).html(),
                            $resultCount = $('.results-count .last', $response).html(),
                            $display_mode = $('.main-items-wrapper').data('display_mode');
                        

                        // Append the new elements
                        if ( $display_mode == 'grid') {
                            $('.main-items-wrapper .items-wrapper .row').append($gridItemElement);
                        } else {
                            $('.main-items-wrapper .items-wrapper').append($gridItemElement);
                        }
                        
                        // Append results
                        $('.main-items-wrapper .results-count .last').html($resultCount);

                        // Update Maps
                        self.updateMakerCards('listings-google-maps');
                        
                        // Load images (init Unveil)
                        self.layzyLoadImage();
                        
                        // Get the 'next page' URL
                        nextPageUrl = $response.find('.apus-pagination-next-link').children('a').attr('href');
                        
                        if (nextPageUrl) {
                            $nextPageLink.attr('href', nextPageUrl);
                        } else {
                            $('.main-items-wrapper').addClass('all-listings-loaded');
                            
                            if (self.infloadScroll) {
                                self.infscrollLock = true;
                            }
                            $infloadControls.find('.apus-loadmore-btn').addClass('hidden');
                            $nextPageLink.removeAttr('href');
                        }
                        
                        self.filterAjax = false;
                        
                        if (self.infloadScroll) {
                            $(window).trigger('scroll'); // Trigger 'scroll' in case the pagination element (+buffer) is still above the window bottom
                        }
                    }
                });
            } else {
                if (self.infloadScroll) {
                    self.infscrollLock = true; // "Lock" scroll (no more products/pages)
                }
            }
        },
        shortenNumber: function($number) {
            var self = this;

            var divisors = wp_listings_directory_opts.divisors;
            var $key_sign = '';
            $.each(divisors, function( $index, $value ) {
                if ($number < ($value['divisor'] * 1000)) {
                    $key_sign = $value['key'];
                    $number = $number / $value['divisor'];
                    return false;
                }
            });

            return self.addCommas($number) + $key_sign;
        },
        addCommas: function(str) {
            var parts = (str + "").split("."),
                main = parts[0],
                len = main.length,
                output = "",
                first = main.charAt(0),
                i;
            
            if (first === '-') {
                main = main.slice(1);
                len = main.length;    
            } else {
                first = "";
            }
            i = len - 1;
            while(i >= 0) {
                output = main.charAt(i) + output;
                if ((len - i) % 3 === 0 && i > 0) {
                    output = wp_listings_directory_opts.money_thousands_separator + output;
                }
                --i;
            }
            // put sign back
            output = first + output;
            // put decimal part back
            if (parts.length > 1) {
                output += wp_listings_directory_opts.money_dec_point + parts[1];
            }
            
            return output;
        },
        galleryPropery: function() {
            var self = this;
            $(document).on( 'mouseenter', 'article.listing-item', function(){
                if ( !$(this).hasClass('loaded-gallery') && $(this).data('images') ) {
                    var $this = $(this);
                    var href = $(this).find('a.listing-image').attr('href')
                    var images = $(this).data('images');
                    var html = '<div class="slick-carousel-gallery-listings hidden" style="width: ' + $(this).find('.listing-thumbnail-wrapper').width() + 'px;"><div class="slick-carousel" data-items="1" data-smallmedium="1" data-extrasmall="1" data-pagination="false" data-nav="true" data-disable_draggable="true">';
                    images.forEach(function(img_url, index){
                        html += '<div class="item"><a class="listing-image" href="'+ href +'"><img src="'+img_url+'"></a></div>';
                    });
                    html += '</div></div>';
                    $(this).find('.listing-thumbnail-wrapper .image-thumbnail').append(html);

                    $(this).find('.slick-carousel-gallery-listings').imagesLoaded( function(){

                        $this.find('.slick-carousel-gallery-listings').removeClass("hidden").delay(200).queue(function(){
                            $(this).addClass("active").dequeue();
                        });

                        self.initSlick($this.find('.slick-carousel'));
                        
                    }).progress( function( instance, image ) {
                        $this.addClass('images-loading');
                    }).done( function( instance ) {
                        $this.addClass('images-loaded').removeClass('images-loading');
                    });

                    $(this).addClass('loaded-gallery');
                }
            });
        }
    });

    $.apusThemeExtensions.listing = $.apusThemeCore.listing_init;

    
})(jQuery);
