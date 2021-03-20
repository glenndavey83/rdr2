<?php
if ( ! defined( 'THEME_FRAMEWORK' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * General frontend functions
 *
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 1.0
 * @package     artbees
 */

define( 'GLOBAL_ASSETS', 'global_assets' );

if ( ! function_exists( 'mk_flush_rules' ) ) {
	function mk_flush_rules() {
		if ( get_option( 'mk_jupiter_flush_rules' ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			delete_option( 'mk_jupiter_flush_rules' );
		}
	}

	add_action( 'wp_loaded', 'mk_flush_rules' );
}

function mk_current_page_url() {
	$pageURL = 'http';
	if ( isset( $_SERVER['HTTPS'] ) ) {
		if ( $_SERVER['HTTPS'] == 'on' ) {
			$pageURL .= 's';
		}
	}
	$pageURL .= '://';
	if ( $_SERVER['SERVER_PORT'] != '80' ) {
		$pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
	} else {
		$pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	return $pageURL;
}

/*
 * Login ajax functions
 */
function ajax_login_init() {

	global $mk_options;

	$theme_js_hook = ($mk_options['minify-js'] == 'true') ? 'theme-scripts-min' : 'theme-scripts';

	wp_localize_script(
		$theme_js_hook, 'ajax_login_object', array(
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'redirecturl'    => mk_current_page_url(),
			'loadingmessage' => __( 'Sending user info, please wait...', 'mk_framework' ),
		)
	);
}
if ( ! is_user_logged_in() ) {
	add_action( 'wp_footer', 'ajax_login_init' );
}

add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );

function ajax_login() {
	check_ajax_referer( 'ajax-login-nonce', 'security' );

	// Nonce is checked, get the POST data and sign user on
	$info                  = array();
	$info['user_login']    = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember']      = true;

	$user_signon = wp_signon( $info, false );
	if ( is_wp_error( $user_signon ) ) {
		echo json_encode(
			array(
				'loggedin' => false,
				'message'  => __( 'Wrong username or password.', 'mk_framework' ),
			)
		);
	} else {
		echo json_encode(
			array(
				'loggedin' => true,
				'message'  => __( 'Login successful, redirecting...', 'mk_framework' ),
			)
		);
	}

	die();
}

/*-----------------*/

/* removes Contactform 7 styles */
remove_action( 'wp_enqueue_scripts', 'wpcf7_enqueue_styles' );

/*
Register your custom function to override some LayerSlider data
 */
add_action( 'layerslider_ready', 'my_layerslider_overrides' );
function my_layerslider_overrides() {
	$GLOBALS['lsAutoUpdateBox'] = false;
}

/**
 * Content Width Calculator
 *
 * Retrieves the content width based on $grid-width
 *
 * @param string  $layout param
 */
if ( ! function_exists( 'mk_count_content_width' ) ) {
	function mk_count_content_width( $post_id = false ) {

		global $mk_options, $post;

		if ( $post_id ) {
			$id = $post_id;
		} else {
			$id = $post->id;
		}

		$layout = get_post_meta( $id, '_layout', true );
		$layout = (empty( $layout )) ? 'full' : $layout;
		if ( is_singular( 'portfolio' ) ) {

			$layout = ( 'default' == $layout ) ? $mk_options['portfolio_single_layout'] : $layout;
		} elseif ( is_singular( 'post' ) ) {

			$layout = ( 'default' == $layout ) ? $mk_options['single_layout'] : $layout;
		}

		if ( 'full' == $layout ) {

			return $mk_options['grid_width'] - 40;
		} else {

			return round( ($mk_options['content_width'] / 100) * $mk_options['grid_width'] - 40 );
		}
	}
}

/*-----------------*/

/**
 * Adds Next/Previous post navigations to single posts
 */

if ( ! function_exists( 'mk_get_single_post_prev_next' ) ) {
	function mk_get_single_post_prev_next() {

		if ( ! class_exists( 'Jupiter_Donut' ) ) {
			return;
		}

		global $mk_options;

		if ( is_singular( 'portfolio' ) && 'true' != $mk_options['portfolio_next_prev'] ) {
			return false;
		}

		if ( is_singular( 'post' ) && 'true' != $mk_options['blog_prev_next'] ) {
			return false;
		}

		if ( is_singular( 'product' ) && 'false' == $mk_options['woo_single_prev_next'] ) {
			return false;
		}

		$options = array();

		$options['excluded_terms'] = '';

		$options['type'] = get_post_type();

		switch ( $options['type'] ) {
			case 'post':
				$options['taxonomy']     = 'category';
				$options['in_same_term'] = isset( $mk_options['blog_prev_next_same_category'] ) ? ($mk_options['blog_prev_next_same_category'] === 'true') : false;
				break;
			case 'portfolio':
				$options['taxonomy']     = 'portfolio_category';
				$options['in_same_term'] = isset( $mk_options['portfolio_prev_next_same_category'] ) ? ($mk_options['portfolio_prev_next_same_category'] === 'true') : false;
				break;
			case 'news':
				$options['taxonomy']     = 'news_category';
				$options['in_same_term'] = false;
				break;
			case 'product':
				$options['taxonomy']     = 'product_cat';
				$options['in_same_term'] = isset( $mk_options['woo_prev_next_same_category'] ) ? ($mk_options['woo_prev_next_same_category'] === 'true') : false;
				break;

			default:
				$options['taxonomy']     = 'category';
				$options['in_same_term'] = false;
				break;
		}

		if ( ! is_singular() || is_post_type_hierarchical( $options['type'] ) ) {
			$options['is_hierarchical'] = true;
		}
		if ( $options['type'] === 'topic' || $options['type'] === 'reply' ) {
			$options['is_bbpress'] = true;
		}

		$options = apply_filters( 'mk_post_nav_settings', $options );
		if ( ! empty( $options['is_bbpress'] ) || ! empty( $options['is_hierarchical'] ) ) {
			return;
		}

		$entries['prev'] = get_adjacent_post( $options['in_same_term'], $options['excluded_terms'], true, $options['taxonomy'] );
		$entries['next'] = get_adjacent_post( $options['in_same_term'], $options['excluded_terms'], false, $options['taxonomy'] );

		$entries = apply_filters( 'mk_post_nav_entries', $entries, $options );
		$output  = '';

		foreach ( $entries as $key => $entry ) {
			if ( empty( $entry ) ) {
				continue;
			}

			$post_type = get_post_type( $entry->ID );

			$icon = $post_image = '';
			$link = esc_url( get_permalink( $entry->ID ) );
			/* Added image-size-150x150 image size in functions.php to have exact 150px * 150px thumbnail size */
			$image = get_the_post_thumbnail( $entry->ID, 'image-size-150x150' );
			$class = $image ? 'with-image' : 'without-image';
			$icon  = ($key == 'prev') ? Mk_SVG_Icons::get_svg_icon_by_class_name( false, 'mk-icon-long-arrow-left' ) : Mk_SVG_Icons::get_svg_icon_by_class_name( false, 'mk-icon-long-arrow-right' );
			$output .= '<a class="mk-post-nav mk-post-' . $key . ' ' . $class . '" href="' . $link . '">';

			$output .= '<span class="pagnav-wrapper">';
			$output .= '<span class="pagenav-top">';

			$icon = '<span class="mk-pavnav-icon">' . $icon . '</span>';

			if ( $image ) {
				$post_image = '<span class="pagenav-image">' . $image . '</span>';
			}

			$output .= $key == 'next' ? $icon . $post_image : $post_image . $icon;
			$output .= '</span>';

			$output .= '<div class="nav-info-container">';
			$output .= '<span class="pagenav-bottom">';

			$output .= '<span class="pagenav-title">' . get_the_title( $entry->ID ) . '</span>';

			if ( $post_type == 'post' ) {
				$cats = get_the_category( $entry->ID );
				foreach ( $cats as $cat ) {
					$category[] = $cat->name;
				}
				$output .= '<span class="pagenav-category">' . implode( ', ', $category ) . '</span>';
				$category = array();
			} elseif ( $post_type == 'portfolio' ) {
				$terms      = get_the_terms( $entry->ID, 'portfolio_category' );
				$terms_slug = array();
				$terms_name = array();
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						$terms_name[] = $term->name;
					}
				}
				$output .= '<span class="pagenav-category">' . implode( ', ', $terms_name ) . '</span>';
				$terms_name = array();
			} elseif ( $post_type == 'product' ) {
				$terms      = get_the_terms( $entry->ID, 'product_cat' );
				$terms_slug = array();
				$terms_name = array();
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						$terms_name[] = $term->name;
					}
				}
				$output .= '<span class="pagenav-category">' . implode( ', ', $terms_name ) . '</span>';
				$terms_name = array();
			} elseif ( $post_type == 'news' ) {
				$terms      = get_the_terms( $entry->ID, 'news_category' );
				$terms_slug = array();
				$terms_name = array();
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						$terms_name[] = $term->name;
					}
				}
				$output .= '<span class="pagenav-category">' . implode( ', ', $terms_name ) . '</span>';
				$terms_name = array();
			} // End if().
			$output .= '</span>';
			$output .= '</div>';
			$output .= '</span>';
			$output .= '</a>';
		} // End foreach().
		echo $output;
	}

	add_action( 'wp_footer', 'mk_get_single_post_prev_next' );
} // End if().

/*-----------------*/

if ( ! function_exists( 'mk_shortcode_empty_paragraph_fix' ) ) {
	function mk_shortcode_empty_paragraph_fix( $content ) {
		$array = array(
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']',
		);

		$content = strtr( $content, $array );

		return $content;
	}
}

/* Safe way to remove autop tags inside shortcodes without touching WordPress filters and default behaviors. */
add_filter( 'the_content', 'mk_shortcode_empty_paragraph_fix' );

/*-----------------*/

if ( ! function_exists( 'mk_add_ajax_library' ) ) {
	function mk_add_ajax_library() {
		$html = '<script type="text/javascript">';
		$html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '";';
		$html .= '</script>';
		echo $html;
	}
}

add_action( 'wp_enqueue_scripts', 'mk_add_ajax_library' );

/*-----------------*/

if ( ! function_exists( 'mk_get_shop_id' ) ) {
	function mk_get_shop_id() {
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() && is_archive() ) {

			return wc_get_page_id( 'shop' );
		} else {

			return false;
		}
	}
}

if ( ! function_exists( 'mk_is_woo_archive' ) ) {
	function mk_is_woo_archive() {
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() && is_archive() ) {

			return wc_get_page_id( 'shop' );
		} else {

			return false;
		}
	}
}

/*-----------------*/

if ( ! function_exists( 'mk_get_skin_color' ) ) {
	function mk_get_skin_color() {
		global $mk_options;
		if ( isset( $_GET['skin'] ) ) {
			return $_GET['skin'];
		} else {
			return $mk_options['skin_color'];
		}
	}
}

/*-----------------*/

/**
 * Purge Cache for dynamic styles and scripts.
 */
add_action( 'admin_post_mk_purge_cache', 'mk_purge_cache' );
function mk_purge_cache() {
	if ( isset( $_GET['action'], $_GET['_wpnonce'] ) ) {

		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'theme_purge_cache' ) ) {
			wp_nonce_ays( '' );
		}

		if ( function_exists( 'mk_purge_cache_actions' ) ) {
			mk_purge_cache_actions();
		}

		wp_redirect( wp_get_referer() );
		die();
	}
}

/*
 * Adds Extra
 */
add_action( 'show_user_profile', 'mk_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'mk_show_extra_profile_fields' );

if ( ! function_exists( 'mk_show_extra_profile_fields' ) ) {
	function mk_show_extra_profile_fields( $user ) {
		?>

		<h3>User Social Networks</h3>

		<table class="form-table">

			<tr>
				<th><label for="twitter">Twitter</label></th>

				<td>
					<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Please enter your Twitter Profile URL.</span>
				</td>
			</tr>

		</table>
		<?php
	}
}

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

if ( ! function_exists( 'my_save_extra_profile_fields' ) ) {
	function my_save_extra_profile_fields( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
	}
}

/*-----------------*/

/*
 * Removes WordPress default excerpt brakets from its endings
 */
if ( ! function_exists( 'mk_theme_excerpt_more' ) ) {
	function mk_theme_excerpt_more( $excerpt ) {
		return str_replace( '[...]', '', $excerpt );
	}
}
add_filter( 'wp_trim_excerpt', 'mk_theme_excerpt_more' );

/*-----------------*/

/*
 * Gives the text widget capability of inserting shortcode.
 */
if ( ! function_exists( 'mk_theme_widget_text_shortcode' ) ) {
	function mk_theme_widget_text_shortcode( $content ) {
		$content          = do_shortcode( $content );
		$new_content      = '';
		$pattern_full     = '{(\[raw\].*?\[/raw\])}is';
		$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		$pieces           = preg_split( $pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE );

		foreach ( $pieces as $piece ) {
			if ( preg_match( $pattern_contents, $piece, $matches ) ) {
				$new_content .= $matches[1];
			} else {
				$new_content .= do_shortcode( $piece );
			}
		}

		return $new_content;
	}
}
add_filter( 'widget_text', 'mk_theme_widget_text_shortcode' );
add_filter( 'widget_text', 'do_shortcode' );

/*-----------------*/

/*
Adds shortcodes dynamic css into footer.php

WARNING !!!!!!!!
The function name is misleading as it is not responsible for css only.
php.hasAdminbar is trival for core functions and removing it already caused problems.
Please move it to head section along with other JS generated from php.
Leave hasAdminbar and jsPath, json holds CSS injected with old methodology and is not relevant anymore.
 */
if ( ! function_exists( 'mk_dynamic_css_injection' ) ) {
	function mk_dynamic_css_injection() {

		global $app_styles, $app_json;

		$output = '<script type="text/javascript">';

		$is_admin_bar   = is_admin_bar_showing() ? 'true' : 'false';
		$mk_json_encode = json_encode( $app_json );
		$output .= '
    php = {
        hasAdminbar: ' . $is_admin_bar . ',
        json: (' . $mk_json_encode . ' != null) ? ' . $mk_json_encode . ' : "",
        jsPath: \'' . THEME_JS . '\'
      };
    </script>';

		echo $output;
	}
}

add_action( 'wp_footer', 'mk_dynamic_css_injection' );

/*-----------------*/

//
//
// Global JSON object to collect all DOM related data
// todo - move here all VC shortcode settings
//
//
function create_global_json() {
	$app_json = array();
	global $app_json;
}
create_global_json();

function create_global_modules() {
	$app_modules = array();
	global $app_modules;
}
create_global_modules();

function create_global_styles() {
	$app_styles = '';
	global $app_styles;
}
create_global_styles();

/**
 * function to check if the current page is admin-ajax.php and the action is sent is vc_edit_form
 *
 * @return boolean
 */
function mk_page_is_vc_edit_form() {
	global $pagenow;

	// make sure we are on the backend
	if ( ! is_admin() ) {
		return false;
	}

	$result = in_array(
		$pagenow, array(
			'admin-ajax.php',
		)
	);
	$ajax_action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

	if ( $result && $ajax_action == 'vc_edit_form' ) {
		return true;
	}
}

/**
 * @param $path
 * @return mixed
 */
function path_convert( $path ) {
	if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
		$path = str_replace( '/', '\\', $path );
	} else {
		$path = str_replace( '\\', '/', $path );
	}
	return $path;
}

/**
 * Scans a directory for files of a certain extension.
 *
 * @since 3.4.0
 * @access private
 *
 * @param string                                                                                       $path Absolute path to search.
 * @param mixed  Array of extensions to find, string of a single extension, or null for all extensions.
 * @param int                                                                                          $depth How deep to search for files. Optional, defaults to a flat scan (0 depth). -1 depth is infinite.
 * @param string                                                                                       $relative_path The basename of the absolute path. Used to control the returned path
 *                                                                                        for the found files, particularly when this function recurses to lower depths.
 */
function mk_scandir( $path, $mode, $relative_path = '' ) {
	$path = path_convert( $path );

	if ( ! is_dir( $path ) ) {
		return false;
	}

	$relative_path = trailingslashit( $relative_path );
	if ( '/' == $relative_path ) {
		$relative_path = '';
	}

	$results = scandir( $path, $mode );
	$results = array_diff( $results, array( '.', '..' ) );

	return $results;
}

if ( ! function_exists( 'mk_base_url' ) ) {
	function mk_base_url( $atRoot = false, $atCore = false, $parse = false ) {
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$http     = isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' ? 'https' : 'http';
			$hostname = $_SERVER['HTTP_HOST'];
			$dir      = str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );

			$core = preg_split( '@/@', str_replace( $_SERVER['DOCUMENT_ROOT'], '', realpath( dirname( __FILE__ ) ) ), null, PREG_SPLIT_NO_EMPTY );
			$core = $core[0];

			$tmplt    = $atRoot ? ($atCore ? '%s://%s/%s/' : '%s://%s/') : ($atCore ? '%s://%s/%s/' : '%s://%s%s');
			$end      = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
			$base_url = sprintf( $tmplt, $http, $hostname, $end );
		} else {
			$base_url = 'http://localhost/';
		}

		if ( $parse ) {
			$base_url = parse_url( $base_url );
			if ( isset( $base_url['path'] ) ) {
				if ( $base_url['path'] == '/' ) {
					$base_url['path'] = '';
				}
			}
		}

		return $base_url;
	}
}

/*
 * To remove accents from the name of uploaded files
 * Thanks to: https://goo.gl/h2tYrZ
 */
if ( ! function_exists( 'mk_sanitize_file_uploads' ) ) {

	function mk_sanitize_file_uploads( $file ) {
		$file['name'] = sanitize_file_name( $file['name'] );
		$file['name'] = preg_replace( '/[^a-zA-Z0-9\_\-\.]/', '', $file['name'] );
		$file['name'] = strtolower( $file['name'] );
		add_filter( 'sanitize_file_name', 'remove_accents' );

		return $file;
	}
	add_filter( 'wp_handle_upload_prefilter', 'mk_sanitize_file_uploads' );

}

/**
 * Add Unify Theme Options in Settings > General for WPML plugin
 *
 * @since 5.3
 */

if ( mk_is_callable( 'SitePress' ) || mk_is_callable( 'Polylang' ) ) {
	add_filter( 'admin_init', 'mk_unify_theme_options_fields' );

	function mk_unify_theme_options_fields() {
		register_setting( 'general', 'mk_unify_theme_options', 'esc_attr' );

		add_settings_field(
			'mk_unify_theme_options',
			'<label for="mk_unify_theme_options">' . __( 'Unify Theme Options', 'mk_framework' ) . '</label>',
			'mk_unify_theme_options_field',
			'general'
		);
	}

	function mk_unify_theme_options_field() {
		$unify_theme_options = get_option( 'mk_unify_theme_options' );

		$html = '<legend class="screen-reader-text"><span>' . __( 'Unify Theme Options', 'mk_framework' ) . '</span></legend>
        <label for="mk_unify_theme_options">
        <input name="mk_unify_theme_options" type="checkbox" id="mk_unify_theme_options" value="1" ' . checked( 1, $unify_theme_options, false ) . '>' . __( 'Unify Theme Options in all languages for WPML/Polylang plugin.', 'mk_framework' ) . '</label>';

		echo $html;
	}
}

/**
 * Adds a custom link to main menu in Theme Options
 *
 * @param string $menu_slug  Custom main menu slug
 * @param string $svg_icon   SVG icon
 * @param string $menu_title Custom main menu title
 *
 * @since 5.5
 *
 * @return mixed
 */
function mk_theme_options_add_main_menu( $menu_slug, $svg_icon, $menu_title ) {
	$menu_link = '<li>
        <a href="#' . $menu_slug . '">
            ' . $svg_icon . '
            <span> ' . $menu_title . '</span>
        </a>
    </li>';

	return $menu_link;
}

/**
 * Adds a settings page to main menu's custom link in Theme Options
 *
 * @param array  $options   Theme options array
 * @param string $menu_slug Custom main menu slug
 *
 * @since 5.5
 *
 * @return array
 */
function mk_theme_options_add_main_menu_settings_page( $options, $menu_slug ) {
	$options[] = array(
		'type'   => 'group',
		'id'     => $menu_slug,
		'menu'   => array(),
		'fields' => array(),
	);

	return $options;
}

/**
 * Adds a custom sub menu to a settings page in Theme Options
 *
 * @param array  $options        Theme options array
 * @param int    $menu_id        Main theme options's menu id, it starts from 0
 * @param string $sub_menu_slug  New sub menu slug
 * @param string $sub_menu_title New sub menu title
 *
 * @since 5.5
 *
 * @return array
 */
function mk_theme_options_add_sub_menu( $options, $menu_id, $sub_menu_slug, $sub_menu_title ) {
	$options[ $menu_id ]['menu'][ $sub_menu_slug ] = $sub_menu_title;

	return $options;
}

/**
 * Adds a sub settings page to a custom sub menu in Theme Options
 *
 * @param array  $options             Theme options array
 * @param int    $menu_id             Main theme options's menu id, it starts from 0
 * @param string $sub_menu_slug       New sub menu slug
 * @param string $sub_menu_page_title New option page title
 * @param string $sub_menu_page_desc  New option page description
 *
 * @since 5.5
 *
 * @return array
 */
function mk_theme_options_add_sub_menu_settings_page( $options, $menu_id, $sub_menu_slug, $sub_menu_page_title, $sub_menu_page_desc ) {
	$options[ $menu_id ]['fields'][] = array(
		'type'   => 'sub_group',
		'id'     => $sub_menu_slug,
		'name'   => $sub_menu_page_title,
		'desc'   => $sub_menu_page_desc,
		'fields' => array(),
	);

	return $options;
}

/**
 * Adds option/s to any settings page in Theme Options
 *
 * @param array $options     Theme options array
 * @param int   $menu_id     Main theme options's menu id, it starts from 0
 * @param int   $sub_menu_id New sub menu id, it starts from 0
 * @param array $settings    New settings array
 *
 * @since 5.5
 *
 * @return array
 */
function mk_theme_options_add_settings( $options, $menu_id, $sub_menu_id, $settings ) {
	$options[ $menu_id ]['fields'][ $sub_menu_id ]['fields'][] = $settings;

	return $options;
}

// Add editor styles.
add_editor_style();

/**
 * Detects if function or class is callable.
 *
 * @param string $callable
 *
 * @return boolean
 */
function mk_is_callable( $callable ) {
	return ( class_exists( $callable ) || function_exists( $callable ) ) ? true : false;
}
