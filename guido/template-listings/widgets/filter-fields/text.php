<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="form-group form-group-<?php echo esc_attr($key); ?>">
	<?php if ( !isset($field['show_title']) || $field['show_title'] ) { ?>
    	<label for="<?php echo esc_attr( $args['widget_id'] ); ?>_<?php echo esc_attr($key); ?>" class="heading-label">
    		<?php echo trim($field['name']); ?>
    	</label>
    <?php } ?>
    <div class="form-group-inner inner <?php echo esc_attr(!empty($field['suggestions_menu']) ? 'has-suggestion' : ''); ?>">
	    <?php if ( !empty($field['icon']) ) { ?>
	    	<i class="<?php echo esc_attr( $field['icon'] ); ?>"></i>
	    <?php } ?>
	    <input type="text" name="<?php echo esc_attr($name); ?>" class="form-control <?php echo esc_attr(!empty($field['add_class']) ? $field['add_class'] : '');?>"
	           value="<?php echo esc_attr($selected); ?>"
	           id="<?php echo esc_attr( $args['widget_id'] ); ?>_<?php echo esc_attr($key); ?>" placeholder="<?php echo esc_attr(!empty($field['placeholder']) ? $field['placeholder'] : ''); ?>">

       	<?php
        if ( !empty($field['suggestions_menu']) ) {
            $args = array(
                'menu'        => $field['suggestions_menu'],
                'container_class' => 'navbar-collapse navbar-collapse-suggestions',
                'menu_class' => 'list search-suggestions-menu',
                'fallback_cb' => '',
                'walker' => new Guido_Nav_Menu()
            );
            wp_nav_menu($args, $field['suggestions_menu']);
        }
        ?>
	</div>
</div><!-- /.form-group -->
