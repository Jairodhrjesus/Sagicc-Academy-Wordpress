<?php
/**
 * Title: Profile Page Sagicc Academy
 * Slug: theme_sagicc_academy/profile
 * Categories: page, custom
 */

// Redirigir a la pantalla de login si el usuario no ha iniciado sesión
if (!is_user_logged_in()) {
	wp_redirect(home_url('/login/'));
	exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Procesamiento de subida de avatar
$upload_error = '';
if ( isset( $_POST['submit_avatar'] ) && ! empty( $_FILES['avatar_file'] ) ) {
	if ( isset( $_POST['avatar_nonce'] ) && wp_verify_nonce( $_POST['avatar_nonce'], 'update_avatar_action' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		// Procesar la subida como un attachment
		$attachment_id = media_handle_upload( 'avatar_file', 0 );

		if ( is_wp_error( $attachment_id ) ) {
			$upload_error = $attachment_id->get_error_message();
		} else {
			// WordPress nativo / plugins de avatar
			update_user_meta( $user_id, 'wp_user_avatar', $attachment_id );
			
			// Ultimate Member: nombre de archivo en profile_photo
			$file_path = get_attached_file( $attachment_id );
			$file_name = basename( $file_path );
			update_user_meta( $user_id, 'profile_photo', $file_name );
		}
	} else {
		$upload_error = 'Error de seguridad. Por favor, intenta de nuevo.';
	}
}

// Procesamiento de cambio de contraseña
$password_error = '';
$password_success = '';
if ( isset( $_POST['submit_password'] ) ) {
	if ( isset( $_POST['password_nonce'] ) && wp_verify_nonce( $_POST['password_nonce'], 'update_password_action' ) ) {
		$current_pass = $_POST['current_password'];
		$new_pass     = $_POST['new_password'];
		$confirm_pass = $_POST['confirm_password'];

		if ( empty( $current_pass ) || empty( $new_pass ) || empty( $confirm_pass ) ) {
			$password_error = 'Por favor, completa todos los campos.';
		} elseif ( ! wp_check_password( $current_pass, $current_user->user_pass, $user_id ) ) {
			$password_error = 'La contraseña actual es incorrecta.';
		} elseif ( $new_pass !== $confirm_pass ) {
			$password_error = 'Las contraseñas nuevas no coinciden.';
		} elseif ( strlen( $new_pass ) < 6 ) {
			$password_error = 'La contraseña debe tener al menos 6 caracteres.';
		} else {
			$update_status = wp_update_user( array(
				'ID'        => $user_id,
				'user_pass' => $new_pass
			) );

			if ( is_wp_error( $update_status ) ) {
				$password_error = $update_status->get_error_message();
			} else {
				// Re-autenticar al usuario para evitar el cierre de sesión
				wp_set_current_user( $user_id );
				wp_set_auth_cookie( $user_id );
				$password_success = 'Contraseña actualizada con éxito.';
			}
		}
	} else {
		$password_error = 'Error de seguridad. Por favor, intenta de nuevo.';
	}
}

// Idioma actual
$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es';

// Traducciones
$translations = array(
	'es' => array(
		'dashboard.profile' => 'Perfil',
		'profile.desc' => 'Gestiona tu información personal y preferencias.',
		'profile.id' => 'ID de Usuario',
		'profile.email' => 'Correo electrónico',
		'profile.avatar_title' => 'Cambiar foto de perfil',
		'profile.change_password' => 'Cambiar contraseña',
		'profile.current_password' => 'Contraseña actual',
		'profile.new_password' => 'Nueva contraseña',
		'profile.confirm_password' => 'Confirmar nueva contraseña',
		'profile.update_btn' => 'Actualizar contraseña',
	),
	'en' => array(
		'dashboard.profile' => 'Profile',
		'profile.desc' => 'Manage your personal information and preferences.',
		'profile.id' => 'User ID',
		'profile.email' => 'Email address',
		'profile.avatar_title' => 'Change profile picture',
		'profile.change_password' => 'Change password',
		'profile.current_password' => 'Current password',
		'profile.new_password' => 'New password',
		'profile.confirm_password' => 'Confirm new password',
		'profile.update_btn' => 'Update password',
	)
);

$t = function ($key) use ($translations, $lang) {
	return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
};

// Obtener detalles del usuario de WordPress
$first_name = $current_user->user_firstname;
$last_name = $current_user->user_lastname;
$display_name = $current_user->display_name;
$email = $current_user->user_email;

$full_name = trim($first_name . ' ' . $last_name);
if (empty($full_name)) {
	$full_name = $display_name;
}

$initial = !empty($first_name) ? strtoupper(substr($first_name, 0, 1)) : (!empty($display_name) ? strtoupper(substr($display_name, 0, 1)) : 'U');

// Obtener el rol del usuario de WordPress
$roles = $current_user->roles;
$role_name = !empty($roles) ? ucfirst($roles[0]) : 'Subscriber';

// Determinar el avatar del usuario
$avatar_url = get_avatar_url( $user_id, array( 'size' => 128 ) );
$has_custom_avatar = false;
$avatar_meta = get_user_meta( $user_id, 'wp_user_avatar', true );
$um_avatar_meta = get_user_meta( $user_id, 'profile_photo', true );
if ( ! empty( $avatar_meta ) || ! empty( $um_avatar_meta ) ) {
	$has_custom_avatar = true;
}
?>

<div class="sa-profile-wrapper">
	<header class="sa-header">
		<h1 class="sa-header-title">
			<?php echo esc_html($t('dashboard.profile')); ?>
		</h1>
		<p class="sa-header-subtitle"><?php echo esc_html($t('profile.desc')); ?></p>
	</header>

	<?php if ( ! empty( $upload_error ) ) : ?>
		<div class="sa-alert-error">
			<?php echo esc_html( $upload_error ); ?>
		</div>
	<?php endif; ?>

	<div class="sa-profile-box">
		<div class="sa-profile-header-flex">
			<!-- Contenedor del Avatar con overlay de carga -->
			<div class="sa-avatar-container">
				<?php if ( $has_custom_avatar ) : ?>
					<img src="<?php echo esc_url( $avatar_url ); ?>" class="sa-avatar-img" alt="<?php echo esc_attr( $full_name ); ?>" />
				<?php else : ?>
					<div class="sa-avatar-placeholder">
						<?php echo esc_html($initial); ?>
					</div>
				<?php endif; ?>
				
				<label for="avatar_file" class="sa-avatar-overlay" title="<?php echo esc_attr( $t('profile.avatar_title') ); ?>">
					<i class="fa-solid fa-camera text-xl mb-1"></i>
					<span class="text-xs font-bold uppercase">Subir</span>
				</label>
			</div>

			<!-- Formulario oculto para procesamiento del archivo -->
			<form method="post" enctype="multipart/form-data" id="avatar-form" class="hidden">
				<?php wp_nonce_field( 'update_avatar_action', 'avatar_nonce' ); ?>
				<input type="file" name="avatar_file" id="avatar_file" accept="image/*" onchange="document.getElementById('avatar-form').submit();" />
				<input type="hidden" name="submit_avatar" value="1" />
			</form>

			<div>
				<h2 class="sa-profile-name"><?php echo esc_html($full_name); ?></h2>
				<p class="sa-profile-role">
					<?php echo esc_html($role_name); ?></p>
				<p class="sa-profile-email"><?php echo esc_html($email); ?></p>
			</div>
		</div>

		<div>
			<div class="sa-info-card">
				<p class="sa-info-label">
					<?php echo esc_html($t('profile.id')); ?></p>
				<p class="sa-info-value sa-info-value-mono"><?php echo esc_html($user_id); ?></p>
			</div>

			<div class="sa-info-card">
				<p class="sa-info-label">
					<?php echo esc_html($t('profile.email')); ?></p>
				<p class="sa-info-value"><?php echo esc_html($email); ?></p>
			</div>
		</div>

		<!-- Formulario de cambio de contraseña -->
		<div class="sa-form-section">
			<h3 class="sa-form-section-title"><?php echo esc_html($t('profile.change_password')); ?></h3>
			
			<?php if ( ! empty( $password_error ) ) : ?>
				<div class="sa-alert-error">
					<?php echo esc_html( $password_error ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $password_success ) ) : ?>
				<div class="sa-alert-success">
					<?php echo esc_html( $password_success ); ?>
				</div>
			<?php endif; ?>

			<form method="post">
				<div class="sa-form-group">
					<?php wp_nonce_field( 'update_password_action', 'password_nonce' ); ?>
					
					<label for="current_password" class="sa-form-label"><?php echo esc_html($t('profile.current_password')); ?></label>
					<input type="password" name="current_password" id="current_password" required class="sa-input" />
				</div>

				<div class="sa-form-grid-2">
					<div class="sa-form-group">
						<label for="new_password" class="sa-form-label"><?php echo esc_html($t('profile.new_password')); ?></label>
						<input type="password" name="new_password" id="new_password" required class="sa-input" />
					</div>
					
					<div class="sa-form-group">
						<label for="confirm_password" class="sa-form-label"><?php echo esc_html($t('profile.confirm_password')); ?></label>
						<input type="password" name="confirm_password" id="confirm_password" required class="sa-input" />
					</div>
				</div>

				<div style="padding-top: 0.5rem;">
					<button type="submit" name="submit_password" class="sa-btn sa-btn-secondary w-full">
						<?php echo esc_html($t('profile.update_btn')); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>