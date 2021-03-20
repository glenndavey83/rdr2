<?php
if (class_exists('woocommerce')) {
    vc_map(array(
		"name" => __("WooCommerce Carousel", 'jupiter-donut') ,
        "base" => "mk_woocommerce_recent_carousel",
		'html_template' => dirname( __FILE__ ) . '/mk_woocommerce_recent_carousel.php',
        "category" => __('Plugins', 'jupiter-donut') ,
        'icon' => 'icon-mk-woo-recent-carousel vc_mk_element-icon',
        "params" => array(
            array(
                "heading" => __("Style", 'jupiter-donut') ,
                "description" => __("", 'jupiter-donut') ,
                "param_name" => "style",
                "value" => array(
                    __("Modern", 'jupiter-donut') => "modern",
                    __("Classic", 'jupiter-donut') => "classic"
                ) ,
                "type" => "dropdown"
            ) ,
            array(
                "type" => "textfield",
                "heading" => __("Title", 'jupiter-donut') ,
                "param_name" => "title",
                "value" => "New Arrivals",
                "dependency" => array(
                    'element' => "style",
                    'value' => array(
                        'classic'
                    )
                )
            ) ,
            array(
                "heading" => __("Image Size", 'jupiter-donut') ,
                "description" => __("Please note that this option only only works for Modern Style", 'jupiter-donut') ,
                "param_name" => "image_size",
                "value" => mk_get_image_sizes(false, false, 'Woocommerce Recent Carousel'),
                "type" => "dropdown",
                "dependency" => array(
                    'element' => "style",
                    'value' => array(
                        'modern',
                    )
                )
            ) ,
            array(
                "type" => "toggle",
                "heading" => __("Featured Products?", 'jupiter-donut') ,
                "param_name" => "featured",
                "value" => "false",
                "description" => __("Enable this option if you want to show featured products.", 'jupiter-donut')
            ) ,
            array(
                "type" => "range",
                "heading" => __("Products Per View", 'jupiter-donut') ,
                "param_name" => "per_view",
                "value" => "3",
                "min" => "1",
                "max" => "8",
                "step" => "1",
                "unit" => 'products',
				'description' => __( '', 'jupiter-donut' ),
            ) ,
            array(
                "type" => "range",
                'heading'     => __( 'How many Products?', 'jupiter-donut' ),
                "param_name" => "per_page",
                "value" => "-1",
                "min" => "-1",
                "max" => "50",
                "step" => "1",
                "unit" => 'posts',
                'description' => __( 'How many Products you would like to show? ( -1 means unlimited, please note that unlimited will be overridden by the limit you defined at : Wordpress Sidebar > Settings > Reading > Blog pages show at most.)', 'jupiter-donut' ),
            ) ,
            array(
                'type'        => 'autocomplete',
                'heading'     => __( 'Select specific Categories', 'jupiter-donut' ),
                'param_name'  => 'category',
                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'unique_values' => true,
                                ),
                'description' => __( 'Search for category name to get autocomplete suggestions', 'jupiter-donut' ),
            ),
            array(
                'type'        => 'autocomplete',
				'heading'     => __( 'Select specific Products', 'jupiter-donut' ),
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
				'dependency'  => array(
					'element' => 'style',
					'value'   => array(
						'modern',
					),
				),
            ),
            array(
                "heading" => __("Order", 'jupiter-donut') ,
                "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut') ,
                "param_name" => "order",
                "value" => array(
                    __("DESC (descending order)", 'jupiter-donut') => "DESC",
                    __("ASC (ascending order)", 'jupiter-donut') => "ASC",
                ) ,
                "type" => "dropdown"
            ) ,
            array(
                "heading" => __("Orderby", 'jupiter-donut') ,
                "description" => __("Sort retrieved Woocommerce items by parameter.", 'jupiter-donut') ,
                "param_name" => "orderby",
                "value" => $mk_orderby,
                "type" => "dropdown"
            ) ,
            $add_device_visibility,
            array(
                "type" => "textfield",
                "heading" => __("Extra class name", 'jupiter-donut') ,
                "param_name" => "el_class",
                "value" => "",
                "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
			),
			array(
				'type'             => 'alpha_colorpicker',
				'heading'          => __( 'Carousel Arrow Color', 'jupiter-donut' ),
				'param_name'       => 'arrow_color',
				'edit_field_class' => 'vc_col-sm-12 vc_column',
				'value'            => 'rgba(34,34,34,1)',
				'description'      => __( '', 'jupiter-donut' ),
				'dependency'       => array(
					'element' => 'style',
					'value'   => array(
						'classic',
					),
				),
				'group'            => 'Colors',
			),
			array(
				'type'             => 'alpha_colorpicker',
				'heading'          => __( 'Carousel Arrow Background Color', 'jupiter-donut' ),
				'param_name'       => 'arrow_bg_color',
				'edit_field_class' => 'vc_col-sm-12 vc_column',
				'value'            => 'rgba(255,255,255,1)',
				'description'      => __( '', 'jupiter-donut' ),
				'dependency'       => array(
					'element' => 'style',
					'value'   => array(
						'classic',
					),
				),
				'group'            => 'Colors',
			),
			array(
				'type'             => 'alpha_colorpicker',
				'heading'          => __( 'Carousel Arrow Hover Color', 'jupiter-donut' ),
				'param_name'       => 'arrow_hover_color',
				'edit_field_class' => 'vc_col-sm-12 vc_column',
				'value'            => 'rgba(255,255,255,1)',
				'description'      => __( '', 'jupiter-donut' ),
				'dependency'       => array(
					'element' => 'style',
					'value'   => array(
						'classic',
					),
				),
				'group'            => 'Colors',
			),
			array(
				'type'             => 'alpha_colorpicker',
				'heading'          => __( 'Carousel Arrow Hover Background Color', 'jupiter-donut' ),
				'param_name'       => 'arrow_hover_bg_color',
				'edit_field_class' => 'vc_col-sm-12 vc_column',
				'value'            => 'rgba(34,34,34,1)',
				'description'      => __( '', 'jupiter-donut' ),
				'dependency'       => array(
					'element' => 'style',
					'value'   => array(
						'classic',
					),
				),
				'group'            => 'Colors',
			),
        )
    ));
}
