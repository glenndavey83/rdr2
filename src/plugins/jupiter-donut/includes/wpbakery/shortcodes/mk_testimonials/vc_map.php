<?php
vc_map(array(
    "name" => __("Testimonials", 'jupiter-donut') ,
    "base" => "mk_testimonials",
	'html_template' => dirname( __FILE__ ) . '/mk_testimonials.php',
    'icon' => 'icon-mk-testimonial-slideshow vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut') ,
    'description' => __('Shows Testimonials in multiple styles.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "heading" => __("Style", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "style",
            "value" => array(
                __("Avant garde", 'jupiter-donut') => "avantgarde",
                __("Boxed", 'jupiter-donut') => "boxed",
                __("Modern", 'jupiter-donut') => "modern",
                __("Simple Centered", 'jupiter-donut') => "simple"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "heading" => __("Show as?", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut') ,
            "param_name" => "show_as",
            "value" => array(
                __("Slideshow", 'jupiter-donut') => "slideshow",
                __("Column Based", 'jupiter-donut') => "column"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "range",
            "heading" => __("How many Columns", 'jupiter-donut') ,
            "param_name" => "column",
            "value" => "3",
            "min" => "1",
            "max" => "4",
            "step" => "1",
            "unit" => 'columns',
            "description" => __("If Column based is selected from the option above, you will need to set in how many columns, testimonials will be showed up.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "show_as",
                'value' => array(
                    'column'
                )
            )
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Skin", 'jupiter-donut') ,
            "param_name" => "skin",
            "value" => array(
                __('Dark', 'jupiter-donut') => "dark",
                __('Light', 'jupiter-donut') => "light"
            ) ,
            "description" => __("This option is only for 'Simple Centered' style and you can use it when you need to place this shortcode inside a page section with dark background.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple',
                    'avantgarde'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Count", 'jupiter-donut') ,
            "param_name" => "count",
            "value" => "10",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'testimonial',
            "description" => __("How many testimonial you would like to show? (-1 means unlimited)", 'jupiter-donut')
        ) ,
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Categories', 'jupiter-donut' ),
            'param_name'  => 'categories',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for category name to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Testimonials', 'jupiter-donut' ),
            'param_name'  => 'testimonials',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            "heading" => __("Order", 'jupiter-donut') ,
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut') ,
            "param_name" => "order",
            "value" => array(
                __("ASC (ascending order)", 'jupiter-donut') => "ASC",
                __("DESC (descending order)", 'jupiter-donut') => "DESC"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "heading" => __("Orderby", 'jupiter-donut') ,
            "description" => __("Sort retrieved client items by parameter.", 'jupiter-donut') ,
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "range",
            "heading" => __("Animation Speed", 'jupiter-donut') ,
            "param_name" => "animation_speed",
            "value" => "700",
            "min" => "100",
            "max" => "3000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "show_as",
                'value' => array(
                    'slideshow'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Slideshow Speed", 'jupiter-donut') ,
            "param_name" => "slideshow_speed",
            "value" => "7000",
            "min" => "1000",
            "max" => "20000",
            "step" => "1",
            "unit" => 'ms',
            "description" => __("", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "show_as",
                'value' => array(
                    'slideshow'
                )
            )
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Content Color", 'jupiter-donut') ,
            "param_name" => "text_color",
            "value" => "#777777",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Author Color", 'jupiter-donut') ,
            "param_name" => "author_color",
            "value" => "#444444",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Skill Color", 'jupiter-donut') ,
            "param_name" => "skill_color",
            "value" => "#777777",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Font Size", 'jupiter-donut') ,
            "param_name" => "font_size",
            "value" => "18",
            "min" => "14",
            "max" => "48",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Font Weight", 'jupiter-donut') ,
            "param_name" => "font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Font Style", 'jupiter-donut') ,
            "param_name" => "font_style",
            "value" => array(
                __('Italic', 'jupiter-donut') => "italic",
                __('Normal', 'jupiter-donut') => "normal",
                __('Default', 'jupiter-donut') => "inherit",
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Text Transform", 'jupiter-donut') ,
            "param_name" => "text_transform",
            "value" => array(
                __('Default', 'jupiter-donut') => "initial",
                __('None', 'jupiter-donut') => "none",
                __('Uppercase', 'jupiter-donut') => "uppercase",
                __('Lowercase', 'jupiter-donut') => "lowercase",
                __('Capitalize', 'jupiter-donut') => "capitalize"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Letter Spacing", 'jupiter-donut') ,
            "param_name" => "letter_spacing",
            "value" => "0",
            "min" => "0",
            "max" => "10",
            "step" => "1",
            "unit" => 'px',
            "description" => __("Space between each character.", 'jupiter-donut')
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
