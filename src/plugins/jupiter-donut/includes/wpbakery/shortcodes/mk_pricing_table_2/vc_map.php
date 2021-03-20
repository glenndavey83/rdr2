<?php
vc_map(array(
    "name" => __("Pricing Table (Builder)", 'jupiter-donut'),
    "base" => "mk_pricing_table_2",
	'html_template' => dirname( __FILE__ ) . '/mk_pricing_table_2.php',
    'icon' => 'icon-mk-pricing-table vc_mk_element-icon',
    'description' => __( 'Shows Pricing table Posts.', 'jupiter-donut' ),
    "category" => __('Loops', 'jupiter-donut'),
    "params" => array(
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Offers", 'jupiter-donut'),
            "param_name" => "content",
            "value" => "",
            "description" => __("Please add your 'offers' text. Note : List of offers must be an unordered list. If you dont need offers list, leave this field empty. The number of the list items should match the number of your pricing items list as well.", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("How Many Tables?", 'jupiter-donut'),
            "param_name" => "table_number",
            "value" => "4",
            "min" => "1",
            "max" => "4",
            "step" => "1",
            "unit" => 'table',
            "description" => __("How many pricing tables would you like your users to view?", 'jupiter-donut')
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Tables', 'jupiter-donut' ),
            'param_name'  => 'tables',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
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
            "description" => __("Sort retrieved pricing items by parameter.", 'jupiter-donut'),
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
