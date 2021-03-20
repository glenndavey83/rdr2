<?php
vc_map(array(
    "name" => __("Theatre Slider", 'jupiter-donut') ,
    "base" => "mk_theatre_slider",
	'html_template' => dirname( __FILE__ ) . '/mk_theatre_slider.php',
    'icon' => 'vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut') ,
    'description' => __('', 'jupiter-donut') ,
    "params" => array(
        array(
            "heading" => __("Background Style", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "background_style",
            "value" => array(
                __("Desktop", 'jupiter-donut') => "desktop_style",
                __("Laptop", 'jupiter-donut') => "laptop_style"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "range",
            "heading" => __("Slider Max Width", 'jupiter-donut') ,
            "param_name" => "max_width",
            "value" => "900",
            "min" => "320",
            "max" => "1200",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "heading" => __("Video Host", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "host",
            "value" => array(
                __("Self Hosted", 'jupiter-donut') => "self_hosted",
                __("Social Hosted", 'jupiter-donut') => "social_hosted"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "upload",
            "heading" => __("MP4 Format", 'jupiter-donut') ,
            "param_name" => "mp4",
            "value" => "",
            "description" => __("Compatibility for Safari, IE9", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "host",
                'value' => array(
                    'self_hosted'
                )
            )
        ) ,
        array(
            "type" => "upload",
            "heading" => __("WebM Format", 'jupiter-donut') ,
            "param_name" => "webm",
            "value" => "",
            "description" => __("Compatibility for Firefox4, Opera, and Chrome", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "host",
                'value' => array(
                    'self_hosted'
                )
            )
        ) ,
        array(
            "type" => "upload",
            "heading" => __("OGV Format", 'jupiter-donut') ,
            "param_name" => "ogv",
            "value" => "",
            "description" => __("Compatibility for older Firefox and Opera versions", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "host",
                'value' => array(
                    'self_hosted'
                )
            )
        ) ,
        array(
            "type" => "upload",
            "heading" => __("Video Preview image (and fallback image)", 'jupiter-donut') ,
            "param_name" => "poster_image",
            "value" => "",
            "description" => __("This Image will shown until video load. in case of video is not supported or did not load the image will remain as fallback.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "host",
                'value' => array(
                    'self_hosted'
                )
            )
        ) ,
        array(
            "heading" => __("Stream Host Website", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "stream_host_website",
            "value" => array(
                __("Youtube", 'jupiter-donut') => "youtube",
                __("Vimeo", 'jupiter-donut') => "vimeo"
            ) ,
            "type" => "dropdown",
            "dependency" => array(
                'element' => "host",
                'value' => array(
                    'social_hosted'
                )
            ) ,
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Show Social Video Controls", 'jupiter-donut') ,
            "param_name" => "video_controls",
            "value" => "true",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "stream_host_website",
                'value' => array(
                    'youtube'
                )
            )
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Video ID", 'jupiter-donut') ,
            "param_name" => "stream_video_id",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "host",
                'value' => array(
                    'social_hosted'
                )
            )
        ) ,
        array(
            "heading" => __("Slider Align", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "align",
            "value" => array(
                __("Left", 'jupiter-donut') => "left",
                __("Center", 'jupiter-donut') => "center",
                __("Right", 'jupiter-donut') => "right"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut') ,
            "param_name" => "margin_bottom",
            "value" => "25",
            "min" => "10",
            "max" => "250",
            "step" => "1",
            "unit" => 'px',
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
