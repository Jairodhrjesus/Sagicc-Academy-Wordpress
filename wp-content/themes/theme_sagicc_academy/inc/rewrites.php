<?php
/**
 * Custom Rewrite Rules & Profile Canonical Filters
 *
 * @package theme_sagicc_academy
 */

if (!defined('ABSPATH')) {
	exit;
}

// Register rewrite rules for profile URLs and CPT language archives (guia, video)
add_action('init', function () {
	add_rewrite_rule('^profile/([^/]+)/?$', 'index.php?pagename=profile&profile_user=$matches[1]', 'top');
	add_rewrite_rule('^(es|en)/profile/([^/]+)/?$', 'index.php?pagename=profile&profile_user=$matches[2]', 'top');

	// Guías CPT language archive rules
	add_rewrite_rule('^(es|en)/guia/?$', 'index.php?post_type=guia&lang=$matches[1]', 'top');
	add_rewrite_rule('^(es|en)/guia/page/([0-9]+)/?$', 'index.php?post_type=guia&paged=$matches[2]&lang=$matches[1]', 'top');

	// Videos CPT language archive rules
	add_rewrite_rule('^(es|en)/video/?$', 'index.php?post_type=video&lang=$matches[1]', 'top');
	add_rewrite_rule('^(es|en)/video/page/([0-9]+)/?$', 'index.php?post_type=video&paged=$matches[2]&lang=$matches[1]', 'top');
});

// Ensure post_type_archive_link outputs language prefix when in English
add_filter('post_type_archive_link', function ($link, $post_type) {
	if (in_array($post_type, array('guia', 'video', 'sfwd-courses'))) {
		$lang = function_exists('pll_current_language') ? pll_current_language() : (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es');
		if ($lang === 'en' && strpos($link, '/en/') === false) {
			$home_url = home_url('/');
			$en_home_url = home_url('/en/');
			return str_replace($home_url, $en_home_url, $link);
		}
	}
	return $link;
}, 10, 2);

add_filter('query_vars', function ($vars) {
	$vars[] = 'profile_user';
	return $vars;
});

// Prevent canonical redirect loops on custom profile URLs and CPT archives
add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
	if (get_query_var('profile_user') || get_query_var('pagename') === 'profile' || (is_page() && get_post_field('post_name', get_queried_object_id()) === 'profile') || strpos($requested_url, '/profile/') !== false || strpos($requested_url, '/guia/') !== false || strpos($requested_url, '/video/') !== false) {
		return false;
	}
	return $redirect_url;
}, 10, 2);

add_filter('pll_check_canonical_url', function ($redirect_url) {
	if (get_query_var('profile_user') || get_query_var('pagename') === 'profile' || (is_page() && get_post_field('post_name', get_queried_object_id()) === 'profile') || is_post_type_archive('guia') || is_post_type_archive('video')) {
		return false;
	}
	return $redirect_url;
}, 10, 2);
