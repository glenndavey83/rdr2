<?php
vc_map(array(
    "name" => __("Ornamental Title", 'jupiter-donut') ,
    "base" => "mk_ornamental_title",
	'html_template' => dirname( __FILE__ ) . '/mk_ornamental_title.php',
    'icon' => 'icon-mk-fancy-title vc_mk_element-icon',
    "category" => __('Typography', 'jupiter-donut') ,
    'description' => __('Advanced headings with cool styles.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Content.", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Tag Name", 'jupiter-donut') ,
            "param_name" => "tag_name",
            "value" => array(
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "h1" => "h1"
            ) ,
            "description" => __("For SEO reasons you might need to define your titles tag names according to priority. Please note that H1 can only be used only once in a page due to the SEO reasons. So try to use lower than H2 to meet SEO best practices.", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Title as", 'jupiter-donut') ,
            "param_name" => "title_as",
            "value" => array(
                __('Text', 'jupiter-donut') => "text",
                __('Image', 'jupiter-donut') => "image",
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "upload",
            "heading" => __("Title Image", 'jupiter-donut') ,
            "param_name" => "title_image",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'image'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Text Color", 'jupiter-donut') ,
            "param_name" => "text_color",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "type" => "theme_fonts",
            "heading" => __("Font Family", 'jupiter-donut') ,
            "param_name" => "font_family",
            "value" => "",
            "description" => __("You can choose a font for this shortcode, however using non-safe fonts can affect page load and performance.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "type" => "hidden_input",
            "param_name" => "font_type",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Font Size", 'jupiter-donut') ,
            "param_name" => "font_size",
            "value" => "14",
            "min" => "12",
            "max" => "70",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Font Weight", 'jupiter-donut') ,
            "param_name" => "font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Font Style", 'jupiter-donut') ,
            "param_name" => "font_style",
            "value" => array(
                __('Default', 'jupiter-donut') => "inherit",
                __('Normal', 'jupiter-donut') => "normal",
                __('Italic', 'jupiter-donut') => "italic",
            ) ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Text Transform", 'jupiter-donut') ,
            "param_name" => "txt_transform",
            "value" => array(
                __('Default', 'jupiter-donut') => "initial",
                __('None', 'jupiter-donut') => "none",
                __('Uppercase', 'jupiter-donut') => "uppercase",
                __('Lowercase', 'jupiter-donut') => "lowercase",
                __('Capitalize', 'jupiter-donut') => "capitalize"
            ) ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "title_as",
                'value' => array(
                    'text'
                )
            )
        ) ,
        array(
            "heading" => __("Ornamental Style", 'jupiter-donut') ,
            "description" => __("You can optionally select a pattern for this section background. This option only works when background image field is empty.", 'jupiter-donut') ,
            "param_name" => "ornament_style",
            "border" => 'true',
            "value" => array(
                'ornamental-style/1.png' => "rovi-single",
                'ornamental-style/2.png' => "rovi-double",
                'ornamental-style/3.png' => "norman-single",
                'ornamental-style/4.png' => "norman-double",
                'ornamental-style/5.png' => "norman-short-single",
                'ornamental-style/6.png' => "norman-short-double",
                'ornamental-style/7.png' => "lemo-single",
                'ornamental-style/8.png' => "lemo-double",
            ) ,
            "type" => "visual_selector"
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Norman Short Style and Lemo Style Align", 'jupiter-donut') ,
            "param_name" => "nss_align",
            "value" => array(
                __('Left', 'jupiter-donut') => "left",
                __('Center', 'jupiter-donut') => "center",
                __('Right', 'jupiter-donut') => "right",
            ) ,
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "ornament_style",
                'value' => array(
                    'norman-short-single',
                    'norman-short-double',
                    'lemo-single',
                    'lemo-double'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Ornament Color", 'jupiter-donut') ,
            "param_name" => "ornament_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Ornament Thickness", 'jupiter-donut') ,
            "param_name" => "ornament_thickness",
            "value" => "1",
            "min" => "1",
            "max" => "4",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Top", 'jupiter-donut') ,
            "param_name" => "margin_top",
            "value" => "0",
            "min" => "-40",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut') ,
            "param_name" => "margin_bottom",
            "value" => "20",
            "min" => "0",
            "max" => "500",
            "step" => "1",
            "unit" => 'px',
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
