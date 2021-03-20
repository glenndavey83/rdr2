<?php
/**
 * Load widgets.
 *
 * @package Jupiter_Core\Widgets
 *
 * @since 1.0.0
 */

add_action( 'widgets_init', 'jupiter_core_widgets' );
/**
 * Register widgets.
 *
 * @since 1.0.0
 */
function jupiter_core_widgets() {
	$widgets = [
		'widgets/widgets-contact-form.php',
		'widgets/widgets-contact-info.php',
		'widgets/widgets-gmap.php',
		'widgets/widgets-popular-posts.php',
		'widgets/widgets-related-posts.php',
		'widgets/widgets-recent-posts.php',
		'widgets/widgets-social-networks.php',
		'widgets/widgets-subnav.php',
		'widgets/widgets-testimonials.php',
		'widgets/widgets-twitter-feeds.php',
		'widgets/widgets-video.php',
		'widgets/widgets-flickr-feeds.php',
		'widgets/widgets-instagram-feeds.php',
		'widgets/widgets-news-slider.php',
		'widgets/widgets-recent-portfolio.php',
		'widgets/widgets-slideshow.php',
	];

	foreach ( $widgets as $widget ) {
		$path       = jupiter_core()->plugin_dir() . 'includes/' . $widget;
		$child_path = get_stylesheet_directory() . '/views/' . $widget;

		if ( file_exists( $child_path ) ) {
			$path = $child_path;
		}

		require_once $path;
	}
}
