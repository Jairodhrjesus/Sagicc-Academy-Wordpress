<?php
/**
 * Profile Hero Banner & Avatar Component
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sa-profile-hero">
	<div class="sa-profile-hero-banner"></div>
	<div class="sa-profile-hero-content">
		<div class="sa-profile-avatar-wrapper">
			<div class="sa-avatar-container">
				<?php if ( $p['has_custom_avatar'] ) : ?>
					<img src="<?php echo esc_url( $p['avatar_url'] ); ?>" class="sa-avatar-img" alt="<?php echo esc_attr( $p['full_name'] ); ?>" />
				<?php else : ?>
					<div class="sa-avatar-placeholder">
						<?php echo esc_html( $p['initial'] ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( $p['is_own_profile'] ) : ?>
					<label for="avatar_file" class="sa-avatar-overlay" title="<?php echo esc_attr( $p['t']( 'profile.avatar_title' ) ); ?>">
						<i class="fa-solid fa-camera text-xl mb-1"></i>
						<span class="text-xs font-bold uppercase">Subir</span>
					</label>
				<?php endif; ?>
			</div>

			<?php if ( $p['is_own_profile'] ) : ?>
				<form method="post" enctype="multipart/form-data" id="avatar-form" class="hidden">
					<?php wp_nonce_field( 'update_avatar_action', 'avatar_nonce' ); ?>
					<input type="file" name="avatar_file" id="avatar_file" accept="image/*" onchange="document.getElementById('avatar-form').submit();" />
					<input type="hidden" name="submit_avatar" value="1" />
				</form>
			<?php endif; ?>
		</div>

		<div class="sa-profile-hero-info">
			<div class="sa-profile-hero-meta">
				<span class="sa-badge sa-badge-role"><?php echo esc_html( $p['role_name'] ); ?></span>
				<span class="sa-badge sa-badge-id">ID: #<?php echo esc_html( $p['user_id'] ); ?></span>
			</div>
			<h2 class="sa-profile-hero-name"><?php echo esc_html( $p['full_name'] ); ?></h2>
			<p class="sa-profile-hero-email">
				<i class="fa-regular fa-envelope me-1"></i><?php echo esc_html( $p['email'] ); ?>
				<span class="ms-3"><i class="fa-regular fa-calendar me-1"></i><?php echo esc_html( $p['t']( 'profile.joined' ) ); ?> <?php echo esc_html( $p['registered_date'] ); ?></span>
			</p>
		</div>
	</div>
</div>
