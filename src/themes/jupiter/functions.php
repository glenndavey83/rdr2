<?php
$theme = new Theme( true );
$theme->init(
	array(
		'theme_name' => 'Jupiter',
		'theme_slug' => 'JP',
	)
);

if ( ! isset( $content_width ) ) {
	$content_width = 1140;
}

class Theme {

	public function __construct( $check = false ) {
		if ( $check ) {
			add_action( 'wp', [ $this, 'check_theme_requirements' ] );
			add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		}
	}

	public function init( $options ) {
		/**
		 * Fires before all files have been loaded.
		 *
		 * @since 6.5.0
		 */
		do_action( 'mk_init_before' );

		$this->constants( $options );
		$this->backward_compatibility();
		$this->helpers();
		$this->functions();
		$this->menu_walkers();
		$this->admin();
		$this->theme_activated();

		/**
		 * Fires after all files have been loaded.
		 *
		 * @since 6.5.0
		 */
		do_action( 'mk_init' );

		add_action(
			'admin_menu', array(
				&$this,
				'admin_menus',
			)
		);

		add_action(
			'init', array(
				&$this,
				'language',
			)
		);

		add_action(
			'after_setup_theme', array(
				&$this,
				'supports',
			)
		);

		add_action(
			'widgets_init', array(
				&$this,
				'widgets',
			)
		);

		// Load RTL when Jupiter child theme is active.
		add_action( 'wp_print_styles', array( &$this, 'load_rtl_in_child' ) );

		add_filter(
			'http_request_timeout', function ( $timeout ) {
				$timeout = 60;
				return $timeout;
			}
		);

		$this->theme_options();
		$this->customizer();
		$this->tour();
		include_once THEME_DIR . '/header-builder/class-mkhb-main.php';
	}

	/**
	 * Define constants
	 *
	 * @param  array $options Theme options.
	 * @return void
	 */
	public function constants( $options ) {

		$mk_parent_theme = get_file_data(
			get_template_directory() . '/style.css',
			array( 'Asset Version' ),
			get_template()
		);

		define( 'NEW_UI_LIBRARY', false );
		define( 'NEW_CUSTOM_ICON', true );
		define( 'V2ARTBEESAPI', 'http://artbees.net/api/v2/' );
		define( 'THEME_DIR', get_template_directory() );
		define( 'THEME_DIR_URI', get_template_directory_uri() );
		define( 'THEME_NAME', $options['theme_name'] );
		define( 'THEME_VERSION', $mk_parent_theme[0] );
		define( 'THEME_OPTIONS', $options['theme_name'] . '_options' . $this->lang() );
		define( 'THEME_OPTIONS_BUILD', $options['theme_name'] . '_options_build' . $this->lang() );
		define( 'IMAGE_SIZE_OPTION', THEME_NAME . '_image_sizes' );
		define( 'THEME_SLUG', $options['theme_slug'] );
		define( 'THEME_STYLES_SUFFIX', '/assets/stylesheet' );
		define( 'THEME_STYLES', THEME_DIR_URI . THEME_STYLES_SUFFIX );
		define( 'THEME_STYLES_DIR', THEME_DIR . THEME_STYLES_SUFFIX );
		define( 'THEME_JS', THEME_DIR_URI . '/assets/js' );
		define( 'THEME_JS_DIR', THEME_DIR . '/assets/js' );
		define( 'THEME_IMAGES', THEME_DIR_URI . '/assets/images' );
		define( 'FONTFACE_DIR', THEME_DIR . '/fontface' );
		define( 'FONTFACE_URI', THEME_DIR_URI . '/fontface' );
		define( 'THEME_FRAMEWORK', THEME_DIR . '/framework' );
		define( 'THEME_COMPONENTS', THEME_DIR_URI . '/components' );
		define( 'THEME_ACTIONS', THEME_FRAMEWORK . '/actions' );
		define( 'THEME_INCLUDES', THEME_FRAMEWORK . '/includes' );
		define( 'THEME_INCLUDES_URI', THEME_DIR_URI . '/framework/includes' );
		define( 'THEME_WIDGETS', THEME_FRAMEWORK . '/widgets' );
		define( 'THEME_HELPERS', THEME_FRAMEWORK . '/helpers' );
		define( 'THEME_FUNCTIONS', THEME_FRAMEWORK . '/functions' );
		define( 'THEME_PLUGIN_INTEGRATIONS', THEME_FRAMEWORK . '/plugin-integrations' );

		define( 'THEME_ADMIN', THEME_FRAMEWORK . '/admin' );
		define( 'THEME_FIELDS', THEME_ADMIN . '/theme-options/builder/fields' );
		define( 'THEME_CONTROL_PANEL', THEME_ADMIN . '/control-panel' );
		define( 'THEME_CONTROL_PANEL_ASSETS', THEME_DIR_URI . '/framework/admin/control-panel/assets' );
		define( 'THEME_CONTROL_PANEL_ASSETS_DIR', THEME_DIR . '/framework/admin/control-panel/assets' );
		define( 'THEME_GENERATORS', THEME_ADMIN . '/generators' );
		define( 'THEME_ADMIN_URI', THEME_DIR_URI . '/framework/admin' );
		define( 'THEME_ADMIN_ASSETS_URI', THEME_DIR_URI . '/framework/admin/assets' );
		define( 'THEME_ADMIN_ASSETS_DIR', THEME_DIR . '/framework/admin/assets' );
		define( 'THEME_CUSTOMIZER_DIR', THEME_DIR . '/framework/admin/customizer' );
		define( 'THEME_CUSTOMIZER_URI', THEME_DIR_URI . '/framework/admin/customizer' );

	}

	public function backward_compatibility() {
		include_once THEME_HELPERS . '/php-backward-compatibility.php';
	}

	public function widgets() {
		include_once THEME_FUNCTIONS . '/widgets-filter.php';
	}

	/**
	 * Add support for different WordPress and plugins features.
	 */
	public function supports() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'yoast-seo-breadcrumbs' );
		add_theme_support( 'wp-block-styles' );

		register_nav_menus(
			array(
				'primary-menu'        => __( 'Primary Navigation', 'mk_framework' ),
				'second-menu'         => __( 'Second Navigation', 'mk_framework' ),
				'third-menu'          => __( 'Third Navigation', 'mk_framework' ),
				'fourth-menu'         => __( 'Fourth Navigation', 'mk_framework' ),
				'fifth-menu'          => __( 'Fifth Navigation', 'mk_framework' ),
				'sixth-menu'          => __( 'Sixth Navigation', 'mk_framework' ),
				'seventh-menu'        => __( 'Seventh Navigation', 'mk_framework' ),
				'eighth-menu'         => __( 'Eighth Navigation', 'mk_framework' ),
				'ninth-menu'          => __( 'Ninth Navigation', 'mk_framework' ),
				'tenth-menu'          => __( 'Tenth Navigation', 'mk_framework' ),
				'footer-menu'         => __( 'Footer Navigation', 'mk_framework' ),
				'toolbar-menu'        => __( 'Header Toolbar Navigation', 'mk_framework' ),
				'side-dashboard-menu' => __( 'Side Dashboard Navigation', 'mk_framework' ),
				'fullscreen-menu'     => __( 'Full Screen Navigation', 'mk_framework' ),
			)
		);

	}

	public function functions() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		include_once THEME_ADMIN . '/general/general-functions.php';

		if ( ! class_exists( 'WpSmush' ) && ! class_exists( 'WP_Smush' ) ) {
			include_once THEME_INCLUDES . '/otf-regen-thumbs/otf-regen-thumbs.php';
		}

		include_once THEME_FUNCTIONS . '/general-functions.php';
		include_once THEME_FUNCTIONS . '/ajax-search.php';
		include_once THEME_FUNCTIONS . '/post-pagination.php';

		include_once THEME_FUNCTIONS . '/enqueue-front-scripts.php';
		include_once THEME_GENERATORS . '/sidebar-generator.php';
		include_once THEME_FUNCTIONS . '/dynamic-styles.php';

		include_once THEME_PLUGIN_INTEGRATIONS . '/woocommerce/init.php';
		include_once THEME_PLUGIN_INTEGRATIONS . '/visual-composer/init.php';

		include_once locate_template( 'framework/helpers/love-post.php' );
		include_once locate_template( 'components/shortcodes/mk_products/quick-view-ajax.php' );
	}
	public function helpers() {
		include_once THEME_HELPERS . '/global.php';
		include_once THEME_HELPERS . '/class-mk-fs.php';
		include_once THEME_HELPERS . '/logic-helpers.php';
		include_once THEME_HELPERS . '/wp_head.php';
		include_once THEME_HELPERS . '/wp_footer.php';
		include_once THEME_HELPERS . '/woocommerce.php';

		// Duplications.
		include_once THEME_FRAMEWORK . '/duplications/functions.php';
		include_once THEME_FRAMEWORK . '/duplications/svg-icons.php';
		include_once THEME_FRAMEWORK . '/duplications/image-resize.php';
	}

	/**
	 * Include all menu walkers libraries.
	 */
	public function menu_walkers() {
		include_once locate_template( 'framework/custom-nav-walker/fallback-navigation.php' );
		include_once locate_template( 'framework/custom-nav-walker/main-navigation.php' );
		include_once locate_template( 'framework/custom-nav-walker/hb-navigation.php' );
		include_once locate_template( 'framework/custom-nav-walker/menu-with-icon.php' );
		include_once locate_template( 'framework/custom-nav-walker/responsive-navigation.php' );
	}

	public function theme_activated() {
		if ( 'themes.php' == basename( $_SERVER['PHP_SELF'] ) && isset( $_GET['activated'] ) && 'true' == $_GET['activated'] ) {
			flush_rewrite_rules();
			update_option( THEME_OPTIONS_BUILD, uniqid() );
			wp_redirect( admin_url( 'admin.php?page=' . THEME_NAME ) );

		}
	}

	/**
	 * Load all required files for admin area.
	 *
	 * @since  5.9.5 Add class-mk-theme-options-misc.php on the list.
	 */
	public function admin() {
		global $abb_phpunit;
		if ( is_admin() || false == ( empty( $abb_phpunit ) && true == $abb_phpunit ) ) {
			include_once THEME_CONTROL_PANEL . '/logic/validator-class.php';
			include_once THEME_CONTROL_PANEL . '/logic/system-messages/js-messages-lib.php';
			include_once THEME_CONTROL_PANEL . '/logic/system-messages/logic-messages-lib.php';
			include_once THEME_CONTROL_PANEL . '/logic/compatibility.php';
			include_once THEME_CONTROL_PANEL . '/logic/functions.php';
			include_once THEME_CONTROL_PANEL . '/logic/plugin-management.php';
			include_once THEME_CONTROL_PANEL . '/logic/template-management.php';
			include_once THEME_CONTROL_PANEL . '/logic/updates-class.php';
			include_once THEME_CONTROL_PANEL . '/logic/class-mk-updates-downgrades.php';
			include_once THEME_CONTROL_PANEL . '/logic/class-mk-export-import.php';
			include_once THEME_ADMIN . '/menus-custom-fields/menu-item-custom-fields.php';
			include_once THEME_ADMIN . '/theme-options/options-check.php';
			include_once THEME_ADMIN . '/general/mega-menu.php';
			include_once THEME_ADMIN . '/general/enqueue-assets.php';
			include_once THEME_ADMIN . '/general/class-mk-live-support.php';
			include_once THEME_ADMIN . '/general/class-update-plugins.php';
			include_once THEME_ADMIN . '/general/class-update-theme.php';
			include_once THEME_ADMIN . '/theme-options/options-save.php';
			include_once THEME_ADMIN . '/theme-options/class-mk-theme-options-misc.php';
			include_once THEME_INCLUDES . '/tgm-plugin-activation/request-plugins.php';

		}
	}
	public function language() {

		load_theme_textdomain( 'mk_framework', get_stylesheet_directory() . '/languages' );
	}

	public function admin_menus() {

		add_menu_page(
			THEME_NAME, THEME_NAME, 'edit_theme_options', THEME_NAME, array(
				&$this,
				'control_panel',
			), 'dashicons-star-filled', 3
		);

		add_submenu_page(
			THEME_NAME, __( 'Control Panel', 'mk_framework' ), __( 'Control Panel', 'mk_framework' ), 'edit_theme_options', THEME_NAME, array(
				&$this,
				'control_panel',
			)
		);
	}


	public function control_panel() {
		include_once THEME_CONTROL_PANEL . '/v2/layout/master.php';
	}


	/**
	 * Check theme requirements.
	 *
	 * @author Artbees
	 * @since 5.0.5
	 * @since 5.0.7
	 * @since 6.0.2 Increase PHP version to 5.6 and improve explanation.
	 * @since 6.1.7 Check theme requirements.
	 */
	public function check_theme_requirements() {
		$required_plugins = '';
		$php_version      = $this->check_php_version();

		if ( ( empty( $required_plugins ) && empty( $php_version ) ) || is_admin() ) {
			return;
		}

		$title   = sprintf( '<h1>%s</h1>', __( 'Maintenance Mode', 'mk_framework' ) );
		$content = sprintf( '<p>%s</p>', __( "We're updating our website. Please check back soon.", 'mk_framework' ) );;

		if ( current_user_can( 'manage_options' ) ) {
			$content .= sprintf( '<p>%s</p>', __( 'Resolve following issues to disable Maintenance Mode. (This part of message is only visible to admin users)', 'mk_framework' ) );
			$content .= '<ul>';
			$content .= $required_plugins;
			$content .= $php_version;
			$content .= '</ul>';
		}

		wp_die( $title . $content );
	}

	/**
	 * Check required plugins.
	 *
	 * @author Artbees
	 *
	 * @since 6.1.7
	 */
	private function check_required_plugins() {
		$content = '';

		if ( ! class_exists( 'Jupiter_Core' ) ) {
			$content .= sprintf( '<li>%s</li>', __( 'Activate <a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '">Jupiter Core</a> plugin.', 'mk_framework' ) );
		}

		if ( ! class_exists( 'Vc_Manager' ) ) {
			$content .= sprintf( '<li>%s</li>', __( 'Activate <a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '">WPBakery Page Builder (Modified Version)</a> plugin.', 'mk_framework' ) );
		}

		if ( ! class_exists( 'Jupiter_Donut' ) ) {
			$content .= sprintf( '<li>%s</li>', __( 'Activate <a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '">Jupiter Donut</a> plugin.', 'mk_framework' ) );
		}

		if ( empty( $content ) ) {
			return '';
		}

		return $content;
	}

	/**
	 * Check PHP version.
	 *
	 * @author Artbees
	 *
	 * @since 6.1.7
	 */
	private function check_php_version() {
		if ( ! in_array( $GLOBALS['pagenow'], array( 'admin-ajax.php' ) ) ) {
			if ( version_compare( phpversion(), '5.6', '<' ) ) {
				return sprintf(
					__( '<li>Your server\'s PHP version (%1$s) is not supported. This version is old, insecure and slow. <br>Please read <a href="%2$s" target="_blank">Checking Server Requirements</a> article to learn about WordPress, Jupiter and other plugins\' server requirements. You may contact your host provider/server administrator to increase the PHP version.</li>', 'mk_framework' ),
					esc_attr( phpversion() ),
					'https://themes.artbees.net/docs/checking-server-requirements/'
				);
			}
		}

		return '';
	}

	/**
	 * Admin notices.
	 */
	public function admin_notices() {
		$required_plugins = $this->check_required_plugins();

		if ( empty( $required_plugins ) ) {
			return;
		}

		?>
		<div class="notice notice-warning is-dismissible">
			<h2><?php _e( 'Required Plugins Installation', 'mk_framework' ); ?></h2>
			<p><?php _e( 'Since Jupiter v6.3.0, <strong>Jupiter Core</strong>, <strong>Jupiter Donut</strong> and <strong>WPBakery Page Builder (Modified Version)</strong> plugins need to be installed and activated to enable different features in the theme.', 'mk_framework' ); ?></p>
			<p><a class="button button-primary" href="<?php echo admin_url( 'themes.php?page=tgmpa-install-plugins' ); ?>"><?php _e( 'Install/Activate the Required Plugins', 'mk_framework' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Include main Theme Options class.
	 */
	private function theme_options() {
		include_once THEME_ADMIN . '/theme-options/class-theme-options.php';
	}

	/**
	 * Define the proper language code.
	 *
	 * @return array The language code.
	 */
	public function lang() {
		global $mk_lang;

		$unify_theme_option = get_option( 'mk_unify_theme_options' );
		$mk_lang = '';

		if ( defined( 'ICL_LANGUAGE_CODE' ) && ! $unify_theme_option ) {
			$mk_lang = '_' . ICL_LANGUAGE_CODE;
		}

			/*
			* Use this constant in child theme functions.php to unify theme options across all languages in WPML
			*  add define('WPML_UNIFY_THEME_OPTIONS', true);
			*/
		if ( defined( 'WPML_UNIFY_THEME_OPTIONS' ) ) {
			$mk_lang = '';
		}

		return $mk_lang;
	}

	/**
	 * Load rtl.css from Jupiter parent theme.
	 *
	 * ATTENTION: This action only runs when user doesn't have any rtl.css file in his
	 * Jupiter child theme.
	 *
	 * @since 6.1.2
	 */
	public function load_rtl_in_child() {
		// Check weather current site is RTL or not.
		if ( ! is_rtl() ) {
			return;
		}

		// Make sure current theme used is Jupiter child theme.
		if ( ! is_child_theme() ) {
			return;
		}

		// Set parent and child theme path directory.
		$parent_dir = get_template_directory();
		$child_dir  = get_stylesheet_directory();

		// Set parent theme URI.
		$parent_dir_uri = get_template_directory_uri();

		/**
		 * Make sure child theme doesn't contain rtl.css file and the file is exist in
		 * Jupiter parent theme.
		 */
		if ( ! file_exists( $child_dir . '/rtl.css' ) && file_exists( $parent_dir . '/rtl.css' ) ) {
			wp_register_style( 'parent-theme-rtl', $parent_dir_uri . '/rtl.css' );
			wp_enqueue_style( 'parent-theme-rtl' );
		}
	}

	/**
	 * Include main customizer class.
	 *
	 * @since 5.9.4
	 */
	private function customizer() {
		include_once THEME_ADMIN . '/customizer/class-mk-customizer.php';
	}

	/**
	 * Add tour list then include main Tour class.
	 *
	 * @since 5.9.6
	 */
	private function tour() {

		// Add tour list. Choose short, one-word id.
		add_filter(
			'mk_tour_list', function( $tour_list ) {
				$tour_list = array(
					'intro' => array(
						'state' => true,
					),
				);

				return $tour_list;
			}
		);

		include_once THEME_ADMIN . '/tour/class-mk-tour.php';
	}
}
