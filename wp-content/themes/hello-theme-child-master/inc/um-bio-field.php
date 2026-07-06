<?php
/**
 * Campo biográfico personalizado para Ultimate Member.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1) Mostrar el campo sólo en “Mi Cuenta”
add_action( 'um_after_account_general', 'showBiographicalField', 100 );
function showBiographicalField() {
	if ( ! function_exists( 'um_is_core_page' ) || ! um_is_core_page( 'account' ) ) {
		return;
	}

	$key     = 'description';
	$label   = 'Acerca de ti';
	$user_id = get_current_user_id();
	$user    = get_user_by( 'ID', $user_id );
	$value   = $user ? $user->description : '';

	echo '<div class="um-field um-field-' . esc_attr( $key ) . '" data-key="' . esc_attr( $key ) . '">
		<div class="um-field-label">
			<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>
			<div class="um-clear"></div>
		</div>
		<div class="um-field-area">
			<textarea class="um-form-field valid"
				name="' . esc_attr( $key ) . '"
				id="' . esc_attr( $key ) . '"
				placeholder="Información biográfica"
				rows="6">' . esc_textarea( $value ) . '</textarea>
		</div>
	</div>';
}

// 2) Guardar siempre sobre el usuario logueado
add_action( 'um_user_edit_profile', 'save_biographical_field', 10 );
add_action( 'um_account_pre_update_profile', 'save_biographical_field', 10 );
function save_biographical_field( $ignored_user_id ) {
	$user_id = get_current_user_id();

	if ( isset( $_POST['description'] ) ) {
		$bio = sanitize_textarea_field( wp_unslash( $_POST['description'] ) );

		update_user_meta( $user_id, 'description', $bio );

		wp_update_user(
			[
				'ID'          => $user_id,
				'description' => $bio,
			]
		);
	}
}
