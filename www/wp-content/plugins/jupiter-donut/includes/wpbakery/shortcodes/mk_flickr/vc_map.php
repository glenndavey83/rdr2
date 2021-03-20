<?php
vc_map(array(
	"name" => __("Flickr Feeds", 'jupiter-donut'),
    "base" => "mk_flickr",
	'html_template' => dirname( __FILE__ ) . '/mk_flickr.php',
    'icon' => 'icon-mk-flickr-feeds vc_mk_element-icon',
    'description' => __( 'Show your Flickr Feeds.', 'jupiter-donut' ),
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
               "heading" => __("Flickr API key", 'jupiter-donut'),
               "param_name" => "api_key",
               "value" => "",
               "description" => __('You must fill this field in order to get this shortcode working. You can obtain your API key from <a href="http://www.flickr.com/services/api/misc.api_keys.html">Flickr The App Garden</a>.', 'jupiter-donut')
          ),
          array(
               "type" => "textfield",
               "heading" => __("Flickr ID", 'jupiter-donut'),
               "param_name" => "flickr_id",
               "value" => "",
               "description" => __('To find your flickID visit <a href="http://idgettr.com/" target="_blank">idGettr</a>.', 'jupiter-donut')
          ),
          array(
               "type" => "range",
               "heading" => __("Number of photos", 'jupiter-donut'),
               "param_name" => "count",
               "value" => "6",
               "min" => "1",
               "max" => "100",
               "step" => "1",
               "unit" => 'photos'
          ),
          array(
               "type" => "range",
               "heading" => __("How many photos in one row?", 'jupiter-donut'),
               "param_name" => "column",
               "value" => "6",
               "min" => "1",
               "max" => "12",
               "step" => "1",
               "unit" => 'columns'
          ),
         /*
          Removed in V5.0
         array(
               "type" => "dropdown",
               "heading" => __("Thumbnail Size", 'jupiter-donut'),
               "param_name" => "thumb_size",
               "value" => array(
                    __("Small", 'jupiter-donut') => "s",
                    __("Medium", 'jupiter-donut') => "m",
                    __("Thumbnail", 'jupiter-donut') => "t"
               ),
               "description" => __("Photo order", 'jupiter-donut')
          ),
          array(
               "type" => "dropdown",
               "heading" => __("Type", 'jupiter-donut'),
               "param_name" => "type",
               "value" => array(
                    __("User", 'jupiter-donut') => "user",
                    __("Group", 'jupiter-donut') => "group"
               ),
               "description" => __("Photo stream type", 'jupiter-donut')
          ),
          array(
               "type" => "dropdown",
               "heading" => __("Display", 'jupiter-donut'),
               "param_name" => "display",
               "value" => array(
                    __("Latest", 'jupiter-donut') => "latest",
                    __("Random", 'jupiter-donut') => "random"
               ),
               "description" => __("Photo order", 'jupiter-donut')
          ),*/
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.", 'jupiter-donut')
        )
    )
));
