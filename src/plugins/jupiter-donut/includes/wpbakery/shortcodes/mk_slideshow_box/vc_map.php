<?php

vc_map(array(
    "name" => __("Slideshow Box", 'jupiter-donut') ,
    "base" => "mk_slideshow_box",
	'html_template' => dirname( __FILE__ ) . '/mk_slideshow_box.php',
    "as_parent" => array(
        'except' => 'mk_page_section'
    ) ,
	'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_slideshow_box/vc_front.js',
    "content_element" => true,
    "show_settings_on_create" => true,
    "description" => __("Slideshow Box For your contents.", 'jupiter-donut') ,
    'icon' => 'icon-mk-custom-box vc_mk_element-icon',
    "category" => __('General', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "attach_images",
            "heading" => __("Add Images", 'jupiter-donut') ,
            "param_name" => "images",
            "value" => "",
            "description" => __("Add images to your background slideshow", 'jupiter-donut')
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Cover whole background", 'jupiter-donut') ,
            "param_name" => "background_cover",
            "description" => __("Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area.", 'jupiter-donut') ,
            "value" => "true"
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Background Repeat", 'jupiter-donut') ,
            "param_name" => "bg_repeat",
            "value" => array(
                __('Repeat', 'jupiter-donut') => "repeat",
                __('No Repeat', 'jupiter-donut') => "no-repeat",
                __('Horizontal Repeat', 'jupiter-donut') => "repeat-x",
                __('Vertical Repeat', 'jupiter-donut') => "repeat-y"
            ) ,
            "description" => __("", 'jupiter-donut') ,
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Background Position", 'jupiter-donut') ,
            "param_name" => "bg_position",
            "width" => 300,
            "value" => array(
                __('Center Center', 'jupiter-donut') => "center center",
                __('Left Top', 'jupiter-donut') => "left top",
                __('Center Top', 'jupiter-donut') => "center top",
                __('Right Top', 'jupiter-donut') => "right top",
                __('Left Center', 'jupiter-donut') => "left center",
                __('Right Center', 'jupiter-donut') => "right center",
                __('Left Bottom', 'jupiter-donut') => "left bottom",
                __('Center Bottom', 'jupiter-donut') => "center bottom",
                __('Right Bottom', 'jupiter-donut') => "right bottom"
            ) ,
            "description" => __("First value defines horizontal position and second vertical positioning.", 'jupiter-donut'),
        ) ,

        array(
            "type" => "range",
            "heading" => __("Slideshow Speed", 'jupiter-donut') ,
            "param_name" => "slideshow_speed",
            "min" => "1000",
            "max" => "10000",
            "step" => "1",
            "unit" => 'ms',
            "value" => "3000"
        ) ,
        array(
            "type" => "range",
            "heading" => __("Transition Speed", 'jupiter-donut') ,
            "param_name" => "transition_speed",
            "min" => "100",
            "max" => "5000",
            "step" => "1",
            "unit" => 'ms',
            "value" => "1000"
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Color Overlay", 'jupiter-donut') ,
            "param_name" => "overlay",
            "value" => "",
            "description" => __("The overlay layer Color. You will need to change the alpha using this color picker to give an opacity to the color you choose.", 'jupiter-donut') ,
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Overlay Mask Pattern?", 'jupiter-donut') ,
            "param_name" => "slideshow_mask",
            "description" => __("Creates an overlay repeated pattern on your slideshow.", 'jupiter-donut') ,
            "value" => "false"
        ) ,
        array(
            "type" => "range",
            "heading" => __("Section Min Height", 'jupiter-donut') ,
            "param_name" => "section_height",
            "value" => "400",
            "min" => "0",
            "max" => "1000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Full Screen Height?", 'jupiter-donut') ,
            "param_name" => "full_height",
            "value" => array(
                __('No', 'jupiter-donut') => "false",
                __('Yes', 'jupiter-donut') => "true"
            ) ,
            "description" => __("Using this option you can make this slideshow box's height to cover the whole visible screen height (Not document height). Please note that if the inner content is larger than the window height, this feature will be disabled. Full height is browser resize sensitive and completely responsive.", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Full Screen Width Content?", 'jupiter-donut') ,
            "param_name" => "full_width_cnt",
            "value" => array(
                __('No', 'jupiter-donut') => "false",
                __('Yes', 'jupiter-donut') => "true"
            ) ,
            "description" => __("If you enable this option you're shortcodes within Slideshow Box will become full width", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Padding Top", 'jupiter-donut') ,
            "param_name" => "padding_top",
            "value" => "10",
            "min" => "0",
            "max" => "200",
            "step" => "1",
            "unit" => 'px',
            "description" => __("The space between the content and top border of slideshow content section", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Padding Bottom", 'jupiter-donut') ,
            "param_name" => "padding_bottom",
            "value" => "10",
            "min" => "0",
            "max" => "200",
            "step" => "1",
            "unit" => 'px',
            "description" => __("The space between the content and bottom border of slideshow content section", 'jupiter-donut')
        ) ,
        array(
            "heading" => __("Order", 'jupiter-donut') ,
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut') ,
            "param_name" => "order",
            "value" => array(
                __("ASC (ascending order)", 'jupiter-donut') => "ASC",
                __("DESC (descending order)", 'jupiter-donut') => "DESC"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "heading" => __("Orderby", 'jupiter-donut') ,
            "description" => __("Sorts retrieved slidebox items by parameter.", 'jupiter-donut') ,
            "param_name" => "orderby",
            "value" => array(
                        __("Date", 'jupiter-donut') => "date",
                        __("Posts In (manually selected posts)", 'jupiter-donut') => "post__in",
                        __("Post Id", 'jupiter-donut') => "id",
                        __("Title", 'jupiter-donut') => "title",
                        __("Random", 'jupiter-donut') => "rand",
                        __("Author", 'jupiter-donut') => "author"
                    ),
            "type" => "dropdown"
        ) ,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        ) ,
    ) ,
    "js_view" => 'VcColumnView'
));
