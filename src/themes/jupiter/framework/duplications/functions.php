<?php

/**
 * These functions are duplicated from Jupiter Donut plugin to handle compatibility when the plugin is inactive.
 * Any changes in these functions, should be applied properly in the associated function in the plugin.
 *
 * @since 6.5.3
 */

defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'mk_color' ) ) {
	/*
	 * Converts Hex value to RGBA if needed.
	 */
	function mk_color( $colour, $alpha ) {
		if ( ! empty( $colour ) ) {
			if ( $alpha >= 0.95 ) {
				return $colour;

				// If alpha is equal 1 no need to convert to RGBA, so we are ok with it. :)
			} else {
				if ( $colour[0] == '#' ) {
					$colour = substr( $colour, 1 );
				}
				if ( strlen( $colour ) == 6 ) {
					list($r, $g, $b) = array(
						$colour[0] . $colour[1],
						$colour[2] . $colour[3],
						$colour[4] . $colour[5],
					);
				} elseif ( strlen( $colour ) == 3 ) {
					list($r, $g, $b) = array(
						$colour[0] . $colour[0],
						$colour[1] . $colour[1],
						$colour[2] . $colour[2],
					);
				} else {
					return false;
				}
				$r      = hexdec( $r );
				$g      = hexdec( $g );
				$b      = hexdec( $b );
				$output = array(
					'red'   => $r,
					'green' => $g,
					'blue'  => $b,
				);

				return 'rgba(' . implode( ',', $output ) . ',' . $alpha . ')';
			} // End if().
		} // End if().
	}
}

if ( ! function_exists( 'global_get_post_id' ) ) {
	function global_get_post_id() {
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() && is_shop() ) {

			return wc_get_page_id( 'shop' );
		} elseif ( is_singular() ) {
			global $post;

			return $post->ID;
		} elseif ( is_home() ) {

			$page_on_front = get_option( 'page_on_front' );
			$show_on_front = get_option( 'show_on_front' );

			if ( $page_on_front == 'page' && ! empty( $page_on_front ) ) {
				global $post;
				return $post->ID;
			} else {
				return false;
			}
		} else {

			return false;
		}
	}
}

if ( ! function_exists( 'hexDarker' ) ) {
	function hexDarker( $hex, $factor = 30 ) {
		$new_hex = '';
		if ( $hex == '' || $factor == '' ) {
			return false;
		}

		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$hex = $hex . $hex;
		}

		$base['R'] = hexdec( $hex[0] . $hex[1] );
		$base['G'] = hexdec( $hex[2] . $hex[3] );
		$base['B'] = hexdec( $hex[4] . $hex[5] );

		foreach ( $base as $k => $v ) {
			$amount      = $v / 100;
			$amount      = round( $amount * $factor );
			$new_decimal = $v - $amount;

			$new_hex_component = dechex( $new_decimal );
			if ( strlen( $new_hex_component ) < 2 ) {
				$new_hex_component = '0' . $new_hex_component;
			}
			$new_hex .= $new_hex_component;
		}

		return '#' . $new_hex;
	}
}

if ( ! function_exists( 'mk_get_bg_cover_class' ) ) {
	/**
	 * Return background image size class when the param is true
	 *
	 * @return class   string
	 */
	function mk_get_bg_cover_class( $val ) {

		if ( 'true' == $val ) {
			return 'mk-background-stretch';
		}
	}
}

if ( ! function_exists( 'mk_get_template_part' ) ) {
	/**
	 * Like get_template_part() put lets you pass args to the template file
	 * Args are available in the tempalte as $view_params array
	 *
	 * @param string filepart
	 * @param mixed wp_args style argument list
	 *
	 * @since 5.0.0
	 * @since 5.9.1 Refactored the function to improve performance.
	 */
	function mk_get_template_part( $file, $view_params = array() ) {
		global $post;
		$view_params = wp_parse_args( $view_params );

		if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) ) {
			$file_path = ( get_stylesheet_directory() . '/' . $file . '.php' );
		} elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) ) {
			$file_path = realpath( get_template_directory() . '/' . $file . '.php' );
		}

		if ( empty( $file_path ) ) {
			return;
		}

		ob_start();
		require( $file_path );
		$output = ob_get_clean();
		echo $output;
	}
}

if ( ! function_exists( 'mk_get_header_view' ) ) {
	/**
	 * Get header components and put them together. this function passes variables into the file too.
	 *
	 * @param string    $slug
	 * @param string    $name
	 * @param boolean   $return
	 * @return object
	 */
	function mk_get_header_view( $location, $component, $view_params = array(), $return = false ) {
		if ( $return ) {
			ob_start();
			mk_get_template_part( 'views/header/' . $location . '/' . $component, $view_params );
			return ob_get_clean();
		} else {
			mk_get_template_part( 'views/header/' . $location . '/' . $component, $view_params );
		}

	}
}

if ( ! function_exists( 'mk_get_view' ) ) {
	/**
	 * Get template parts from views folder
	 *
	 * @param string    $slug
	 * @param string    $name
	 * @param boolean   $return
	 * @return object
	 */
	function mk_get_view( $slug, $name = '', $return = false, $view_params = array() ) {
		if ( $return ) {
			ob_start();
			mk_get_template_part( 'views/' . $slug . '/' . $name, $view_params );
			return ob_get_clean();
		} else {
			mk_get_template_part( 'views/' . $slug . '/' . $name, $view_params );
		}
	}
}

if ( ! function_exists( 'mk_get_shortcode_view' ) ) {
	/**
	 * Get template parts from shortcodes folder
	 *
	 * @param string    $slug
	 * @param string    $name
	 * @param boolean   $return
	 * @return object
	 */
	function mk_get_shortcode_view( $shortcode_name, $name = '', $return = false, $view_params = array() ) {
		$name = sanitize_text_field( $name );

		if ( is_dir( get_template_directory() . '/components/shortcodes/' . $shortcode_name ) ) {
			$directory = 'components';
		}

		if ( empty( $directory ) ) {
			return;
		}

		if ( $return ) {
			ob_start();
			mk_get_template_part( $directory . '/shortcodes/' . $shortcode_name . '/' . $name, $view_params );
			return ob_get_clean();
		} else {
			mk_get_template_part( $directory . '/shortcodes/' . $shortcode_name . '/' . $name, $view_params );
		}

	}
}

if ( ! function_exists( 'get_schema_markup' ) ) {
	/**
	 * Schema.org addtions for better SEO
	 *
	 * @param   string  Type of the element
	 * @return  string  HTML Attribute
	 */
	function get_schema_markup( $type, $echo = false ) {

		if ( empty( $type ) ) {
			return false;
		}

			$attributes = '';
		$attr = array();

		switch ( $type ) {
			case 'body':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WebPage';
				break;

			case 'header':
				$attr['role'] = 'banner';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPHeader';
				break;

			case 'nav':
				$attr['role'] = 'navigation';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/SiteNavigationElement';
				break;

			case 'title':
				$attr['itemprop'] = 'headline';
				break;

			case 'sidebar':
				$attr['role'] = 'complementary';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPSideBar';
				break;

			case 'footer':
				$attr['role'] = 'contentinfo';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/WPFooter';
				break;

			case 'main':
				$attr['role'] = 'main';
				$attr['itemprop'] = 'mainContentOfPage';
				if ( is_search() ) {
					$attr['itemtype'] = 'https://schema.org/SearchResultsPage';
				}

				break;

			case 'author':
				$attr['itemprop'] = 'author';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'person':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'comment':
				$attr['itemprop'] = 'comment';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Comment';
				break;

			case 'comment_author':
				$attr['itemprop'] = 'creator';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'comment_author_link':
				$attr['itemprop'] = 'creator';
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Person';
				$attr['rel'] = 'external nofollow';
				break;

			case 'comment_time':
				$attr['itemprop'] = 'datePublished';
				$attr['itemscope'] = 'itemscope';
				$attr['datetime'] = get_the_time( 'c' );
				break;

			case 'comment_text':
				$attr['itemprop'] = 'text';
				break;

			case 'author_box':
				$attr['itemprop'] = 'author';
				$attr['itemtype'] = 'https://schema.org/Person';
				break;

			case 'video':
				$attr['itemprop'] = 'video';
				$attr['itemtype'] = 'https://schema.org/VideoObject';
				break;

			case 'audio':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/AudioObject';
				break;

			case 'blog':
				$attr['itemscope'] = 'itemscope';
				$attr['itemtype'] = 'https://schema.org/Blog';
				break;

			case 'blog_posting':
				$attr['itemscope'] = 'itemscope';
				$attr['itemprop'] = 'blogPost';
				$attr['itemtype'] = 'http://schema.org/BlogPosting';
				break;

			case 'name':
				$attr['itemprop'] = 'name';
				break;

			case 'url':
				$attr['itemprop'] = 'url';
				break;

			case 'email':
				$attr['itemprop'] = 'email';
				break;

			case 'job':
				$attr['itemprop'] = 'jobTitle';
				break;

			case 'post_time':
							$attr['itemprop'] = 'datePublished';
				$attr['datetime'] = get_the_time( 'c', $args['id'] );
				break;

			case 'post_title':
				$attr['itemprop'] = 'headline';
				break;

			case 'post_content':
				$attr['itemprop'] = 'text';
				break;
			case 'publisher':
				$attr['itemprop'] = 'publisher';
				$attr['itemtype'] = 'https://schema.org/Organization';
				break;
		}

		foreach ( $attr as $key => $value ) {
			$attributes .= $key . '="' . $value . '" ';
		}

		if ( $echo ) {
			echo $attributes;
		} else {
			return $attributes;
		}
	}
}

if ( ! function_exists( 'mk_structured_data_img_attr' ) ) {

	/**
	 * This function adds itemprop = image proprety to image attachments and by doing so improves structured data of a page
	 *
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 *
	 *
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */
	function mk_structured_data_img_attr( $attr ) {

		$attr['itemprop'] = 'image';
		return $attr;
	}
}

if ( ! function_exists( 'mk_structured_data_post_meta_hidden' ) ) {
	/**
	 * This function will add structured markup data to blog post
	 * all data is wrapped with single hidden container
	 * data that is added contain (author,datePublished, dateModified,publisher,logo, image)
	 *
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */
	function mk_structured_data_post_meta_hidden() {
		global $post;
		global $structured_data_headline;
		global $structured_data_datePublished;
		global $structured_data_dateModified;
		global $structured_data_publisher;
		global $structured_data_image;
		$post_id = $post->ID;

		if ( function_exists( 'has_post_thumbnail' ) ) {
			if ( has_post_thumbnail( $post_id ) ) {
				$thumbnail = get_the_post_thumbnail_url( $post_id );
			}
		}

		$thumbnail = ( ! empty( $thumbnail )) ? $thumbnail : '';

		echo '<div class="mk-post-meta-structured-data" style="display:none;visibility:hidden;">';
		if ( empty( $structured_data_headline ) ) {
			echo '<span itemprop="headline">' . get_the_title() . '</span>';
			$structured_data_headline = true;
		}
		if ( empty( $structured_data_datePublished ) ) {
			echo '<span itemprop="datePublished">' . get_the_date( 'Y-m-d' ) . '</span>';
			$structured_data_datePublished = true;
		}
		if ( empty( $structured_data_dateModified ) ) {
			echo '<span itemprop="dateModified">' . get_the_modified_date( 'Y-m-d' ) . '</span>';
			$structured_data_dateModified = true;
		}
		if ( empty( $structured_data_publisher ) ) {
			echo  '<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
				echo  '<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
					echo  '<span itemprop="url"></span>';
				echo  '</span>';
				echo  '<span itemprop="name">' . get_bloginfo( 'name' ) . '</span>';
			echo  '</span>';
			$structured_data_publisher = true;
		}
		if ( empty( $structured_data_image ) ) {
			echo '<span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
				echo '<span itemprop="contentUrl url">' . $thumbnail . '</span>';
			echo '<span  itemprop="width">200px</span>';
			echo '<span itemprop="height">200px</span>';
			echo  '</span>';
			$structured_data_image = true;
		}
		echo '</div>';
	}
}
