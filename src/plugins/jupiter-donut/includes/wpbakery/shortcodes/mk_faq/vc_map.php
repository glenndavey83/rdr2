<?php
vc_map(array(
    "name" => __("FAQ", 'jupiter-donut') ,
    "base" => "mk_faq",
	'html_template' => dirname( __FILE__ ) . '/mk_faq.php',
    'icon' => 'icon-mk-faq vc_mk_element-icon',
    "category" => __('Loops', 'jupiter-donut') ,
    'description' => __('Shows FAQ posts in multiple styles.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut') ,
            "param_name" => "style",
            "width" => 150,
            "value" => array(
                __('Fancy', 'jupiter-donut') => "fancy",
                __('Simple', 'jupiter-donut') => "simple"
            ) ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("[All] Link Title", 'jupiter-donut'),
            "param_name" => "view_all_text",
            "value" => "All",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Sortable?", 'jupiter-donut') ,
            "param_name" => "sortable",
            "value" => "true",
            "description" => __("Disable this option if you do not want sortable filter navigation.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Count", 'jupiter-donut') ,
            "param_name" => "count",
            "value" => "50",
            "min" => "-1",
            "max" => "300",
            "step" => "1",
            "unit" => 'FAQs'
        ) ,
        array(
            "type" => "range",
            "heading" => __("Offset", 'jupiter-donut') ,
            "param_name" => "offset",
            "value" => "0",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("Number of post to displace or pass over. It means based on your order of the loop, this number will define how many posts to pass over and start from the nth number of the offset.", 'jupiter-donut')
        ) ,
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Categories', 'jupiter-donut' ),
            'param_name'  => 'faq_cat',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                                // In UI show results except selected. NB! You should manually check values in backend
                            ),
            'description' => __( 'Search for category name to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Posts', 'jupiter-donut' ),
            'param_name'  => 'posts',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                                // In UI show results except selected. NB! You should manually check values in backend
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
            "description" => __("Sort retrieved FAQ items by parameter.", 'jupiter-donut') ,
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Pane Content Background Color", 'jupiter-donut') ,
            "param_name" => "background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut') ,
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
