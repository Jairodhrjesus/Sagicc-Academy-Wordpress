<?php
/**
 * Plugin Name:  LearnDash Multilingual Integration
 * Plugin URI:   https://support.learndash.com
 * Description:  Adds Multilingual support with WPML and Polylang plugins.
 * Author:       LearnDash
 * Author URI:   https://support.learndash.com
 * Version:      1.0.0
 * License:      GPL v2 or later
 * Text Domain: ld-multilingual
 * Doman Path: /languages/
 *
 * @package LearnDash
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'LD_MULTILINGUAL_VERSION', '1.0.0' );

if ( ! defined( 'LD_MULTILINGUAL_DIR' ) ) {
	define( 'LD_MULTILINGUAL_DIR', trailingslashit( str_replace( '\\', '/', WP_PLUGIN_DIR ) . '/' . basename( dirname( __FILE__ ) ) ) );
}

if ( ! class_exists( 'LD_Multilingual' ) ) {

	/**
	 * Class to create the instance.
	 */
	class LD_Multilingual {

		/**
		 * String for the field key.
		 *
		 * @var string
		 */
		protected $provider_key = '';

		/**
		 * Array to hold all field type instances.
		 *
		 * @var array
		 */
		protected static $provider_instances = array();

		/**
		 * Public constructor for class
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Hooks served only by the base class.
			add_action( 'learndash_init', array( $this, 'init_providers' ) );

			// Main hooks coming into this based class.
			add_filter( 'learndash_header_data', array( $this, 'learndash_header_data' ), 10, 3 );
			add_filter( 'learndash_permalinks_nested_urls', array( $this, 'learndash_permalinks_nested_urls' ), 10, 3 );
			add_filter( 'learndash_post_type_rewrite_slug', array( $this, 'get_provider_post_type_slug_translation' ), 10, 3 );

			// Provider hooks. These are common to all providers.
			add_filter( 'ld_multilingual_providers_get_current_language_code', array( $this, 'get_current_language_code' ), 10, 1 );
			add_filter( 'ld_multilingual_providers_get_all_language_codes', array( $this, 'get_all_language_codes' ), 10, 1 );
			add_filter( 'ld_multilingual_providers_get_post_type_slug_translation', array( $this, 'get_post_type_slug_translation' ), 10, 3 );
		}

		/**
		 * Called via the LearnDash init action. This function will trigger
		 * an action to initialize any provider instances.
		 */
		final public function init_providers() {
			do_action( 'ld_multilingual_providers_init' );
		}

		/**
		 * Hook into the LearnDash admin 'header_data' filter.
		 *
		 * @since 1.0.0
		 * @param array  $header_data    Header data structure.
		 * @param string $menu_tab_key   Menu tab key.
		 * @param array  $admin_tab_sets Admin Tabs arrays.
		 *
		 * @return array $header_data
		 */
		final public function learndash_header_data( $header_data = array(), $menu_tab_key = '', $admin_tab_sets = array() ) {
			$lang = $this->get_provider_current_language_code();

			if ( ! empty( $lang ) ) {
				$header_data['ajaxurl']       = add_query_arg( 'lang', $lang, $header_data['ajaxurl'] );
				$header_data['adminurl']      = add_query_arg( 'lang', $lang, $header_data['adminurl'] );
				$header_data['quizImportUrl'] = add_query_arg( 'lang', $lang, $header_data['quizImportUrl'] );
				$header_data['postadminurl']  = add_query_arg( 'lang', $lang, $header_data['postadminurl'] );
				$header_data['back_to_url']   = add_query_arg( 'lang', $lang, $header_data['back_to_url'] );
			}

			// Always return $header_data.
			return $header_data;
		}

		/**
		 * Filter the custom rewrite rules added by LearnDash core.
		 *
		 * @since 1.0.0
		 * @param array $ld_rewrite_rules    Array of custom rewrite rules to be added to WordPress.
		 * @param array $ld_rewrite_patterns Array of custom rewrite patterns.
		 * @param array $ld_rewrite_values   Array of rewrite placeholder/value pairs.
		 */
		final public function learndash_permalinks_nested_urls( $ld_rewrite_rules = array(), $ld_rewrite_patterns = array(), $ld_rewrite_values = array() ) {
			if ( ( ! empty( $ld_rewrite_patterns ) ) && ( ! empty( $ld_rewrite_values ) ) ) {
				$language_codes = $this->get_provider_all_language_codes();
				if ( ! empty( $language_codes ) ) {
					foreach ( $language_codes as $language_code ) {
						if ( ( ! empty( $ld_rewrite_patterns ) ) && ( ! empty( $ld_rewrite_values ) ) ) {
							foreach ( $ld_rewrite_patterns as $rewrite_pattern_key => $rewrite_pattern_rule ) {
								foreach ( $ld_rewrite_values as $post_type_name => $ld_rewrite_values_sets ) {
									if ( ! empty( $ld_rewrite_values_sets ) ) {
										foreach ( $ld_rewrite_values_sets as $ld_rewrite_values_set_key => $ld_rewrite_values_set ) {
											if ( ! empty( $ld_rewrite_values_set ) ) {
												if ( ( ! isset( $ld_rewrite_values_set['placeholder'] ) ) || ( empty( $ld_rewrite_values_set['placeholder'] ) ) ) {
													continue;
												}
												if ( ( ! isset( $ld_rewrite_values_set['value'] ) ) || ( empty( $ld_rewrite_values_set['value'] ) ) ) {
													continue;
												}

												if ( 'slug' === $ld_rewrite_values_set_key ) {
													$ld_rewrite_values_set['value'] = $this->get_provider_post_type_slug_translation( $ld_rewrite_values_set['value'], $post_type_name, $language_code );
												}

												$rewrite_pattern_key  = str_replace( $ld_rewrite_values_set['placeholder'], $ld_rewrite_values_set['value'], $rewrite_pattern_key );
												$rewrite_pattern_rule = str_replace( $ld_rewrite_values_set['placeholder'], $ld_rewrite_values_set['value'], $rewrite_pattern_rule );
											}
										}
									}
								}
								if ( ! isset( $ld_rewrite_rules[ $rewrite_pattern_key ] ) ) {
									$ld_rewrite_rules[ $rewrite_pattern_key ] = $rewrite_pattern_rule;
								}
							}
						}
					}
				}
			}

			return $ld_rewrite_rules;
		}

		/**
		 * Utility function to determine the language for the current page.
		 *
		 * @since 1.0.0
		 * @return string language code.
		 */
		final public function get_provider_current_language_code() {
			return apply_filters( 'ld_multilingual_providers_get_current_language_code', '' );
		}

		/**
		 * Utility function to determine all languages.
		 *
		 * @since 1.0.0
		 * @return array Array of installed languages.
		 */
		final public function get_provider_all_language_codes() {
			return apply_filters( 'ld_multilingual_providers_get_all_language_codes', array() );
		}

		/**
		 * Utility function to determine the post type translated slug.
		 *
		 * This is needed when the language add-on (WPML, Polylang, etc.)
		 * translates the front-end URL slug for the post_type.
		 *
		 * @since 1.0.0
		 * @param string $rewrite_slug   The Post Type rewrite slug to translate.
		 * @param string $post_type_name The Post Type name (slug) used in register_post_type().
		 * @param string $language_code  The language used for translation. Optional.
		 */
		final public function get_provider_post_type_slug_translation( $rewrite_slug = '', $post_type_name = '', $language_code = '' ) {
			if ( ( ! empty( $rewrite_slug ) ) && ( ! empty( $post_type_name ) ) ) {
				if ( empty( $language_code ) ) {
					$language_code = $this->get_provider_current_language_code();
				}
				if ( ! empty( $language_code ) ) {
					$rewrite_slug = apply_filters( 'ld_multilingual_providers_get_post_type_slug_translation', $rewrite_slug, $post_type_name, $language_code );
				}
			}
			return $rewrite_slug;
		}

		/**
		 * Add Provider instance by key
		 *
		 * @since 1.0.0
		 * @param string $provider_key Key to unique provider instance.
		 * @return object instance of provider if present.
		 */
		final public static function add_provider_instance( $provider_key = '' ) {
			if ( ! empty( $provider_key ) ) {
				if ( ! isset( self::$provider_instances[ $provider_key ] ) ) {
					$provider_class                            = get_called_class();
					self::$provider_instances[ $provider_key ] = new $provider_class();
				}

				return self::$provider_instances[ $provider_key ];
			}
		}

		/**
		 * Get provider instance by key
		 *
		 * @since 1.0.0
		 * @param string $provider_key Key to unique provider instance.
		 * @return object instance of provider if present.
		 */
		final public static function get_provider_instance( $provider_key = '' ) {
			if ( ! empty( $provider_key ) ) {
				if ( isset( self::$provider_instances[ $provider_key ] ) ) {
					return self::$provider_instances[ $provider_key ];
				}
			}
		}

		/**
		 * Get the current language code for the page.
		 *
		 * Stub function. This function should be implemented in Provider class.
		 *
		 * @since 1.0.0
		 * @param string $lang language code.
		 * @return string $lang
		 */
		public function get_current_language_code( $lang = '' ) {
			return $lang;
		}

		/**
		 * Get All language codes for provider.
		 *
		 * Stub function. This function should be implemented in Provider class.
		 *
		 * @since 1.0.0
		 *
		 * @return array Return array of language codes.
		 */
		public function get_all_language_codes() {
			return array();
		}


		/**
		 * Get the translation for the Post Type slug.
		 *
		 * Stub function. This function should be implemented in Provider class.
		 *
		 * @since 1.0.0
		 * @param string $post_type_slug Slug of Post Type to translate.
		 * @param string $lang           Language to get translation of.
		 * @return string Return the translated post type slug.
		 */
		public function get_post_type_slug_translation( $post_type_slug = '', $lang = '' ) {
			return $post_type_slug;
		}

		// End of functions.
	}

	include_once LD_MULTILINGUAL_DIR . 'providers/ld-multilingual-provider-Polylang.php';
	include_once LD_MULTILINGUAL_DIR . 'providers/ld-multilingual-provider-WPML.php';
	/**
	 * Action hook to let external providers know to load their files.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ld_multilingual_providers_file_load' );

	$ld_multilingual = new LD_Multilingual();
}

require LD_MULTILINGUAL_DIR . 'includes/class-ld-multilingual-dependency-check.php';
LD_Multilingual_Dependency_Check::get_instance()->set_dependencies(
	array(
		'plugins' => array(
			'sfwd-lms/sfwd_lms.php' => array(
				'label'       => '<a href="http://learndash.com">LearnDash LMS</a>',
				'class'       => 'SFWD_LMS',
				'min_version' => '3.1.4',
			),
			'sitepress-multilingual-cms/sitepress.php' => array(
				'label'       => '<a href="http://wpml.org">WPML</a>',
				'min_version' => '4.3.0',
			),
			'polylang/polylang.php' => array(
				'label'       => '<a href="https://wordpress.org/plugins/polylang/">Polylang</a>',
				'min_version' => '2.7.0',
			),
		),
		/*
		'groups' => array(
			array(
				'compare' => 'OR',
				'plugins' => array( 'sitepress-multilingual-cms/sitepress.php', 'polylang/polylang.php' ),
			),
		),
		*/
	)
);
LD_Multilingual_Dependency_Check::get_instance()->set_message(
	esc_html__( 'LearnDash Multilingual requires one or more of the following plugin(s) be active:', 'ld-multilingual' )
);
