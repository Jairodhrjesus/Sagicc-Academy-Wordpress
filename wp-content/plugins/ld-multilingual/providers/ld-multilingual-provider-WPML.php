<?php
/**
 * LearnDash LMS Multilingual - WPML
 *
 * @since 1.0.0
 * @package LearnDash LMS Multilingual
 * @subpackage WPML
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ( class_exists( 'LD_Multilingual' ) ) && ( ! class_exists( 'LD_Multilingual_Provider_WPML' ) ) ) {
	/**
	 * Class for handling the LearnDash Multi-Language WPML.
	 */
	class LD_Multilingual_Provider_WPML extends LD_Multilingual {
		/**
		 * Public constructor for class
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->provider_key = 'wpml';

			parent::__construct();
		}

		/**
		 * Get the current language code for the viewed page.
		 *
		 * @since 1.0.0
		 * @param string $lang language code.
		 * @return string $lang
		 */
		final public function get_current_language_code( $lang = '' ) {
			return apply_filters( 'wpml_current_language', $lang );
		}

		/**
		 * Get All language codes for provider.
		 *
		 * @since 1.0.0
		 *
		 * @return array Return array of language codes.
		 */
		final public function get_all_language_codes() {
			$languages_codes = array();
			$languages       = apply_filters( 'wpml_active_languages', null, '' );

			if ( ! empty( $languages ) ) {
				$languages_codes = wp_list_pluck( $languages, 'language_code' );
			}
			return $languages_codes;
		}

		/**
		 * Get the translated post type slug.
		 *
		 * @since 1.0.0
		 * @param string $rewrite_slug   The Post Type slug to translate.
		 * @param string $post_type_name The Post Type name.
		 * @param string $lang           The language used for translation. Optional.
		 *
		 * @return string The translated post type rewrite slug.
		 */
		final public function get_post_type_slug_translation( $rewrite_slug = '', $post_type_name = '', $lang = '' ) {
			if ( ( ! empty( $rewrite_slug ) ) && ( ! empty( $post_type_name ) ) ) {
				$rewrite_slug = apply_filters( 'wpml_get_translated_slug', $rewrite_slug, $post_type_name, $lang );
			}

			return $rewrite_slug;
		}

		// End of functions.
	}

	add_action(
		'ld_multilingual_providers_init',
		function() {
			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				LD_Multilingual_Provider_WPML::add_provider_instance( 'wpml' );
			}
		}
	);
}
