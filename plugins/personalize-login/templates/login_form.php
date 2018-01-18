<div class="login-form-container drop-shadow">
	<?php if ( $attributes['show_title'] ) : ?>
		<h2 class="login"><?php _e( 'Client Login', 'personalize-login' ); ?></h2>
	<?php endif; ?>

	<!-- Show errors if there are any -->
	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
		<?php foreach ( $attributes['errors'] as $error ) : ?>
			<p class="login-error">
				<?php echo $error; ?>
			</p>
		<?php endforeach; ?>
	<?php endif; ?>

	<!-- Show logged out message if user just logged out -->
	<?php if ( $attributes['logged_out'] ) : ?>
		<p class="login-info">
			<?php _e( 'You have signed out. Would you like to sign in again?', 'personalize-login' ); ?>
		</p>
	<?php endif; ?>

	<?php
		wp_login_form(
			array(
				'label_username' => __( 'Email', 'personalize-login' ),
				'label_log_in' => __( 'Login', 'personalize-login' ),
				'redirect' => $attributes['redirect'],
			)
		);
	?>

	
</div>
<a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
		<?php _e( 'FORGOT PASSWORD?', 'personalize-login' ); ?>
</a>