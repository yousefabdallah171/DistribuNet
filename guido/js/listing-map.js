(function($) {
    "use strict";
    
    var map, mapSidebar, markers, CustomHtmlIcon, group;
    var markerArray = [];

    $.extend($.apusThemeCore, {
        /**
         *  Initialize scripts
         */
        listing_map_init: function() {
            var self = this;

            if ($('#listings-google-maps').length) {
                L.Icon.Default.imagePath = 'wp-content/themes/guido/images/';
            }
            
            setTimeout(function(){
                
                self.mapInit('listings-google-maps');
                self.mapInit('single-listing-google-maps');

            }, 50);
            
        },
        mapInit: function(map_e_id) {
            var self = this;

            var $window = $(window);

            if (!$('#' + map_e_id).length) {
                return;
            }

            map = L.map(map_e_id, {
                scrollWheelZoom: false
            });

            markers = new L.MarkerClusterGroup({
                showCoverageOnHover: false
            });

            CustomHtmlIcon = L.HtmlIcon.extend({
                options: {
                    html: "<div class='map-popup'></div>",
                    iconSize: [38, 50],
                    iconAnchor: [19, 50],
                    popupAnchor: [0, -40]
                }
            });

            $window.on('pxg:refreshmap', function() {
                map._onResize();
                setTimeout(function() {
                    
                    if(markerArray.length > 0 ){
                        group = L.featureGroup(markerArray);
                        map.fitBounds(group.getBounds()); 
                    }
                }, 100);
            });

            $window.on('pxg:simplerefreshmap', function() {
                map._onResize();
            });

            $('.tabs-gallery-map .nav-tabs .tab-google-map').on('click', function(){
                window.dispatchEvent(new Event('resize'));
                
            });

            if ( guido_listing_map_opts.map_service == 'mapbox' ) {
                var tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/'+guido_listing_map_opts.mapbox_style+'/tiles/{z}/{x}/{y}?access_token='+ guido_listing_map_opts.mapbox_token, {
                    attribution: " &copy;  <a href='https://www.mapbox.com/about/maps/'>Mapbox</a> &copy;  <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> <strong><a href='https://www.mapbox.com/map-feedback/' target='_blank'>Improve this map</a></strong>",
                    maxZoom: 18,
                });
            } else if ( guido_listing_map_opts.map_service == 'here' ) {

                var hereTileUrl = 'https://2.base.maps.ls.hereapi.com/maptile/2.1/maptile/newest/'+guido_listing_map_opts.here_style+'/{z}/{x}/{y}/512/png8?apiKey='+ guido_listing_map_opts.here_map_api_key +'&ppi=320';
                var tileLayer = L.tileLayer(hereTileUrl, {
                    attribution: " &copy;  <a href='https://www.mapbox.com/about/maps/'>Here</a> &copy; <strong><a href='https://www.mapbox.com/map-feedback/' target='_blank'>Improve this map</a></strong>",
                    maxZoom: 18,
                });

            } else if ( guido_listing_map_opts.map_service == 'openstreetmap' ) {
                
                var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                });

            } else {
                if ( guido_listing_map_opts.custom_style != '' ) {
                    try {
                        var custom_style = $.parseJSON(guido_listing_map_opts.custom_style);
                        var tileLayer = L.gridLayer.googleMutant({
                            type: guido_listing_map_opts.googlemap_type,
                            styles: custom_style
                        });
                    } catch(err) {
                        var tileLayer = L.gridLayer.googleMutant({
                            type: guido_listing_map_opts.googlemap_type
                        });
                    }
                } else {
                    var tileLayer = L.gridLayer.googleMutant({
                        type: guido_listing_map_opts.googlemap_type
                    });
                }
                $('#apus-listing-map').addClass('map--google');
            }

            map.addLayer(tileLayer);

            // check archive/single page
            if ( !$('#'+map_e_id).is('.single-listing-map') ) {
                self.updateMakerCards(map_e_id);
            } else {
                var $item = $('.single-listing-wrapper');
                
                if ( $item.data('latitude') !== "" && $item.data('latitude') !== "" ) {
                    var zoom = (typeof MapWidgetZoom !== "undefined") ? MapWidgetZoom : 15;
                    self.addMakerToMap($item);
                    map.addLayer(markers);
                    map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                    $(window).on('update:map', function() {
                        map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                    });

                    $('.location-map-view').on('click', function(e){
                        e.preventDefault();
                        $('#single-listing-street-view-map').hide();
                        $('#'+map_e_id).show();
                        $('.location-street-view').removeClass('hidden');
                        $(this).removeClass('hidden').addClass('hidden');
                        map._onResize();
                    });

                } else {
                    $('#' + map_e_id).hide();
                }
            }
        },
        updateMakerCards: function(map_e_id, ajax) {
            var self = this;
            var $items = $('.main-items-wrapper .map-item');

            if (typeof ajax !== "undefined") {
                markerArray = [];
            }
            
            if ($('#' + map_e_id).length && typeof map !== "undefined") {
                
                if (!$items.length) {
                    map.setView([guido_listing_map_opts.default_latitude, guido_listing_map_opts.default_longitude], 12);
                    return;
                }

                map.removeLayer(markers);
                markers = new L.MarkerClusterGroup({
                    showCoverageOnHover: false
                });
                $items.each(function(i, obj) {
                    self.addMakerToMap($(obj), true);
                });

                map.addLayer(markers);

                if(markerArray.length > 0 ){
                    group = L.featureGroup(markerArray);
                    map.fitBounds(group.getBounds()); 
                }
            }
        },
        addMakerToMap: function($item, archive) {
            var self = this;
            var marker;

            if ( $item.data('latitude') == "" || $item.data('longitude') == "") {
                return;
            }

            if ( $item.data('logo') ) {
                var img_icon = "<img src='" + $item.data('logo') + "'>";
            } else if(guido_listing_map_opts.default_pin){
                var img_icon = "<img src='" + guido_listing_map_opts.default_pin + "'>";
            }else{
                var img_icon = '<i class="flaticon-pin"></i>';
            }
            var mapPinHTML = "<div class='map-popup'><div class='icon-wrapper has-img'>" + img_icon + "</div></div>";
            

            marker = L.marker([$item.data('latitude'), $item.data('longitude')], {
                icon: new CustomHtmlIcon({ html: mapPinHTML })
            });

            if (typeof archive !== "undefined") {
                
                $item.hover(function() {
                    $(marker._icon).find('.map-popup').addClass('map-popup-selected');
                }, function() {
                    $(marker._icon).find('.map-popup').removeClass('map-popup-selected');
                });

                var customOptions = {
                    'maxWidth': '290',
                };

                var logo_html = '';
                if ( $item.data('img') ) {
                    logo_html =  "<div class='image-wrapper image-loaded'>" +
                                "<img src='" + $item.data('img') + "'>" +
                            "</div>";
                }

                var title_html = '';
                if ( $item.find('.listing-title').length ) {
                    title_html = "<h3 class='listing-title'>" + $item.find('.listing-title').html() + "</h3>";
                }


                var phone = '';
                if ( $item.find('.phone-wrapper a.phone').length ) {
                    var phone_wraper = $('<div>' + $item.find('.phone-wrapper').html() + '</div>');
                    phone = "<div class='phone-wrapper'>" + phone_wraper.find('i').wrapAll('<div>').parent().html() + phone_wraper.find('a.phone').wrapAll('<div>').parent().html() + "</div>";
                }

                var location = '';
                if ( $item.find('.job-location').length ) {
                    location = "<div class='job-location'>" + $item.find('.job-location').html() + "</div>";
                }

                marker.bindPopup(
                    "<div class='listing-item listing-grid-map d-flex align-items-center'>" +
                        "<div class='listing-thumbnail-wrapper flex-shrink-0'>" + logo_html +
                        "</div><div class='top-info flex-grow-1'>" + title_html +
                        "<div class='top-info'><div class='listing-metas d-flex align-items-center flex-wrap'>" + phone + location + "</div>" +
                    "</div></div></div>", customOptions).openPopup();
            }

            markers.addLayer(marker);
            markerArray.push(L.marker([$item.data('latitude'), $item.data('longitude')]));
        }

    });

    $.apusThemeExtensions.listing_map = $.apusThemeCore.listing_map_init;

    
})(jQuery);