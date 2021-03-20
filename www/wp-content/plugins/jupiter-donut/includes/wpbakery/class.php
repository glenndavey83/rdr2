<?php

class Jupiter_Donut_WPBakery {

	public function __construct() {
		add_action( 'vc_mapper_init_before', [ $this, 'register_shortcodes' ], 20 );

		$this->register_fields();
		$this->register_special_shortcodes();
	}

	public function register_shortcodes() {
		require_once JUPITER_DONUT_INCLUDES_DIR . '/wpbakery/global-params.php';

		$shortcodes = glob( JUPITER_DONUT_INCLUDES_DIR . '/wpbakery/shortcodes/*/vc_map.php' );

		foreach ( $shortcodes as $shortcode ) {
			$shortcode = substr( $shortcode, strpos( $shortcode, 'wpbakery' ) );
			$shortcode = mk_get_overridable_path( $shortcode );

			require_once $shortcode;
		}
	}

	protected function register_special_shortcodes() {
		require_once JUPITER_DONUT_INCLUDES_DIR . '/wpbakery/page-section.php';
		require_once JUPITER_DONUT_INCLUDES_DIR . '/wpbakery/accordions.php';
	}

	protected function register_fields() {
		jupiter_donut()->load_directory( '/wpbakery/fields/' );
	}
}
