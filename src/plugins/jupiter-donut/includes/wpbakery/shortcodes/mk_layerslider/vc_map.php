<?php
if (is_plugin_active('LayerSlider/layerslider.php')) {
    $use_old = class_exists('LS_Sliders');
    if (!class_exists('LS_Sliders') && defined('LS_ROOT_PATH') && strpos(LS_ROOT_PATH, '.php') === false) {
        include_once LS_ROOT_PATH . '/classes/class.ls.sliders.php';
        $use_old = false;
    }
    if (!class_exists('LS_Sliders')) {

        //again check is needed if some problem inside file "class.ls.sliders.php
        $use_old = true;
    }

    /**
     * Filter to use old type of layerslider vendor.
     * @since 4.4.2
     */
    $use_old = apply_filters('vc_vendor_layerslider_old', $use_old);
     // @since 4.4.2 hook to use old style return true.
    $layer_sliders = array();
    if ($use_old) {
        global $wpdb;
        $ls = $wpdb->get_results("
  SELECT id, name, date_c
  FROM " . $wpdb->prefix . "layerslider
  WHERE flag_hidden = '0' AND flag_deleted = '0'
  ORDER BY date_c ASC LIMIT 999
  ");
        $layer_sliders = array();
        if (!empty($ls)) {
            foreach ($ls as $slider) {
                $layer_sliders[$slider->name] = $slider->id;
            }
        }
        else {
            $layer_sliders[__('No sliders found', 'jupiter-donut') ] = 0;
        }
    }
    else {
        $ls = LS_Sliders::find(array(
            'limit' => 999,
            'order' => 'ASC',
        ));
        $layer_sliders = array();
        if (!empty($ls)) {
            foreach ($ls as $slider) {
                $layer_sliders[$slider['name']] = $slider['id'];
            }
        }
        else {
            $layer_sliders[__('No sliders found', 'jupiter-donut') ] = 0;
        }
    }
    vc_map(array(
        "name" => __("Layerslider", 'jupiter-donut') ,
        "base" => "mk_layerslider",
		'html_template' => dirname( __FILE__ ) . '/mk_layerslider.php',
        'icon' => 'icon-mk-layerslider vc_mk_element-icon',
        "category" => __('Slideshows', 'jupiter-donut') ,
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => __("Select Slideshow", 'jupiter-donut') ,
                "param_name" => "id",
                'save_always' => true,
                "value" => $layer_sliders,
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
}
