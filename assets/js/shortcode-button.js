(function() {
    'use strict';
    
    // Register the TinyMCE plugin
    tinymce.PluginManager.add('distributor_shortcodes', function(editor, url) {
        
        // Add button to toolbar
        editor.addButton('distributor_shortcodes', {
            title: 'إدراج شورت كود الموزعين',
            text: 'شورت كود',
            icon: 'icon dashicons-store',
            type: 'menubutton',
            menu: [
                {
                    text: 'نموذج البحث الكامل',
                    onclick: function() {
                        editor.insertContent('[distributor_search layout="full"]');
                    }
                },
                {
                    text: 'نموذج البحث المضغوط',
                    onclick: function() {
                        editor.insertContent('[distributor_search layout="compact"]');
                    }
                },
                {
                    text: 'البحث المصغر',
                    onclick: function() {
                        editor.insertContent('[mini_search placeholder="ابحث عن موزع..."]');
                    }
                },
                {
                    text: 'موزعين بالمحافظة',
                    onclick: function() {
                        var governorate = prompt('أدخل كود المحافظة (مثل: cairo, alexandria):', 'cairo');
                        if (governorate) {
                            editor.insertContent('[distributors_by_governorate gov="' + governorate + '" limit="6"]');
                        }
                    }
                },
                {
                    text: 'الموزعين المميزين',
                    onclick: function() {
                        editor.insertContent('[featured_distributors limit="4"]');
                    }
                },
                {
                    text: 'قائمة المحافظات',
                    onclick: function() {
                        editor.insertContent('[governorate_list layout="grid" columns="4"]');
                    }
                },
                {
                    text: 'إعدادات متقدمة...',
                    onclick: function() {
                        openAdvancedDialog();
                    }
                }
            ]
        });
        
        // Advanced shortcode dialog
        function openAdvancedDialog() {
            editor.windowManager.open({
                title: 'إعدادات شورت كود الموزعين المتقدمة',
                width: 500,
                height: 400,
                body: [
                    {
                        type: 'listbox',
                        name: 'shortcode_type',
                        label: 'نوع الشورت كود:',
                        values: [
                            {text: 'نموذج البحث', value: 'search'},
                            {text: 'البحث المصغر', value: 'mini'},
                            {text: 'موزعين بالمحافظة', value: 'by_gov'},
                            {text: 'الموزعين المميزين', value: 'featured'},
                            {text: 'قائمة المحافظات', value: 'gov_list'}
                        ]
                    },
                    {
                        type: 'listbox',
                        name: 'layout',
                        label: 'التخطيط:',
                        values: [
                            {text: 'كامل', value: 'full'},
                            {text: 'مضغوط', value: 'compact'},
                            {text: 'مصغر', value: 'mini'},
                            {text: 'شبكي', value: 'grid'},
                            {text: 'قائمة', value: 'list'}
                        ]
                    },
                    {
                        type: 'textbox',
                        name: 'limit',
                        label: 'عدد النتائج:',
                        value: '6'
                    },
                    {
                        type: 'textbox',
                        name: 'columns',
                        label: 'عدد الأعمدة:',
                        value: '3'
                    },
                    {
                        type: 'listbox',
                        name: 'type',
                        label: 'نوع الموزع:',
                        values: [
                            {text: 'جميع الأنواع', value: ''},
                            {text: 'جملة', value: 'wholesale'},
                            {text: 'جملة مختلطة', value: 'mixed'},
                            {text: 'قطاعي', value: 'retail'}
                        ]
                    },
                    {
                        type: 'textbox',
                        name: 'governorate',
                        label: 'كود المحافظة (للفلترة):',
                        placeholder: 'مثل: cairo, alexandria'
                    },
                    {
                        type: 'checkbox',
                        name: 'show_filters',
                        label: 'إظهار فلاتر البحث'
                    },
                    {
                        type: 'checkbox',
                        name: 'show_title',
                        label: 'إظهار العنوان'
                    },
                    {
                        type: 'checkbox',
                        name: 'show_more_link',
                        label: 'إظهار رابط "عرض المزيد"'
                    },
                    {
                        type: 'textbox',
                        name: 'custom_class',
                        label: 'كلاسات CSS إضافية:',
                        placeholder: 'my-custom-class'
                    }
                ],
                onsubmit: function(e) {
                    var data = e.data;
                    var shortcode = generateShortcode(data);
                    editor.insertContent(shortcode);
                }
            });
        }
        
        // Generate shortcode based on user input
        function generateShortcode(data) {
            var shortcode = '';
            var attributes = [];
            
            switch(data.shortcode_type) {
                case 'search':
                    shortcode = 'distributor_search';
                    if (data.layout) attributes.push('layout="' + data.layout + '"');
                    if (data.show_filters) attributes.push('show_filters="true"');
                    if (data.columns) attributes.push('columns="' + data.columns + '"');
                    if (data.type) attributes.push('type="' + data.type + '"');
                    if (data.governorate) attributes.push('governorate="' + data.governorate + '"');
                    if (data.custom_class) attributes.push('class="' + data.custom_class + '"');
                    break;
                    
                case 'mini':
                    shortcode = 'mini_search';
                    attributes.push('placeholder="ابحث عن موزع..."');
                    break;
                    
                case 'by_gov':
                    shortcode = 'distributors_by_governorate';
                    if (data.governorate) {
                        attributes.push('gov="' + data.governorate + '"');
                    } else {
                        attributes.push('gov="cairo"');
                    }
                    if (data.type) attributes.push('type="' + data.type + '"');
                    if (data.limit) attributes.push('limit="' + data.limit + '"');
                    if (data.columns) attributes.push('columns="' + data.columns + '"');
                    if (data.show_title) attributes.push('show_title="true"');
                    if (data.show_more_link) attributes.push('show_more_link="true"');
                    break;
                    
                case 'featured':
                    shortcode = 'featured_distributors';
                    if (data.limit) attributes.push('limit="' + data.limit + '"');
                    if (data.columns) attributes.push('columns="' + data.columns + '"');
                    if (data.type) attributes.push('type="' + data.type + '"');
                    if (data.show_title) attributes.push('show_title="true"');
                    break;
                    
                case 'gov_list':
                    shortcode = 'governorate_list';
                    if (data.layout) attributes.push('layout="' + data.layout + '"');
                    if (data.columns) attributes.push('columns="' + data.columns + '"');
                    attributes.push('show_count="true"');
                    break;
            }
            
            return '[' + shortcode + (attributes.length ? ' ' + attributes.join(' ') : '') + ']';
        }
        
        // Add shortcode examples to help menu
        editor.addCommand('DistributorShortcodeHelp', function() {
            editor.windowManager.open({
                title: 'أمثلة على شورت كودات الموزعين',
                width: 600,
                height: 500,
                body: [
                    {
                        type: 'container',
                        html: `
                            <div style="direction: rtl; text-align: right; padding: 20px;">
                                <h3>أمثلة على الشورت كودات:</h3>
                                
                                <h4>1. نموذج البحث الكامل:</h4>
                                <code>[distributor_search layout="full" show_filters="true"]</code>
                                
                                <h4>2. نموذج البحث المضغوط:</h4>
                                <code>[distributor_search layout="compact"]</code>
                                
                                <h4>3. البحث المصغر للشريط الجانبي:</h4>
                                <code>[mini_search placeholder="ابحث عن موزع..."]</code>
                                
                                <h4>4. موزعين محافظة القاهرة:</h4>
                                <code>[distributors_by_governorate gov="cairo" limit="6"]</code>
                                
                                <h4>5. موزعين الجملة في الإسكندرية:</h4>
                                <code>[distributors_by_governorate gov="alexandria" type="wholesale" limit="4"]</code>
                                
                                <h4>6. الموزعين المميزين:</h4>
                                <code>[featured_distributors limit="4" show_title="true"]</code>
                                
                                <h4>7. قائمة جميع المحافظات:</h4>
                                <code>[governorate_list layout="grid" columns="4"]</code>
                                
                                <h4>8. قائمة المحافظات المنسدلة:</h4>
                                <code>[governorate_list layout="dropdown"]</code>
                                
                                <hr>
                                
                                <h4>المعاملات المتاحة:</h4>
                                <ul>
                                    <li><strong>layout:</strong> full, compact, mini, grid, list, dropdown</li>
                                    <li><strong>type:</strong> wholesale, mixed, retail</li>
                                    <li><strong>gov:</strong> cairo, alexandria, giza, etc.</li>
                                    <li><strong>limit:</strong> عدد النتائج (مثل: 6, 12)</li>
                                    <li><strong>columns:</strong> عدد الأعمدة (مثل: 3, 4)</li>
                                    <li><strong>show_filters:</strong> true/false</li>
                                    <li><strong>show_title:</strong> true/false</li>
                                </ul>
                            </div>
                        `
                    }
                ]
            });
        });
        
        // Add help item to menu
        editor.addMenuItem('distributor_help', {
            text: 'مساعدة شورت كودات الموزعين',
            context: 'tools',
            cmd: 'DistributorShortcodeHelp'
        });
    });
    
})();

// Add custom CSS for the button
(function() {
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = 'data:text/css;base64,' + btoa(`
        .mce-i-icon.dashicons-store:before {
            content: "\\f153";
            font-family: dashicons;
            font-size: 16px;
            line-height: 1;
        }
        
        .mce-distributor_shortcodes .mce-text {
            direction: rtl;
        }
        
        .mce-menu .mce-menu-item .mce-text {
            direction: rtl;
            text-align: right;
        }
    `);
    document.head.appendChild(link);
})();