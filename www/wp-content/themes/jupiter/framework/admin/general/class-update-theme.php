<?php

/**
 * Handle theme update.
 *
 * @since 6.8.0
 */
class Jupiter_Update_Theme {

	/**
	 * API URL.
	 *
	 * @var string
	 */
	private $api_url;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->api_url = 'https://artbees.net/api/v1/';

		if ( mk_is_registered() ) {
			add_filter( 'pre_set_site_transient_update_themes', [ $this, 'update_theme' ] );
		}
	}

	/**
	 * Update theme transient data.
	 *
	 * @since 6.8.0
	 *
	 * @param array $transient Update transient.
	 * @return array $transient Update transient.
	 */
	public function update_theme( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Theme data.
		$theme_data = $this->get_theme_data();

		// Theme update data.
		$theme_update = $this->get_last_update();

		// Compare versions.
		if ( version_compare( $theme_update['version'], $theme_data['theme_version'] ) <= 0 ) {
			return $transient;
		}

		// Add update data to the transient.
		$response['theme']       = $theme_data['theme_base'];
		$response['package']     = $this->get_theme_latest_release_package_url( $theme_update['id'] );
		$response['new_version'] = $theme_update['version'];
		$response['url']         = false;

		$transient->response[ $theme_data['theme_base'] ] = $response;

		return $transient;
	}

	/**
	 * Get theme data.
	 *
	 * @since 6.8.0
	 *
	 * @return array
	 */
	private function get_theme_data() {
		$theme_data = wp_get_theme( get_option( 'template' ) );

		return [
			'theme_version' => $theme_data->version,
			'theme_base'    => 'jupiter',
		];
	}

	/**
	 * Get the last update.
	 *
	 * @since 6.8.0
	 *
	 * @return array
	 */
	private function get_last_update() {
		$theme_data = $this->get_theme_data();

		// Get the last release note.
		$request = wp_remote_post( $this->api_url . 'update-theme', [
			'body' => [
				'action'  => 'get_release_note',
				'request' => [
					'slug'    => $theme_data['theme_base'],
					'version' => $theme_data['theme_version'],
				],
			],
		] );

		// Check for errors.
		if ( 200 != wp_remote_retrieve_response_code( $request ) ) {
			return;
		}

		// Get the response body.
		$response = wp_remote_retrieve_body( $request );

		// Unserialize it.
		$release = maybe_unserialize( $response );

		// Get id and release.
		$data = [
			'id'      => $release->ID,
			'version' => trim( str_replace( 'V', '', $release->post_title ) ),
		];

		return $data;
	}

	/**
	 * Get theme latest version package url.
	 *
	 * @since 6.8.0
	 *
	 * @param string $release_id Theme Release Id.
	 *
	 * @return string $url
	 */
	private function get_theme_latest_release_package_url( $release_id ) {
		// Get the download link.
		$request = wp_remote_post( $this->api_url . 'update-theme', [
			'body' => [
				'action'          => 'get_release_download_link',
				'apikey'          => mk_get_api_key(),
				'domain'          => $_SERVER['SERVER_NAME'], // phpcs:ignore WordPress.Security
				'release_id'      => $release_id,
				'release_package' => '',
			],
		] );

		// Check for errors.
		if ( 200 != wp_remote_retrieve_response_code( $request ) ) {
			return;
		}

		// Get response.
		$response = wp_remote_retrieve_body( $request );

		// Formate the response.
		$response = json_decode( json_decode( $response, JSON_FORCE_OBJECT ) );

		// Check for errors.
		if ( ! is_object( $response ) || ! $response->success ) {
			return;
		}

		return $response->download_link;
	}
}

new Jupiter_Update_Theme();
