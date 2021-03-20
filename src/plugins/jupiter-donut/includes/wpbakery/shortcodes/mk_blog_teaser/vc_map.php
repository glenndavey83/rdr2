<?php
    vc_map(array(
        "name" => __("Blog Teaser", 'jupiter-donut'),
        "base" => "mk_blog_teaser",
		'html_template' => dirname( __FILE__ ) . '/mk_blog_teaser.php',
        'icon' => 'icon-mk-blog vc_mk_element-icon',
        "category" => __('Loops', 'jupiter-donut'),
        'description' => __( 'Blog teaser style loops are here.', 'jupiter-donut' ),
        "params" => array(
            array(
                'type'        => 'autocomplete',
                'heading'     => __( 'Select specific Categories to Appear in slideshow', 'jupiter-donut' ),
                'param_name'  => 'slideshow_cat',
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
                'heading'     => __( 'Select specific Categories to appear as Side Thumbnails', 'jupiter-donut' ),
                'param_name'  => 'side_thumb_cat',
                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'unique_values' => true,
                                    // In UI show results except selected. NB! You should manually check values in backend
                                ),
                'description' => __( 'Search for category name to get autocomplete suggestions', 'jupiter-donut' ),
            ),
            array(
                "type" => "range",
                "heading" => __("Images Height", 'jupiter-donut'),
                "param_name" => "image_height",
                "value" => "350",
                "min" => "100",
                "max" => "1000",
                "step" => "1",
                "unit" => 'px'
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
                "description" => __("Sort retrieved Blog items by parameter.", 'jupiter-donut'),
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
