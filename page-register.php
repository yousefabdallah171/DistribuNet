<?php
/**
 * Template Name: صفحة التسجيل للموزعين
 * Enhanced Arabic Registration Form
 */

get_header(); ?>
<div class="container registration-form" style="max-width:600px; margin:2rem auto;">
    <h1 class="text-center">تسجيل موزع جديد</h1>
    <?php if (isset($_GET['registration']) && $_GET['registration'] === 'success'): ?>
        <div class="form-message success">تم إرسال طلبك بنجاح! سنقوم بمراجعته قريبًا.</div>
    <?php endif; ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data" style="direction:rtl;">
        <input type="hidden" name="action" value="distributor_registration">
        <?php wp_nonce_field('distributor_registration', 'distributor_nonce'); ?>
        <div class="form-group">
            <label for="distributor_type">نوع الموزع <span class="required">*</span></label>
            <select name="distributor_type" id="distributor_type" required>
                <option value="">اختر النوع</option>
                <option value="wholesale">جملة</option>
                <option value="retail">قطاعي</option>
                <option value="mixed">مختلط</option>
            </select>
        </div>
        <div class="form-group">
            <label for="company_name">اسم الشركة <span class="required">*</span></label>
            <input type="text" name="company_name" id="company_name" required>
        </div>
        <div class="form-group">
            <label for="full_name">اسم المسؤول <span class="required">*</span></label>
            <input type="text" name="full_name" id="full_name" required>
        </div>
        <div class="form-group">
            <label for="phone">رقم الهاتف <span class="required">*</span></label>
            <input type="text" name="phone" id="phone" required>
        </div>
        <div class="form-group">
            <label for="whatsapp">رقم واتساب</label>
            <input type="text" name="whatsapp" id="whatsapp" placeholder="اتركه فارغًا لاستخدام رقم الهاتف">
        </div>
        <div class="form-group">
            <label for="email">البريد الإلكتروني <span class="required">*</span></label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="full_address">العنوان الكامل</label>
            <textarea name="full_address" id="full_address" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label for="governorate">المحافظة <span class="required">*</span></label>
            <select name="governorate" id="governorate" required>
                <option value="">اختر المحافظة</option>
                <?php
                $governorates = get_terms(array('taxonomy' => 'governorate', 'hide_empty' => false));
                foreach ($governorates as $gov) {
                    echo '<option value="' . esc_attr($gov->term_id) . '">' . esc_html($gov->name) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="location">الموقع على الخريطة</label>
            <input type="text" name="location" id="location" placeholder="أدخل رابط Google Maps أو الإحداثيات">
        </div>
        <div class="form-group">
            <label>روابط التواصل الاجتماعي</label>
            <div id="social-links-repeater">
                <div class="social-link-row">
                    <select name="social_type[]">
                        <option value="">اختر النوع</option>
                        <option value="facebook">فيسبوك</option>
                        <option value="instagram">انستجرام</option>
                        <option value="website">موقع إلكتروني</option>
                    </select>
                    <input type="url" name="social_url[]" placeholder="الرابط">
                    <button type="button" class="remove-social-link" onclick="this.parentNode.remove();">حذف</button>
                </div>
            </div>
            <button type="button" id="add-social-link">إضافة رابط جديد</button>
        </div>
        <div class="form-group">
            <label for="business_description">وصف النشاط</label>
            <textarea name="business_description" id="business_description" rows="3"></textarea>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="submit-btn">إرسال الطلب</button>
        </div>
    </form>
</div>
<script>
document.getElementById('add-social-link').onclick = function() {
    var row = document.createElement('div');
    row.className = 'social-link-row';
    row.innerHTML = `<select name="social_type[]">
        <option value="">اختر النوع</option>
        <option value="facebook">فيسبوك</option>
        <option value="instagram">انستجرام</option>
        <option value="website">موقع إلكتروني</option>
    </select>
    <input type="url" name="social_url[]" placeholder="الرابط">
    <button type="button" class="remove-social-link" onclick="this.parentNode.remove();">حذف</button>`;
    document.getElementById('social-links-repeater').appendChild(row);
};
</script>
<?php get_footer(); ?>