<?php
vc_map(array(
    "name" => __("Fullwidth Slideshow", 'jupiter-donut'),
    "base" => "mk_fullwidth_slideshow",
	'html_template' => dirname( __FILE__ ) . '/mk_fullwidth_slideshow.php',
    'icon' => 'icon-mk-fullwidth-slideshow vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Fullwdith image slider.', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "range",
            "heading" => __("Top & Bottom Padding", 'jupiter-donut'),
            "param_name" => "padding",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "value" => "30",
            "type" => "range"
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Top and Bottom Borders Color", 'jupiter-donut'),
            "param_name" => "border_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Box Background Color", 'jupiter-donut'),
            "param_name" => "bg_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "upload",
            "heading" => __("Background Image", 'jupiter-donut'),
            "param_name" => "bg_image",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Background Attachment", 'jupiter-donut'),
            "param_name" => "attachment",
            "width" => 150,
            "value" => array(
                __('Scroll', 'jupiter-donut') => "scroll",
                __('Fixed', 'jupiter-donut') => "fixed"
            ),
            "description" => __("The background-attachment property sets whether a background image is fixed or scrolls with the rest of the page. <a href='http://www.w3schools.com/CSSref/pr_background-attachment.asp'>Read More</a>", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Background Position", 'jupiter-donut'),
            "param_name" => "bg_position",
            "width" => 300,
            "value" => array(
                __('Left Top', 'jupiter-donut') => "left top",
                __('Center Top', 'jupiter-donut') => "center top",
                __('Right Top', 'jupiter-donut') => "right top",
                __('Left Center', 'jupiter-donut') => "left center",
                __('Center Center', 'jupiter-donut') => "center center",
                __('Right Center', 'jupiter-donut') => "right center",
                __('Left Bottom', 'jupiter-donut') => "left bottom",
                __('Center Bottom', 'jupiter-donut') => "center bottom",
                __('Right Bottom', 'jupiter-donut') => "right bottom"
            ),
            "description" => __("First value defines horizontal position and second vertical positioning.", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Enable 3D background", 'jupiter-donut'),
            "param_name" => "enable_3d",
            "value" => "false"
        ),
        array(
            "type" => "range",
            "heading" => __("3D Speed Factor", 'jupiter-donut'),
            "param_name" => "speed_factor",
            "min" => "-10",
            "max" => "10",
            "step" => "0.1",
            "unit" => 'factor',
            "value" => "0.3",
            "type" => "range"
        ),
        array(
            "type" => "attach_images",
            "heading" => __("Add Images", 'jupiter-donut'),
            "param_name" => "images",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Stretch Images to the Container?", 'jupiter-donut'),
            "param_name" => "stretch_images",
            "value" => "false",
            "description" => __("If enabled, the images will scale up to fit the container.", 'jupiter-donut')
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
            "description" => __("", 'jupiter-donut')
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
