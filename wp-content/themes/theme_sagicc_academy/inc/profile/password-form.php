<?php
/**
 * Profile Password Form Component
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sa-card sa-profile-card">
	<div class="sa-card-header">
		<h3 class="sa-section-title"><i class="fa-solid fa-lock me-2 text-primary"></i><?php echo esc_html( $p['t']( 'profile.change_password' ) ); ?></h3>
	</div>
	<div class="sa-card-body">
		<?php if ( ! empty( $p['password_error'] ) ) : ?>
			<div class="sa-alert-error"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo esc_html( $p['password_error'] ); ?></div>
		<?php endif; ?>
		<?php if ( ! empty( $p['password_success'] ) ) : ?>
			<div class="sa-alert-success"><i class="fa-solid fa-circle-check me-2"></i><?php echo esc_html( $p['password_success'] ); ?></div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'update_password_action', 'password_nonce' ); ?>
			
			<div class="sa-form-group">
				<label for="current_password" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.current_password' ) ); ?></label>
				<input type="password" name="current_password" id="current_password" required class="sa-input" />
			</div>

			<div class="sa-form-grid-2">
				<div class="sa-form-group">
					<label for="new_password" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.new_password' ) ); ?></label>
					<input type="password" name="new_password" id="new_password" required class="sa-input" />
				</div>
				
				<div class="sa-form-group">
					<label for="confirm_password" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.confirm_password' ) ); ?></label>
					<input type="password" name="confirm_password" id="confirm_password" required class="sa-input" />
				</div>
			</div>

			<div class="sa-form-actions">
				<button type="submit" name="submit_password" class="sa-btn sa-btn-outline">
					<i class="fa-solid fa-key me-2"></i><?php echo esc_html( $p['t']( 'profile.update_btn' ) ); ?>
				</button>
			</div>
		</form>
	</div>
</div>
