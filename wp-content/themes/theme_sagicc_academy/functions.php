<?php
/**
 * Sagicc Academy Theme functions and definitions
 *
 * @package theme_sagicc_academy
 */

if (!function_exists('theme_sagicc_academy_setup')):
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function theme_sagicc_academy_setup()
	{
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support('post-thumbnails');

		// Editor styles support.
		add_theme_support('editor-styles');
	}
endif;
add_action('after_setup_theme', 'theme_sagicc_academy_setup');

// Enqueue styles, scripts and inline Tailwind settings
require get_template_directory() . '/inc/enqueue.php';

// Register custom shortcodes (sidebar, profile, login, home header)
require get_template_directory() . '/inc/shortcodes.php';

// Register Custom Post Types (Videos)
require get_template_directory() . '/inc/post-types.php';




