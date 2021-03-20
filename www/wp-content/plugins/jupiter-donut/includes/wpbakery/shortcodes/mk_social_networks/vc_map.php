<?php
vc_map(array(
    "name" => __("Social Networks", 'jupiter-donut'),
    "base" => "mk_social_networks",
	'html_template' => dirname( __FILE__ ) . '/mk_social_networks.php',
    'icon' => 'icon-mk-social-networks vc_mk_element-icon',
    'description' => __( 'Adds social network icons.', 'jupiter-donut' ),
    "category" => __('Social', 'jupiter-donut'),
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Size", 'jupiter-donut'),
            "param_name" => "size",
            "value" => array(
                "Small" => "small",
                "Medium" => "medium",
                "Large" => "large",
                "X Large" => "x-large",
                "XX Large" => "xx-large"
            )
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Style", 'jupiter-donut'),
            "param_name" => "style",
            "value" => array(
                "Rounded" => "rounded",
                "Circle" => "circle",
                "Simple" => "simple",
                "Simple Rounded" => "simple-rounded",
                "Square Pointed Corner" => "square-pointed",
                "Square Rounded Corner" => "square-rounded"

            )
        ),

        array(
            "type" => "range",
            "heading" => __("Margin", 'jupiter-donut'),
            "param_name" => "margin",
            "value" => "4",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("The distance between icons. This margin will be applied to all directions.", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Border Color", 'jupiter-donut'),
            "param_name" => "border_color",
            "value" => "#ccc",
            "description" => __("(default: #ccc)", 'jupiter-donut'),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'square-pointed',
                    'square-rounded',
                    'simple-rounded'
                )
            )
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Background Color", 'jupiter-donut'),
            "param_name" => "bg_color",
            "value" => "",
            "description" => __("(default: transparent)", 'jupiter-donut'),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple-rounded',
                    'square-pointed',
                    'square-rounded'
                )
            )
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Background Hover Color", 'jupiter-donut'),
            "param_name" => "bg_hover_color",
            "value" => "#232323",
            "description" => __("(default: #232323)", 'jupiter-donut'),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'simple-rounded',
                    'square-pointed',
                    'square-rounded'
                )
            )
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icons Color", 'jupiter-donut'),
            "param_name" => "icon_color",
            "value" => "#ccc",
            "description" => __("(default: #ccc)", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Icons Hover Color", 'jupiter-donut'),
            "param_name" => "icon_hover_color",
            "value" => "#eee",
            "description" => __("(default: #eee)", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Icons Align", 'jupiter-donut'),
            "param_name" => "align",
            "width" => 150,
            "value" => array(
                __('Left', 'jupiter-donut') => "left",
                __('Right', 'jupiter-donut') => "right",
                __('Center', 'jupiter-donut') => "center"
            ),
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Facebook URL", 'jupiter-donut'),
            "param_name" => "facebook",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Twitter URL", 'jupiter-donut'),
            "param_name" => "twitter",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("RSS URL", 'jupiter-donut'),
            "param_name" => "rss",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Dribbble URL", 'jupiter-donut'),
            "param_name" => "dribbble",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Digg URL", 'jupiter-donut'),
            "param_name" => "digg",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Pinterest URL", 'jupiter-donut'),
            "param_name" => "pinterest",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Flickr URL", 'jupiter-donut'),
            "param_name" => "flickr",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Google Plus URL", 'jupiter-donut'),
            "param_name" => "google_plus",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Skype URL", 'jupiter-donut'),
            "param_name" => "skype",
            "value" => "",
            "description" => __("Enter the full URL including 'http://'' for profile page or 'skype:USERNAME?call' for direct call (replace USERNAME with your own). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Instagram URL", 'jupiter-donut'),
            "param_name" => "instagram",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Linkedin URL", 'jupiter-donut'),
            "param_name" => "linkedin",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Blogger URL", 'jupiter-donut'),
            "param_name" => "blogger",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Youtube URL", 'jupiter-donut'),
            "param_name" => "youtube",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Last-fm URL", 'jupiter-donut'),
            "param_name" => "last_fm",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Stumble-upon URL", 'jupiter-donut'),
            "param_name" => "stumble_upon",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Sound Cloud URL", 'jupiter-donut'),
            "param_name" => "soundcloud",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Tumblr URL", 'jupiter-donut'),
            "param_name" => "tumblr",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Vimeo URL", 'jupiter-donut'),
            "param_name" => "vimeo",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("WordPress URL", 'jupiter-donut'),
            "param_name" => "wordpress",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Yelp URL", 'jupiter-donut'),
            "param_name" => "yelp",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Reddit URL", 'jupiter-donut'),
            "param_name" => "reddit",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Xing URL", 'jupiter-donut'),
            "param_name" => "xing",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
            //  "dependency" => array(
            //     'element' => "style",
            //     'value' => array(
            //         'rounded',
            //         'circle',
            //     )
            // )
        ),
        array(
            "type" => "textfield",
            "heading" => __("IMDB URL", 'jupiter-donut'),
            "param_name" => "imdb",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Qzone URL", 'jupiter-donut'),
            "param_name" => "qzone",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Renren URL", 'jupiter-donut'),
            "param_name" => "renren",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("VK.com URL", 'jupiter-donut'),
            "param_name" => "vk",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Wechat URL", 'jupiter-donut'),
            "param_name" => "wechat",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Weibo URL", 'jupiter-donut'),
            "param_name" => "weibo",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Whatsapp URL", 'jupiter-donut'),
            "param_name" => "whatsapp",
            "value" => "",
            "description" => __("Enter the full URL of your corresponding social network. Include (http://). If left blank, this social network icon will not be shown.", 'jupiter-donut')
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
