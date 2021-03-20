<?php
vc_map(array(
    "name" => __("Twitter Feeds", 'jupiter-donut'),
    "base" => "vc_twitter",
	'html_template' => dirname( __FILE__ ) . '/vc_twitter.php',
    'icon' => 'icon-mk-twitter-feeds vc_mk_element-icon',
    'description' => __( 'Adds Twitter Feeds.', 'jupiter-donut' ),
    "category" => __('Social', 'jupiter-donut'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Widget Title", 'jupiter-donut'),
            "param_name" => "title",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Twitter name", 'jupiter-donut'),
            "param_name" => "twitter_name",
            "value" => "",
            "description" => __("Type in twitter profile name from which load tweets.", 'jupiter-donut')
        ),
        array(
            "type" => "range",
            "heading" => __("Tweets count", 'jupiter-donut'),
            "param_name" => "tweets_count",
            "value" => "5",
            "min" => "1",
            "max" => "30",
            "step" => "1",
            "unit" => 'tweets',
            "description" => __("How many recent tweets to load.", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Text & Icon color", 'jupiter-donut'),
            "param_name" => "text_color",
            "value" => "",
            "description" => __("You can set a color for text and icon color.", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Link Color", 'jupiter-donut'),
            "param_name" => "link_color",
            "value" => "",
            "description" => __("You can change link color.", 'jupiter-donut')
        ),
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'jupiter-donut')
        ),
        array(
            'type' => 'item_id',
            'heading' => __( 'Item ID', 'jupiter-donut' ),
            'param_name' => "item_id"
        )
    )
));
