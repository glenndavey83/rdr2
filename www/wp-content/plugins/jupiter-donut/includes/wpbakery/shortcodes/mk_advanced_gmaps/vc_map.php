<?php
vc_map(array(
    "name" => __("Advanced Google Maps", 'jupiter-donut'),
    "base" => "mk_advanced_gmaps",
	'html_template' => dirname( __FILE__ ) . '/mk_advanced_gmaps.php',
    'icon' => 'icon-mk-advanced-google-maps vc_mk_element-icon',
    "admin_enqueue_js" => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_advanced_gmaps/vc_admin.js',
    "admin_enqueue_css" => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_advanced_gmaps/vc_admin.css',
    'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_advanced_gmaps/vc_admin.js',
    'front_enqueue_css' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/mk_advanced_gmaps/vc_admin.css',
    'description' => __( 'Powerful Google Maps element.', 'jupiter-donut' ),
    "category" => __('Social', 'jupiter-donut'),
    "params" => array(

        array(
            "type" => "toggle",
            "heading" => __("Custom markers?", 'jupiter-donut'),
            "param_name" => "custom_markers",
            "value" => "false",
            "description" => __("Add custom markers per address.", 'jupiter-donut')
        ),

        array(
            "type" => "textfield",
            "heading" => __("Address 1 : Latitude", 'jupiter-donut'),
            "param_name" => "latitude",
            "value" => "",
            "description" => __('Example : 40.748829', 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Address 1 : Longitude", 'jupiter-donut'),
            "param_name" => "longitude",
            "value" => "",
            "description" => __('Example : -73.984118', 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Address 1 : Full Address Text (shown in tooltip)", 'jupiter-donut'),
            "param_name" => "address",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "upload",
            "heading" => __("Upload Marker Icon for address 1", 'jupiter-donut'),
            "param_name" => "custom_marker_1",
            "value" => "",
            "description" => __("If left blank it will fall back to a default shared marker that you can set below.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "custom_markers",
                'value' => array(
                    'true'
                )
            )
        ),

        array(
            "type" => "textfield",
            "heading" => __("Address 2 : Latitude", 'jupiter-donut'),
            "param_name" => "latitude_2",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Address 2 : Longitude", 'jupiter-donut'),
            "param_name" => "longitude_2",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Address 2 : Full Address Text (shown in tooltip)", 'jupiter-donut'),
            "param_name" => "address_2",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "upload",
            "heading" => __("Upload Marker Icon for address 2", 'jupiter-donut'),
            "param_name" => "custom_marker_2",
            "value" => "",
            "description" => __("If left blank it will fall back to a default shared marker that you can set below.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "custom_markers",
                'value' => array(
                    'true'
                )
            )
        ),



        array(
            "type" => "textfield",
            "heading" => __("Address 3 : Latitude", 'jupiter-donut'),
            "param_name" => "latitude_3",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Address 3 : Longitude", 'jupiter-donut'),
            "param_name" => "longitude_3",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Address 3 : Full Address Text (shown in tooltip)", 'jupiter-donut'),
            "param_name" => "address_3",
            "value" => "",
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "upload",
            "heading" => __("Upload Marker Icon for address 3", 'jupiter-donut'),
            "param_name" => "custom_marker_3",
            "value" => "",
            "description" => __("If left blank it will fall back to a default shared marker that you can set below.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "custom_markers",
                'value' => array(
                    'true'
                )
            )
        ),



        array(
            "type" => "upload",
            "heading" => __("Upload Default Marker Icon", 'jupiter-donut'),
            "param_name" => "pin_icon",
            "value" => "",
            "description" => __("If left blank Google Default marker will be used.", 'jupiter-donut')
        ),


        array(
            "type" => "gmap_marker",
            "heading" => __("Need More Address?", 'jupiter-donut'),
            "param_name" => "additional_markers",
            "value" => "false",
            "description" => __("", 'jupiter-donut')
        ),

        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Content Box Background Color", 'jupiter-donut'),
            "param_name" => "content_bg_color",
            "value" => "#fff",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Content Box Font Color", 'jupiter-donut'),
            "param_name" => "content_font_color",
            "value" => "#777",
            "description" => __("", 'jupiter-donut')
        ),

         array(
            "type" => "dropdown",
            "heading" => __("Map Height", 'jupiter-donut'),
            "param_name" => "map_height",
            "value" => array(
                __("Custom (choose from below option)", 'jupiter-donut') => "custom",
                __("Screen Height", 'jupiter-donut') => "full",
            ),

        ),
         array(
            "type" => "range",
            "heading" => __("Custom Map Height", 'jupiter-donut'),
            "param_name" => "height",
            "value" => "300",
            "min" => "1",
            "max" => "1000",
            "step" => "1",
            "unit" => 'px',
            "description" => __('Enter map height in pixels. Example: 200).', 'jupiter-donut'),
            "dependency" => array(
                'element' => "map_height",
                'value' => array(
                    'custom'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Zoom", 'jupiter-donut'),
            "param_name" => "map_zoom",
            "value" => "14",
            "min" => "1",
            "max" => "19",
            "step" => "1",
            "unit" => '',
            "description" => __('', 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Pan Control", 'jupiter-donut'),
            "param_name" => "pan_control",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Draggable", 'jupiter-donut'),
            "param_name" => "draggable",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Zoom Control", 'jupiter-donut'),
            "param_name" => "zoom_control",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Map Type", 'jupiter-donut'),
            "param_name" => "map_type",
            "value" => array(
                __("ROADMAP (Displays a normal, default 2D map)", 'jupiter-donut') => "ROADMAP",
                __("HYBRID (Displays a photographic map + roads and city names)", 'jupiter-donut') => "HYBRID",
                __("SATELLITE (Displays a photographic map)", 'jupiter-donut') => "SATELLITE",
                __("TERRAIN (Displays a map with mountains, rivers, etc.)", 'jupiter-donut') => "TERRAIN"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Map Type Control", 'jupiter-donut'),
            "param_name" => "map_type_control",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Scale Control", 'jupiter-donut'),
            "param_name" => "scale_control",
            "value" => "true",
            "description" => __("", 'jupiter-donut')
        ),

        array(
            "type" => "dropdown",
            "heading" => __("Custom Map Styles", 'jupiter-donut'),
            "param_name" => "modify_json",
            "value" => array(
                __("No", 'jupiter-donut') => "false",
                __("Yes", 'jupiter-donut') => "true"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textarea_raw_html",
            "heading" => __("JSON", 'jupiter-donut'),
            "param_name" => "map_json",
            "holder" => 'div',
            "value" => "",
            "description" => __("Paste your code here", 'jupiter-donut'),
            "dependency" => array(
                'element' => "modify_json",
                'value' => array(
                    'true'
                )
            )
        ),

        array(
            "type" => "dropdown",
            "heading" => __("Modify Google Maps Hue, Saturation, Lightness", 'jupiter-donut'),
            "param_name" => "modify_coloring",
            "value" => array(
                __("No", 'jupiter-donut') => "false",
                __("Yes", 'jupiter-donut') => "true"
            ),
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "modify_json",
                'value' => array(
                    'false'
                )
            )
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Hue", 'jupiter-donut'),
            "param_name" => "hue",
            "value" => "#ccc",
            "description" => __("Sets the hue of the feature to match the hue of the color supplied. Note that the saturation and lightness of the feature is conserved, which means, the feature will not perfectly match the color supplied .", 'jupiter-donut'),
            "dependency" => array(
                'element' => "modify_coloring",
                'value' => array(
                    'true'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Saturation", 'jupiter-donut'),
            "param_name" => "saturation",
            "value" => "1",
            "min" => "-100",
            "max" => "100",
            "step" => "1",
            "unit" => '',
            "description" => __('Shifts the saturation of colors by a percentage of the original value if decreasing and a percentage of the remaining value if increasing. Valid values: [-100, 100].', 'jupiter-donut'),
            "dependency" => array(
                'element' => "modify_coloring",
                'value' => array(
                    'true'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Lightness", 'jupiter-donut'),
            "param_name" => "lightness",
            "value" => "1",
            "min" => "-100",
            "max" => "100",
            "step" => "1",
            "unit" => '',
            "description" => __('Shifts lightness of colors by a percentage of the original value if decreasing and a percentage of the remaining value if increasing. Valid values: [-100, 100].', 'jupiter-donut'),
            "dependency" => array(
                'element' => "modify_coloring",
                'value' => array(
                    'true'
                )
            )
        ),
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.", 'jupiter-donut')
        )
    )
));
