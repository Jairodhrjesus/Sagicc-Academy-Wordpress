<?php
/**
 * Theme functions and definitions.
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles' );


// Funciones para auto enrolment
require_once get_stylesheet_directory() . '/inc/auto-enrollment.php';

// Funciones específicas para LearnDash
require_once get_stylesheet_directory() . '/inc/learndash-helpers.php';

// Ultimate Member - Helpers
require_once get_stylesheet_directory() . '/inc/UltimateMember-helpers.php';

// One User Avatar - Helpers
require_once get_stylesheet_directory() . '/inc/OneUserAvatar-helpers.php';

// Author Shortcode - Helper
require_once get_stylesheet_directory() . '/inc/Author-Shortcode.php';

// Avatar en el menú (Mi perfil)
require_once get_stylesheet_directory() . '/inc/menu-avatar.php';

// Shortcodes de perfil y autores
require_once get_stylesheet_directory() . '/inc/profile-shortcodes.php';

// Campo biográfico personalizado (Ultimate Member)
require_once get_stylesheet_directory() . '/inc/um-bio-field.php';

// Filtros LearnDash por rol y cursos
require_once get_stylesheet_directory() . '/inc/learndash-filters.php';

// Shortcode mejorado para subir avatar
require_once get_stylesheet_directory() . '/inc/avatar-upload-shortcode.php';
