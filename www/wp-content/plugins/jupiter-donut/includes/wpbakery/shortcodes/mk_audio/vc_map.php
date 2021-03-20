<?php
    vc_map(array(
        "name" => __("Audio Player", 'jupiter-donut') ,
        "base" => "mk_audio",
		'html_template' => dirname( __FILE__ ) . '/mk_audio.php',
        'icon' => 'icon-mk-audio-player vc_mk_element-icon',
        'description' => __('Adds player to your audio files.', 'jupiter-donut') ,
        "category" => __('General', 'jupiter-donut') ,
        "params" => array(
            array(
                "type" => "upload",
                "heading" => __("Upload MP3 file format", 'jupiter-donut') ,
                "param_name" => "mp3_file",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "upload",
                "heading" => __("Upload OGG file format", 'jupiter-donut') ,
                "param_name" => "ogg_file",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "upload",
                "heading" => __("Upload A Thumbnail for this audio", 'jupiter-donut') ,
                "param_name" => "thumb",
                "value" => "",
                "description" => __("It will automatically cropped to the correct size needed for the container.", 'jupiter-donut')
            ) ,
            array(
                "type" => "textfield",
                "heading" => __("Sound Author", 'jupiter-donut') ,
                "param_name" => "audio_author",
                "value" => "",
                "description" => __("", 'jupiter-donut')
            ) ,
            array(
                "type" => "alpha_colorpicker",
                "heading" => __("Player Background", 'jupiter-donut'),
                "param_name" => "player_background",
                "value" => "",
                "description" => __("If left empty a random color will be shown in each page visit.", 'jupiter-donut'),
            ),
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
