<?php
/**
 *
 * Search form.
 * @since 1.0.0
 * @version 1.0.0
 *
 */
?>
<div class="widget_search">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
		<input type="text" placeholder="<?php esc_attr_e( 'Enter keyword and hit enter', 'guido' ); ?>" name="s" class="form-control"/>
		<input type="hidden" name="post_type" value="post" class="post_type" />
	</form>
</div>