<?php
vc_map(array(
    "name" => __("Imagebox Item", 'jupiter-donut'),
    "base" => "mk_imagebox_item",
	'html_template' => dirname( __FILE__ ) . '/mk_imagebox_item.php',
    "as_child" => array('only' => 'mk_imagebox'),
    'icon' => 'icon-mk-content-box vc_mk_element-icon',
    "content_element" => true,
    "category" => __('Slideshows', 'jupiter-donut'),
    'params' => array(
        array(
            "type" => "dropdown",
            "heading" => __("Icon Type", 'jupiter-donut'),
            "param_name" => "icon_type",
            "value" => array(
                __('Image', 'jupiter-donut') => "image",
                __('Video', 'jupiter-donut') => "video"
            ),
            "description" => __("Choose Box Type.", 'jupiter-donut')
        ),
        array(
            "type" => "upload",
            "heading" => __("Background Video (.MP4)", 'jupiter-donut'),
            "param_name" => "mp4",
            "value" => "",
            "description" => __("Upload your video with .MP4 extension. (Compatibility for Safari and IE9)", 'jupiter-donut'),
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'video'
                )
            )
        ),
        array(
            "type" => "upload",
            "heading" => __("Background Video (.WebM)", 'jupiter-donut'),
            "param_name" => "webm",
            "value" => "",
            "description" => __("Upload your video with .WebM extension. (Compatibility for Firefox4, Opera, and Chrome)", 'jupiter-donut'),
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'video'
                )
            )
        ),
        array(
            "type" => "upload",
            "heading" => __("Background Video (.OGV)", 'jupiter-donut'),
            "param_name" => "ogv",
            "value" => "",
            "description" => __("Upload your video with .OGV extension. (Compatibility for Firefox, Opera, and Chrome)", 'jupiter-donut'),
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'video'
                )
            )
        ),
        array(
            "type" => "upload",
            "heading" => __("Preview Image", 'jupiter-donut'),
            "param_name" => "preview_image",
            "value" => "",
            "description" => __("Upload preview image for mobile devices", 'jupiter-donut'),
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'video'
                )
            )
        ),
        array(
            "type" => "upload",
            "heading" => __("Item Image", 'jupiter-donut'),
            "param_name" => "item_image",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "icon_type",
                'value' => array(
                    'image'
                )
            )
        ),
        array(
            "type" => "toggle",
            "heading" => __("Add Padding to Image?", 'jupiter-donut'),
            "param_name" => "image_padding",
            "value" => "true",
            "description" => __("", 'jupiter-donut'),
            // "dependency" => array(
            //     'element' => "icon_type",
            //     'value' => array(
            //         'image'
            //     )
            // )
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Background Color", 'jupiter-donut'),
            "param_name" => "background_color",
            "value" => "#eaeaea",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Item Title", 'jupiter-donut'),
            "param_name" => "item_title",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
        ),
        array(
            "type" => "range",
            "heading" => __("Title Font Size", 'jupiter-donut'),
            "param_name" => "title_text_size",
            "value" => "16",
            "min" => "10",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Title Font Weight", 'jupiter-donut'),
            "param_name" => "title_font_weight",
            "value" => $font_weight,
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Title Color", 'jupiter-donut'),
            "param_name" => "title_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textarea",
            "heading" => __("Description", 'jupiter-donut'),
            "param_name" => "content",
            "holder" => 'div',
            "value" => "",
            "description" => __("", 'jupiter-donut'),
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Description Color", 'jupiter-donut'),
            "param_name" => "text_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Button Text", 'jupiter-donut'),
            "param_name" => "btn_text",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Button Url", 'jupiter-donut'),
            "param_name" => "btn_url",
            "value" => "",
            "description" => __("", 'jupiter-donut'),
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Button Background Color", 'jupiter-donut'),
            "param_name" => "btn_background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Button Text Color", 'jupiter-donut'),
            "param_name" => "btn_text_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Button Hover Background Color", 'jupiter-donut'),
            "param_name" => "btn_hover_background_color",
            "value" => "",
            "description" => __("", 'jupiter-donut')
        ),

        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        )
    )
));
