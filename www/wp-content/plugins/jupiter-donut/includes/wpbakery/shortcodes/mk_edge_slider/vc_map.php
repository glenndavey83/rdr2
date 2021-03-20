<?php
vc_map(array(
    "name" => __("Edge Slider", 'jupiter-donut'),
    "base" => "mk_edge_slider",
	'html_template' => dirname( __FILE__ ) . '/mk_edge_slider.php',
    'icon' => 'icon-mk-edge-slider vc_mk_element-icon',
    "admin_enqueue_js" => JUPITER_DONUT_INCLUDES_URL . "/wpbakery/shortcodes/mk_edge_slider/vc_admin.js",
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Powerful Edge Slider.', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "toggle",
            "heading" => __("First Element?", 'jupiter-donut'),
            "param_name" => "first_el",
            "value" => "false",
            "description" => __("If you are not using this slideshow as first element in content then disable this option. This option is useful only when Transparent Header style is enabled in this page, having this option enabled will allow the header skin follow slide item's => header skin.", 'jupiter-donut')
        ),

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Slideshow Background Color", 'jupiter-donut'),
            "param_name" => "swiper_bg",
            "value" => "#000",
            "description" => __("Choose it for a color behind the slides. Useful with some animation types where background is revealed.", 'jupiter-donut')
        ),

        array(
            "type" => "toggle",
            "heading" => __("Parallax Background?", 'jupiter-donut'),
            "param_name" => "parallax",
            "value" => "false",
            "description" => __("Please note that Smooth Scroll option should be enabled for the parallax feature function correctly. Smooth Scroll option is loctated in Theme Options > General Settings > Site Settings.", 'jupiter-donut')
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select Specific Slides', 'jupiter-donut' ),
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
                __("DESC (descending order)", 'jupiter-donut') => "DESC",
                __("ASC (ascending order)", 'jupiter-donut') => "ASC",

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
            "type" => "toggle",
            "heading" => __("Full Height?", 'jupiter-donut'),
            "param_name" => "full_height",
            "value" => "true",
            "description" => __("If you do not want full screen height slideshow disable this option. If you disable this option set the height of slideshow using below option.", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Slideshow Height", 'jupiter-donut'),
            "param_name" => "height",
            "value" => "700",
            "min" => "100",
            "max" => "2000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("This option only works when above option is disabled.", 'jupiter-donut')
        ),
        array(
            "heading" => __("Animation Effect", 'jupiter-donut'),
            "description" => __("", 'jupiter-donut'),
            "param_name" => "animation_effect",
            "value" => array(
                __("Slide", 'jupiter-donut') => "slide",
                __("Slide Vertical", 'jupiter-donut') => "vertical_slide",
                __("Zoom", 'jupiter-donut') => "zoom",
                __("Zoom Out", 'jupiter-donut') => "zoom_out",
                __("Horizontal Curtain", 'jupiter-donut') => "horizontal_curtain",
                __("Fade", 'jupiter-donut') => "fade",
                __("Perspective Flip", 'jupiter-donut') => "perspective_flip",
                __("Kenburned", 'jupiter-donut') => "kenburned"
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
            "heading" => __("Pause Time", 'jupiter-donut'),
            "param_name" => "slideshow_speed",
            "value" => "7000",
            "min" => "1000",
            "max" => "20000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("How long each slide will show.", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Direction Nav", 'jupiter-donut'),
            "param_name" => "direction_nav",
            "width" => 300,
            "value" => array(
                __('Rounded Slide', 'jupiter-donut') => "roundslide",
                __('Rounded', 'jupiter-donut') => "round",
                __('Split', 'jupiter-donut') => "slit",
                __('Thumbnail Flip', 'jupiter-donut') => "thumbflip",
                __('-- No Navigation', 'jupiter-donut') => "none"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Pagination", 'jupiter-donut'),
            "param_name" => "pagination",
            "width" => 300,
            "value" => array(
                __('Stroke', 'jupiter-donut') => "stroke",
                __('Small Dot With Stroke', 'jupiter-donut') => "small_dot_stroke",
                __('-- No Pagination', 'jupiter-donut') => "none"
            ),
            "description" => __("", 'jupiter-donut')
        ),

        array(
            "type" => "toggle",
            "heading" => __("Scroll to Bottom Arrow", 'jupiter-donut'),
            "param_name" => "skip_arrow",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),

        array(
            "type" => "toggle",
            "heading" => __("Lazy Load", 'jupiter-donut'),
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
