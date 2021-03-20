<?php

vc_map(array(
    "name" => __("News", 'jupiter-donut'),
    "base" => "mk_news",
	'html_template' => dirname( __FILE__ ) . '/mk_news.php',
   'icon' => 'icon-mk-news vc_mk_element-icon',
    "category" => __('Loops', 'jupiter-donut'),
    'description' => __( 'News Loop is here.', 'jupiter-donut' ),
    "params" => array(
        array(
            "type" => "range",
            "heading" => __("How many News Posts?", 'jupiter-donut'),
            "param_name" => "count",
            "value" => "10",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("(-1 means unlimited)", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Offset", 'jupiter-donut'),
            "param_name" => "offset",
            "value" => "0",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("Number of post to displace or pass over, it means based on your order of the loop, this number will define how many posts to pass over and start from the nth number of the offset.", 'jupiter-donut')
        ),
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
            'heading'     => __( 'Select specific Posts', 'jupiter-donut' ),
            'param_name'  => 'posts',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),

        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Authors', 'jupiter-donut' ),
            'param_name'  => 'author',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for user ID, Username, Email Address to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            "type" => "range",
            "heading" => __("Image Height", 'jupiter-donut'),
            "param_name" => "image_height",
            "value" => "250",
            "min" => "150",
            "max" => "1000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("This height will be applied to all posts including posts without a featured image.", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Show Pagination?", 'jupiter-donut'),
            "param_name" => "pagination",
            "value" => "true",
            "description" => __("Disable this option if you do not want pagination for this loop.", 'jupiter-donut')
        ),
        array(
            "heading" => __("Pagination Style", 'jupiter-donut'),
            "description" => __("Select which pagination style you would like to use for this loop.", 'jupiter-donut'),
            "param_name" => "pagination_style",
            "value" => array(
                __("Load more button", 'jupiter-donut') => "2",
                __("Classic Pagination Navigation", 'jupiter-donut') => "1",
                __("Load more on page scroll", 'jupiter-donut') => "3"
            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Order", 'jupiter-donut'),
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut'),
            "param_name" => "order",
            "value" => array(
                __("DESC (descending order)", 'jupiter-donut') => "DESC",
                __("ASC (ascending order)", 'jupiter-donut') => "ASC"
            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Orderby", 'jupiter-donut'),
            "description" => __("Sort retrieved News items by parameter.", 'jupiter-donut'),
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ),
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
