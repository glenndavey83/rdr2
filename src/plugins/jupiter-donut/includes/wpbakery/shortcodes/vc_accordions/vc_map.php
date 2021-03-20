<?php
vc_map(array(
    "name" => __("Accordion", 'jupiter-donut') ,
    "base" => "vc_accordions",
	'html_template' => dirname( __FILE__ ) . '/vc_accordions.php',
    "show_settings_on_create" => false,
    "is_container" => true,
	'front_enqueue_js' => JUPITER_DONUT_INCLUDES_URL . '/wpbakery/shortcodes/vc_accordions/vc_front.js',
    'icon' => 'icon-mk-accordion vc_mk_element-icon',
    'description' => __('Collapsible content panels', 'jupiter-donut') ,
    "category" => __('Content', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "heading_title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "style",
            "width" => 150,
            "value" => array(
                __('Fancy', 'jupiter-donut') => "fancy-style",
                __('Simple', 'jupiter-donut') => "simple-style"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Action Style", 'jupiter-donut') ,
            "param_name" => "action_style",
            "width" => 400,
            "value" => array(
                __('One Toggle Open At A Time', 'jupiter-donut') => "accordion-action",
                __('Multiple Toggles Open At A Time', 'jupiter-donut') => "toggle-action"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Initial Index", 'jupiter-donut') ,
            "param_name" => "open_toggle",
            "value" => "0",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'index',
            "description" => __("Specify which toggle to be open by default when The page loads. please note that this value is zero based therefore zero is the first item. this option works when you have chosen [One Toggle Open At A Time] option from above setting. -1 will close all accordions on page load.", 'jupiter-donut') ,
            "dependency" => array(
                "element" => "action_style",
                "value" => array(
                    "accordion-action"
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Container Background Color", 'jupiter-donut') ,
            "param_name" => "container_bg_color",
            "value" => "#fff",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Mobile Friendly Accordions?", 'jupiter-donut') ,
            "description" => __("If enabled accordion functionality will removed in mobile devices, each toggle and its content will be inserted below each other.", 'jupiter-donut') ,
            "param_name" => "responsive",
            "value" => array(
                "Yes please!" => "true",
                "No!" => "false"
            ) ,
        ) ,
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut') ,
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'jupiter-donut')
        )
    ) ,
    "custom_markup" => '
  <div class="wpb_accordion_holder wpb_holder jupiter-donut-clearfix vc_container_for_children">
  %content%
  </div>
  <div class="tab_controls">
  <a class="add_tab" title="' . __('Add section', 'jupiter-donut') . '"><span class="vc_icon"></span> <span class="tab-label">' . __('Add section', 'jupiter-donut') . '</span></a>
  </div>
  ',
    'default_content' => '
  [vc_accordion_tab title="' . __('Section 1', 'jupiter-donut') . '"][/vc_accordion_tab]
  [vc_accordion_tab title="' . __('Section 2', 'jupiter-donut') . '"][/vc_accordion_tab]
  ',
    'js_view' => 'VcAccordionView'
));
