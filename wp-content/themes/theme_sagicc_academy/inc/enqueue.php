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
	// Google Fonts: Inter and Outfit
	wp_enqueue_style( 'theme-sagicc-academy-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Outfit:wght@400;600;800;900&display=swap', array(), null );

	// FontAwesome for Icons
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

	// Pure CSS Main Stylesheet
	wp_enqueue_style( 'theme-sagicc-academy-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );

	// Theme style.css
	wp_enqueue_style( 'theme-sagicc-academy-style', get_stylesheet_uri(), array( 'theme-sagicc-academy-main' ), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'theme_sagicc_academy_scripts' );
