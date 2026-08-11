<?php
/**
 * Custom Rewrite Rules & Profile Canonical Filters
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register rewrite rules for profile URLs (/profile/{username}/ and /{lang}/profile/{username}/)
add_action( 'init', function () {
	add_rewrite_rule( '^profile/([^/]+)/?$', 'index.php?pagename=profile&profile_user=$matches[1]', 'top' );
	add_rewrite_rule( '^(es|en)/profile/([^/]+)/?$', 'index.php?pagename=profile&profile_user=$matches[2]', 'top' );
} );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'profile_user';
	return $vars;
} );

// Prevent canonical redirect loops on custom profile URLs (/profile/ and /profile/{username}/)
add_filter( 'redirect_canonical', function ( $redirect_url, $requested_url ) {
	if ( get_query_var( 'profile_user' ) || get_query_var( 'pagename' ) === 'profile' || ( is_page() && get_post_field( 'post_name', get_queried_object_id() ) === 'profile' ) || strpos( $requested_url, '/profile/' ) !== false ) {
		return false;
	}
	return $redirect_url;
}, 10, 2 );

add_filter( 'pll_check_canonical_url', function ( $redirect_url ) {
	if ( get_query_var( 'profile_user' ) || get_query_var( 'pagename' ) === 'profile' || ( is_page() && get_post_field( 'post_name', get_queried_object_id() ) === 'profile' ) ) {
		return false;
	}
	return $redirect_url;
}, 10, 2 );

// Sobrescribir redirecciones de login (eliminar redirección a /tablero/)
add_filter( 'login_redirect', function ( $redirect_to, $requested_redirect_to, $user ) {
	if ( empty( $requested_redirect_to ) || strpos( $redirect_to, '/tablero' ) !== false ) {
		return home_url( '/' );
	}
	return $redirect_to;
}, 9999, 3 );

add_filter( 'um_login_redirect_url', function ( $url, $id ) {
	if ( empty( $url ) || strpos( $url, '/tablero' ) !== false ) {
		return home_url( '/' );
	}
	return $url;
}, 9999, 2 );
