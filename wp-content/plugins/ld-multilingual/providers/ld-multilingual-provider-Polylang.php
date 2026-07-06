<?php
/**
 * LearnDash LMS Multilingual - Polylang
 *
 * @since 1.0.0
 * @package LearnDash LMS Multilingual
 * @subpackage Polylang
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ( class_exists( 'LD_Multilingual' ) ) && ( ! class_exists( 'LD_Multilingual_Provider_Polylang' ) ) ) {
	/**
	 * Class for handling the LearnDash Multi-Language WPML.
	 */
	class LD_Multilingual_Provider_Polylang extends LD_Multilingual {
		/**
		 * Public constructor for class
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->provider_key = 'polylang';

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
			if ( function_exists( 'pll_current_language' ) ) {
				$lang = pll_current_language( 'slug' );
			}

			return $lang;
		}

		/**
		 * Get All language codes for provider.
		 *
		 * @since 1.0.0
		 *
		 * @return array Return array of language codes.
		 */
		final public function get_all_language_codes() {

			return;
		}
	}

	add_action(
		'ld_multilingual_providers_init',
		function() {
			if ( function_exists( 'pll_current_language' ) ) {
				LD_Multilingual_Provider_WPML::add_provider_instance( 'polylang' );
			}
		}
	);
}
