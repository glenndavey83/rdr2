<?php
vc_map(array(
    "name" => __("Flip Box", 'jupiter-donut') ,
    "base" => "mk_flipbox",
	'html_template' => dirname( __FILE__ ) . '/mk_flipbox.php',
    'icon' => 'icon-mk-tab-slider vc_mk_element-icon',
    "category" => __('General', 'jupiter-donut') ,
    'description' => __('Flip based boxes.', 'jupiter-donut') ,
    'params' => array(
        array(
            "type" => "dropdown",
            "heading" => __("Flip Direction", 'jupiter-donut') ,
            "param_name" => "flip_direction",
            "width" => 300,
            "value" => array(
                __('Horizontal', 'jupiter-donut') => "horizontal",
                __('Vertical', 'jupiter-donut') => "vertical"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Front Background Color", 'jupiter-donut') ,
            "param_name" => "front_background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Back Background Color", 'jupiter-donut') ,
            "param_name" => "back_background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "heading" => __("Minimum Height", 'jupiter-donut') ,
            "param_name" => "min_height",
            "value" => "300",
            "min" => "250",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            'type' => 'range'
        ) ,

        array(
            "heading" => __("Icon Type", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "icon_type",
            "value" => array(
                __("Image", 'jupiter-donut') => "image",
                __("Icon", 'jupiter-donut') => "icon"
            ) ,
            "type" => "dropdown"
        ) ,

        array(
            "type" => "upload",
            "heading" => __("Image", 'jupiter-donut') ,
            "param_name" => "image",
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
            "heading" => __("Icon Size", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "icon_size",
            "value" => array(
                __("16px", 'jupiter-donut') => "16",
                __("32px", 'jupiter-donut') => "32",
                __("64px", 'jupiter-donut') => "64",
                __("128px", 'jupiter-donut') => "128",
            ) ,
            "type" => "dropdown",
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
            "type" => "textfield",
            "heading" => __("Front Title", 'jupiter-donut') ,
            "param_name" => "front_title",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
        ) ,
        array(
            "heading" => __("Front Title Font Size", 'jupiter-donut') ,
            "param_name" => "front_title_size",
            "value" => "20",
            "min" => "15",
            "max" => "30",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            'type' => 'range'
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Front Title Font Color", 'jupiter-donut') ,
            "param_name" => "front_title_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "textfield",
            "heading" => __("Back Title", 'jupiter-donut') ,
            "param_name" => "back_title",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
        ) ,
        array(
            "heading" => __("Back Title Font Size", 'jupiter-donut') ,
            "param_name" => "back_title_size",
            "value" => "20",
            "min" => "15",
            "max" => "30",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            'type' => 'range'
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Back Title Font Color", 'jupiter-donut') ,
            "param_name" => "back_title_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Title Font Weight", 'jupiter-donut') ,
            "param_name" => "font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "textarea",
            "heading" => __("Front Description", 'jupiter-donut') ,
            "param_name" => "front_desc",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
        ) ,
        array(
            "heading" => __("Front Description Font Size", 'jupiter-donut') ,
            "param_name" => "front_desc_size",
            "value" => "20",
            "min" => "15",
            "max" => "30",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            'type' => 'range'
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Front Description Font Color", 'jupiter-donut') ,
            "param_name" => "front_desc_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "textarea",
            "heading" => __("Back Description", 'jupiter-donut') ,
            "param_name" => "back_desc",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
        ) ,
        array(
            "heading" => __("Back Description Font Size", 'jupiter-donut') ,
            "param_name" => "back_desc_size",
            "value" => "20",
            "min" => "15",
            "max" => "30",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut') ,
            'type' => 'range'
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Back Description Font Color", 'jupiter-donut') ,
            "param_name" => "back_desc_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,

        array(
            "type" => "textfield",
            "heading" => __("Button Url", 'jupiter-donut') ,
            "param_name" => "button_url",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Button Target", 'jupiter-donut') ,
            "param_name" => "button_target",
            "width" => 200,
            "value" => $target_arr,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Button Text", 'jupiter-donut') ,
            "param_name" => "button_text",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Button Background Color", 'jupiter-donut') ,
            "param_name" => "button_bg_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Button Hover Background Color", 'jupiter-donut') ,
            "param_name" => "button_bg_hover_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Button Text Skin", 'jupiter-donut') ,
            "param_name" => "button_text_skin",
            "width" => 300,
            "value" => array(
                __('Light', 'jupiter-donut') => "light",
                __('Dark', 'jupiter-donut') => "dark"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
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
