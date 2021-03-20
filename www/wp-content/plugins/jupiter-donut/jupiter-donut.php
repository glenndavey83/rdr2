<?php
/**
 * Plugin Name: Jupiter Donut
 * Plugin URI: https://artbees.net
 * Description: A WPBakery page builder addon
 * Version: 1.2.0
 * Author: Artbees
 * Author URI: https://artbees.net
 * Text Domain: jupiter-donut
 * License: GPL2
 *
 * @package Jupiter_Donut
 */

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'Jupiter_Donut' ) ) {

	/**
	 * Artbees Donut class.
	 *
	 * @since 1.0.0
	 */
	class Jupiter_Donut {

		/**
		 * Artbees Donut instance.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var Jupiter_Donut
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
		 * Returns Jupiter_Donut instance.
		 *
		 * @since 1.0.0
		 *
		 * @return Jupiter_Donut
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

			if ( ! $this->check_requirements() ) {
				return false;
			}

			$this->define_constants();
			$this->add_actions();
		}

		/**
		 * Defines constants used by the plugin.
		 *
		 * @since 1.0.0
		 */
		protected function define_constants() {
			$plugin_data = get_file_data( __FILE__, array( 'Plugin Name', 'Version' ), 'jupiterx-core' );

			self::$plugin_basename = plugin_basename( __FILE__ );
			self::$plugin_name     = array_shift( $plugin_data );
			self::$version         = array_shift( $plugin_data );
			self::$plugin_dir      = trailingslashit( plugin_dir_path( __FILE__ ) );
			self::$plugin_url      = trailingslashit( plugin_dir_url( __FILE__ ) );

			define( 'JUPITER_DONUT_ASSETS_DIR', self::$plugin_dir . 'assets' );
			define( 'JUPITER_DONUT_ASSETS_URL', self::$plugin_url . 'assets' );
			define( 'JUPITER_DONUT_INCLUDES_DIR', self::$plugin_dir . 'includes' );
			define( 'JUPITER_DONUT_INCLUDES_URL', self::$plugin_url . 'includes' );
		}

		/**
		 * Adds required action hooks.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function add_actions() {
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 19 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		}

		/**
		 * Initializes the plugin.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			load_plugin_textdomain( 'jupiter-donut', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			$this->load_files( [
				'utilities/shared-functions',
				'utilities/functions',
				'helpers/global', // At the end, combine with following then move to shared-functions.
				'helpers/general-functions',
				'helpers/class-mk-fs',
				'helpers/svg-icons',
				'helpers/template-part-helpers',
				'helpers/schema-markup',
				'helpers/bfi_thumb',
				'helpers/image-resize',
				'helpers/minify/src/Minifier',
				'helpers/minify/src/SimpleCssMinifier',
				'helpers/dynamic-styles',
				'helpers/phpQuery',
				'helpers/wp_query',
				'helpers/load-more',
				'helpers/send-email',
				'helpers/captcha',
				'helpers/MailChimpApi',
				'helpers/subscribe-mailchimp',
				'wpbakery/class',
				'wpbakery/functions',
				'wpbakery/shortcodes/mk_portfolio/ajax',
				'views/icon-selector',
			] );

			add_action( 'init', function() {
				$this->load_files([
					'/meta-boxes/class',
					'/post-types/custom_post_types.helpers.class',
					'/post-types/register_post_type.class',
					'/post-types/register_taxonomy.class',
					'/post-types/config',
				]);
			} );

			/**
			 * Fires after all files have been loaded.
			 *
			 * @since 1.0.0
			 *
			 * @param Jupiter_Donut
			 */
			do_action( 'jupiter_donut_init', $this );
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {
			$css_deps           = false;
			$js_shortcodes_deps = [ 'jquery' ];

			// Core styles.
			if ( ! jupiter_donut_is_jupiter() ) {
				wp_enqueue_style(
					'jupiter-donut',
					$this->plugin_url() . 'assets/css/styles' . $this->suffix() . '.css',
					$css_deps,
					$this->version()
				);
			}

			// Shortcodes styles.
			wp_enqueue_style(
				'jupiter-donut-shortcodes',
				$this->plugin_url() . 'assets/css/shortcodes-styles' . $this->suffix() . '.css',
				$css_deps,
				$this->version()
			);

			wp_register_script(
				'jquery-raphael',
				JUPITER_DONUT_ASSETS_URL . '/lib/js/jquery.raphael.js',
				[ 'jquery' ],
				$this->version(),
				false
			);

			wp_register_script(
				'api-vimeo',
				'http' . ( ( is_ssl() ) ? 's' : '' ) . '://f.vimeocdn.com/js/froogaloop2.min.js',
				[],
				false,
				false
			);

			wp_register_script(
				'api-youtube',
				'http' . ( ( is_ssl() ) ? 's' : '' ) . '://www.youtube.com/player_api',
				[],
				false,
				false
			);

			// Core scripts.
			if ( ! jupiter_donut_is_jupiter() ) {
				wp_enqueue_script(
					'jupiter-donut',
					$this->plugin_url() . 'assets/js/scripts' . $this->suffix() . '.js',
					[ 'jquery' ],
					$this->version(),
					true
				);
			}

			// Shortcodes scripts.
			if ( wp_script_is( 'theme-scripts' ) ) {
				$js_shortcodes_deps[] = 'theme-scripts';
			}

			if ( wp_script_is( 'core-scripts' ) ) {
				$js_shortcodes_deps[] = 'core-scripts';
			}

			if ( wp_script_is( 'components-full' ) ) {
				$js_shortcodes_deps[] = 'components-full';
			}

			wp_enqueue_script(
				'jupiter-donut-shortcodes',
				$this->plugin_url() . 'assets/js/shortcodes-scripts' . $this->suffix() . '.js',
				$js_shortcodes_deps,
				$this->version(),
				true
			);

			// Localize.
			$localize_handle = 'jupiter-donut';

			if ( jupiter_donut_is_jupiter() ) {
				$localize_handle = 'jupiter-donut-shortcodes';
			}

			wp_localize_script( $localize_handle, 'jupiterDonutVars', [
				'themeDir'  => get_template_directory_uri(),
				'assetsUrl' => JUPITER_DONUT_ASSETS_URL,
				'gridWidth' => jupiter_donut_get_option( 'grid_width' ),
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'jupiter-donut' ),
			] );
		}

		/**
		 * Enqueue Admin Scripts.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_admin_scripts( $hook ) {

			if ( jupiter_donut_is_jupiter()  ) {
				return;
			}

			if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
				return;
			}

			wp_register_style(
				'jupiter-donut-alpha-color-picker',
				JUPITER_DONUT_ASSETS_URL . '/lib/css/alpha-color-picker.css',
				false,
				'1.0.0'
			);

			wp_register_script(
				'jupiter-donut-alpha-color-picker',
				JUPITER_DONUT_ASSETS_URL . '/lib/js/alpha-color-picker.js',
				[ 'jquery' ],
				'1.0.0',
				true
			);

			wp_register_style(
				'jupiter-donut-select2',
				JUPITER_DONUT_ASSETS_URL . '/lib/css/select2.css',
				false,
				'4.0.0'
			);

			wp_register_script(
				'jupiter-donut-select2',
				JUPITER_DONUT_ASSETS_URL . '/lib/js/select2.js',
				[ 'jquery' ],
				'4.0.0',
				true
			);

			wp_register_script(
				'jupiter-donut-localforage',
				JUPITER_DONUT_ASSETS_URL . '/lib/js/localforage.js',
				[],
				'1.4.3',
				true
			);

			wp_enqueue_style(
				'jupiter-donut-admin',
				JUPITER_DONUT_ASSETS_URL . '/css/admin-styles' . $this->suffix() . '.css',
				[ 'jupiter-donut-select2', 'jupiter-donut-alpha-color-picker' ],
				$this->version()
			);

			wp_enqueue_script(
				'jupiter-donut-admin',
				JUPITER_DONUT_ASSETS_URL . '/js/admin-scripts' . $this->suffix() . '.js',
				[ 'jquery', 'jupiter-donut-select2', 'wp-color-picker', 'jupiter-donut-alpha-color-picker', 'jupiter-donut-localforage' ],
				$this->version(),
				true
			);

			wp_localize_script( 'jupiter-donut-admin', 'icon_selector_locolized_data', [
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'select_icon_string' => __( 'Select Icon', 'jupiter-donut' ),
				'replace_icon_string' => __( 'Replace Icon', 'jupiter-donut' ),
			] );
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
		 * Loads all PHP files in a given directory.
		 *
		 * @since 1.0.0
		 *
		 * @param string $directory_name The directory name to load the files.
		 */
		public function load_directory( $directory_name ) {
			$path       = trailingslashit( $this->plugin_dir() . 'includes/' . $directory_name );
			$file_names = glob( $path . '*.php' );

			foreach ( $file_names as $filename ) {
				if ( file_exists( $filename ) ) {
					require_once $filename;
				}
			}
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
		 * Returns .min suffix.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function suffix() {
			return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		}

		/**
		 * Checks requirements.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function check_requirements() {

			// Required plugin.
			if ( ! class_exists( 'Vc_Manager' ) ) {
				return false;
			}

			// Compatible themes.
			$current_theme = $this->get_current_theme();
			$text_domain   = $current_theme->get( 'TextDomain' ); // Safe since it's unique and won't change.

			if ( 'mk_framework' === $text_domain && version_compare( $current_theme->get( 'Version' ), '6.3.0', '>=' ) ) {
				return true;
			}

			if ( 'jupiterx' === $text_domain ) {
				return true;
			}

			return false;
		}

		/**
		 * Gets current theme. It returns parent if child theme is active.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_current_theme() {
			return wp_get_theme( get_template() );
		}
	}
}

/**
 * Returns the Artbees Donut application instance.
 *
 * @since 1.0.0
 *
 * @return Jupiter_Donut
 */
function jupiter_donut() {
	return Jupiter_Donut::get_instance();
}

/**
 * Initializes the Artbees Donut application.
 *
 * @since 1.0.0
 */
jupiter_donut();
