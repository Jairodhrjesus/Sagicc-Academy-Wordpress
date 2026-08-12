<?php
/**
 * Enqueue scripts and styles for Sagicc Academy Theme
 *
 * @package theme_sagicc_academy
 */

/**
 * Enqueue scripts and styles.
 */
function theme_sagicc_academy_scripts() {
	$theme_version = filemtime( get_template_directory() . '/assets/css/main.css' );

	// Google Fonts: Inter and Outfit
	wp_enqueue_style( 'theme-sagicc-academy-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Outfit:wght@400;600;800;900&display=swap', array(), null );

	// Google Material Symbols Icons
	wp_enqueue_style( 'google-material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0', array(), null );

	// FontAwesome for Icons
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

	// Pure CSS Main Stylesheet
	wp_enqueue_style( 'theme-sagicc-academy-main', get_template_directory_uri() . '/assets/css/main.css', array(), $theme_version );

	// Theme style.css
	wp_enqueue_style( 'theme-sagicc-academy-style', get_stylesheet_uri(), array( 'theme-sagicc-academy-main' ), $theme_version );

	// GSAP for smooth animations
	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
}
add_action( 'wp_enqueue_scripts', 'theme_sagicc_academy_scripts' );

// Enqueue Google Material Symbols in WordPress Admin Area
add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'google-material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0', array(), null );
} );
