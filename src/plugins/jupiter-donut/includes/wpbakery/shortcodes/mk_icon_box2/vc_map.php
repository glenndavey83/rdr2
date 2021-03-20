<?php
vc_map(array(
    "name" => __("Icon Box 2", 'jupiter-donut') ,
    "base" => "mk_icon_box2",
	'html_template' => dirname( __FILE__ ) . '/mk_icon_box2.php',
    "category" => __('General', 'jupiter-donut') ,
    'icon' => 'icon-mk-icon-box vc_mk_element-icon',
    'description' => __('Powerful & versatile Icon Boxes.', 'jupiter-donut') ,
    "params" => array(
        array(
            "heading" => __("Icon Type?", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "icon_type",
            "value" => array(
                __("Icon", 'jupiter-donut') => "icon",
                __("Image", 'jupiter-donut') => "image"
            ) ,
            "type" => "dropdown"
        ) ,

        array(
            "heading" => __("Icon/Image Size", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "icon_size",
            "value" => array(
                __("16", 'jupiter-donut') => "16",
                __("32", 'jupiter-donut') => "32",
                __("48", 'jupiter-donut') => "48",
                __("64", 'jupiter-donut') => "64",
                __("128", 'jupiter-donut') => "128",
                __("No Limit (Images only)", 'jupiter-donut') => "inherit"
            ) ,
            "type" => "dropdown"
        ) ,

        array(
            "type" => "upload",
            "heading" => __("Icon Image", 'jupiter-donut') ,
            "param_name" => "icon_image",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'image'
                )
            )
        ) ,

        array(
            "type" => "icon_selector",
            "heading" => __("Icon", 'jupiter-donut') ,
            "param_name" => "icon",
            "value" => "mk-li-smile",
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Color", 'jupiter-donut') ,
            "param_name" => "icon_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Background Color", 'jupiter-donut') ,
            "param_name" => "icon_background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Border Color", 'jupiter-donut') ,
            "param_name" => "icon_border_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Hover Color", 'jupiter-donut') ,
            "param_name" => "icon_hover_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Hover Background Color", 'jupiter-donut') ,
            "param_name" => "icon_hover_background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icon Hover Border Color", 'jupiter-donut') ,
            "param_name" => "icon_hover_border_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'icon'
                )
            )
        ) ,

        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Title Font Size", 'jupiter-donut') ,
            "param_name" => "title_size",
            "value" => "20",
            "min" => "5",
            "max" => "40",
            "step" => "1",
            "unit" => 'px'
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Title Font Weight", 'jupiter-donut') ,
            "param_name" => "title_weight",
            "width" => 150,
            "value" => array(
                __('Default', 'jupiter-donut') => "inherit",
                __('Bold', 'jupiter-donut') => "bold",
                __('Bolder', 'jupiter-donut') => "bolder",
                __('Normal', 'jupiter-donut') => "normal",
                __('Light', 'jupiter-donut') => "300"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Title Font Color", 'jupiter-donut') ,
            "param_name" => "title_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Title Top Padding", 'jupiter-donut') ,
            "param_name" => "title_top_padding",
            "value" => "10",
            "min" => "5",
            "max" => "60",
            "step" => "1",
            "unit" => 'px'
        ) ,
        array(
            "type" => "range",
            "heading" => __("Title Bottom Padding", 'jupiter-donut') ,
            "param_name" => "title_bottom_padding",
            "value" => "10",
            "min" => "5",
            "max" => "60",
            "step" => "1",
            "unit" => 'px'
        ) ,

        array(
            "type" => "textarea_html",
            "holder" => "div",
            'toolbar' => 'full',
            "heading" => __("Description", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("Enter your content.", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Description Font Color", 'jupiter-donut') ,
            "param_name" => "description_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Box Align", 'jupiter-donut') ,
            "param_name" => "align",
            "description" => __("This option will align the whole box content.", 'jupiter-donut') ,
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            )
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Read More URL", 'jupiter-donut') ,
            "param_name" => "read_more_url",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
         array(
            "type" => "dropdown",
            "heading" => __("Read More Link Target", 'jupiter-donut') ,
            "param_name" => "link_target",
            "width" => 200,
            "value" => $target_arr,
            "description" => __("", 'jupiter-donut')
        ) ,
        $add_css_animations,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
