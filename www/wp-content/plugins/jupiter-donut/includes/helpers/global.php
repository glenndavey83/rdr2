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

if ( ! function_exists( 'is_html' ) ) {
	/**
	 * Checks if string contains HTML tags
	 *
	 * @param $string
	 * @return      bool
	 * @author      Zeljko Dzafic
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.3
	 */
	function is_html( $string ) {
		if ( $string != strip_tags( $string ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Return View port animation classes
 *
 * @return class   string
 */

if ( ! function_exists( 'get_viewport_animation_class' ) ) {

	function get_viewport_animation_class( $animation = false ) {

		if ( ! empty( $animation ) ) {
			return ' mk-animate-element ' . $animation . ' ';
		}
	}
}

if ( ! function_exists( 'mk_vc_parallax_scroll' ) ) {
	/**
	 * Generate data-parallax based on params.
	 *
	 * @since 5.9.7
	 *
	 * @param  boolean|string $pxs enabled or disabled.
	 * @param  number         $pxs_x Value for x position.
	 * @param  number         $pxs_y Value for y position.
	 * @param  number         $pxs_z Value for z position.
	 * @param  number         $pxs_smoothness Value for smoothness level.
	 * @return string         data attribute.
	 */
	function mk_vc_parallax_scroll( $pxs, $pxs_x, $pxs_y, $pxs_z, $pxs_smoothness ) {
		$parallax_scroll = [];

		if ( empty( $pxs ) || 'false' === $pxs ) {
			return;
		}

		if ( ! empty( $pxs_x ) ) {
			$parallax_scroll[] = '"x":' . $pxs_x;
		}

		if ( ! empty( $pxs_y ) ) {
			$parallax_scroll[] = '"y":' . $pxs_y;
		}

		if ( ! empty( $pxs_z ) ) {
			$parallax_scroll[] = '"z":' . $pxs_z;
		}

		if ( ! empty( $pxs_smoothness ) ) {
			$parallax_scroll[] = '"smoothness":' . $pxs_smoothness;
		}

		if ( empty( $parallax_scroll ) ) {
			return;
		}

		return 'data-parallax={' . implode( ',', $parallax_scroll ) . '}';
	}
}// End if().

if ( ! function_exists( 'mk_shadow_angle_parser' ) ) {

	/**
	 * Parses the angle to a usable distance value for box shadow css property
	 *
	 * @return      string
	 * @author      Michael Taheri
	 * @copyright   Artbees LTD (c)
	 * @link        http://artbees.net
	 * @since       Version 5.7
	 */
function mk_shadow_angle_parser( $angle, $distance ) {
	$angle = $angle * ( pi() / 180 );
	$x = round( $distance * cos( $angle ) );
	$y = round( $distance * sin( $angle ) );
	return $x . 'px ' . $y . 'px';
}
}

/**
 * Return background image size class when the param is true
 *
 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
 *
 * @return class   string
 */

if ( ! function_exists( 'mk_get_bg_cover_class' ) ) {

	function mk_get_bg_cover_class( $val ) {

		if ( 'true' == $val ) {
			return 'mk-background-stretch';
		}
	}
}

/**
 * Insert inline styles for node based on class. Run checks if they are declared
 *
 * @return styles string
 * ==================================================================================
 */

if ( ! function_exists( 'mk_insert_style' ) ) {

	function mk_insert_style( $class, $style ) {
		if ( ! array_key_exists( $class, $style ) ) {
			return;
		}
		return $style[ $class ];
	}
}

/**
 * Checks the header for given source. If it's a valid image returns getimagesize array.
 * Otherwise returns another getimagesize array for 1x1 empty.png image.
 *
 * Usage Example:
 * mk_getimagesize('http://jupiter-v5.dev/wp-content/themes/jupiter-v5/images/empty.png');
 *
 * @param  string $location
 * @return int  $id
 * @author      Ugur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0
 * @last_update Version 5.0.6
 *
 * TODO:: Create a report section for missing requests to the images.
 * TODO:: Add svg
 *
 *   $svgfile = simplexml_load_file("svgimage.svg");
 *   $width = substr($svgfile[width],0,-2);
 *   $height = substr($svgfile[height],0,-2);
 *
 *   'image/svg+xml'
 */

if ( ! function_exists( 'mk_getimagesize' ) ) {

	function mk_getimagesize( $src ) {
		global $total_time;

		$time = microtime();
		$time = explode( ' ', $time );
		$time = $time[1] + $time[0];
		$start = $time;

		$return = '';
		$return_transient = 'mk_getimagesize_';
		$return_transient .= sha1( $src );
		$return = get_transient( $return_transient );
		if ( ! is_array( $return ) ) {
			global $wp_filesystem;

			$mkfs = new Mk_Fs();
			if ( $mkfs->wp_filesystem instanceof WP_Filesystem_Base ) {
				$wp_filesystem = $mkfs->wp_filesystem;
			}

			// if the image is local.
			$home_url = esc_url( get_home_url( '/' ) );
			$home_path = esc_url( get_home_path() );
			$src_directory = str_replace( array( $home_url ), array( $home_path ), $src );

			try {
				if ( file_exists( $src_directory ) and $src_directory != $src ) {
					$return = @getimagesize( $src_directory );
				}
			} catch ( Exception $e ) {

			}
			// if the image is local
			// if the image is not local.
			$return = mk_curl_getimage_dimensions( $src );

			// if our special curl function fails try with wp_get_http_headers one more time.
			if ( ! is_array( $return ) ) {
				$remote_file = wp_get_http_headers( $src );
				if ( ! mk_is_image( $remote_file['content-type'] ) ) {
					$return = array( '', '', '' );
				}

				if ( ! is_array( $return ) ) {
					try {
						$return = @getimagesize( $src );
					} catch ( Exception $e ) {

					}
				}
			}

			// Get any existing copy of our transient data.
			if ( is_array( $return ) ) {
				// It wasn't there, so regenerate the data and save the transient.
				set_transient( $return_transient, $return, 15 * 60 );
			}
		}// End if().

		if ( '' == $return ) {
			$return = array( '', '', '' );
		}

		$time = microtime();
		$time = explode( ' ', $time );
		$time = $time[1] + $time[0];
		$finish = $time;
		$function_time = round( ($finish - $start), 4 );
		$total_time += $function_time;
		return $return;

	}
}// End if().

/**
 * Uses mk_curl_getimage for downloading image and gets the image dimensions
 * Returns false if it's corrupt or not proper image.
 *
 * @source
 * @author      Uğur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0.6
 * @last_update Version 5.0.6
 */
if ( ! function_exists( 'mk_curl_getimage_dimensions' ) ) {
	function mk_curl_getimage_dimensions( $url ) {

		if ( mk_is_curl_active() == false ) {
			return false;
		}

		$raw = mk_curl_getimage( $url );
		if ( false == $raw ) {
			return false;
		}

		$file_ext = strtolower( pathinfo( parse_url( $url )['path'], PATHINFO_EXTENSION ) );
		if ( 'png' == $file_ext ) {
			// The identity for a PNG is 8Bytes (64bits)long.
			$ident = unpack( 'Nupper/Nlower', $raw );
			// Make sure we get PNG.
			if ( 0x89504E47 !== $ident['upper'] || 0x0D0A1A0A !== $ident['lower'] ) {
				return false;
			}
			// Get rid of the first 8 bytes that we processed.
			$data = substr( $raw, 8 );
			// Grab the first chunk tag, should be IHDR.
			$chunk = unpack( 'Nlength/Ntype', $data );
			// IHDR must come first, if not we return false.
			if ( 0x49484452 === $chunk['type'] ) {
				// Get rid of the 8 bytes we just processed.
				$data = substr( $data, 8 );
				// Grab our x and y.
				$info = unpack( 'NX/NY', $data );
				// Return in common format.
				return array( $info['X'], $info['Y'] );
			} else {
				return false;
			}
		}

		if ( 'jpg' == $file_ext || 'jpeg' == $file_ext ) {
			$im = @imagecreatefromstring( $raw );
			if ( $im ) {
				$width = imagesx( $im );
				$height = imagesy( $im );
				return array( $width, $height, '' );
			}
		}

		if ( 'gif' == $file_ext ) {
			// The identity for a GIF is 6bytes (48Bits)long.
			$ident = unpack( 'nupper/nmiddle/nlower', $raw );
			// Make sure we get GIF 87a or 89a.
			if ( 0x4749 !== $ident['upper'] || 0x4638 !== $ident['middle'] ||
				( 0x3761 !== $ident['lower'] && 0x3961 !== $ident['lower']) ) {
				return false;
			}
			// Get rid of the first 6 bytes that we processed.
			$data = substr( $raw, 6 );
			// Grab our x and y, GIF is little endian for width and length.
			$info = unpack( 'vX/vY', $raw );
			// Return in common format.
			return array( $info['X'], $info['Y'] );
		}

		return false;

	}
}// End if().

/**
 * Checks if curl is available
 *
 * @source
 * @author      Uğur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0.6
 * @last_update Version 5.0.6
 */
if ( ! function_exists( 'mk_is_curl_active' ) ) {
	function mk_is_curl_active() {
		if ( is_callable( 'curl_init' ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Gets the only necessary bytes for given url and checks the image content type
 * Returns false if it's not JPG, PNG or GIF
 *
 * @author      Uğur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0.6
 * @last_update Version 5.0.6
 */
if ( ! function_exists( 'mk_curl_getimage' ) ) {
	function mk_curl_getimage( $url ) {
		$file_ext = strtolower( pathinfo( parse_url( $url )['path'], PATHINFO_EXTENSION ) );
		// Returns undefined in some cases.
		$range = '32768';

		if ( 'png' == $file_ext ) {
			$range = '24';
		} elseif ( 'gif' == $file_ext ) {
			$range = '10';
		} elseif ( 'jpeg' == $file_ext || 'jpg' == $file_ext ) {
			$range = '32768';
		}

		$headers = array(
			'Range: bytes=0-' . $range,
		);

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
		$data = curl_exec( $curl );
		$contentType = curl_getinfo( $curl, CURLINFO_CONTENT_TYPE );
		$curl_info = curl_getinfo( $curl );
		$curl_info['file_ext'] = $file_ext;
		if ( mk_is_image( $contentType ) == false ) {
			return false;
		}
		curl_close( $curl );
		return $data;
	}
}

/**
 * Check image content types Returns false if it's not JPG, PNG or GIF
 *
 * @source
 * @author      Uğur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0.6
 * @last_update Version 5.0.6
 */
if ( ! function_exists( 'mk_is_image' ) ) {
	function mk_is_image( $content_type ) {
		$image_extensions = array( 'image/jpeg', 'image/gif', 'image/png' );

		if ( in_array( $content_type, $image_extensions ) ) {
			return true;
		}

		return false;
	}
}

/**
 * Generate gradient angles based on the options provided
 *
 * @return angles   array
 */

if ( ! function_exists( 'mk_gradient_option_parser' ) ) {

	function mk_gradient_option_parser( $style, $angle ) {
		$output = array();
		if ( $style == 'linear' ) {
			$output['type'] = $style;
			switch ( $angle ) {
				case 'vertical':
					$output['angle_1'] = 'top,';
					$output['angle_2'] = 'to bottom,';
					$output['name'] = 'vertical';
					break;

				case 'horizontal':
					$output['angle_1'] = 'left,';
					$output['angle_2'] = 'to right,';
					$output['name'] = 'horizontal';
					break;

				case 'diagonal_left_bottom':
					$output['angle_1'] = 'top left,';
					$output['angle_2'] = 'to bottom right,';
					$output['name'] = 'diagonal_left_bottom';
					break;

				case 'diagonal_left_top':
					$output['angle_1'] = 'bottom left,';
					$output['angle_2'] = 'to top right,';
					$output['name'] = 'diagonal_left_top';
					break;
			}
		} elseif ( 'radial' == $style ) {
			$output['type'] = $style;
			$output['angle_1'] = '';
			$output['angle_2'] = '';
		}
		return $output;
	}
}// End if().

/**
 * Converts a super link output to an actual link and returns.
 *
 * @param string  $link  link type | type id
 * @return string
 */

if ( ! function_exists( 'mk_get_super_link' ) ) {
	function mk_get_super_link( $link = false, $get_permalink = true ) {
		$permalink = '';
		if ( ! empty( $link ) ) {
			$link_array = explode( '||', $link );
			switch ( $link_array[0] ) {
				case 'page':
					$permalink = get_page_link( $link_array[1] );
					break;

				case 'cat':
					$permalink = get_category_link( $link_array[1] );
					break;

				case 'portfolio':
					$permalink = esc_url( get_permalink( $link_array[1] ) );
					break;

				case 'post':
					$permalink = esc_url( get_permalink( $link_array[1] ) );
					break;

				case 'manually':
					$permalink = $link_array[1];
					break;
			}
		}
		if ( empty( $permalink ) && $get_permalink == true ) {
			return esc_url( get_permalink() );
		}
		return $permalink;
	}
}

/**
 * Converts column number to class
 *
 * @return class   string
 */

if ( ! function_exists( 'mk_get_column_class' ) ) {

	function mk_get_column_class( $column = 4 ) {

			return 'jupiter-donut-' . $column . 'col';
	}
}


/**
 * Get portfolio lightbox url based on post type
 *
 * @return style   string
 */

if ( ! function_exists( 'mk_get_portfolio_lightbox_url' ) ) {
	function mk_get_portfolio_lightbox_url( $post_type = 'image' ) {
		switch ( $post_type ) {
			case 'image':
				$src_array = wp_get_attachment_image_src( get_post_thumbnail_id() , 'full', true );
				$src = $src_array[0];
				break;

			case 'video':
								$video_id = get_post_meta( get_the_ID() , '_single_video_id', true );
				$video_site = get_post_meta( get_the_ID() , '_single_video_site', true );

				switch ( get_post_meta( get_the_ID() , '_single_video_site', true ) ) {
					case 'vimeo':
						$src = '//player.vimeo.com/video/' . $video_id . '?autoplay=0';
						break;

					case 'youtube':
						$src = '//www.youtube.com/embed/' . $video_id . '?autoplay=0';
						break;

					case 'dailymotion':
						$src = '//www.dailymotion.com/embed/video/' . $video_id . '?logo=0';
						break;
				}

				break;
		}
		return $src;
	}
}

/**
 * Check WooCommerce version.
 *
 * @since 5.9.4
 * @param string $version The version you want to check for.
 */
function mk_is_woocommerce_version( $version = '3.0' ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Gets blog post thumbnail conditionally from blog slideshow type if no featured image is provided.
 */
if ( ! function_exists( 'mk_get_blog_post_thumbnail' ) ) {
	function mk_get_blog_post_thumbnail( $post_type = 'image' ) {
		global $post;

		if ( 'portfolio' == $post_type ) {

			if ( has_post_thumbnail() ) {

				$attachment_id = get_post_thumbnail_id();

			} else {
				$attachment_id = get_post_meta( $post->ID, '_gallery_images', true );
				$attachment_id = explode( ',', $attachment_id );
				$attachment_id = $attachment_id[0];
			}
		} else {
			$attachment_id = get_post_thumbnail_id();
		}

				return $attachment_id;

	}
}

/**
 * Get HB header list.
 *
 * @since 6.0.0
 *
 * @return array Header builder list in array with key and title.
 */
function mkhb_get_header_list() {
	// Get Global Header post.
	$fallback_id   = get_option( 'mkhb_global_header', 'nothing' );
	$fallback_post = get_post( $fallback_id );

	// Get data from DB.
	$posts = get_posts( array(
		'post_type'   => 'mkhb_header',
		'post_status' => 'publish',
		'numberposts' => -1,
		'orderby'     => 'title',
		'order'       => 'ASC',
		'exclude'     => $fallback_id,
	) );

	// Insert Global Header to the list in the first place.
	if ( null !== $fallback_post ) {
		array_unshift( $posts, $fallback_post );
	}

	// Set header options.
	$options = array();
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $header ) {
			$options[ $header->ID ] = $header->post_title;

			// If the header is the global header, add prefix Global Header.
			if ( absint( $header->ID ) === absint( $fallback_id ) ) {
				/* translators: %s: page title */
				$options[ $header->ID ] = sprintf( __( 'Global Header - %s', 'jupiter-donut' ), $header->post_title );
			}
		}
	}

	// If the options generated is empty, set default option with "No header found" option.
	if ( empty( $options ) ) {
		$options = array(
			0 => __( 'No header found', 'jupiter-donut' ),
		);
	}

	return $options;
}

if ( ! function_exists( 'mk_plain_txt_to_link' ) ) {
	/**
	 * This function converts simple links in tweeter post type to clickable link.
	 *
	 * @since 1.0.3
	 *
	 * @param  string $plain_text twitter post cntent.
	 * @return string Simple string with <a> tag.
	 */
	function mk_plain_txt_to_link( $plain_text ) {
		// The Regular Expression filter.
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$text = $plain_text;

		// Check if there is a url in the text.
		if( preg_match( $reg_exUrl, $text, $url ) ) {

			// Make the urls hyper links.
			return preg_replace( $reg_exUrl, '<a target="_blank" href="' . $url[0] . '" >' . $url[0] . '</a> ', $text );
		} else {

			// If no urls in the text just return the text.
			return $text;

		}
	}
}

/**
 * Its intended to get the right domain for GDPR purposes.
 *
 * @param  name   The third party website domain name
 * @return domain string
 */
if ( ! function_exists( 'mk_get_third_party_domain_name' ) ) {
	function mk_get_third_party_domain_name( $name ) {
		if ( 'youtube' == $name ) {
			if ( 'true' == jupiter_donut_get_option( 'third_party_gdpr' ) ) {
				return 'youtube-nocookie.com';
			} else {
				return 'youtube.com';
			}
		}
	}
}
