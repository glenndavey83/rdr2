<?php

// If captcha plugin is not active do not show captcha form
if(!Mk_Theme_Captcha::is_plugin_active()) return false;

?>
<div class="mk-form-row">
	<?php if(isset($view_params['show_icon'])) : ?>
		<?php Mk_SVG_Icons::get_svg_icon_by_class_name(true, 'mk-li-lock', 16); ?>
	<?php endif; ?>	
	<input placeholder="<?php _e( 'Enter Captcha', 'jupiter-donut' ); ?>" data-placeholder="<?php _e( 'Enter Captcha', 'jupiter-donut' ); ?>" class="captcha-form text-input s_txt-input full" type="text" data-type="captcha" name="captcha" required="required" autocomplete="off" tabindex="<?php echo $view_params['tab_index']; ?>" />
	<div class="captcha-block">
	<span class="captcha-image-holder">
		<img src="<?php Mk_Theme_Captcha::create_captcha_image(); ?>" class="captcha-image" alt="captcha txt"/>
	</span> 
	</div>
	<?php 
		if(isset($view_params['add_br'])) {
			echo '<br>';
		} 
	?>
	<a href="#" class="captcha-change-image"><?php _e( 'Not readable?', 'jupiter-donut' ); ?> <?php _e( 'Change text.', 'jupiter-donut' ); ?></a>
	
</div>

