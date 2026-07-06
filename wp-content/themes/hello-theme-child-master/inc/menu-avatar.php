<?php
/**
 * Reemplazar item de menú "Mi perfil" por el avatar del usuario.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sagicc_reemplazar_item_menu_con_avatar( $items, $args ) {
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$avatar_url   = get_avatar_url( $current_user->ID, [ 'size' => 40 ] );

		foreach ( $items as &$item ) {
			// Ajusta "Mi perfil" al texto exacto de tu ítem en el menú
			if ( $item->title === 'Mi perfil' ) {
				$item->title = '<img src="' . esc_url( $avatar_url ) . '" alt="Mi perfil" style="width:32px; height:32px; border-radius:50%;">';
			}
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'sagicc_reemplazar_item_menu_con_avatar', 10, 2 );
