<?php
vc_map(array(
    "name" => __("Laptop Slideshow", 'jupiter-donut'),
    "base" => "mk_laptop_slideshow",
	'html_template' => dirname( __FILE__ ) . '/mk_laptop_slideshow.php',
    'icon' => 'icon-mk-laptop-slideshow vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Slider inside laptop frame', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut'),
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),

        array(
            "type" => "attach_images",
            "heading" => __("Add Images", 'jupiter-donut'),
            "param_name" => "images",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "heading" => __("Slide Nav Size", 'jupiter-donut'),
            "description" => __("This option will only change the slide navigation button sizes.", 'jupiter-donut'),
            "param_name" => "size",
            "value" => array(
                __("Full Width", 'jupiter-donut') => "full",
                __("One Half", 'jupiter-donut') => "one-half",
                __("One Third", 'jupiter-donut') => "one-third",
                __("One Fourth", 'jupiter-donut') => "one-fourth"
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
            "heading" => __("Pause on Hover", 'jupiter-donut'),
            "param_name" => "pause_on_hover",
            "value" => "false",
            "description" => __("Pauses the slideshow when hovering over slider, then resume when no longer hovering", 'jupiter-donut')
        ),
        $add_css_animations,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
