<?php

defined( 'ABSPATH' ) || die();

/**
 * Class to for MailChimp operations using ajax
 *
 * @author      Mucahit Yilmaz
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @version     5.0
 * @package     artbees
 */

class Mk_Ajax_Subscribe {


	public function __construct() {
		add_action(
			'wp_ajax_nopriv_mk_ajax_subscribe', array(
				&$this,
				'subscribe_to_list',
			)
		);
		add_action(
			'wp_ajax_mk_ajax_subscribe', array(
				&$this,
				'subscribe_to_list',
			)
		);
	}

	public function subscribe_to_list() {

		$email   = stripslashes( $_POST['email'] );
		$list_id = stripslashes( $_POST['list_id'] );
		$optin   = stripslashes( $_POST['optin'] );

		$result = $this->subscribe( $email, $list_id, $optin );

		if ( empty( $result['status'] ) == false ) {
			switch ( $result['name'] ) {
				case 'Invalid_ApiKey':
					echo json_encode(
						array(
							'action_status' => false,
							'message'       => $result['error'],
						)
					);
					break;
				case 'List_DoesNotExist':
					echo json_encode(
						array(
							'action_status' => false,
							'message'       => $result['error'],
						)
					);
					break;
				case 'ValidationError':
					echo json_encode(
						array(
							'action_status' => false,
							'message'       => __( 'Oops! Enter a valid Email address', 'jupiter-donut' ),
						)
					);
					break;

				case 'List_AlreadySubscribed':
					echo json_encode(
						array(
							'action_status' => false,
							'message'       => __( 'This email already subscribed to the list.', 'jupiter-donut' ),
						)
					);
					break;

				case 'curl_package_disabled':
					echo json_encode(
						array(
							'action_status' => false,
							'message'       => __( 'Curl is disabled. Please enable curl in server php.ini settings.', 'jupiter-donut' ),
						)
					);
					break;
			}
		} elseif ( isset( $result['email'] ) ) {
			echo json_encode(
				array(
					'action_status' => true,
					'message'       => $result['email'] . __( ' has been subscribed.', 'jupiter-donut' ),
				)
			);
		}
		wp_die();
	}

	private function subscribe( $email, $list_id, $optin ) {
		$mailchimp = new MK_MailChimp( trim( jupiter_donut_get_option( 'mailchimp_api_key' ) ) );

		return $mailchimp->subscribe( $email, $list_id, $optin );
	}
}

new Mk_Ajax_Subscribe();
