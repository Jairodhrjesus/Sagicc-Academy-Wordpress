<?php
/**
 * Profile Header Component
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sa-profile-header-card">
	<div class="sa-profile-header-main">
		<!-- Avatar con botón directo de cambio (sin hover oculto) -->
		<div class="sa-profile-avatar-container">
			<?php if ( $p['has_custom_avatar'] ) : ?>
				<img src="<?php echo esc_url( $p['avatar_url'] ); ?>" class="sa-profile-avatar" alt="<?php echo esc_attr( $p['full_name'] ); ?>" />
			<?php else : ?>
				<div class="sa-profile-avatar-initial">
					<?php echo esc_html( $p['initial'] ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $p['is_own_profile'] ) : ?>
				<label for="avatar_file" class="sa-avatar-change-btn" title="<?php echo esc_attr( $p['t']( 'profile.avatar_title' ) ); ?>">
					<i class="fa-solid fa-camera"></i>
				</label>
				<form method="post" enctype="multipart/form-data" id="avatar-form" class="hidden">
					<?php wp_nonce_field( 'update_avatar_action', 'avatar_nonce' ); ?>
					<input type="file" name="avatar_file" id="avatar_file" accept="image/*" onchange="document.getElementById('avatar-form').submit();" />
					<input type="hidden" name="submit_avatar" value="1" />
				</form>
			<?php endif; ?>
		</div>

		<!-- Información principal del usuario -->
		<div class="sa-profile-user-info">
			<div class="sa-profile-badges">
				<span class="sa-badge sa-badge-role"><?php echo esc_html( $p['role_name'] ); ?></span>
				<span class="sa-badge sa-badge-id">ID: #<?php echo esc_html( $p['user_id'] ); ?></span>
			</div>
			<h2 class="sa-profile-user-name"><?php echo esc_html( $p['full_name'] ); ?></h2>
			<div class="sa-profile-user-details">
				<span><i class="fa-regular fa-envelope me-2"></i><?php echo esc_html( $p['email'] ); ?></span>
				<span><i class="fa-regular fa-calendar me-2"></i><?php echo esc_html( $p['t']( 'profile.joined' ) ); ?> <?php echo esc_html( $p['registered_date'] ); ?></span>
			</div>
		</div>
	</div>
</div>
