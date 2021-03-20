<?php
vc_map(array(
    "name" => __("Clients", 'jupiter-donut') ,
    "base" => "mk_clients",
	'html_template' => dirname( __FILE__ ) . '/mk_clients.php',
    'icon' => 'icon-mk-clients vc_mk_element-icon',
    "category" => __('Loops', 'jupiter-donut') ,
    'description' => __('Shows Clients posts in multiple styles.', 'jupiter-donut') ,
    "params" => array(

        array(
            "heading" => __("Style", 'jupiter-donut') ,
            "description" => __("Choose clients loop style", 'jupiter-donut') ,
            "param_name" => "style",
            "value" => array(
                __("Carousel", 'jupiter-donut') => "carousel",
                __("Column", 'jupiter-donut') => "column"
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "heading" => __("Border Style", 'jupiter-donut') ,
            "description" => __("Choose border style", 'jupiter-donut') ,
            "param_name" => "border_style",
            "value" => array(
                __("Boxed", 'jupiter-donut') => "boxed",
                __("Opened Edges", 'jupiter-donut') => "opened_edges"
            ) ,
            "type" => "dropdown",
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'column'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("How many Columns?", 'jupiter-donut') ,
            "param_name" => "column",
            "value" => "3",
            "min" => "1",
            "max" => "6",
            "step" => "1",
            "unit" => 'columns',
            "description" => __("Specify how many columns will be set in one row. This option works only for column style", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'column'
                )
            )
        ) ,
        array(
            "type" => "range",
            "heading" => __("Column Gutter Space", 'jupiter-donut') ,
            "param_name" => "gutter_space",
            "value" => "0",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("The space between columns.", 'jupiter-donut') ,
            "dependency" => array(
                'element' => "border_style",
                'value' => array(
                    'boxed'
                )
            )
        ) ,
        array(
            "type" => "textfield",
            "heading" => __("Heading Title", 'jupiter-donut') ,
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Count", 'jupiter-donut') ,
            "param_name" => "count",
            "value" => "10",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'clients',
            "description" => __("How many Clients you would like to show? (-1 means unlimited)", 'jupiter-donut')
        ) ,
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Clients', 'jupiter-donut' ),
            'param_name'  => 'clients',
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
            "description" => __("Sort retrieved client items by parameter.", 'jupiter-donut') ,
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Box Background Color", 'jupiter-donut') ,
            "param_name" => "bg_color",
            "value" => "",
            "description" => __("Color of the box containing the client's logo", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Box Hover Background Color", 'jupiter-donut') ,
            "param_name" => "bg_hover_color",
            "value" => "",
            "description" => __("Hover color of the box containing the client's logo", 'jupiter-donut')
        ) ,
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Box Border Color", 'jupiter-donut') ,
            "param_name" => "border_color",
            "value" => "",
            "description" => __("Border color of the box containing the client's logo", 'jupiter-donut')
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Fit to Background", 'jupiter-donut') ,
            "description" => __("Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area", 'jupiter-donut') ,
            "param_name" => "cover",
            "value" => "false"
        ) ,
        array(
            "type" => "range",
            "heading" => __("Logos Height", 'jupiter-donut') ,
            "param_name" => "height",
            "value" => "110",
            "min" => "50",
            "max" => "300",
            "step" => "1",
            "unit" => 'px',
            "description" => __("You can change logos height using this option.", 'jupiter-donut')
        ) ,
        array(
            "type" => "toggle",
            "heading" => __("Autoplay?", 'jupiter-donut') ,
            "param_name" => "autoplay",
            "value" => "true",
            "description" => __("Disable this option if you do not want the client slideshow to autoplay.", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Target", 'jupiter-donut') ,
            "param_name" => "target",
            "width" => 200,
            "value" => $target_arr,
            "description" => __("Target for the links.", 'jupiter-donut')
        ) ,
        array(
            "type" => "range",
            "heading" => __("Margin Bottom", 'jupiter-donut') ,
            "param_name" => "margin_bottom",
            "value" => "20",
            "min" => "0",
            "max" => "200",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
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
