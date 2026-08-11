<?php
/**
 * Profile Personal Info Form Component
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sa-card sa-profile-card">
	<div class="sa-card-header">
		<h3 class="sa-section-title"><i class="fa-solid fa-user-pen me-2 text-primary"></i><?php echo esc_html( $p['t']( 'profile.info_title' ) ); ?></h3>
	</div>
	<div class="sa-card-body">
		<form method="post">
			<?php wp_nonce_field( 'update_profile_info_action', 'profile_info_nonce' ); ?>
			
			<div class="sa-form-grid-2">
				<div class="sa-form-group">
					<label for="first_name" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.first_name' ) ); ?></label>
					<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $p['first_name'] ); ?>" class="sa-input" />
				</div>
				
				<div class="sa-form-group">
					<label for="last_name" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.last_name' ) ); ?></label>
					<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $p['last_name'] ); ?>" class="sa-input" />
				</div>
			</div>

			<div class="sa-form-grid-2">
				<div class="sa-form-group">
					<label for="display_name" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.display_name' ) ); ?></label>
					<input type="text" name="display_name" id="display_name" value="<?php echo esc_attr( $p['display_name'] ); ?>" required class="sa-input" />
				</div>

				<div class="sa-form-group">
					<label for="email" class="sa-form-label"><?php echo esc_html( $p['t']( 'profile.email' ) ); ?></label>
					<div class="position-relative">
						<input type="email" id="email" value="<?php echo esc_attr( $p['email'] ); ?>" readonly class="sa-input sa-input-readonly" title="El correo electrónico no se puede modificar desde aquí." />
					</div>
				</div>
			</div>

			<div class="sa-form-actions">
				<button type="submit" name="submit_profile_info" class="sa-btn sa-btn-primary">
					<i class="fa-solid fa-floppy-disk me-2"></i><?php echo esc_html( $p['t']( 'profile.save_info_btn' ) ); ?>
				</button>
			</div>
		</form>
	</div>
</div>
