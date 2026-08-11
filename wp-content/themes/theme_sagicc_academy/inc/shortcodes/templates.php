<?php
/**
 * Template Loader Shortcodes (Sidebar, Profile, Login, Register)
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'sagicc_sidebar', function () {
	ob_start();
	include get_template_directory() . '/patterns/sidebar.php';
	return ob_get_clean();
} );

add_shortcode( 'sagicc_profile', function () {
	ob_start();
	include get_template_directory() . '/patterns/profile.php';
	return ob_get_clean();
} );

add_shortcode( 'sagicc_login', function () {
	ob_start();
	include get_template_directory() . '/patterns/login.php';
	return ob_get_clean();
} );

add_shortcode( 'sagicc_register', function () {
	ob_start();
	include get_template_directory() . '/patterns/register.php';
	return ob_get_clean();
} );
