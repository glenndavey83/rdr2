<?php

    vc_map(array(
        "name" => __("Category Showcase", 'jupiter-donut'),
        "base" => "mk_category",
		'html_template' => dirname( __FILE__ ) . '/mk_category.php',
        'icon' => 'vc_mk_element-icon',
        'description' => __( 'Taxonomy Loop for posts, portfolio, news and product categories.', 'jupiter-donut' ),
        "category" => __('Loops', 'jupiter-donut'),
        "params" => array(
            array(
                "heading" => __("Choose Loop Feed", 'jupiter-donut'),
                "description" => __("Using this option you will choose which taxonomy to bring into this loop", 'jupiter-donut'),
                "param_name" => "feed",
                "value" => array(
                    __("Post Category", 'jupiter-donut') => "post",
                    __("Portfolio Category", 'jupiter-donut') => "portfolio",
                    __("Woocommerce Product Category", 'jupiter-donut') => "product",
                    __("News Category", 'jupiter-donut') => "news",

                ),
                "type" => "dropdown"
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => __( 'Select Specific Categories to Show', 'jupiter-donut' ),
                'param_name'  => 'specific_categories_post',
                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'unique_values' => true,
                                    // In UI show results except selected. NB! You should manually check values in backend
                                ),
                'description' => __( 'Search for category name to get autocomplete suggestions', 'jupiter-donut' ),
                "dependency" => array(
                    'element' => "feed",
                    'value' => array(
                        'post'
                    )
                )
            ),
            array(
                "type" => "textfield",
                "heading" => __("Select Specific Categories", 'jupiter-donut'),
                "param_name" => "specific_categories_other",
                "value" => '',
                "description" => __("You will need to go to Wordpress Dashboard => post type => post type Categories. In the right hand find Slug column and paste them here. add comma to separate them.", 'jupiter-donut'),
                "dependency" => array(
                    'element' => "feed",
                    'value' => array(
                        'product',
                        'portfolio',
                        'news'
                    )
                )
            ),
             array(
                "heading" => __("Image Size", 'jupiter-donut'),
                "description" => __("", 'jupiter-donut'),
                "param_name" => "image_size",
                "value" => mk_get_image_sizes(),
                "type" => "dropdown"
            ),
            /*array(
               "type" => "group_heading",
               "title" => __("Moe Setting?", 'jupiter-donut'),
               "param_name" => "moe_title",
               "style" => "border: 0; font-size: 18px;"
            ),*/
            array(
                "type" => "toggle",
                "heading" => __("Show Description", 'jupiter-donut'),
                "param_name" => "description",
                "value" => "false",
                "description" => __("", 'jupiter-donut')
            ),
            $add_device_visibility,
            array(
                "type" => "textfield",
                "heading" => __("Extra class name", 'jupiter-donut'),
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
            ),
            array(
                'type' => 'item_id',
                'heading' => __( 'Item ID', 'jupiter-donut' ),
                'param_name' => "item_id"
            ),

            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "type" => "group_heading",
                "title" => __("Layout", 'jupiter-donut'),
                "param_name" => "layout_title",
                "style" => "border: 0; font-size: 18px;"
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "type" => "range",
                "heading" => __("How Many Columns?", 'jupiter-donut'),
                "param_name" => "columns",
                "value" => "4",
                "min" => "2",
                "max" => "4",
                "step" => "1",
                "unit" => 'columns',
                "description" => __("How many categories would you like your users to view?", 'jupiter-donut')
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "heading" => __("Layout Style", 'jupiter-donut'),
                "description" => __("", 'jupiter-donut'),
                "param_name" => "layout_style",
                "value" => array(
                    __("Grid", 'jupiter-donut') => "grid",
                    __("Masonry", 'jupiter-donut') => "masonry"
                ),
                "type" => "dropdown"
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "type" => "range",
                "heading" => __("Row Height", 'jupiter-donut'),
                "param_name" => "row_height",
                "value" => "300",
                "min" => "100",
                "max" => "1000",
                "step" => "1",
                "unit" => 'px',
                "description" => __("", 'jupiter-donut'),
                "dependency" => array(
                    'element' => "layout_style",
                    'value' => array(
                        'grid'
                    )
                )
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "heading" => __("Item Spacing", 'jupiter-donut'),
                "description" => __("Space between loop items.", 'jupiter-donut'),
                "param_name" => "gutter",
                "value" => "0",
                "min" => "0",
                "max" => "50",
                "step" => "1",
                "unit" => 'px',
                "type" => "range",
            ),

            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "type" => "group_heading",
                "title" => __("Styles & Animations", 'jupiter-donut'),
                "param_name" => "layout_title",
                "style" => "border: 0; font-size: 18px;"
            ),
             array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "type" => "alpha_colorpicker",
                "heading" => __("Title / Description Color", 'jupiter-donut'),
                "param_name" => "text_color",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "heading" => __("Title / Description Animations  ( on mouse over)", 'jupiter-donut'),
                "description" => __("", 'jupiter-donut'),
                "param_name" => "title_hover",
                "value" => array(
                    __("None", 'jupiter-donut') => "none",
                    __("Simple", 'jupiter-donut') => "simple",
                    __("Framed", 'jupiter-donut') => "framed",
                    __("Modern", 'jupiter-donut') => "modern",
                    __("Editorial", 'jupiter-donut') => "editorial",
                ),
                "type" => "dropdown",
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "heading" => __("Image Animations  ( on mouse over)", 'jupiter-donut'),
                "description" => __("", 'jupiter-donut'),
                "param_name" => "image_hover",
                "value" => array(
                    __("None", 'jupiter-donut') => "none",
                    __("Blur", 'jupiter-donut') => "blur",
                    __("Gradient", 'jupiter-donut') => "gradient",
                    __("Zoom", 'jupiter-donut') => "zoom",
                    __("Slide", 'jupiter-donut') => "slide",
                ),
                "type" => "dropdown",
            ),
            array(
                "group" => __('Styles & Colors', 'jupiter-donut'),
                "type" => "alpha_colorpicker",
                "heading" => __("Overlay Color", 'jupiter-donut'),
                "param_name" => "overlay_color",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ),
        )
    ));
