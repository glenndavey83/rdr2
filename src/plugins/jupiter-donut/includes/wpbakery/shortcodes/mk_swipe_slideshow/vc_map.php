<?php
vc_map(array(
    "name" => __("Swipe Slideshow", 'jupiter-donut'),
    "base" => "mk_swipe_slideshow",
	'html_template' => dirname( __FILE__ ) . '/mk_swipe_slideshow.php',
    'icon' => 'icon-mk-swipe-slideshow vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    "admin_enqueue_js" => JUPITER_DONUT_INCLUDES_URL . "/wpbakery/shortcodes/mk_swipe_slideshow/vc_admin.js",
    'description' => __( 'Swipe enabled slideshow.', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "attach_images",
            "heading" => __("Add Images", 'jupiter-donut'),
            "param_name" => "images",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "heading" => __("Image Size", 'jupiter-donut'),
            "description" => __("", 'jupiter-donut'),
            "param_name" => "image_size",
            "value" => mk_get_image_sizes(),
            "type" => "dropdown",
        ),
        array(
            "type" => "range",
            "heading" => __("Width", 'jupiter-donut'),
            "param_name" => "image_width",
            "value" => "770",
            "min" => "100",
            "max" => "1380",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop',
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Height", 'jupiter-donut'),
            "param_name" => "image_height",
            "value" => "350",
            "min" => "100",
            "max" => "1000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop',
                )
            )
        ),
        array(
            "heading" => __("Slide Direction", 'jupiter-donut'),
            "description" => __("", 'jupiter-donut'),
            "param_name" => "direction",
            "value" => array(
                __("Horizontal", 'jupiter-donut') => "horizontal",
                __("Vertical", 'jupiter-donut') => "vertical"
            ),
            "type" => "dropdown"
        ),
        array(
            "type" => "range",
            "heading" => __("Animation Speed", 'jupiter-donut'),
            "param_name" => "animation_speed",
            "value" => "700",
            "min" => "100",
            "max" => "3000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Slideshow Speed", 'jupiter-donut'),
            "param_name" => "slideshow_speed",
            "value" => "7000",
            "min" => "1000",
            "max" => "20000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Direction Nav", 'jupiter-donut'),
            "param_name" => "direction_nav",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),
        $add_device_visibility,
        array(
            "type" => "toggle",
            "heading" => __("Lazyload", 'jupiter-donut'),
            "param_name" => "lazyload",
            "value" => "false",
        ),
        array(
            "type" => "toggle",
            "heading" => __("Disable Lazyload", 'jupiter-donut'),
            "param_name" => "disable_lazyload",
            "value" => "false",
            "description" => __("Disable Lazyload is only available when 'Global Lazyload' is enabled in the Theme Options.", 'jupiter-donut'),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
