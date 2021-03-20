<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'mk_jupiter_register_required_plugins' );
/**
 * Register Jupiter required and recommended plugins.
 *
 * @return void
 */
function mk_jupiter_register_required_plugins() {
	$transient_key = 'mk_tgmpa_plugins_check';
	$option_key    = 'mk_tgmpa_plugins';

	$plugins       = get_option( $option_key );
	$plugins_check = get_transient( $transient_key );

	if ( false === $plugins_check ) {
		$headers = [
			'api-key'      => get_option( 'artbees_api_key' ),
			'domain'       => $_SERVER['SERVER_NAME'],
			'from'         => 0,
			'count'        => 20,
			'list-of-attr' => wp_json_encode( [
				'name',
				'slug',
				'required',
				'version',
				'source',
			] ),
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( 'https://artbees.net/api/v2/tools/plugin-custom-list', [
			'headers' => $headers,
		] ) ) );

		if ( empty( $response->data ) || ! is_array( $response->data ) ) {
			set_transient( $transient_key, [], 2 * HOUR_IN_SECONDS );

			return;
		}

		foreach ( $response->data as $index => $plugin ) {
			$plugins[ $index ] = (array) $plugin;

			if ( 'wp-repo' === $plugin->version ) {
				unset( $plugins[ $index ]['source'] );
			}
		}

		update_option( $option_key, $plugins, 'no' );
		set_transient( $transient_key, [], DAY_IN_SECONDS );
	}

	if ( empty( $plugins ) ) {
		return;
	}

	$config = [
		'id'           => 'jupiter',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	];

	tgmpa( $plugins, $config );
}
