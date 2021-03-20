<?php
/**
 * Plugin Name: Jupiter Core
 * Plugin URI: https://artbees.net
 * Description: Jupiter Core
 * Version: 1.0.3
 * Author: Artbees
 * Author URI: https://artbees.net
 * Text Domain: jupiter-core
 * License: GPL2
 *
 * @package Jupiter_Core
 */

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'Jupiter_Core' ) ) {

	/**
	 * Jupiter Core class.
	 *
	 * @since 1.0.0
	 */
	class Jupiter_Core {

		/**
		 * Jupiter Core instance.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var Jupiter_Core
		 */
		private static $instance;

		/**
		 * The plugin version number.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $version;

		/**
		 * The plugin basename.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_basename;

		/**
		 * The plugin name.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_name;

		/**
		 * The plugin directory.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_dir;

		/**
		 * The plugin URL.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_url;

		/**
		 * The plugin assets URL.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var string
		 */
		public static $plugin_assets_url;

		/**
		 * Returns Jupiter_Core instance.
		 *
		 * @since 1.0.0
		 *
		 * @return Jupiter_Core
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->define_constants();
			$this->add_actions();
		}

		/**
		 * Defines constants used by the plugin.
		 *
		 * @since 1.0.0
		 */
		protected function define_constants() {
			$plugin_data = get_file_data( __FILE__, array( 'Plugin Name', 'Version' ), 'jupiter-core' );

			self::$plugin_basename   = plugin_basename( __FILE__ );
			self::$plugin_name       = array_shift( $plugin_data );
			self::$version           = array_shift( $plugin_data );
			self::$plugin_dir        = trailingslashit( plugin_dir_path( __FILE__ ) );
			self::$plugin_url        = trailingslashit( plugin_dir_url( __FILE__ ) );
			self::$plugin_assets_url = trailingslashit( self::$plugin_url . 'assets' );
		}

		/**
		 * Adds required action hooks.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function add_actions() {
			add_action( 'plugins_loaded', [ $this, 'init' ] );
			add_filter( 'script_loader_tag', [ $this, 'modify_scripts' ], 10, 3 );
		}

		/**
		 * Initializes the plugin.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			load_plugin_textdomain( 'jupiter-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			add_action( 'mk_init_before', function() {
				$this->load_files( [
					'helpers/helpers',
					'helpers/class-logger',
					'helpers/db-management',
					'importer/functions',
				] );

				add_action( 'admin_bar_menu', [ $this, 'admin_bar' ], 100 );
			} );

			add_action( 'mk_init', function() {
				$this->load_files( [
					'post-types/functions',
					'theme-options/functions',
					'widgets/functions',
				] );
			} );

			/**
			 * Fires after all files have been loaded.
			 *
			 * @since 1.0.0
			 *
			 * @param Jupiter_Core
			 */
			do_action( 'jupiter_core_init', $this );
		}

		/**
		 * Returns the version number of the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function version() {
			return self::$version;
		}

		/**
		 * Returns the plugin basename.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_basename() {
			return self::$plugin_basename;
		}

		/**
		 * Returns the plugin name.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_name() {
			return self::$plugin_name;
		}

		/**
		 * Returns the plugin directory.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_dir() {
			return self::$plugin_dir;
		}

		/**
		 * Returns the plugin URL.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_url() {
			return self::$plugin_url;
		}

		/**
		 * Loads specified PHP files from the plugin includes directory.
		 *
		 * @since 1.0.0
		 *
		 * @param array $file_names The names of the files to be loaded in the includes directory.
		 */
		public function load_files( $file_names = array() ) {
			foreach ( $file_names as $file_name ) {
				$path = $this->plugin_dir() . 'includes/' . $file_name . '.php';

				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}

		/**
		 * Add additional admin bar links.
		 *
		 * @since 1.0.0
		 */
		function admin_bar() {
			global $wp_admin_bar;

			if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
				return;
			}

			$wp_admin_bar->add_menu(
				array(
					'id'    => 'theme_options',
					'title' => __( 'Theme Options', 'jupiter-core' ),
					'href'  => admin_url( 'admin.php?page=theme_options' ),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'title' => __( 'Clear Theme Cache', 'jupiter-core' ),
					'id'    => 'clean_dynamic_styles',
					'href'  => wp_nonce_url( admin_url( 'admin-post.php?action=mk_purge_cache' ), 'theme_purge_cache' ),
				)
			);
		}

		/**
		 * Modify scripts.
		 *
		 * @since 1.0.0
		 */
		public function modify_scripts( $tag, $handle, $src ) {

			// The handles.
			$scripts = [ 'mk-webfontloader' ];

			// Add data-no-optimize for Autoptimize and data-no-minify for WP Rocket.
			if ( in_array( $handle, $scripts ) ) {
				return str_replace( "src=", "data-noptimize='' data-no-minify='' src=", $tag );
			}

			return $tag;
		}
	}
}

/**
 * Returns the Jupiter Core application instance.
 *
 * @since 1.0.0
 *
 * @return Jupiter_Core
 */
function jupiter_core() {
	return Jupiter_Core::get_instance();
}

/**
 * Initializes the Jupiter Core application.
 *
 * @since 1.0.0
 */
jupiter_core();
