<?php
vc_map(array(
    "name" => __("Image Slideshow", 'jupiter-donut'),
    "base" => "mk_image_slideshow",
	'html_template' => dirname( __FILE__ ) . '/mk_image_slideshow.php',
    'icon' => 'icon-mk-image-slideshow vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Simple image slideshow.', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Heading Title", 'jupiter-donut'),
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
            "type" => "range",
            "heading" => __("Width", 'jupiter-donut'),
            "param_name" => "image_width",
            "value" => "770",
            "min" => "100",
            "max" => "1380",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
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
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Margin Top", 'jupiter-donut'),
            "param_name" => "margin_top",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut'),
            "param_name" => "margin_bottom",
            "value" => "0",
            "min" => "0",
            "max" => "100",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "heading" => __("Animation Effect", 'jupiter-donut'),
            "description" => __("", 'jupiter-donut'),
            "param_name" => "effect",
            "value" => array(
                __("Fade", 'jupiter-donut') => "fade",
                __("Slide", 'jupiter-donut') => "slide"
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
            "description" => __("Pauses the slideshow when hovering over slider, then resumes when no longer hovering", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Smooth Height", 'jupiter-donut'),
            "param_name" => "smooth_height",
            "value" => "true",
            "description" => __("Allows height of slider to animate smoothly in horizontal mode", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Direction Nav", 'jupiter-donut'),
            "param_name" => "direction_nav",
            "value" => "true",
            "description" => __("The next/pervious buttons to navigate between slides", 'jupiter-donut')
        ),
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
