<?php
/**
 * Profile Data Handler
 * Handles form submissions, translation data, and profile stats calculations.
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$profile_username = get_query_var( 'profile_user' );
$view_user = null;

if ( ! empty( $profile_username ) ) {
	$view_user = get_user_by( 'slug', $profile_username );
	if ( ! $view_user ) {
		$view_user = get_user_by( 'login', $profile_username );
	}
}

if ( ! $view_user ) {
	$view_user = wp_get_current_user();
}

$current_user   = $view_user;
$user_id        = $current_user->ID;
$is_own_profile = ( get_current_user_id() === $user_id );

// Form Messages
$info_error       = '';
$info_success     = '';
$upload_error     = '';
$upload_success   = '';
$password_error   = '';
$password_success = '';

// 1. Update Personal Info Form
if ( $is_own_profile && isset( $_POST['submit_profile_info'] ) ) {
	if ( isset( $_POST['profile_info_nonce'] ) && wp_verify_nonce( $_POST['profile_info_nonce'], 'update_profile_info_action' ) ) {
		$new_first_name   = sanitize_text_field( $_POST['first_name'] );
		$new_last_name    = sanitize_text_field( $_POST['last_name'] );
		$new_display_name = sanitize_text_field( $_POST['display_name'] );
		$new_email        = sanitize_email( $_POST['email'] );

		if ( ! is_email( $new_email ) ) {
			$info_error = 'El correo electrónico no es válido.';
		} else {
			$update_data = array(
				'ID'           => $user_id,
				'first_name'   => $new_first_name,
				'last_name'    => $new_last_name,
				'display_name' => $new_display_name,
				'user_email'   => $new_email,
			);
			$res = wp_update_user( $update_data );
			if ( is_wp_error( $res ) ) {
				$info_error = $res->get_error_message();
			} else {
				$info_success = 'Información personal actualizada con éxito.';
				$current_user = get_userdata( $user_id );
			}
		}
	} else {
		$info_error = 'Error de seguridad. Por favor, intenta de nuevo.';
	}
}

// 2. Update Avatar Upload
if ( $is_own_profile && isset( $_POST['submit_avatar'] ) && ! empty( $_FILES['avatar_file'] ) ) {
	if ( isset( $_POST['avatar_nonce'] ) && wp_verify_nonce( $_POST['avatar_nonce'], 'update_avatar_action' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$attachment_id = media_handle_upload( 'avatar_file', 0 );

		if ( is_wp_error( $attachment_id ) ) {
			$upload_error = $attachment_id->get_error_message();
		} else {
			update_user_meta( $user_id, 'wp_user_avatar', $attachment_id );
			$file_path = get_attached_file( $attachment_id );
			$file_name = basename( $file_path );
			update_user_meta( $user_id, 'profile_photo', $file_name );
			$upload_success = 'Foto de perfil actualizada correctamente.';
		}
	} else {
		$upload_error = 'Error de seguridad. Por favor, intenta de nuevo.';
	}
}

// 3. Update Password
if ( $is_own_profile && isset( $_POST['submit_password'] ) ) {
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
				wp_set_current_user( $user_id );
				wp_set_auth_cookie( $user_id );
				$password_success = 'Contraseña actualizada con éxito.';
			}
		}
	} else {
		$password_error = 'Error de seguridad. Por favor, intenta de nuevo.';
	}
}

// Language Translations
$lang = isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es';
$translations = array(
	'es' => array(
		'dashboard.profile'       => 'Perfil de Usuario',
		'profile.desc'            => 'Administra tu cuenta, información personal y preferencias de seguridad.',
		'profile.id'              => 'ID de Usuario',
		'profile.username'        => 'Nombre de Usuario',
		'profile.email'           => 'Correo Electrónico',
		'profile.first_name'      => 'Nombre',
		'profile.last_name'       => 'Apellido',
		'profile.display_name'    => 'Nombre Visibilidad',
		'profile.role'            => 'Rol',
		'profile.joined'          => 'Miembro desde',
		'profile.avatar_title'    => 'Cambiar foto de perfil',
		'profile.info_title'      => 'Información Personal',
		'profile.save_info_btn'   => 'Guardar Cambios',
		'profile.change_password' => 'Seguridad y Contraseña',
		'profile.current_password'=> 'Contraseña Actual',
		'profile.new_password'    => 'Nueva Contraseña',
		'profile.confirm_password'=> 'Confirmar Nueva Contraseña',
		'profile.update_btn'      => 'Actualizar Contraseña',
		'profile.stats_courses'   => 'Cursos Completados',
		'profile.stats_certs'     => 'Certificados Obtenidos',
		'profile.stats_status'    => 'Estado de Cuenta',
		'profile.active'          => 'Activa',
	),
	'en' => array(
		'dashboard.profile'       => 'User Profile',
		'profile.desc'            => 'Manage your account, personal information, and security preferences.',
		'profile.id'              => 'User ID',
		'profile.username'        => 'Username',
		'profile.email'           => 'Email Address',
		'profile.first_name'      => 'First Name',
		'profile.last_name'       => 'Last Name',
		'profile.display_name'    => 'Display Name',
		'profile.role'            => 'Role',
		'profile.joined'          => 'Member since',
		'profile.avatar_title'    => 'Change profile picture',
		'profile.info_title'      => 'Personal Information',
		'profile.save_info_btn'   => 'Save Changes',
		'profile.change_password' => 'Security & Password',
		'profile.current_password'=> 'Current Password',
		'profile.new_password'    => 'New Password',
		'profile.confirm_password'=> 'Confirm New Password',
		'profile.update_btn'      => 'Update Password',
		'profile.stats_courses'   => 'Completed Courses',
		'profile.stats_certs'     => 'Earned Certificates',
		'profile.stats_status'    => 'Account Status',
		'profile.active'          => 'Active',
	)
);

$t = function ( $key ) use ( $translations, $lang ) {
	return isset( $translations[ $lang ][ $key ] ) ? $translations[ $lang ][ $key ] : $key;
};

// User Profile Attributes
$first_name   = $current_user->user_firstname;
$last_name    = $current_user->user_lastname;
$display_name = $current_user->display_name;
$email        = $current_user->user_email;
$username     = $current_user->user_login;

$full_name = trim( $first_name . ' ' . $last_name );
if ( empty( $full_name ) ) {
	$full_name = $display_name;
}

$initial = ! empty( $first_name ) ? strtoupper( substr( $first_name, 0, 1 ) ) : ( ! empty( $display_name ) ? strtoupper( substr( $display_name, 0, 1 ) ) : 'U' );

$roles     = $current_user->roles;
$role_name = ! empty( $roles ) ? ucfirst( $roles[0] ) : 'Subscriber';

$registered_date = date_i18n( get_option( 'date_format' ), strtotime( $current_user->user_registered ) );

$avatar_url        = get_avatar_url( $user_id, array( 'size' => 160 ) );
$has_custom_avatar = false;
$avatar_meta       = get_user_meta( $user_id, 'wp_user_avatar', true );
$um_avatar_meta    = get_user_meta( $user_id, 'profile_photo', true );
if ( ! empty( $avatar_meta ) || ! empty( $um_avatar_meta ) ) {
	$has_custom_avatar = true;
}

// User Stats
$completed_courses_count = 0;
if ( function_exists( 'learndash_user_get_completed_courses' ) ) {
	$completed_courses = learndash_user_get_completed_courses( $user_id );
	if ( is_array( $completed_courses ) ) {
		$completed_courses_count = count( $completed_courses );
	}
}

$certificates_count = 0;
$user_quizzes       = get_user_meta( $user_id, '_sfwd-quizzes', true );
if ( is_array( $user_quizzes ) ) {
	foreach ( $user_quizzes as $q ) {
		if ( ! empty( $q['pass'] ) && ! empty( $q['certificate']['url'] ) ) {
			$certificates_count++;
		}
	}
}
$certificates_count += $completed_courses_count;

return compact(
	'user_id',
	'current_user',
	'is_own_profile',
	'first_name',
	'last_name',
	'display_name',
	'email',
	'username',
	'full_name',
	'initial',
	'role_name',
	'registered_date',
	'avatar_url',
	'has_custom_avatar',
	'completed_courses_count',
	'certificates_count',
	'info_error',
	'info_success',
	'upload_error',
	'upload_success',
	'password_error',
	'password_success',
	't'
);
