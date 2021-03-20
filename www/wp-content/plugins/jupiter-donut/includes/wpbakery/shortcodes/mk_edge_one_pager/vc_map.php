<?php
vc_map(array(
    "name" => __("Edge One Pager", 'jupiter-donut'),
    "base" => "mk_edge_one_pager",
	'html_template' => dirname( __FILE__ ) . '/mk_edge_one_pager.php',
    'icon' => 'icon-mk-edge-one-pager vc_mk_element-icon',
    "category" => __('Slideshows', 'jupiter-donut'),
    'description' => __( 'Converts Edge Slider to vertical scroll.', 'jupiter-donut' ),
    "params" => array(

        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select Specific Slides', 'jupiter-donut' ),
            'param_name'  => 'slides',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                                // In UI show results except selected. NB! You should manually check values in backend
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),

        array(
            "heading" => __("Order", 'jupiter-donut'),
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut'),
            "param_name" => "order",
            "value" => array(
                __("ASC (ascending order)", 'jupiter-donut') => "ASC",
                __("DESC (descending order)", 'jupiter-donut') => "DESC"
            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Orderby", 'jupiter-donut'),
            "description" => __("Sort retrieved slides items by parameter.", 'jupiter-donut'),
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Pagination", 'jupiter-donut'),
            "param_name" => "pagination",
            "width" => 300,
            "value" => array(
                __('Stroke', 'jupiter-donut') => "stroke",
                __('Small Dot With Stroke', 'jupiter-donut') => "small_dot_stroke"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
