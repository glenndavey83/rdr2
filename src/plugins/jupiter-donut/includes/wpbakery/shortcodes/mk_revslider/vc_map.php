<?php
if (is_plugin_active('revslider/revslider.php')) {
    global $wpdb;
    $rs         = $wpdb->get_results("
      SELECT id, title, alias
      FROM " . $wpdb->prefix . "revslider_sliders
      ORDER BY id ASC LIMIT 999
      ");
    $revsliders = array();
    if ($rs) {
        foreach ($rs as $slider) {
            $revsliders[$slider->title] = $slider->alias;
        }
    } else {
        $revsliders["No sliders found"] = 0;
    }
    vc_map(array(
        "name" => __("Revolution Slider", 'jupiter-donut'),
        "base" => "mk_revslider",
		'html_template' => dirname( __FILE__ ) . '/mk_revslider.php',
        'icon' => 'icon-mk-image-slideshow vc_mk_element-icon',
        "category" => __('Slideshows', 'jupiter-donut'),
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => __("Select Slideshow", 'jupiter-donut'),
                "param_name" => "id",
                'save_always' => true,
                "value" => $revsliders,
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
}
