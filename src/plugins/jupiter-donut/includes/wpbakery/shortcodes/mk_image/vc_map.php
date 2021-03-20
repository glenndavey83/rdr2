<?php

vc_map(array(
    'name'        => __( 'Image', 'jupiter-donut' ),
    'base'        => 'mk_image',
	'html_template' => dirname( __FILE__ ) . '/mk_image.php',
    'category'    => __( 'General', 'jupiter-donut' ),
    'description' => __( 'Adds Image element with many styles.', 'jupiter-donut' ),
    'icon'        => 'icon-mk-image vc_mk_element-icon',
    'params'      => array(
        array(
            "type" => "textfield",
            "heading" => __("Heading Title", 'jupiter-donut') ,
            "param_name" => "heading_title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "upload",
            "heading" => __("Upload Your image", 'jupiter-donut') ,
            "param_name" => "src",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
         array(
            "heading" => __("Image Size", 'jupiter-donut'),
            "description" => __("Please note that image size option will work if the image is uploaded locally in this server. If it's hot-linked from different source you will get the full image size!", 'jupiter-donut'),
            "param_name" => "image_size",
            "value" => mk_get_image_sizes(),
            "type" => "dropdown"
        ),
        /*array(
            "type" => "toggle",
            "heading" => __("Image Cropping", 'jupiter-donut') ,
            "param_name" => "crop",
            "value" => "true",
            "description" => __("If you dont want to crop your image based on the dimensions you defined above disable this option. Only wdith will be used to give the image container max-width property.", 'jupiter-donut')
        ) ,*/
        array(
            "type" => "range",
            "heading" => __("Image Width", 'jupiter-donut') ,
            "param_name" => "image_width",
            "value" => "800",
            "min" => "10",
            "max" => "2600",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Image Height", 'jupiter-donut') ,
            "param_name" => "image_height",
            "value" => "350",
            "min" => "10",
            "max" => "5000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop'
                )
            )
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("SVG Enable?", 'jupiter-donut') ,
            "param_name" => "svg",
            "value" => "false",
            "description" => __("If enabled max-width property will be added to image tag and you should enable this option if you are using SVG format in this image shortcode.", 'jupiter-donut')
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Lightbox", 'jupiter-donut') ,
            "param_name" => "lightbox",
            "value" => "false",
            "description" => __("If you would like to have lightbox (image zoom in a frame) enable this option.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Custom Lightbox URL", 'jupiter-donut') ,
            "param_name" => "custom_lightbox",
            "value" => "",
            "description" => __("You can use this field to add your custom lightbox URL to appear in pop up box. it can be image SRC, youtube URL.", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Lightbox Group rel", 'jupiter-donut') ,
            "param_name" => "group",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Image Frame Style", 'jupiter-donut') ,
            "param_name" => "frame_style",
            "value" => array(
                "No Frame" => "simple",
                "Rounded Frame" => "rounded",
                "Single Line Frame" => "single_line",
                "Gray Border Frame" => "gray_border",
                "Border With Shadow" => "border_shadow",
                "Shadow Only" => "shadow_only"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Image Link", 'jupiter-donut') ,
            "param_name" => "link",
            "value" => "",
            "description" => __("Optionally you can link your image.", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Target", 'jupiter-donut') ,
            "param_name" => "target",
            "width" => 200,
            "value" => $target_arr,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Image Caption Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Image Caption Description", 'jupiter-donut') ,
            "param_name" => "desc",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Image Caption Location", 'jupiter-donut') ,
            "param_name" => "caption_location",
            "value" => array(
                "Inside Image" => "inside-image",
                "Outside Image" => "outside-image"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type"        => "toggle",
            "heading"     => __( "Image Hover Overlay", 'jupiter-donut' ) ,
            "param_name"  => "hover_image_overlay",
            "value"       => "true",
            "description" => __( "", 'jupiter-donut' ),
            "dependency" => array(
                'element' => "lightbox",
                'value' => array(
                    'true'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Align", 'jupiter-donut') ,
            "param_name" => "align",
            "width" => 150,
            "value" => array(
                __('Left', 'jupiter-donut') => "left",
                __('Right', 'jupiter-donut') => "right",
                __('Center', 'jupiter-donut') => "center"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut') ,
            "param_name" => "margin_bottom",
            "value" => "10",
            "min" => "-50",
            "max" => "300",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        $add_device_visibility,
        $add_css_animations,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'jupiter-donut')
        ),
        array(
            'type'        => 'message',
            'heading'     => __( 'Drop Shadow', 'jupiter-donut' ),
            'param_name'  => 'drop_shadow_placeholder',
            'description' => __( 'Set <strong>Image Frame Style</strong> option to <strong>No Frame</strong>, <strong>Rounded Frame</strong>, <strong>Single Line Frame</strong> or <strong>Gray Border Frame</strong> to enable drop shadow.', 'jupiter-donut' ),
            'dependency'  => array(
                'element' => 'frame_style',
                'value'   => array(
                    'border_shadow',
                    'shadow_only'
                )
            ),
            'group'      => __( 'Styles & Colors', 'jupiter-donut' )
        ),
        array(
            'type'        => 'toggle',
            'heading'     => __( 'Drop Shadow', 'jupiter-donut' ),
            'param_name'  => 'drop_shadow',
            'value'       => 'false',
            'description' => __( 'Enable drop shadow for the image.', 'jupiter-donut' ),
            'dependency'  => array(
                'element' => 'frame_style',
                'value'   => array(
                    'simple',
                    'rounded',
                    'single_line',
                    'gray_border'
                )
            ),
            'group'      => __( 'Styles & Colors', 'jupiter-donut' )
        ),
        array(
            'type'        => 'range',
            'heading'     => __( 'Angle', 'jupiter-donut' ),
            'param_name'  => 'drop_shadow_angle',
            'value'       => '45',
            'min'         => '0',
            'max'         => '360',
            'step'        => '1',
            'unit'        => 'deg',
            'description' => __( 'Set the angle of the shadow between 0 and 360.', 'jupiter-donut' ),
            'dependency'  => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'jupiter-donut' )
        ),
        array(
            'type'        => 'range',
            'heading'     => __( 'Distance', 'jupiter-donut' ),
            'param_name'  => 'drop_shadow_distance',
            'value'       => '8',
            'min'         => '1',
            'max'         => '100',
            'step'        => '1',
            'unit'        => 'px',
            'description' => __( 'Set the distance/size of the shadow between 1 and 100.', 'jupiter-donut' ),
            'dependency'  => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'jupiter-donut' )
        ),
        array(
            'type'        => 'range',
            'heading'     => __( 'Blur', 'jupiter-donut' ),
            'param_name'  => 'drop_shadow_blur',
            'value'       => '20',
            'min'         => '0',
            'max'         => '100',
            'step'        => '1',
            'unit'        => 'px',
            'description' => __( 'Set the blur radius of the shadow between 0 and 100.', 'jupiter-donut' ),
            'dependency'  => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'jupiter-donut' )
        ),
        array(
            'type'       => 'alpha_colorpicker',
            'heading'    => __( 'color', 'jupiter-donut' ),
            'param_name' => 'drop_shadow_color',
            'value'      => 'rgba(0,0,0,0.5)',
            'dependency' => array(
                'element' => 'drop_shadow',
                'value'   => array(
                    'true'
                )
            ),
            'group'      => __( 'Styles & Colors', 'jupiter-donut' )
        ),
		$mk_vc_map_parallax_scroll['pxs'],
		$mk_vc_map_parallax_scroll['pxs_x'],
		$mk_vc_map_parallax_scroll['pxs_y'],
		$mk_vc_map_parallax_scroll['pxs_z'],
		$mk_vc_map_parallax_scroll['pxs_smoothness'],
	)
));
