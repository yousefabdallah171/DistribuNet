<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h1 class="title-profile"><?php esc_html_e('Change Password', 'guido'); ?></h1>
<div class="box-white-dashboard max-600">
	<form method="post" action="" class="change-password-form form-theme">
		<div class="clearfix">
			<div class="row">
				<div class="col-12">
					<div class="form-group">
						<label><?php echo esc_html__( 'Old password', 'guido' ); ?></label>
						<input id="change-password-form-old-password" class="form-control" type="password" name="old_password" required="required">
					</div><!-- /.form-control -->
				</div>
				<div class="col-12">
					<div class="form-group">
						<label><?php echo esc_html__( 'New password', 'guido' ); ?></label>
						<input id="change-password-form-new-password" class="form-control" type="password" name="new_password" required="required" minlength="8">
					</div><!-- /.form-control -->
				</div>
				<div class="col-12">
					<div class="form-group">
						<label><?php echo esc_html__( 'Retype password', 'guido' ); ?></label>
						<input id="change-password-form-retype-password" class="form-control" type="password" name="retype_password" required="required" minlength="8">
					</div><!-- /.form-control -->
				</div>
			</div>
		</div>
		<button type="submit" name="change_password_form" class="button btn btn-theme btn-inverse"><?php echo esc_html__( 'Change Password', 'guido' ); ?></button>
	</form>
</div>