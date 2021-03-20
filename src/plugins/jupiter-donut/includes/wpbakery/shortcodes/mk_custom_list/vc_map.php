<?php
	vc_map(array(
	    "name" => __("Custom List", 'jupiter-donut') ,
	    "base" => "mk_custom_list",
		'html_template' => dirname( __FILE__ ) . '/mk_custom_list.php',
	    "category" => __('Typography', 'jupiter-donut') ,
	    'icon' => 'icon-mk-custom-list vc_mk_element-icon',
	    'description' => __('Powerful list styles with icons.', 'jupiter-donut') ,
	    "params" => array(
	        array(
	            "type" => "textfield",
	            "heading" => __("List Title", 'jupiter-donut') ,
	            "param_name" => "title",
	            "value" => "",
	            "description" => __("", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "textarea_html",
	            "holder" => "div",
	            "heading" => __("Add your unordered list into this textarea. Allowed Tags : [ul][li][strong][i][em][u][b][a][small]", 'jupiter-donut') ,
	            "param_name" => "content",
	            "value" => "<ul><li>List Item</li></ul>",
	            "description" => __("", 'jupiter-donut')
	        ) ,
	        array(
	            "type" => "icon_selector",
	            "heading" => __("Add Icon", 'jupiter-donut') ,
	            "param_name" => "style",
	            "value" => "mk-icon-check",
	        ) ,
	        array(
	            "type" => "alpha_colorpicker",
	            "heading" => __("Icons Color", 'jupiter-donut') ,
	            "param_name" => "icon_color",
	            "value" => $skin_color,
	            "description" => __("", 'jupiter-donut') ,
	            "group" => "Design",
	        ) ,
	        array(
	            "type" => "range",
	            "heading" => __("Margin Button", 'jupiter-donut') ,
	            "param_name" => "margin_bottom",
	            "value" => "30",
	            "min" => "-30",
	            "max" => "500",
	            "step" => "1",
	            "unit" => 'px',
	            "description" => __("", 'jupiter-donut') ,
	            "group" => "Design",
	        ) ,
	        array(
	            "type" => "dropdown",
	            "heading" => __("Align", 'jupiter-donut') ,
	            "param_name" => "align",
	            "width" => 150,
	            "value" => array(
	                __('No Align', 'jupiter-donut') => "none",
	                __('Left', 'jupiter-donut') => "left",
	                __('Center', 'jupiter-donut') => "center",
	                __('Right', 'jupiter-donut') => "right"
	            ) ,
	            "description" => __("Please note that align left and right will make the shortcode to float, therefore in order to keep your page elements from wrapping into each other you should add a padding divider shortcode right after this shortcode.", 'jupiter-donut') ,
	            "group" => "Design",
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
