<?php
vc_map(array(
    "name" => __("Flexslider", 'jupiter-donut'),
    "base" => "mk_flexslider",
	'html_template' => dirname( __FILE__ ) . '/mk_flexslider.php',
    'icon' => 'icon-mk-flex-slider vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Flexslider with captions.', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut'),
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Count", 'jupiter-donut'),
            "param_name" => "count",
            "value" => "10",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'slides',
            "description" => __("How many slides you would like to show? (-1 means unlimited)", 'jupiter-donut')
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific slides', 'jupiter-donut' ),
            'param_name'  => 'slides',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                                // In UI show results except selected. NB! You should manually check values in backend
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            "heading" => __("Order", 'jupiter-donut'),
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut'),
            "param_name" => "order",
            "value" => array(
                __("ASC (ascending order)", 'jupiter-donut') => "ASC",
                __("DESC (descending order)", 'jupiter-donut') => "DESC"
            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Orderby", 'jupiter-donut'),
            "description" => __("Sort retrieved slides items by parameter.", 'jupiter-donut'),
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
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
            "description" => __("Pause the slideshow when hovering over slider, then resume when no longer hovering", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Smooth Height", 'jupiter-donut'),
            "param_name" => "smooth_height",
            "value" => "true",
            "description" => __("Allow height of the slider to animate smoothly in horizontal mode", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Direction Nav", 'jupiter-donut'),
            "param_name" => "direction_nav",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Caption Background Color", 'jupiter-donut'),
            "param_name" => "caption_bg_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Caption Text Color", 'jupiter-donut'),
            "param_name" => "caption_color",
            "value" => "#fff",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Caption Opacity", 'jupiter-donut'),
            "param_name" => "caption_bg_opacity",
            "value" => "0.6",
            "min" => "0.1",
            "max" => "1",
            "step" => "0.1",
            "unit" => 'alpha',
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
