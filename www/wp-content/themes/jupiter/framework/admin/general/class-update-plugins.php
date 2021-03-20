<?php

/**
 * Handle theme plugins update.
 *
 * @since 6.8.0
 */
class Jupiter_Update_Plugins {

	/**
	 * API URL.
	 *
	 * @var string
	 */
	private $api_url;

	/**
	 * Constructor.
	 *
	 * @since 6.8.0
	 */
	public function __construct() {
		$this->api_url = 'https://artbees.net/api/v2/tools/plugin-custom-list';

		add_action( 'vc_after_init_updater', [ $this, 'remove_update_message' ] );

		if ( mk_is_registered() ) {
			add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'update_plugins' ] );
		}
	}

	/**
	 * Remove extra update message added by WPBakery.
	 *
	 * @since 6.8.0
	 *
	 * @return null
	 */
	public function remove_update_message() {
		remove_all_actions( 'in_plugin_update_message-js_composer_theme/js_composer.php' );
	}

	/**
	 * Update plugins transient.
	 *
	 * @since 6.8.0
	 *
	 * @param array $transient Transient object.
	 * @return object
	 */
	public function update_plugins( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$installed_plugins = $this->get_plugins();
		$theme_plugins     = $this->get_theme_plugins();

		foreach ( $theme_plugins as $theme_plugin ) {
			if ( empty( $theme_plugin->source ) || 'wp-repo' === $theme_plugin->source ) {
				continue;
			}

			foreach ( $installed_plugins as $basename => $installed_plugin ) {
				if ( in_array( $basename, $this->skip_plugins(), true ) ) {
					continue;
				}

				if ( strpos( $basename, $theme_plugin->slug ) === false ) {
					continue;
				}

				if ( version_compare( $theme_plugin->version, $installed_plugin['Version'] ) <= 0 ) {
					unset( $transient->response[ $basename ] );

					continue;
				}

				$update = new stdClass();

				$update->slug        = $theme_plugin->slug;
				$update->plugin      = $basename;
				$update->new_version = $theme_plugin->version;
				$update->url         = false;
				$update->package     = $theme_plugin->source;

				$transient->response[ $basename ] = $update;
			}
		}

		return $transient;
	}

	/**
	 * Get the theme plugins.
	 *
	 * @since 6.8.0
	 *
	 * @return array Array of theme plugins.
	 */
	private function get_theme_plugins() {
		// Send a request.
		$request = wp_remote_get( $this->api_url, [
			'headers' => [
				'api-key'      => mk_get_api_key(),
				'domain'       => $_SERVER['SERVER_NAME'], // phpcs:ignore
				'from'         => 0,
				'count'        => 20,
				'list-of-attr' => wp_json_encode( [
					'name',
					'slug',
					'required',
					'version',
					'source',
					'pro',
				] ),
			],
		] );

		// Check for errors.
		if ( 200 != wp_remote_retrieve_response_code( $request ) ) {
			return [];
		}

		// Get the response.
		$response = json_decode( wp_remote_retrieve_body( $request ) );

		// Check for errors.
		if ( ! isset( $response->data ) || ! is_array( $response->data ) ) {
			return [];
		}

		return $response->data;
	}

	/**
	 * Get the plugins.
	 *
	 * @since 6.8.0
	 *
	 * @return array Array of installed plugins.
	 */
	private function get_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugins();
	}

	/**
	 * Ignore plugins update source from Artbees.
	 *
	 * @since 6.8.0
	 *
	 * @return array
	 */
	private function skip_plugins() {
		$plugins = [];

		$this->skip_revslider( $plugins );

		return $plugins;
	}

	/**
	 * Ignore revslider update source from Artbees.
	 *
	 * @since 6.8.0
	 *
	 * @param array $plugins Plugins array.
	 */
	private function skip_revslider( &$plugins ) {
		if (
			'true' !== get_option( 'revslider-valid' ) ||
			empty( get_option( 'revslider-code' ) )
		) {
			return;
		}

		$plugins[] = 'revslider/revslider.php';
	}
}

new Jupiter_Update_Plugins();
