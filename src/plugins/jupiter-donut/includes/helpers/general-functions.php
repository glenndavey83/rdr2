<?php
/**
 * General frontend functions
 *
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 1.0
 * @package     artbees
 */

defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'hexDarker' ) ) {
	/**
	 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
	 */
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

/**
 * Return default and custom image sizes
 *
 * @since 5.0
 *
 * @return array $image_sizes
 */

if ( ! function_exists( 'mk_get_image_sizes' ) ) {
	function mk_get_image_sizes( $reverse_array = false, $crop = true, $external_size = false ) {

		$image_sizes = array();

		if ( $external_size ) {
			$image_sizes[ $external_size ] = sanitize_title( $external_size );
		}

		if ( $crop ) {
			$image_sizes[ __( 'Resize & Crop (Not Recommended)', 'jupiter-donut' ) ] = 'crop';
		}

		$image_sizes[ __( 'Default - Original Size', 'jupiter-donut' ) ] = 'full';
		$image_sizes[ __( 'Default - Large Size', 'jupiter-donut' ) ]    = 'large';
		$image_sizes[ __( 'Default - Medium Size', 'jupiter-donut' ) ]   = 'medium';

		$custom_image_sizes = get_option( 'Jupiter_image_sizes' );
		if ( ! empty( $custom_image_sizes ) ) {
			foreach ( $custom_image_sizes as $size ) {
				$custom_sizes[ 'Custom - ' . $size['size_n'] ] = $size['size_n'];
			}
			$sizes = $image_sizes + $custom_sizes;
		} else {
			$sizes = $image_sizes;
		}
		foreach ( $sizes as $key => $value ) {
			if ( $reverse_array ) {
				$final_array[ $value ] = $key;
			} else {
				$final_array[ $key ] = $value;
			}
		}
		return $final_array;
	}
} // End if().

function mk_get_attachment_id_from_url( $attachment_url = '' ) {

	global $wpdb;
	$attachment_id = false;

	// If there is no url, return.
	if ( '' == $attachment_url ) {
		return;
	}

	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	// if (false !== strpos($attachment_url, $upload_dir_paths['baseurl'])) {
	// If this is the URL of an auto-generated thumbnail, get the URL of the original image
	$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

	// Remove the upload path base directory from the attachment URL
	// $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);
	$attachment_url = strstr( $attachment_url, 'uploads' );
	$attachment_url = str_replace( 'uploads/', '', $attachment_url );

	// return $attachment_url;
	// Finally, run a custom database query to get the attachment ID from the modified attachment URL
	$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	// }
	return $attachment_id;
}

function mk_get_fontfamily( $element_name, $id, $font_family, $font_type ) {
	$output = '';
	if ( $font_type == 'google' ) {
		if ( ! function_exists( 'my_strstr' ) ) {
			function my_strstr( $haystack, $needle, $before_needle = false ) {
				if ( ! $before_needle ) {
					return strstr( $haystack, $needle );
				} else {
					return substr( $haystack, 0, strpos( $haystack, $needle ) );
				}
			}
		}
		wp_enqueue_style( $font_family, '//fonts.googleapis.com/css?family=' . $font_family . ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900', false, false, 'all' );
		$format_name = strpos( $font_family, ':' );
		if ( $format_name !== false ) {
			$google_font = my_strstr( str_replace( '+', ' ', $font_family ), ':', true );
		} else {
			$google_font = str_replace( '+', ' ', $font_family );
		}
		$output .= '<style>' . $element_name . $id . ' {font-family: "' . $google_font . '"}</style>';
	} elseif ( $font_type == 'safefont' ) {
		$output .= '<style>' . $element_name . $id . ' {font-family: ' . $font_family . ' !important}</style>';
	}

	return $output;
}

if ( ! function_exists( 'global_get_post_id' ) ) {
	/**
	 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
	 */
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

/*
 * Converts Hex value to RGBA if needed.
 *
 * Note: Same function exists in Jupiter theme. Any changes here, should be applied properly in the same function.
 */
if ( ! function_exists( 'mk_color' ) ) {
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
} // End if().

/**
 * Retrieves Portfolio Categories
 *
 * @param string  $id   current post ID
 * @param string  $link to return link or text.
 */

if ( ! function_exists( 'mk_get_custom_tax' ) ) {
	function mk_get_custom_tax( $id, $tax, $link = true, $slug = false ) {

		if ( empty( $id ) ) {
			return;
		}

		$terms      = get_the_terms( $id, $tax . '_category' );
		$terms_slug = array();
		$terms_name = array();
		if ( is_array( $terms ) && ! empty( $terms ) ) {
			if ( $link == true ) {
				foreach ( $terms as $term ) {
					$terms_name[] = '<a href="' . get_term_link( $term->slug, $tax . '_category' ) . '">' . $term->name . '</a>';
				}
			} else {
				foreach ( $terms as $term ) {
					if ( $slug ) {
						$terms_name[] = $term->slug;
					} else {
						$terms_name[] = $term->name;
					}
				}
			}
			return $terms_name;
		}
		return array();
	}
}

/**
 * Deletes cache files and transients
 *
 * @param bool $remove
 *
 * @author      Bob Ulusoy & UÄŸur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0
 * @last_update Version 5.1.4
 */
function mk_purge_cache_actions( $remove = false ) {

	$static = new Mk_Static_Files( false );
	$static->DeleteThemeOptionStyles( $remove );
	$static->delete_transient_mk_getimagesize();
	$static->delete_transient_mk_critical_path_css();

	$static->prevent_cache_plugins();
	mk_clear_cache_plugins();
}

if ( ! function_exists( 'mk_clear_super_cache' ) ) {
	function mk_clear_cache_plugins() {
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
			// echo 'W3 Total Cache: Page cache flushed';
		} elseif ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
			// echo 'WP Super Cache: Page cache cleared.';
		} elseif ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
			// echo 'WP Rocket: Domain cache purged.';
		} else {
			// echo 'No known caching plugin detected!';
		}
		// die;
	}
}

/*
Uses get_the_excerpt() to print an excerpt by specifying a maximium number of characters.
 */
if ( ! function_exists( 'mk_excerpt_max_charlength' ) ) {
	function mk_excerpt_max_charlength( $charlength ) {
		$excerpt = get_the_excerpt();
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = -(mb_strlen( $exwords[ count( $exwords ) - 1 ] ));
			if ( $excut < 0 ) {
				echo mb_substr( $subex, 0, $excut );
			} else {
				echo $subex;
			}
			echo '[...]';
		} else {
			echo $excerpt;
		}
	}
}

/* Convert hexdec color string to rgb(a) string */
function mk_hex2rgba( $color, $opacity = false ) {

	$default = 'rgb(0,0,0)';

	// Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 );
	}

	// Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
		$hex = array(
			$color[0] . $color[1],
			$color[2] . $color[3],
			$color[4] . $color[5],
		);
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array(
			$color[0] . $color[0],
			$color[1] . $color[1],
			$color[2] . $color[2],
		);
	} else {
		return $default;
	}

	// Convert hexadec to rgb
	$rgb = array_map( 'hexdec', $hex );

	// Check if opacity is set(rgba or rgb)
	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	// Return rgb(a) color string
	return $output;
}

if ( ! function_exists( 'mk_ago' ) ) {
	function mk_ago( $time ) {
		$periods = array(
			__( 'second', 'jupiter-donut' ),
			__( 'minute', 'jupiter-donut' ),
			__( 'hour', 'jupiter-donut' ),
			__( 'day', 'jupiter-donut' ),
			__( 'week', 'jupiter-donut' ),
			__( 'month', 'jupiter-donut' ),
			__( 'year', 'jupiter-donut' ),
			__( 'decade', 'jupiter-donut' ),
		);
		$lengths = array(
			'60',
			'60',
			'24',
			'7',
			'4.35',
			'12',
			'10',
		);

		$now = time();

		$difference = $now - $time;
		$tense      = __( 'ago', 'jupiter-donut' );

		for ( $j = 0; $difference >= $lengths[ $j ] && $j < count( $lengths ) - 1; $j++ ) {
			$difference /= $lengths[ $j ];
		}

		$difference = round( $difference );

		if ( $difference != 1 ) {
			$periods[ $j ] .= 's';
		}

		return "$difference $periods[$j] {$tense} ";
	}
} // End if().

if ( ! function_exists( 'mk_get_wpml_media' ) ) {
	/**
	 * Get original/translated media url/id for WPML Media plugin.
	 *
	 * @since 1.1.0
	 *
	 * @param string $media_url The url of the media.
	 * @param string $media_id The id of the media.
	 * @param string $return The url/id of the media.
	 *
	 * @return string $media_url/id The original/translated media url/id.
	 */
	function mk_get_wpml_media( $media_url = false, $media_id = false, $return = 'url' ) {
		$enable = apply_filters( 'jupiter_donut_wpml_media_translation', true );

		if ( ! $enable || ! function_exists( 'icl_object_id' ) ) {
			return ( 'id' === $return ) ? $media_id : $media_url;
		}

		if ( ! empty( $media_url ) && empty( $media_id ) ) {
			$media_id = attachment_url_to_postid( $media_url );
		}

		$media_id_translated = icl_object_id( $media_id, 'attachment', false, ICL_LANGUAGE_CODE );

		if ( 'id' === $return ) {
			return $media_id_translated;
		}

		// If translated media id exists.
		if ( empty( $media_id_translated ) ) {
			return $media_url;
		}

		// If translated media url doesn't exists.
		if ( $media_url_translated = wp_get_attachment_url( $media_id_translated ) ) {
			$media_url = $media_url_translated;
		}

		return $media_url;
	}
}
