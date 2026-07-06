<?php
/**
 * Set up Dependency Check
 *
 * @package LearnDash LMS Multilingual
 * @since 1.0.0
 */

if ( ! class_exists( 'LD_Multilingual_Dependency_Check' ) ) {

	final class LD_Multilingual_Dependency_Check {

		/**
		 * Static instance.
		 *
		 * @var object $instance.
		 */
		private static $instance;

		/**
		 * The displayed message shown to the user on admin pages.
		 *
		 * @var string $admin_notice_message.
		 */
		private $admin_notice_message = '';

		/**
		 * The array of dependencies to check Should be key => label paird. The label can be anything to display.
		 *
		 * @var array $dependencies.
		 */
		private $dependencies = array();

		/**
		 * Array to hold the inactive plugins. This is populated during the
		 * admin_init action via the function call to check_plugin_dependency()
		 *
		 * @var array $plugins_inactive.
		 */
		private $plugins_inactive = array();

		/**
		 * LearnDash_ProPanel constructor.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 1 );
		}

		/**
		 * Get or create instance of current class.
		 */
		public static function get_instance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		/**
		 * 
		 */
		public function get_dependency_inactive_items() {
			$inactive_plugins = array();
			if ( ( isset( $this->dependencies['plugins'] ) ) && ( ! empty( $this->dependencies['plugins'] ) ) ) {
				foreach ( $this->dependencies['plugins'] as $plugin_key => $plugin_data ) {
					if ( ( isset( $plugin_data['active'] ) ) && ( false === $plugin_data['active'] ) ) {
						$inactive_plugins[ $plugin_key ] = $plugin_data;
					}
				}
			}

			/*
			if ( ( isset( $this->dependencies['groups'] ) ) && ( ! empty( $this->dependencies['groups'] ) ) ) {
				foreach ( $this->dependencies['groups'] as $group ) {
					if ( ( ! isset( $group['compare'] ) ) || ( ! in_array( $group['compare'], array( 'OR', 'AND' ) ) ) ) {
						$group['compare'] = 'OR';
					}

					if ( ( ! isset( $group['items'] ) ) && ( empty( $group['items'] ) ) ) {
						continue;
					}

					foreach ( $group['items'] as $group_item_key ) {
						if ( ( isset( $this->dependencies['plugins'][ $group_item_key ] ) ) && ( true === $this->dependencies['plugins'][ $group_item_key ]['active'] ) ) {

						}
					}
				}
			}
			*/

			return $inactive_plugins;
		}

		/**
		 * Check Dependency Results.
		 *
		 * Checks if dependency requirements have been met.
		 *
		 * @return true is all clear. false if missing plugins.
		 */
		public function check_dependency_results() {
			if ( empty( $this->get_dependency_inactive_items() ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Callback function for the admin_init action
		 */
		public function plugins_loaded() {
			$this->check_plugin_dependency();
		}

		/**
		 * Function called during the admin_init process to check if required plugins
		 * are present and active. Handles regular and Multisite checks.
		 *
		 * @param boolean $set_admin_notice True to set admin notice.
		 */
		public function check_plugin_dependency( $set_admin_notice = true ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( ( isset( $this->dependencies['plugins'] ) ) && ( ! empty( $this->dependencies['plugins'] ) ) ) {
				if ( ! function_exists( 'is_plugin_active' ) ) {
					include_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				foreach ( $this->dependencies['plugins'] as $plugin_key => &$plugin_data ) {
					$plugin_data['active'] = false;
					if ( is_plugin_active( $plugin_key ) ) {
						$plugin_data['active'] = true;
					} elseif ( ( is_multisite() ) && ( is_plugin_active_for_network( $plugin_key ) ) ) {
						$plugin_data['active'] = true;
					}

					if ( ( false === $plugin_data['active'] ) && ( isset( $plugin_data['class'] ) ) && ( ! empty( $plugin_data['class'] ) ) ) {
						if ( ! class_exists( $plugin_data['class'] ) ) {
							$plugin_data['active'] = true;
						}
					}

					if ( ( true === $plugin_data['active'] ) && ( isset( $plugin_data['min_version'] ) ) && ( ! empty( $plugin_data['min_version'] ) ) ) {
						if ( ( 'sfwd-lms/sfwd_lms.php' === $plugin_key ) && ( defined( 'LEARNDASH_VERSION' ) ) ) {
							// Special logic for LearnDash since it can be installed in any directory.
							if ( version_compare( LEARNDASH_VERSION, $plugin_data['min_version'], '<' ) ) {
								$plugin_data['active'] = false;
							}
						} else {
							if ( file_exists( trailingslashit( str_replace( '\\', '/', WP_PLUGIN_DIR ) ) . $plugin_key ) ) {
								$plugin_header = get_plugin_data( trailingslashit( str_replace( '\\', '/', WP_PLUGIN_DIR ) ) . $plugin_key );
								if ( version_compare( $plugin_header['Version'], $plugin_data['min_version'], '<' ) ) {
									$plugin_data['active'] = false;
								}
							}
						}
					}
				}

				if ( ( true === $this->dependencies['plugins']['sitepress-multilingual-cms/sitepress.php']['active'] ) || ( true === $this->dependencies['plugins']['polylang/polylang.php']['active'] ) ) {
					$this->dependencies['plugins']['sitepress-multilingual-cms/sitepress.php']['active'] = true;
					$this->dependencies['plugins']['polylang/polylang.php']['active'] = true;
				}

				if ( ( ! empty( $this->get_dependency_inactive_items() ) ) && ( $set_admin_notice ) ) {
					add_action( 'admin_notices', array( $this, 'notify_required' ) );
				}
			}

			return $this->plugins_inactive;
		}

		/**
		 * Function to set custom admin motice message
		 *
		 * @since 1.0.0
		 * @param string $message Message.
		 */
		public function set_message( $message = '' ) {
			if ( ! empty( $message ) ) {
				$this->admin_notice_message = $message;
			}
		}

		/**
		 * Set plugin required dependencies.
		 *
		 * @since 1.0.0
		 * @param array $plugins Array of of plugins to check.
		 */
		public function set_dependencies( $dependencies = array() ) {
			$this->dependencies = $dependencies;
		}

		/**
		 * Notify user that LearnDash is required.
		 */
		public function notify_required() {
			$inactive_plugins = $this->get_dependency_inactive_items();
			if ( ( ! empty( $this->admin_notice_message ) ) && ( ! empty( $inactive_plugins ) ) ) {

				$plugins_list_str = '';
				foreach ( $inactive_plugins as $plugin ) {
					if ( ! empty( $plugins_list_str ) ) {
						$plugins_list_str .= '<br />';
					}
					$plugins_list_str .= $plugin['label'];

					if ( ( isset( $plugin['min_version'] ) ) && ( ! empty( $plugin['min_version'] ) ) ) {
						$plugins_list_str .= ' v' . $plugin['min_version'];
					}
				}
				if ( ! empty( $plugins_list_str ) ) {
					$admin_notice_message = sprintf( $this->admin_notice_message . '<br />%s', $plugins_list_str );
					if ( ! empty( $admin_notice_message ) ) {
						?>
						<div class="notice notice-error ld-notice-error is-dismissible">
							<p><?php echo wp_kses_post( $admin_notice_message ); ?></p>
						</div>
						<?php
					}
				}
			}
		}
	}
}
