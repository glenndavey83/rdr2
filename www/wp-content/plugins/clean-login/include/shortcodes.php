<?php

class CleanLogin_Shortcode{
	public function load(){
		add_shortcode( 'clean-login', array( $this, 'clean_login' ) );
		add_shortcode( 'clean-login-edit', array( $this, 'clean_login_edit' ) );
		add_shortcode( 'clean-login-register', array( $this, 'clean_login_register' ) );
		add_shortcode( 'clean-login-restore', array( $this, 'clean_login_restore' ) );
		
		add_action( 'save_post', array( $this, 'get_pages_with_shortcodes' ), 10, 2 );
	}

	function clean_login( $atts ) {
		ob_start();
		
		if ( isset( $_GET['authentication'] ) ) {
			if ( $_GET['authentication'] == 'success' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'Successfully logged in!', 'clean-login' ) ."</p></div>";
			else if ( $_GET['authentication'] == 'failed' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Wrong credentials', 'clean-login' ) ."</p></div>";
			else if ( $_GET['authentication'] == 'logout' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'Successfully logged out!', 'clean-login' ) ."</p></div>";
			else if ( $_GET['authentication'] == 'failed-activation' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Something went wrong while activating your user', 'clean-login' ) ."</p></div>";
					else if ( $_GET['authentication'] == 'disabled' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Your account is currently disabled', 'clean-login' ) ."</p></div>";
			else if ( $_GET['authentication'] == 'success-activation' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'Successfully activated', 'clean-login' ) ."</p></div>";
		}

		if ( is_user_logged_in() ) {
			CleanLogin_Frontend::get_template_file( 'login-preview.php' );
		} else {
			CleanLogin_Frontend::get_template_file( 'login-form.php' );
		}

		return ob_get_clean();
	}

	function clean_login_edit( $atts ) {
		$atts = shortcode_atts( array( 'show_email' => true ), $atts );
	
		ob_start();
	
		if ( isset( $_GET['updated'] ) ) {
			if ( $_GET['updated'] == 'success' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'Information updated', 'clean-login' ) ."</p></div>";
			else if ( $_GET['updated'] == 'passcomplex' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Passwords must be eight characters including one upper/lowercase letter, one special/symbol character and alphanumeric characters. Passwords should not contain the user\'s username, email, or first/last name.', 'clean-login' ) ."</p></div>";
			else if ( $_GET['updated'] == 'wrongpass' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Passwords must be identical', 'clean-login' ) ."</p></div>";
			else if ( $_GET['updated'] == 'wrongmail' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Error updating email', 'clean-login' ) ."</p></div>";
			else if ( $_GET['updated'] == 'failed' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Something strange has ocurred', 'clean-login' ) ."</p></div>";
		}
	
		if ( is_user_logged_in() ) {
			CleanLogin_Frontend::get_template_file( 'login-edit.php', $atts );
		} else {
			echo "<div class='cleanlogin-notification error'><p>". __( 'You need to be logged in to edit your profile', 'clean-login' ) ."</p></div>";
			CleanLogin_Frontend::get_template_file( 'login-form.php' );
		}
	
		return ob_get_clean();
	}
	
	function clean_login_register( $atts ){
		if( !get_option( 'users_can_register' ) ){
			echo "<div class='cleanlogin-notification error'><p>". __( 'Registration is not allowed in this site', 'clean-login' ) ."</p></div>";
			return;
		}
		
		$param = shortcode_atts( array(
			'role' => false,
			'template' => 'register-form.php',
		), $atts );
	
		ob_start();
	
		if ( isset( $_GET['created'] ) ) {
			if ( $_GET['created'] == 'success' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'User created', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'success-link' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'User created', 'clean-login' ) ."<br>". __( 'Please confirm your account, you will receive an email', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'created' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'New user created', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'passcomplex' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Passwords must be eight characters including one upper/lowercase letter, one special/symbol character and alphanumeric characters. Passwords should not contain the user\'s username, email, or first/last name.', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'wronguser' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Username is not valid', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'wrongname' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'First name is not valid', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'wrongsurname' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Last name is not valid', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'wrongpass' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Passwords must be identical and filled', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'wrongmail' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Email is not valid', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'emailexists' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'There is already a user registered with this email. Login with this existing account. If you do not remember your password, you will find a recuperation link at the login form.', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'wrongcaptcha' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'CAPTCHA is not valid, please try again', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'failed' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Something strange has ocurred while created the new user', 'clean-login' ) ."</p></div>";
			else if ( $_GET['created'] == 'terms' )
				echo "<div class='cleanlogin-notification error'><p>\"". get_option ( 'cl_termsconditionsMSG' ) . '" ' .__( 'must be checked', 'clean-login' ) . "</p></div>";
		}
	
		if ( !is_user_logged_in() ) {
			CleanLogin_Frontend::get_template_file( $param['template'], $param );
		} else {
			echo "<div class='cleanlogin-notification error'><p>". __( 'You are now logged in. It makes no sense to register a new user', 'clean-login' ) ."</p></div>";
			CleanLogin_Frontend::get_template_file( 'login-preview.php' );
		}
	
		return ob_get_clean();
	}

	function clean_login_restore( $atts ) {
		ob_start();
	
		if ( isset( $_GET['sent'] ) ) {
			if ( $_GET['sent'] == 'success' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'You will receive an email with the activation link', 'clean-login' ) ."</p></div>";
			else if ( $_GET['sent'] == 'sent' )
				echo "<div class='cleanlogin-notification success'><p>". __( 'You may receive an email with the activation link', 'clean-login' ) ."</p></div>";
			else if ( $_GET['sent'] == 'failed' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'An error has ocurred sending the email', 'clean-login' ) ."</p></div>";
			else if ( $_GET['sent'] == 'wronguser' )
				echo "<div class='cleanlogin-notification error'><p>". __( 'Username is not valid', 'clean-login' ) ."</p></div>";
		}
	
		if ( !is_user_logged_in() ) {
			if ( isset( $_GET['pass_changed'] ) ) {
				CleanLogin_Frontend::get_template_file( 'restore-new.php' );
			} else
				CleanLogin_Frontend::get_template_file( 'restore-form.php' );
		} else {
			echo "<div class='cleanlogin-notification error'><p>". __( 'You are now logged in. It makes no sense to restore your account', 'clean-login' ) ."</p></div>";
			CleanLogin_Frontend::get_template_file( 'login-preview.php' );
		}
	
		return ob_get_clean();
	}

	public static function is_login_page(){
		if( get_the_ID() == get_option( 'cl_login_id' ) )
			return true;

		if( get_the_ID() == url_to_postid( CleanLogin_Controller::get_login_url() ) )
			return true;

		return false;
	}

	function get_pages_with_shortcodes( $post_id, $post ) {
		$revision = wp_is_post_revision( $post_id );
	
		if ( $revision ) $post_id = $revision;
		
		$post = get_post( $post_id );
	
		if ( has_shortcode( $post->post_content, 'clean-login' ) ) {
			update_option( 'cl_login_url', get_permalink( $post->ID ), false );
			update_option( 'cl_login_id', $post->ID, false );
		}
	
		if ( has_shortcode( $post->post_content, 'clean-login-edit' ) ) {
			update_option( 'cl_edit_url', get_permalink( $post->ID ), false );
			update_option( 'cl_edit_id', $post->ID, false );
		}
	
		if ( has_shortcode( $post->post_content, 'clean-login-register' ) ) {
			update_option( 'cl_register_url', get_permalink( $post->ID ), false );
			update_option( 'cl_register_id', $post->ID, false );
		}
	
		if ( has_shortcode( $post->post_content, 'clean-login-restore' ) ) {
			update_option( 'cl_restore_url', get_permalink( $post->ID ), false );
			update_option( 'cl_restore_id', $post->ID, false );
		}
	
		// delete if not used
		$keys = array( 'login' => 'clean-login', 'edit' => 'clean-login-edit', 'register' => 'clean-login-register', 'restore' => 'clean-login-restore' );
		foreach ( $keys as $key => $shortcode ) {
			if( $post_id == get_option( 'cl_' . $key . '_id' ) && !has_shortcode( $post->post_content, $shortcode ) ){
				delete_option( 'cl_' . $key . '_url' );
				delete_option( 'cl_' . $key . '_id' );
			}
		}
	}	
}