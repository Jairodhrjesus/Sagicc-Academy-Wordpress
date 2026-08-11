<?php
/**
 * Register custom shortcodes for dynamic layout components
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cargar módulos individuales de shortcodes
require_once get_template_directory() . '/inc/shortcodes/templates.php';
require_once get_template_directory() . '/inc/shortcodes/headers.php';
require_once get_template_directory() . '/inc/shortcodes/courses.php';
require_once get_template_directory() . '/inc/shortcodes/videos.php';
require_once get_template_directory() . '/inc/shortcodes/guides.php';
require_once get_template_directory() . '/inc/shortcodes/certificates.php';
