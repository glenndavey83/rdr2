<?php

if ( ! function_exists( 'jupiter_core_load_importer' ) ) {
	/**
	 * Load importer.
	 *
	 * @since 1.0.0
	 */
	function jupiter_core_load_importer() {
		// Avoid redeclare WP_Importer class.
		if ( ! class_exists( 'WP_Importer' ) ) {
			defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
			include ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

		// Avoid redeclare WXR_Importer class and others.
		if ( class_exists( 'WXR_Importer' ) ) {
			return true;
		}

		// Load all needed class.
		jupiter_core()->load_files( [
			'/importer/class-logger',
			'/importer/class-logger-serversentevents',
			'/importer/class-wxr-import-info',
			'/importer/class-wxr-importer',
		] );

		return true;
	}
}
