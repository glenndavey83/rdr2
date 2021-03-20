<?php
/**
 * Prepare theme options.
 *
 * @package Jupiter_Core\Theme_Options
 *
 * @since 1.0.0
 */

add_filter( 'mk_theme_options_config', 'jupiter_core_theme_options' );
/**
 * Filter theme options.
 *
 * @since 1.0.0
 */
function jupiter_core_theme_options( $options ) {
	$additional_options = [
		'menu' => [
			'general' => [
				'submenu' => [
					'quick_contact'    => __( 'Quick Contact', 'jupiter-core' ),
					'api_integrations' => __( 'API Integrations', 'jupiter-core' ),
				],
			],
		],
		'schema' => [
			'quick_contact'    => [
				'label'    => __( 'Quick Contact', 'jupiter-core' ),
				'sections' => [
					[
						'label'  => false,
						'fields' => [
							[
								'type'         => 'mk-toggle',
								'label'        => __( 'Quick Contact', 'jupiter-core' ),
								'help'         => __( 'Display quick contact form?', 'jupiter-core' ),
								'model'        => 'disable_quick_contact',
								'default'      => 'true',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-toggle',
								'label'        => __( 'Show On Blog & News / Single Post', 'jupiter-core' ),
								'help'         => __( 'Display quick contact form on blog and portfolio single posts?', 'jupiter-core' ),
								'model'        => 'quick_contact_on_single',
								'default'      => 'true',
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'default'      => 'true',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-toggle',
								'label'        => __( 'Captcha', 'jupiter-core' ),
								'help'         => __( 'Display Captcha in quick contact form to keep the spam away.', 'jupiter-core' ),
								'model'        => 'captcha_quick_contact',
								'default'      => 'true',
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Email', 'jupiter-core' ),
								'help'         => __( "Enter an email for sending this form's inqueries. Admin's email will be used as default email.", 'jupiter-core' ),
								'model'        => 'quick_contact_email',
								'default'      => get_bloginfo( 'admin_email' ),
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Title', 'jupiter-core' ),
								'help'         => __( 'Quick Contact Title' , 'jupiter-core' ),
								'model'        => 'quick_contact_title',
								'default'      => 'Contact Us',
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-textarea',
								'label'        => __( 'Description', 'jupiter-core' ),
								'model'        => 'quick_contact_desc',
								'default'      => 'We\'re not around right now. But you can send us an email and we\'ll get back to you, asap.',
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-toggle',
								'label'        => __( 'GDPR Consent Check', 'jupiter-core' ),
								'help'         => '',
								'model'        => 'quick_contact_gdpr_consent',
								'default'      => 'true',
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-textarea',
								'label'        => __( 'GDPR Consent Checkbox Text', 'jupiter-core' ),
								'help'         => '',
								'model'        => 'quick_contact_gdpr_consent_text',
								'default'      => sprintf( __( 'I consent to %s collecting my details through this form.', 'jupiter-core' ), get_bloginfo( 'name' ) ),
								'condition'    => [
									'model' => 'disable_quick_contact',
									'value' => 'true',
								],
								'styleClasses' => 'col-sm-12 col-md-6',
							],
						],
					],
				],
			],
			'api_integrations' => [
				'label'    => __( 'API Integrations', 'jupiter-core' ),
				'sections' => [
					[
						'label'  => __( 'Twitter Settings', 'jupiter-core' ),
						'fields' => [
							[
								'type'         => 'mk-input',
								'label'        => __( 'Consumer Key', 'jupiter-core' ),
								'model'        => 'twitter_consumer_key',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Consumer Secret', 'jupiter-core' ),
								'model'        => 'twitter_consumer_secret',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Access Token', 'jupiter-core' ),
								'model'        => 'twitter_access_token',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Access Token Secret', 'jupiter-core' ),
								'model'        => 'twitter_access_token_secret',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
						],
						'help'   => sprintf(
							wp_kses(
								__( '<ol style="%1$s"><li>Go to "<a target="%2$s" href="%3$s">%4$s</a>," login with your twitter account and click "Create a new application".</li><li>Fill out the required fields, accept the rules of the road, and then click on the "Create your Twitter application" button. You will not need a callback URL for this app, so feel free to leave it blank.</li><li>Once the app has been created, click the "Create my access token" button.</li><li>You are done! You will need the following data later on:</ol>' , 'jupiter-core' ),
								[
									'a'  => [
										'href'   => [],
										'target' => [],
									],
									'ol' => [
										'style' => [],
									],
									'li' => [],
								],
								'https'
							),
							'list-style-type:decimal !important;',
							'_blank',
							'https://dev.twitter.com/apps',
							'https://dev.twitter.com/apps'
						),
					],
					[
						'label'  => __( 'MailChimp Settings', 'jupiter-core' ),
						'fields' => [
							[
								'type'         => 'mk-input',
								'label'        => __( 'MailChimp API Key', 'jupiter-core' ),
								'help'         => sprintf(
									wp_kses(
										__( 'Enter a <a href="%s">MailChimp API Key</a>.' , 'jupiter-core' ),
										[
											'a' => [
												'href' => [],
											],
										]
									), 'http://kb.mailchimp.com/integrations/api-integrations/about-api-keys'
								),
								'model'        => 'mailchimp_api_key',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Mailchimp List ID', 'jupiter-core' ),
								'help'         => sprintf(
									wp_kses(
										__( 'Add your MailChimp List ID here. For more information, please read <a href="%s">Find Your List ID</a> article.' , 'jupiter-core' ),
										[
											'a' => [
												'href' => [],
											],
										]
									), 'http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id'
								),
								'model'        => 'mailchimp_list_id',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-toggle',
								'label'        => __( 'Mailchimp Opt-In Email', 'jupiter-core' ),
								'help'         => __( 'Subscribers must receive a <strong>Please Confirm Subscription</strong> email?', 'jupiter-core' ),
								'model'        => 'mailchimp_optin',
								'default'      => 'false',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
						],
					],
					[
						'label'  => __( 'Other Integrations', 'jupiter-core' ),
						'fields' => [
							[
								'type'         => 'mk-input',
								'label'        => __( 'Google Maps API Key', 'jupiter-core' ),
								'help'         => sprintf( __( 'Enter an <a target="_blank" href="%1$s">API key</a> for Google Maps.<br>1. Go to the <a target="_blank" href="%2$s">Google Developers Console</a>. <br>2. Create or select a project. <br>3. Click Continue to enable the API and any related services.<br>4. On the Credentials page, get a Browser key (and set the API Credentials).', 'jupiter-core' ), 'https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true', 'https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true' ),
								'model'        => 'google_maps_api_key',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Google Analytics ID', 'jupiter-core' ),
								'help'         => __( 'Enter your Google Analytics ID here to track your site with Google Analytics. Jupiter does not support Event Tracking. To use this feature, a 3rd-party plugin is required.', 'jupiter-core' ),
								'model'        => 'analytics',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-input',
								'label'        => __( 'Typekit Kit ID', 'jupiter-core' ),
								'help'         => sprintf(
									wp_kses(
										__( 'Enter a <a href="%s"> Typekit Kit ID</a> for using Typkit fonts.' , 'jupiter-core' ),
										[
											'a' => [
												'href' => [],
											],
										]
									), 'https://themes.artbees.net/docs/integrating-typekit/'
								),
								'model'        => 'typekit_id',
								'default'      => '',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
							[
								'type'         => 'mk-toggle',
								'label'        => __( 'Third Party API GDPR compliance', 'jupiter-core' ),
								'help'         => __( 'Using this option you can turn on GDPR compatibility feature for various third party services we use inside theme (e.g. Google Analytics). Some API services have not yet updated their terms and API endpoints, however we will keep improving the codebase for maximum compliance. keep your theme up to date!', 'jupiter-core' ),
								'model'        => 'third_party_gdpr',
								'default'      => 'false',
								'styleClasses' => 'col-sm-12 col-md-6',
							],
						],
					],
				],
			],
		],
	];

	return array_merge_recursive( $options, $additional_options );
}


add_filter( 'style_loader_src', 'jupiter_core_remove_src_version', 9999 );
add_filter( 'script_loader_src', 'jupiter_core_remove_src_version', 9999 );
/**
 * Remove version from source.
 *
 * @param string $src Source URL.
 * @return string Modified URL.
 */
function jupiter_core_remove_src_version( $src ) {
	global $mk_options;

	$remove_query_string = isset( $mk_options['remove-js-css-ver'] ) ? $mk_options['remove-js-css-ver'] : 'false';

	if ( $remove_query_string == 'false' ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
	}

	return $src;
}

