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

// Enqueue styles and scripts
require get_template_directory() . '/inc/enqueue.php';

// Register custom shortcodes (sidebar, profile, login, home header)
require get_template_directory() . '/inc/shortcodes.php';

// Register Custom Post Types (Videos)
require get_template_directory() . '/inc/post-types.php';

// Restrict and optimize Ajax Search Lite for maximum response speed
add_filter( 'asl_query_args', function ( $args, $search_id ) {
	$args['post_type'] = array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'video', 'guia' );
	$args['posts_per_page'] = 6;
	$args['post_count'] = 6;
	return $args;
}, 99, 2 );

// Suppress open_basedir restriction warnings triggered by third-party plugins in local environment
set_error_handler( function ( $errno, $errstr ) {
	if ( ( $errno === E_WARNING || $errno === E_USER_WARNING ) && strpos( $errstr, 'file_exists(): open_basedir restriction in effect' ) !== false ) {
		return true; // Suppress warning gracefully
	}
	return false; // Pass all other errors to standard handler
}, E_WARNING | E_USER_WARNING );

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

// Optimización para generación de certificados PDF en LearnDash desde el tema
add_filter( 'learndash_certificate_builder_mpdf', function ( $mpdf ) {
	@ini_set( 'memory_limit', '2048M' );
	return $mpdf;
} );

add_filter( 'learndash_certificate_builder_block_fallback', function ( $output ) {
	if ( empty( $output ) || ! is_string( $output ) ) {
		return $output;
	}
	return preg_replace_callback( '/class=(["\'])([^"\']+)\1/i', function ( $matches ) {
		$classes = preg_split( '/\s+/', trim( $matches[2] ) );
		if ( count( $classes ) > 5 ) {
			$classes = array_slice( $classes, 0, 5 );
		}
		return 'class=' . $matches[1] . implode( ' ', $classes ) . $matches[1];
	}, $output );
} );




