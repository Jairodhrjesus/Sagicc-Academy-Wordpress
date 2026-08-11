<?php
/**
 * Sagicc Academy Theme functions and definitions
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Theme Setup & Support
if ( ! function_exists( 'theme_sagicc_academy_setup' ) ) :
	function theme_sagicc_academy_setup() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );
	}
endif;
add_action( 'after_setup_theme', 'theme_sagicc_academy_setup' );

// 2. Enqueue Styles & Scripts (Frontend & Admin)
require_once get_template_directory() . '/inc/enqueue.php';

// 3. Custom Post Types & Taxonomies
require_once get_template_directory() . '/inc/post-types.php';

// 4. Shortcodes Loader
require_once get_template_directory() . '/inc/shortcodes.php';

// 5. Custom Rewrite & Profile Rules
require_once get_template_directory() . '/inc/rewrites.php';

// 6. LearnDash Certificate & PDF Optimizations
require_once get_template_directory() . '/inc/learndash.php';

// 7. Admin UI & Custom Banners
require_once get_template_directory() . '/inc/admin-banner.php';

// 8. Ajax Search Lite Query Optimization
add_filter( 'asl_query_args', function ( $args, $search_id ) {
	$args['post_type']      = array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'video', 'guia' );
	$args['posts_per_page'] = 6;
	$args['post_count']     = 6;
	return $args;
}, 99, 2 );

// 9. Local environment warning suppression
set_error_handler( function ( $errno, $errstr ) {
	if ( ( $errno === E_WARNING || $errno === E_USER_WARNING ) && strpos( $errstr, 'file_exists(): open_basedir restriction in effect' ) !== false ) {
		return true;
	}
	return false;
}, E_WARNING | E_USER_WARNING );
