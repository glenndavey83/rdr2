<?php
/**
 * Helper functions for various parts of the theme
 *
 * @author    Bob Ulusoy
 * @copyright Artbees LTD (c)
 * @link      http://artbees.net
 * @since     4.2
 * @since     5.9.1
 * @package   artbees
 */

defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'mk_build_main_wrapper' ) ) {
	/**
	 * Builds content wrappers for the given content
	 *
	 * @param string $content HTML string to manpulate.
	 * @param string $wrapper_custom_class CSS class name for wrapper element.
	 * @param string $master_holder_class CSS class name for master holder element.
	 * @return void
	 */
	function mk_build_main_wrapper( $content, $wrapper_custom_class = '', $master_holder_class = '' ) {

		// Get theme options from global mk_options variable.
		global $mk_options, $post;

		// Get layout option from post meta.
		$layout = is_singular() ? get_post_meta( $post->ID, '_layout', true ) : '';

		// Check if it's single portfolio and and get the layout option from theme options.
		$layout = (is_singular( 'portfolio' )) ? ('default' == $layout ? $mk_options['portfolio_single_layout'] : $layout) : $layout;

		// Check if it's single blog and and get the layout option from theme options.
		$layout = (is_singular()) ? (('default' == $layout) ? $mk_options['single_layout'] : $layout) : $layout;

		// Employees single should always be full width.
		$layout = is_singular( 'employees' ) ? 'full' : $layout;

		$layout = ( is_404() && is_active_sidebar( 'Sidebar-7' ) ) ? 'right' : $layout;

				$layout = (is_archive() && get_post_type() == 'post') ? $mk_options['archive_page_layout'] : $layout;

				$layout = (is_archive() && get_post_type() == 'portfolio') ? $mk_options['archive_portfolio_layout'] : $layout;

				$layout = is_search() ? $mk_options['search_page_layout'] : $layout;

		if ( isset( $_REQUEST['layout'] ) && ! empty( $_REQUEST['layout'] ) ) {
			$layout = esc_html( $_REQUEST['layout'] );
		}

		// For other empty scenarios we get full layout.
		$layout = (empty( $layout )) ? 'full' : $layout;
		$layout_grid = ( 'full-width' !== $layout ) ? 'mk-grid' : '';

		$wrapper_class = empty( $wrapper_custom_class ) ? 'mk-main-wrapper ' . $layout_grid : $wrapper_custom_class;

		$wrapper_id = is_singular() ? 'id="mk-page-id-' . esc_attr( $post->ID ) . '"' : '';
		$itemprop = (is_singular()) ? 'mainEntityOfPage' : 'mainContentOfPage';

		$schema_markup = (is_singular()) ? get_schema_markup( 'blog' ) : get_schema_markup( 'main' );

		$post_id = global_get_post_id();
		$has_parallax = get_post_meta( $post_id, 'page_parallax', true ) ? get_post_meta( $post_id, 'page_parallax', true ) : 'false';
		$parallax_conf = '';
		if ( 'true' === $has_parallax ) {
			$parallax_conf .= ' data-mk-component="Parallax" ';
			$parallax_conf .= ' data-parallax-config=\'{"speed" : 0.3 }\' ';
		}

		/*
            Option to remove top and bottom padding of the content.
            Its used when page section will be added right after header
            and no space is desired.
        */
		$padding = is_singular() ? get_post_meta( $post->ID, '_padding', true ) : '';

		if ( 'true' === $padding ) {
			$padding = 'no-padding';
		} else if ( is_singular() ) {
			$post_type = get_post_type();
			if ( ( ! empty( $mk_options['stick_template_page'] ) && 'page' === $post_type && 'true' === $mk_options['stick_template_page'] ) ||
				( ! empty( $mk_options['stick_template_portfolio'] ) && 'portfolio' === $post_type && 'true' === $mk_options['stick_template_portfolio'] )
			 ) {
				$padding = 'no-padding';
			}
		}

		if ( mk_get_blog_single_style() === 'bold' ) {
			mk_get_view( 'blog/components', 'blog-single-bold-hero' );
		}
?>

		<div id="theme-page" class="master-holder <?php echo esc_attr( $master_holder_class ); ?> clearfix" <?php echo $schema_markup; ?>>
			<div class="master-holder-bg-holder">
				<div id="theme-page-bg" class="master-holder-bg js-el" <?php echo $parallax_conf; ?> ></div>
			</div>
			<div class="mk-main-wrapper-holder">
				<div <?php echo $wrapper_id; ?> class="theme-page-wrapper <?php echo esc_attr( $wrapper_class ) . ' ' . esc_attr( $layout ) . '-layout ' . esc_attr( $padding ); ?>">
					<div class="theme-content <?php echo esc_attr( $padding ); ?>" itemprop="<?php echo esc_attr( $itemprop ); ?>">
							<?php echo $content; ?>
							<div class="clearboth"></div>
						<?php
						if ( mk_is_pages_comments_enabled() ) {
							if ( comments_open() ) {
								comments_template( '', true );
							}
						}
						?>
					</div>
					<?php
					if ( 'left' === $layout || 'right' === $layout ) {
						get_sidebar(); }
?>
					<div class="clearboth"></div>
				</div>
			</div>
			<?php
			if ( is_singular( 'portfolio' ) && 'true' === $mk_options['enable_portfolio_similar_posts'] && 'true' === get_post_meta( $post->ID, '_portfolio_similar', true ) ) {
				// Will be loaded in single portfolio page only. located in views/portfolio/portfolio-similar-posts.php.
				mk_get_view( 'portfolio/components', 'portfolio-similar-posts' );
			}
			?>
		</div>

<?php
	}
}// End if().

/**
 * Choosing Main Navigation menu location
 *
 * @return menu location    string
 */

if ( ! function_exists( 'mk_main_nav_location' ) ) {

	function mk_main_nav_location() {
		global $mk_options;
		$post_id = global_get_post_id();
		$post_id = mk_is_woo_archive() ? mk_is_woo_archive() : $post_id;
		$meta_menu_location = ! empty( $post_id ) ? get_post_meta( $post_id, '_menu_location', true ) : false;

		if ( is_user_logged_in() && ! empty( $mk_options['loggedin_menu'] ) ) {
			$menu_location = $mk_options['loggedin_menu'];
		} else {

			if ( $post_id && isset( $meta_menu_location ) && ! empty( $meta_menu_location ) ) {
				$menu_location = $meta_menu_location;
			} else {
				$menu_location = 'primary-menu';
			}
		}
			return $menu_location;
	}
}

/**
 * Get blog single post style
 *
 * @return style   string
 */

if ( ! function_exists( 'mk_get_blog_single_style' ) ) {

	function mk_get_blog_single_style() {

		if ( ! is_singular( 'post' ) ) {
			return false;
		}

			global $mk_options, $post;
			$style = get_post_meta( $post->ID, '_single_blog_style', true );
			$style = ('default' == $style || empty( $style )) ? $mk_options['single_blog_style'] : $style;

			return $style;
	}
}

/**
 * Get blog single post type
 *
 * @return style   string
 */

if ( ! function_exists( 'mk_get_blog_single_type' ) ) {

	function mk_get_blog_single_type() {

		if ( ! is_singular( 'post' ) ) {
			return false;
		}

			global $mk_options, $post;
			$style = get_post_meta( $post->ID, '_single_post_type', true );

			return $style;
	}
}

/**
 * Its intended to get the right domain for GDPR purposes.
 *
 * @param   name     the third party website domain name
 * @return  domain   string
 */

if ( ! function_exists( 'mk_get_thirdparty_embed_domain' ) ) {

	function mk_get_thirdparty_domain_name( $name ) {
		global $mk_options;
		$gdpr = $mk_options['third_party_gdpr'];
		if ( 'youtube' == $name ) {
			if ( 'true' == $gdpr ) {
				return 'youtube-nocookie.com';
			} else {
				return 'youtube.com';
			}
		}
	}
}

/**
 * Return menu ID by the location its assigned to
 *
 * @param  string $location
 * @since  5.9.1 Fixed empty menu locations undefined index.
 * @return int  $id
 */

if ( ! function_exists( 'mk_get_nav_id_by_location' ) ) {

	function mk_get_nav_id_by_location( $location ) {

			$locations = get_nav_menu_locations();

		if ( empty( $locations ) ) {
			return array();
		}

			$menu_obj = get_term( $locations[ $location ], 'nav_menu' );

			return $menu_obj->term_id;
	}
}

/**
 * Set logo position in the middle of menu
 *
 * @param  string $location
 * @return int  $id
 */

if ( ! function_exists( 'mk_insert_logo_middle_of_nav' ) ) {

	function mk_insert_logo_middle_of_nav( $nav_id, $menu, $logo ) {

		// Assign all first level menu item titles into array.
		$menu_items = wp_get_nav_menu_items( $nav_id );
		$titles = array();

		foreach ( (array) $menu_items as $key => $menu_item ) {
			$parent = $menu_item->menu_item_parent;

			if ( ! $parent ) {
				if ( 'wpml_ls_menu_item' != $menu_item->type ) {
					$title = $menu_item->title;
					$ID = $menu_item->ID;
					$DOM_ID = 'menu-item-' . $ID;
					$titles[ $DOM_ID ] = $title;
				}
			}
		}

			$insert_position = 0;
			$count_menu_items = count( $titles );

		if ( $count_menu_items % 2 == 0 ) :
			// IF MENU ITEMS ARE EVEN NUMBERED.
			$insert_position = $count_menu_items / 2;

		else :
			// IF MENU ITEMS ARE ODD NUMBERED
			// Count total lenght of letters.
			$letter_sum = 0;
			foreach ( $titles as $key => $title ) {
				$lenght = strlen( $title );
				$letter_sum = $letter_sum + $lenght;
			}

			// Get insert position for logo by finding a point closest to a half number of letters without breaking the word.
			// The word that is in the middle is divided by the center point and we compare both sides.
			// If left side is longer we set insert position after this word, otherwise before.
			$half_letter_sum = $letter_sum / 2;
			$left_half_sum = 0;
			$set_position = false;

			foreach ( $titles as $key => $title ) {
				$lenght = strlen( $title );

				if ( $left_half_sum < $half_letter_sum ) {
					$left_half_sum_before_addition = $left_half_sum;
					$left_half_sum = $left_half_sum + $lenght;

					// Check again after addition to see if we passed our center point.
					if ( $left_half_sum < $half_letter_sum ) {
						$insert_position++;
					} else {

						// When we reach to our center point check the last title left & right sides.
						// First set dividor to a number of letters that remain to reach to the center.
						$length_to_center = $half_letter_sum - $left_half_sum_before_addition;

						// To check if center point is on left or right side we check if it's smaller or higher from half title length.
						$half_title = $lenght / 2;

						// Set insert position after current title if center position is in right side of title or before when in left ( including exact center -
						// as we usually have icons on right so it makes more sence to balance menu items a little bit more onto left ).
						if ( $length_to_center > $half_title ) {
							$insert_position++;
							break;
						} else {
							break;
						}
					}
				}
			}

		endif;

		// Insert Logo.
		$menu_item_ids = array_keys( $titles );
		$menu_item_id = $menu_item_ids[ $insert_position ];
		$match_string = '<li id="' . $menu_item_id . '"';

			$menu = str_replace( $match_string, $logo . $match_string, $menu );

			return $menu;
	}
}// End if().

/**
 * Get the list of enteries from database
 * This function used in components/shortcodes//vc_map.php
 *
 * Usage Example:
 * mk_get_post_enteries('portfolio', 40)
 *
 * @param  string $post_type
 * @param int  $count
 * @return array
 * @deprecated : since v5.1
 */
if ( ! function_exists( 'mk_get_post_enteries' ) ) {

	function mk_get_post_enteries( $post_type = false, $count = 30 ) {
		if ( mk_page_is_vc_edit_form() ) {
			$post_type_enteries = get_posts( 'post_type=' . $post_type . '&orderby=title&numberposts=' . $count . '&order=ASC&suppress_filters=0' );

			if ( ! empty( $post_type_enteries ) ) {
				foreach ( $post_type_enteries as $key => $entry ) {
					$enteries[ $entry->ID ] = $entry->post_title;
				}
				return $enteries;
			}
		}
		return false;
	}
}

/**
 * Get the list of categories based on the taxonomy from database
 * This function used in components/shortcodes//vc_map.php
 *
 * Usage Example:
 * mk_get_category_enteries('product_cat', 50)
 *
 * @param  string $taxonomy
 * @param int  $count
 * @return array
 * @deprecated : since v5.1
 */
if ( ! function_exists( 'mk_get_category_enteries' ) ) {

	function mk_get_category_enteries( $taxonomy = 'category', $count = 50 ) {
		if ( mk_page_is_vc_edit_form() ) {
			$cat_enteries = get_categories( '&orderby=name&number=' . $count );

			if ( ! empty( $cat_enteries ) ) {
				foreach ( $cat_enteries as $key => $entry ) {
					$enteries[ $entry->term_id ] = $entry->name;
				}
				return $enteries;
			}
		}
		return false;
	}
}

/**
 * Get the list of pages from database
 * This function used in components/shortcodes//vc_map.php
 *
 * Usage Example:
 * mk_get_page_enteries(50)
 *
 * @param  string $taxonomy
 * @param int  $count
 * @return array
 * @deprecated : since v5.1
 */
if ( ! function_exists( 'mk_get_page_enteries' ) ) {

	function mk_get_page_enteries( $count = 50 ) {

		$page_enteries = get_pages( 'title_li=&orderby=name&number' . $count );

		if ( ! empty( $page_enteries ) ) {
			foreach ( $page_enteries as $key => $entry ) {
				$enteries['None'] = '*';
				$enteries[ $entry->post_title ] = $entry->ID;
			}
			return $enteries;
		}
				// }
				return false;
	}
}

/**
 * Get the list of users from database
 * This function used in components/shortcodes//vc_map.php
 *
 * Usage Example:
 * mk_get_authors(50)
 *
 * @param  string $taxonomy
 * @param int  $count
 * @return array
 * @deprecated : since v5.1
 */
if ( ! function_exists( 'mk_get_authors' ) ) {

	function mk_get_authors( $count = 50 ) {
		if ( mk_page_is_vc_edit_form() ) {
			$user_enteries = get_users(
				array(
					'number' => $count,
				)
			);

			if ( ! empty( $user_enteries ) ) {
				foreach ( $user_enteries as $user ) {
					$enteries[ $user->ID ] = $user->display_name;
				}
				return $enteries;
			}
		}
		return false;
	}
}




/**
 * Check if comments in pages is enabled/disbaled through theme options
 *
 * @return boolean
 */
if ( ! function_exists( 'mk_is_pages_comments_enabled' ) ) {

	function mk_is_pages_comments_enabled() {
		global $mk_options;

		if ( ! is_page() ) {
			return false;
		}

		if ( 'true' == $mk_options['pages_comments'] ) {
			return true;
		}
	}
}




/**
 * Used in views/layout/breadcrumbs.php
 */
if ( ! function_exists( 'mk_breadcrumbs_get_parents' ) ) {
	function mk_breadcrumbs_get_parents( $post_id = '', $separator = '/' ) {

				$parents = array();

		if ( 0 == $post_id ) {
			return $parents;
		}

		while ( $post_id ) {
			$page = get_page( $post_id );
			$parents[] = '<a href="' . esc_url( get_permalink( $post_id ) ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';
			$post_id = $page->post_parent;
		}

		if ( $parents ) {
			$parents = array_reverse( $parents );
		}

				return $parents;
	}
}

if ( ! function_exists( 'mk_get_theme_version' ) ) {
	/**
	 * Gets current jupiter version
	 *
	 * @return mixed|void
	 * @author      Ugur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.0.11
	 */
	function mk_get_theme_version() {
		return get_option( 'mk_jupiter_theme_current_version' );
	}
}

if ( ! function_exists( 'mk_str_contains' ) ) {
	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param       string       $haystack
	 * @param       string|array $needles
	 * @param       bool         $case_insensitive
	 * @return      bool
	 * @author      Uğur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.1.4
	 */
	function mk_str_contains( $haystack, $needles, $case_insensitive = false ) {
		foreach ( (array) $needles as $needle ) {
			if ( false == $case_insensitive ) {
				$pos = strpos( $haystack, $needle );
			} else {
				$pos = stripos( $haystack, $needle );
			}
			if ( '' != $needle && false !== $pos ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! function_exists( 'array2string' ) ) {
	/**
	 * Gets an array gives a readable string
	 *
	 * @param       $data
	 * @return      string
	 * @author      Uğur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.1.4
	 */
	function array2string( $data ) {

		$log_a = '';
		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				$log_a .= '[' . $key . '] => (' . array2string( $value ) . ") \n";
			} else {
				$log_a .= '[' . $key . '] => ' . $value . "\n";
			}
		}
		return $log_a;
	}
}

if ( ! function_exists( 'str_replace_last' ) ) {
	/**
	 * Only replaces the last occurrence of the specified string in the haystack
	 *
	 * @param $search
	 * @param $replace
	 * @param $subject
	 * @return      mixed
	 * @author      Uğur Mirza ZEYREK
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.1.4
	 */
	function str_replace_last( $search, $replace, $subject ) {
		$pos = strrpos( $subject, $search );

		if ( false !== $pos ) {
			$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}

		return $subject;
	}
}

if ( ! function_exists( 'mk_array_filter_key' ) ) {
	/**
	 * Filters a array by its keys using a callback.
	 * Thanks to https://gist.github.com/h4cc/8e2e3d0f6a8cd9cacde8
	 *
	 * @since 5.6
	 * @param $array array The array to filter
	 * @param $callback Callback The filter callback, that will get the key as first argument.
	 *
	 * @return array The remaining key => value combinations from $array.
	 */
	function mk_array_filter_key( array $array, $callback ) {
		$matchedKeys = array_filter( array_keys( $array ), $callback );
		return array_intersect_key( $array, array_flip( $matchedKeys ) );
	}
}

if ( ! function_exists( 'mk_get_option' ) ) {
	/**
	 * Get theme options value
	 *
	 * @author      Sofyan Sitorus
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.9.2
	 * @param       string $key Option key name.
	 * @param       string $default set default value for the option if key not exists.
	 * @return      string
	 */
	function mk_get_option( $key = null, $default = null ) {
		global $mk_options;
		if ( empty( $key ) || ! is_string( $key ) ) {
			return apply_filters( 'mk_get_option', $mk_options );
		}
		$value = isset( $mk_options[ $key ] ) ? $mk_options[ $key ] : $default;
		return apply_filters( 'mk_get_option_' . $key, $value );
	}
}

if ( ! function_exists( 'mk_maybe_json_decode' ) ) {
	/**
	 * Try to decode string data as JSON object
	 *
	 * @since 5.9.4
	 * @param string  $data Current value stored in db or default value defined.
	 * @param boolean $as_array Return as array.
	 * @return object
	 */
	function mk_maybe_json_decode( $data, $as_array = 0 ) {

		if ( is_string( $data ) ) {
			$data = json_decode( $data, $as_array );
			return ( json_last_error() === JSON_ERROR_NONE ) ? $data : false;
		}

		if ( is_object( $data ) ) {
			return $as_array ? get_object_vars( $data ) : $data;
		}

		if ( is_array( $data ) ) {
			return $as_array ? $data : (object) $data;
		}

		return false;
	}
}

if ( ! function_exists( 'mk_maybe_json_encode' ) ) {
	/**
	 * Try to encode data as JSON string
	 *
	 * @since 5.9.4
	 * @param array|object|string $data Data value need to encode.
	 */
	function mk_maybe_json_encode( $data ) {

		if ( is_array( $data ) || is_object( $data ) ) {
			return wp_json_encode( $data );
		}

		return $data;
	}
}

if ( ! function_exists( 'mk_array_key_matches_string' ) ) {
	/**
	 * Check if an array key matches a string.
	 *
	 * @since  5.9.4
	 * @param  string $string String to check against.
	 * @param  array  $array Array to check against.
	 * @return boolean
	 */
	function mk_array_key_matches_string( $string, $array ) {
		$keys = array_keys( $array );

		foreach ( $keys as $key ) {
			if ( strpos( $key, $string ) !== false ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! function_exists( 'mk_cz_get_option' ) ) {
	/**
	 * Retrieve option value.
	 *
	 * If the modification name does not exist, then the $default will be passed
	 * through {@link https://secure.php.net/sprintf sprintf()} PHP function with the first
	 * string the template directory URI and the second string the stylesheet
	 * directory URI.
	 *
	 * @since 5.9.4
	 *
	 * @param string      $name Option name.
	 * @param bool|string $default The fefault value.
	 * @return string
	 */
	function mk_cz_get_option( $name, $default = false ) {
		$options = get_option( 'mk_cz' );

		if ( isset( $options[ $name ] ) ) {
			return apply_filters( "mk_cz_option_{$name}", $options[ $name ] );
		}

		if ( is_string( $default ) ) {
			$default = sprintf( $default, get_template_directory_uri(), get_stylesheet_directory_uri() );
		}

		return apply_filters( "mk_cz_option_{$name}", $default );
	}
} // End if().

if ( ! function_exists( 'mk_shop_customizer_enabled' ) ) {
	/**
	 * If shop customizer is enabled.
	 *
	 * @since 6.1.3
	 */
	function mk_shop_customizer_enabled() {
		global $mk_options;

		if ( ! empty( $mk_options['shop_customizer'] ) && 'true' === $mk_options['shop_customizer'] ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'mk_decode' ) ) {
	/**
	 * Decode the data.
	 *
	 * @since 6.5.0
	 *
	 * @param string $data Data.
	 */
	function mk_decode( $data ) {
		if ( ! function_exists( 'jupiter_core_decode' ) ) {
			return $data;
		}

		return jupiter_core_decode( $data );
	}
}

if ( ! function_exists( 'mk_encode' ) ) {
	/**
	 * Encode the data.
	 *
	 * @since 6.5.0
	 *
	 * @param string $data Data.
	 */
	function mk_encode( $data ) {
		if ( ! function_exists( 'jupiter_core_encode' ) ) {
			return $data;
		}

		return jupiter_core_encode( $data );
	}
}

if ( ! function_exists( 'mk_minify_css' ) ) {
	/**
	 * Minify CSS.
	 *
	 * @since 6.5.3
	 *
	 * @param string $string string.
	 */
	function mk_minify_css( $css ) {
		if ( ! class_exists( 'SimpleCssMinifier' ) ) {
			return $css;
		}

		$minifier = new SimpleCssMinifier();
		return $minifier->minify( $css );
	}
}

if ( ! function_exists( 'mk_the_default_loop' ) ) {
	/**
	 * The default loop.
	 *
	 * @since 6.5.3
	 *
	 * @param string $string string.
	 */
	function mk_the_default_loop() {
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); ?>
				<h3 class="the-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h3>
				<?php the_content(); ?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" target="_self">READ MORE</span></a>
				<br><br><br>
			<?php endwhile;
		endif;
	}
}

if ( ! function_exists( 'mk_get_api_key' ) ) {
	/**
	 * Get API key.
	 *
	 * @since 6.8.0
	 *
	 * @param string.
	 */
	function mk_get_api_key() {
		return get_option( 'artbees_api_key' );
	}
}

if ( ! function_exists( 'mk_is_registered' ) ) {
	/**
	 * Check if theme registered.
	 *
	 * @since 6.8.0
	 *
	 * @param bool
	 */
	function mk_is_registered() {
		if ( ! empty( mk_get_api_key() ) ) {
			return true;
		}

		return false;
	}
}
