<?php
vc_map(array(
    "name" => __("Message Box", 'jupiter-donut') ,
    "base" => "mk_message_box",
	'html_template' => dirname( __FILE__ ) . '/mk_message_box.php',
    'icon' => 'icon-mk-message-box vc_mk_element-icon',
    "category" => __('General', 'jupiter-donut') ,
    'description' => __('Message Box with multiple types.', 'jupiter-donut') ,
    "params" => array(
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "heading" => __("Write your message inside the text box", 'jupiter-donut') ,
            "param_name" => "content",
            "value" => __("", 'jupiter-donut') ,
            "description" => __("", 'jupiter-donut')
        ) ,
        array(
            "type" => "dropdown",
            "heading" => __("Type", 'jupiter-donut') ,
            "param_name" => "type",
            "value" => array(
                "Confirm" => "confirm-message",
                "Comment" => "comment-message",
                "Warning" => "warning-message",
                "Error" => "error-message",
                "Info" => "info-message"
            ) ,
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
