<?php
/**
 * This file is responsible from all dynamic css and js proccess and minification.
 *
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       1.0.0
 * @since       5.9.3
 * @package     artbees
 */

defined( 'ABSPATH' ) || die();

/**
 * Mk_Static_Files is responsible for dynamic styles.
 *
 * @copyright   Artbees LTD (c)
 * @since       1.0.0
 * @since       5.9.3
 * @package     artbees
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if ( ! jupiter_donut_is_jupiter() ) {

	class Mk_Static_Files {

		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'process_global_styles' ) );

			if ( ! self::is_vc_editing() ) {
				add_action( 'wp_footer', array( &$this, 'move_short_styles_footer' ), 9 );
			}
		}

		public static function is_referer_admin_ajax() {
			global $pagenow;

			$result = in_array(
				$pagenow, array(
					'admin-ajax.php',
				)
			);

			if ( $result ) {
				return true;
			}
		}

		public static function shortcode_id() {

			if ( self::is_vc_editing() || self::is_referer_admin_ajax() ) {
				return uniqid();
			}

			global $mk_shortcode_order;
			$mk_shortcode_order++;
			return $mk_shortcode_order;
		}

		public function init_globals() {
			$glob_dynamic_styles = $local_dynamic_styles = '';
			global $glob_dynamic_styles, $local_dynamic_styles;
		}

		public static function addCSS( $app_styles, $css_id ) {
			if ( self::is_vc_editing() ) {
				$minifier = new SimpleCssMinifier();
				$output = $minifier->minify( $app_styles );
				echo '<style id="mk-shortcode-style-' . $css_id . '" type="text/css">' . $output . '</style>';
				return;
			}

			global $mk_dynamic_styles;

			$mk_dynamic_styles[] = $app_styles;

			if ( self::is_referer_admin_ajax() ) {
				$minifier = new SimpleCssMinifier();
				$output   = $minifier->minify( $app_styles );
				echo '<style id="mk-shortcode-style-' . $css_id . '" type="text/css">' . $output . '</style>';
			}

		}

		public function move_short_styles_footer() {
			if ( self::is_vc_editing() ) {
				return;
			}
			global $mk_dynamic_styles;

			if ( empty( $mk_dynamic_styles ) ) {
				return;
			}

			$conc_dynamic_styles = implode( '', $mk_dynamic_styles );
			$minifier                         = new SimpleCssMinifier();
			$output                           = $minifier->minify( $conc_dynamic_styles );
			echo '<style id="mk-shortcode-static-styles" type="text/css">' . $output . '</style>';
		}

		public static function addAssets( $shortcode_name ) {
			if ( $shortcode_name ) {
				return true;
			}
			return false;
		}

		public static function StoreAsset( $folder, $filename, $file_content ) {
			global $mk_dev;

			$mkfs = new Mk_Fs(
				array(
					'context' => $folder,
				)
			);

			self::createPath( $folder );
			$sha1_concat_string = sha1( $file_content );
			$file_path          = path_convert( $folder . '' . $filename );
			if ( get_option( $filename . '_sha1' ) != $sha1_concat_string || ! $mkfs->exists( $file_path ) ) {
				$comment = ''; // define comment var.
				if ( $mk_dev ) {
					$comment = "\n /* $filename " . time() . " */ \n ";
				}

				$mkfs->put_contents( $file_path, $comment . $file_content );

				update_option( $filename . '_sha1', $sha1_concat_string );
			}
		}

		public function process_global_styles() {
			// declaring the globals.
			global $local_dynamic_styles;
			$is_css_min = ( ! ( defined( 'MK_DEV' ) ? constant( 'MK_DEV' ) : true ) || 'true' === jupiter_donut_get_option( 'minify-css' ) );
			$handle = 'components-full';

			$this->init_globals();
			$output = $local_dynamic_styles;

			if ( $is_css_min ) {
				$minifier = new SimpleCssMinifier();
				$output = $minifier->minify( $output );
				$handle = 'jupiter-donut';
			}

			wp_add_inline_style( $handle, $output );
		}

		public static function prevent_cache_plugins() {
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );
			}

			if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
				define( 'DONOTCACHCEOBJECT', true );
			}

			if ( ! defined( 'DONOTMINIFY' ) ) {
				define( 'DONOTMINIFY', true );
			}

			if ( ! defined( 'DONOTCACHEDB' ) ) {
				define( 'DONOTCACHEDB', true );
			}

			if ( ! defined( 'DONOTCDN' ) ) {
				define( 'DONOTCDN', true );
			}

		}

		public static function addGlobalStyle( $styles ) {
			global $glob_dynamic_styles;

			$glob_dynamic_styles .= $styles;
		}

		public static function addLocalStyle( $styles ) {
			global $local_dynamic_styles;

			$local_dynamic_styles .= $styles;
		}

		public static function get_global_asset_upload_folder( $type ) {
			$upload_folder_name = 'mk_assets';
			$wp_upload_dir      = wp_upload_dir();
			if ( 'directory' == $type ) {
				$upload_dir = $wp_upload_dir['basedir'] . '/' . $upload_folder_name . '/';
			} elseif ( 'url' == $type ) {
				// Converts url to https even if site url is not primarily set as https.
				$baseurl    = is_ssl() ? str_replace( 'http://', 'https://', $wp_upload_dir['baseurl'] ) : $wp_upload_dir['baseurl'];
				$upload_dir = $baseurl . '/' . $upload_folder_name . '/';
			} elseif ( 'url' != $type && 'directory' != $type ) {
				return '';
			}

			return $upload_dir;
		}

		public static function get_global_asset_address( $extension, $type ) {
			if ( 'css' != $extension && 'js' != $extension ) {
				return '';
			}

			if ( get_option( 'global_assets_filename' ) ) {
				$upload_dir = self::get_global_asset_upload_folder( $type );
				if ( ! $upload_dir ) {
					return '';
				}

				$filename_pre = get_option( 'global_assets_filename' );
				$filename     = $upload_dir . $filename_pre . '.min.' . $extension;

				return $filename;
			}

				return '';
		}

		public static function createPath( $path ) {
			$mkfs = new Mk_Fs(
				array(
					'context' => $path,
				)
			);
			if ( $mkfs->is_dir( $path ) ) {
				return true;
			}

			return $mkfs->mkdir( $path );
		}

		public static function deleteFile( $filename ) {

			$mkfs = new Mk_Fs(
				array(
					'context' => $filename,
				)
			);
			if ( ! $mkfs->exists( $filename ) ) {
				return true;
			}

			return $mkfs->delete( $filename );
		}

		private static function is_vc_editing() {
			$move_styles_footer = jupiter_donut_get_option( 'move-shortcode-css-footer' );

			if ( 'false' == $move_styles_footer ) {
				return true;
			}

			if ( function_exists( 'vc_is_page_editable' ) && vc_is_page_editable() ) {
				return true;
			}

			return false;
		}

		public static function delete_transient_mk_getimagesize() {}

		public function DeleteThemeOptionStyles( $remove_cache_plugins ) {}

		public function delete_transient_mk_critical_path_css() {}
	}

	new Mk_Static_Files();

}
