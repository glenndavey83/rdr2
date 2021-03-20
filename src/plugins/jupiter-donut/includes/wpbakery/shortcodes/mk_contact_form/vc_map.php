<?php
$captcha_plugin_status = '';
if ( ! Mk_Theme_Captcha::is_plugin_active() ) {
	$captcha_plugin_status = '<span style="color:red">Artbees Themes Captcha plugin is not activated! <a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '">Click here</a> to begin installing.</span>';
}

 vc_map(
	 array(
		 'name' => __( 'Contact Form', 'jupiter-donut' ),
		 'base' => 'mk_contact_form',
		 'html_template' => dirname( __FILE__ ) . '/mk_contact_form.php',
		 'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_contact_form/vc_front.js',
		 'icon' => 'icon-mk-contact-form vc_mk_element-icon',
		 'description' => __( 'Adds Contact form element.', 'jupiter-donut' ),
		 'category' => __( 'Social', 'jupiter-donut' ),
		 'params' => array(
			 array(
				 'type' => 'textfield',
				 'heading' => __( 'Heading Title', 'jupiter-donut' ),
				 'param_name' => 'title',
				 'value' => '',
				 'description' => __( '', 'jupiter-donut' ),
			 ),
			 array(
				 'type' => 'dropdown',
				 'heading' => __( 'Style', 'jupiter-donut' ),
				 'param_name' => 'style',
				 'value' => array(
					 __( 'Outline', 'jupiter-donut' ) => 'outline',
					 __( 'Modern', 'jupiter-donut' ) => 'modern',
					 __( 'Classic', 'jupiter-donut' ) => 'classic',
					 __( 'Corporate', 'jupiter-donut' ) => 'corporate',
					 __( 'Line', 'jupiter-donut' ) => 'line',
				 ),
				 'description' => __( 'Choose your contact form style', 'jupiter-donut' ),
			 ),
			 array(
				 'type' => 'dropdown',
				 'heading' => __( 'Skin', 'jupiter-donut' ),
				 'param_name' => 'skin',
				 'value' => array(
					 __( 'Dark', 'jupiter-donut' ) => 'dark',
					 __( 'Light', 'jupiter-donut' ) => 'light',
				 ),
				 'description' => __( 'Choose your contact form style', 'jupiter-donut' ),
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'modern',
						 'outline',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'textfield',
				 'heading' => __( 'Button Text', 'jupiter-donut' ),
				 'param_name' => 'button_text',
				 'value' => '',
				 'description' => __( '', 'jupiter-donut' ),
			 ),
			 array(
				 'type' => 'alpha_colorpicker',
				 'heading' => __( 'Background Color', 'jupiter-donut' ),
				 'param_name' => 'bg_color',
				 'description' => __( '', 'jupiter-donut' ),
				 'value' => '#f6f6f6',
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'corporate',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'alpha_colorpicker',
				 'heading' => __( 'Border Color', 'jupiter-donut' ),
				 'param_name' => 'border_color',
				 'description' => __( '', 'jupiter-donut' ),
				 'value' => '#f6f6f6',
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'corporate',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'alpha_colorpicker',
				 'heading' => __( 'Font Color', 'jupiter-donut' ),
				 'param_name' => 'font_color',
				 'description' => __( '', 'jupiter-donut' ),
				 'value' => '#373737',
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'corporate',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'alpha_colorpicker',
				 'heading' => __( 'Button Background Color', 'jupiter-donut' ),
				 'param_name' => 'button_color',
				 'description' => __( '', 'jupiter-donut' ),
				 'value' => '#373737',
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'corporate',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'alpha_colorpicker',
				 'heading' => __( 'Button Font Color', 'jupiter-donut' ),
				 'param_name' => 'button_font_color',
				 'description' => __( '', 'jupiter-donut' ),
				 'value' => '#fff',
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'corporate',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'alpha_colorpicker',
				 'heading' => __( 'Skin Color', 'jupiter-donut' ),
				 'param_name' => 'line_skin_color',
				 'description' => __( '', 'jupiter-donut' ),
				 'value' => '#eee',
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'line',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'dropdown',
				 'heading' => __( 'Button Text Color', 'jupiter-donut' ),
				 'param_name' => 'line_button_text_color',
				 'value' => array(
					 __( 'Dark', 'jupiter-donut' ) => 'dark',
					 __( 'Light', 'jupiter-donut' ) => 'light',
				 ),
				 'description' => __( '', 'jupiter-donut' ),
				 'dependency' => array(
					 'element' => 'style',
					 'value' => array(
						 'line',
					 ),
				 ),
			 ),
			 array(
				 'type' => 'textfield',
				 'heading' => __( 'Email', 'jupiter-donut' ),
				 'param_name' => 'email',
				 'value' => get_bloginfo( 'admin_email' ),
				 'description' => sprintf( __( 'Which email would you like the contacts to be sent, if left empty emails will be sent to admin email : "%s"', 'jupiter-donut' ), get_bloginfo( 'admin_email' ) ),
			 ),
			 array(
				 'type' => 'toggle',
				 'heading' => __( 'Show Phone Number Field?', 'jupiter-donut' ),
				 'param_name' => 'phone',
				 'value' => 'false',
				 'description' => __( '', 'jupiter-donut' ),
			 ),
			 array(
				 'type' => 'toggle',
				 'heading' => __( 'Captcha authentication?', 'jupiter-donut' ),
				 'param_name' => 'captcha',
				 'value' => 'true',
				 'description' => sprintf( __( 'Keep away spam bots. %s' , 'jupiter-donut' ), $captcha_plugin_status ),

			 ),
			 array(
				 'type' => 'toggle',
				 'heading' => __( 'GDPR Consent Check?', 'jupiter-donut' ),
				 'param_name' => 'gdpr_consent',
				 'value' => 'true',
				 'description' => __( '', 'jupiter-donut' ),

			 ),

			 array(
				 'type' => 'textarea',
				 'heading' => __( 'GDPR Consent Checkbox Text', 'jupiter-donut' ),
				 'param_name' => 'gdpr_consent_text',
				 'value' => sprintf( __( 'I consent to %s collecting my details through this form.', 'jupiter-donut' ), get_bloginfo( 'name' ) ),
				 'description' => __( '', 'jupiter-donut' ),
				 'dependency' => array(
					 'element' => 'gdpr_consent',
					 'value' => array(
						 'true',
					 ),
				 ),
			 ),

			 $add_device_visibility,
			 array(
				 'type' => 'textfield',
				 'heading' => __( 'Extra class name', 'jupiter-donut' ),
				 'param_name' => 'el_class',
				 'value' => '',
				 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.', 'jupiter-donut' ),
			 ),
		 ),
)
 );
