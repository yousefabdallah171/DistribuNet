<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


ob_start();

$current_currency = WP_Listings_Directory_Price::get_current_currency();
$multi_currencies = WP_Listings_Directory_Price::get_currencies_settings();

if ( !empty($multi_currencies) && !empty($multi_currencies[$current_currency]) ) {
	$currency_args = $multi_currencies[$current_currency];
}

if ( !empty($currency_args) ) {
	$currency_symbol = !empty($currency_args['custom_symbol']) ? $currency_args['custom_symbol'] : '';
	if ( empty($currency_symbol) ) {
		$currency = !empty($currency_args['currency']) ? $currency_args['currency'] : 'USD';
		$currency_symbol = WP_Listings_Directory_Price::currency_symbol($currency);
	}
}

if ( empty($currency_symbol) ) {
	$currency_symbol = '$';
}

$price_range = WP_Listings_Directory_Mixes::price_range_icons();
foreach ($price_range as $key => $value) {
    ?>
    <option value="<?php echo esc_attr($key); ?>" <?php selected($selected, $key); ?>>
        <?php echo str_repeat( $currency_symbol, $value['icon'] ).' - '.$value['label']; ?>
    </option>
    <?php
}

$output = ob_get_clean();

if ( !empty($output) ) {
    $placeholder = !empty($field['placeholder']) ? $field['placeholder'] : sprintf(__('Filter by %s', 'wp-listings-directory'), $field['label']);
?>
    <div class="form-group form-group-<?php echo esc_attr($key); ?>">
        <?php if ( !isset($field['show_title']) || $field['show_title'] ) { ?>
            <label for="<?php echo esc_attr( $args['widget_id'] ); ?>_<?php echo esc_attr($key); ?>" class="heading-label">
                <?php echo wp_kses_post($field['name']); ?>
            </label>
        <?php } ?>
        <div class="form-group-inner inner">
            <?php if ( !empty($field['icon']) ) { ?>
                <i class="<?php echo esc_attr( $field['icon'] ); ?>"></i>
            <?php } ?>
            <select name="<?php echo esc_attr($name); ?>" class="form-control" id="<?php echo esc_attr( $args['widget_id'] ); ?>_<?php echo esc_attr($key); ?>" data-placeholder="<?php echo esc_attr($placeholder); ?>">
                <option value=""><?php echo esc_html($placeholder); ?></option>
                <?php echo $output; ?>
            </select>
        </div>
    </div><!-- /.form-group -->
<?php }