<?php
// نموذج بحث وفرز الموزعين (AJAX)
$governorates = get_terms(array('taxonomy' => 'governorate', 'hide_empty' => false));
?>
<div id="distributor-search-filter" style="direction:rtl; text-align:right;">
    <form id="distributor-filter-form">
        <div class="form-group">
            <label for="filter_type">نوع الموزع</label>
            <select name="type" id="filter_type">
                <option value="">الكل</option>
                <option value="wholesale">جملة</option>
                <option value="retail">قطاعي</option>
                <option value="mixed">مختلط</option>
            </select>
        </div>
        <div class="form-group">
            <label for="filter_governorate">المحافظة</label>
            <select name="governorate" id="filter_governorate">
                <option value="">الكل</option>
                <?php foreach ($governorates as $gov): ?>
                    <option value="<?php echo esc_attr($gov->term_id); ?>"><?php echo esc_html($gov->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="filter_search">بحث بالاسم</label>
            <input type="text" name="search" id="filter_search" placeholder="ابحث باسم الموزع...">
        </div>
        <button type="submit" class="submit-btn">بحث</button>
    </form>
    <div id="distributor-search-results" style="margin-top:2rem;"></div>
</div>
<script>
jQuery(document).ready(function($){
    var offset = 0;
    var lastData = {};
    function loadDistributors(reset) {
        var data = $('#distributor-filter-form').serializeArray();
        var dataObj = {};
        $.each(data, function(i, field){ dataObj[field.name] = field.value; });
        if (!reset) offset += 6; else offset = 0;
        dataObj['offset'] = offset;
        dataObj['action'] = 'distributor_ajax_filter';
        dataObj['nonce'] = distributor_ajax.nonce;
        if (reset) $('#distributor-search-results').html('<div class="loading">جاري التحميل...</div>');
        $.post(distributor_ajax.ajax_url, dataObj, function(response){
            if (reset) $('#distributor-search-results').html(response);
            else $('#distributor-search-results').append(response);
            if ($('#load-more-distributors').length) {
                $('#load-more-distributors').off('click').on('click', function(){
                    $(this).remove();
                    loadDistributors(false);
                });
            }
        });
        lastData = dataObj;
    }
    $('#distributor-filter-form').on('submit', function(e){
        e.preventDefault();
        offset = 0;
        loadDistributors(true);
    });
    // Initial load
    loadDistributors(true);
});
</script>