<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style( 'dashicons' );
?>
<div class="profile-form-wrapper">
	<h1 class="title-profile"><?php esc_html_e( 'Edit Profile', 'guido' ) ; ?></h1>
	<div class="row">
		<div class="col-12 col-lg-8">
			<div class="box-white-dashboard">
				<h3 class="title"><?php esc_html_e( 'Profile Details', 'guido' ) ; ?></h3>
				<?php if ( ! empty( $_SESSION['messages'] ) ) : ?>

					<?php foreach ( $_SESSION['messages'] as $message ) { ?>
						<?php
						$status = !empty( $message[0] ) ? $message[0] : 'success';
						if ( !empty( $message[1] ) ) {
						?>
						<div class="alert alert-<?php echo esc_attr( $status ) ?> margin-bottom-15">
							<?php echo trim( $message[1] ); ?>
						</div>
					<?php
						}
					}
					unset( $_SESSION['messages'] );
					?>

				<?php endif; ?>

				<?php
					$user_id = get_current_user_id();
					echo cmb2_get_metabox_form( $metaboxes_form, $user_id, array(
						'form_format' => '<form action="' . esc_url(WP_Listings_Directory_Mixes::get_full_current_url()) . '" class="cmb-form form-profile" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<input type="submit" name="submit-cmb-profile" value="%4$s" class="button-primary btn btn-theme"></form>',
						'save_button' => esc_html__( 'Save Profile', 'guido' ),
					) );
				?>
			</div>
		</div>	
		<div class="col-12 col-lg-4">
			<div class="box-white-dashboard">
				<h3 class="title"><?php esc_html_e( 'Change Password', 'guido' ) ; ?></h3>
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
		</div>
	</div>
</div>