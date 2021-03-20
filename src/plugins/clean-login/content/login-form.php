<?php
	if ( ! defined( 'ABSPATH' ) ) exit; 
	$login_url = CleanLogin_Controller::get_login_url();
	$register_url = CleanLogin_Controller::get_register_url();
	$restore_url = CleanLogin_Controller::get_restore_password_url();
?>
<div class="cleanlogin-container">		

	<form class="cleanlogin-form" method="post" action="<?php echo $login_url;?>">
			
		<fieldset>

			<?php do_action("cleanlogin_before_login_form"); ?>
			<div class="cleanlogin-field">
				<input class="cleanlogin-field-username" type="text" name="log" placeholder="<?php echo __( 'Username', 'clean-login' ); ?>" aria-label="<?php echo __( 'Username', 'clean-login' ); ?>">
			</div>
			
			<div class="cleanlogin-field">
				<input class="cleanlogin-field-password" type="password" name="pwd" placeholder="<?php echo __( 'Password', 'clean-login' ); ?>" aria-label="<?php echo __( 'Password', 'clean-login' ); ?>">
			</div>
		
			<input type="hidden" name="clean_login_wpnonce" value="<?php echo wp_create_nonce( 'clean_login_wpnonce' ); ?>">

			<?php do_action("cleanlogin_after_login_form"); ?>
		</fieldset>
		
		<fieldset>
			<input class="cleanlogin-field" type="submit" value="<?php echo __( 'Log in', 'clean-login' ); ?>" name="submit">
			<input type="hidden" name="action" value="login">
			
			<div class="cleanlogin-field cleanlogin-field-remember">
				<input type="checkbox" id="rememberme" name="rememberme" value="forever">
				<label for="rememberme"><?php echo __( 'Remember?', 'clean-login' ); ?></label>
			</div>
		</fieldset>

		<?php echo do_shortcode( apply_filters( 'cl_login_form', '') ); ?>

		<div class="cleanlogin-form-bottom">
			
			<?php if ( $restore_url != '' )
				echo "<a href='$restore_url' class='cleanlogin-form-pwd-link'>". __( 'Lost password?', 'clean-login' ) ."</a>";
			?>

			<?php if ( $register_url != '' && get_option( 'users_can_register' ) )
				echo "<a href='$register_url' class='cleanlogin-form-register-link'>". __( 'Register', 'clean-login' ) ."</a>";
			?>
						
		</div>
		
	</form>

</div>
