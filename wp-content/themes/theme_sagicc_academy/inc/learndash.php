<?php
/**
 * LearnDash Optimizations & Filters
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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



