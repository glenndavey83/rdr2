<?php
	vc_map(array(
	    "name" => __("Chart", 'jupiter-donut') ,
	    "base" => "mk_chart",
		'html_template' => dirname( __FILE__ ) . '/mk_chart.php',
	    "category" => __('General', 'jupiter-donut') ,
	    'icon' => 'icon-mk-chart vc_mk_element-icon',
	    'description' => __('Powerful & versatile Chart element.', 'jupiter-donut') ,
	    "params" => array(
	        array(
	            "type" => "range",
	            "heading" => __("Percent", 'jupiter-donut') ,
	            "param_name" => "percent",
	            "value" => "50",
	            "min" => "0",
	            "max" => "100",
	            "step" => "1",
	            "unit" => '%',
	            "description" => __("", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "alpha_colorpicker",
	            "heading" => __("Bar Color", 'jupiter-donut') ,
	            "param_name" => "bar_color",
	            "value" => $skin_color,
	            "description" => __("The color of the circular bar.", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "alpha_colorpicker",
	            "heading" => __("Track Color", 'jupiter-donut') ,
	            "param_name" => "track_color",
	            "value" => "#ececec",
	            "description" => __("The color of the track for the bar.", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "range",
	            "heading" => __("Line Width", 'jupiter-donut') ,
	            "param_name" => "line_width",
	            "value" => "10",
	            "min" => "1",
	            "max" => "20",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("The bar stroke thickness", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "range",
	            "heading" => __("Bar Size", 'jupiter-donut') ,
	            "param_name" => "bar_size",
	            "value" => "150",
	            "min" => "1",
	            "max" => "500",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("The Diameter of the bar.", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "dropdown",
	            "heading" => __("Content inside the chart", 'jupiter-donut') ,
	            "param_name" => "content_type",
	            "width" => 200,
	            "value" => array(
	                "Percentage" => "percent",
	                "Icon" => "icon",
	                "Custom Text" => "custom_text"
	            ) ,
	            "description" => __("The content inside the circular bar.", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "icon_selector",
	            "heading" => __("Add Icon", 'jupiter-donut') ,
	            "param_name" => "icon",
	            "value" => "",
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'icon'
	            )
	        ) ,
	        array(
	            "type" => "range",
	            "heading" => __("Icon Size", 'jupiter-donut') ,
	            "param_name" => "icon_size",
	            "value" => "32",
	            "min" => "1",
	            "max" => "200",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("", 'jupiter-donut') ,
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'icon'
	            )
	        ) ,
	        array(
	            "type" => "alpha_colorpicker",
	            "heading" => __("Icon Color", 'jupiter-donut') ,
	            "param_name" => "icon_color",
	            "value" => "#444",
	            "description" => __("", 'jupiter-donut') ,
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'icon'
	            )
	        ) ,

	        array(
	            "type" => "textfield",
	            "heading" => __("Custom Text", 'jupiter-donut') ,
	            "param_name" => "custom_text",
	            "value" => "",
	            "description" => __("This will appear inside the circular chart.", 'jupiter-donut') ,
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'custom_text'
	            )
	        ) ,
	         array(
	            "type" => "range",
	            "heading" => __("Custom Text Size", 'jupiter-donut') ,
	            "param_name" => "custom_text_size",
	            "value" => "15",
	            "min" => "10",
	            "max" => "50",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("", 'jupiter-donut') ,
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'custom_text'
	            )
	        ) ,
	        array(
	            "type" => "range",
	            "heading" => __("Percentage Text Size", 'jupiter-donut') ,
	            "param_name" => "percentage_text_size",
	            "value" => "15",
	            "min" => "10",
	            "max" => "100",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("", 'jupiter-donut') ,
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'percent'
	            )
	        ) ,
	        array(
	            "type" => "alpha_colorpicker",
	            "heading" => __("Percentage Text Color", 'jupiter-donut') ,
	            "param_name" => "percentage_color",
	            "value" => "#444",
	            "description" => __("", 'jupiter-donut') ,
	            "dependency" => array(
	                'element' => "content_type",
	                'value' => 'percent'
	            )
	        ) ,
	        array(
	            "type" => "textfield",
	            "heading" => __("Description", 'jupiter-donut') ,
	            "param_name" => "desc",
	            "value" => "",
	            "description" => __("Description will appear below each chart.", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "range",
	            "heading" => __("Description Text Size", 'jupiter-donut') ,
	            "param_name" => "desc_text_size",
	            "value" => "15",
	            "min" => "10",
	            "max" => "100",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "alpha_colorpicker",
	            "heading" => __("Description Text Color", 'jupiter-donut') ,
	            "param_name" => "desc_color",
	            "value" => "#444",
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
