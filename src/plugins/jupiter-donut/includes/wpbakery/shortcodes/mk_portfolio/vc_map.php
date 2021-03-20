<?php
vc_map(array(
    "name" => __("Portfolio", 'jupiter-donut'),
    "base" => "mk_portfolio",
	'html_template' => dirname( __FILE__ ) . '/mk_portfolio.php',
    'icon' => 'icon-mk-portfolio vc_mk_element-icon',
    "admin_enqueue_js" => JUPITER_DONUT_INCLUDES_URL . "/wpbakery/shortcodes/mk_portfolio/vc_admin.js",
    "category" => __('Loops', 'jupiter-donut'),
    'description' => __( 'Portfolio loops are here.', 'jupiter-donut' ),
    "params" => array(
        array(
            "heading" => __("Style", 'jupiter-donut'),
            "description" => __("Select which Portfolio loop style you would like to use.", 'jupiter-donut'),
            "param_name" => "style",
            "value" => array(
                __("Classic", 'jupiter-donut') => "classic",
                __("Grid", 'jupiter-donut') => "grid",
                __("Masonry", 'jupiter-donut') => "masonry"
            ),
            "type" => "dropdown"
        ),

        array(
            "heading" => __("Hover Scenarios", 'jupiter-donut'),
            "description" => __("This is what happens when user hovers over a portfolio item. Different animations and styles will be showed up on each scenario.", 'jupiter-donut'),
            "param_name" => "hover_scenarios",
            "value" => array(
                __("Slide Box", 'jupiter-donut') => "slidebox",
                __("Fade Box", 'jupiter-donut') => "fadebox",
                __("Zoom In Box", 'jupiter-donut') => "zoomin",
                __("Zoom Out Box", 'jupiter-donut') => "zoomout",
                __("Light Zoom In", 'jupiter-donut') => "light-zoomin",
                __("3D Cube", 'jupiter-donut') => "cube",
                __("None (only link to the single portfolio page)", 'jupiter-donut') => "none"
            ),
            "type" => "dropdown",
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'grid',
                    'masonry'
                )
            )
        ),
        array(
            "heading" => __("Grid Spacing", 'jupiter-donut'),
            "description" => __("Space between items in grid and masonry portfolio styles.", 'jupiter-donut'),
            "param_name" => "grid_spacing",
            "value" => "4",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "type" => "range",
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'grid',
                    'masonry'
                )
            )
        ),
        array(
            "heading" => __("Image Size", 'jupiter-donut'),
            "description" => __("Please note that this option will not work for Masonry option.", 'jupiter-donut'),
            "param_name" => "image_size",
            "value" => mk_get_image_sizes(),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'classic',
                    'grid',
                    'masonry'
                )
            ),
            "type" => "dropdown"
        ),
        array(
            "type" => "range",
            "heading" => __("Image Height", 'jupiter-donut'),
            "param_name" => "height",
            "value" => "300",
            "min" => "100",
            "max" => "1000",
            "step" => "1",
            "unit" => 'px',
            "description" => __("Please note that this option will not work in Masonry portfolio style.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "image_size",
                'value' => array(
                    'crop'
                )
            )
        ),
        array(
            "type" => "toggle",
            "heading" => __("Shows Posts Using Ajax?", 'jupiter-donut'),
            "param_name" => "ajax",
            "value" => "false",
            "description" => __("If you enable this option the portfolio posts items will be viewed in the same page above the portfolio loop.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'grid',
                    'masonry'
                )
            )
        ),

        array(
            "type" => "range",
            "heading" => __("How many Columns?", 'jupiter-donut'),
            "param_name" => "column",
            "value" => "3",
            "min" => "1",
            "max" => "6",
            "step" => "1",
            "unit" => 'columns',
            "description" => __("How many columns you would like to show in one row? Please note that the actual size you will get will be different with 10px tolerance. 3, 4, 5, 6 column with sidebar layouts will be 2 columns.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'grid',
                    'classic'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Post Excerpt Length", 'jupiter-donut'),
            "description" => __("Define the length of the excerpt by number of characters. Zero will disable excerpt.", 'jupiter-donut'),
            "param_name" => "excerpt_length",
            "value" => "200",
            "min" => "0",
            "max" => "2000",
            "step" => "1",
            "unit" => 'characters',
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'classic'
                )
            )
        ),
        array(
            "heading" => __("Choose Meta Element", 'jupiter-donut'),
            "description" => __("Choose the type of meta data you would like to show in portfolio loop items.", 'jupiter-donut'),
            "param_name" => "meta_type",
            "value" => array(
                __("Category", 'jupiter-donut') => "category",
                __("Date", 'jupiter-donut') => "date",
                __("None", 'jupiter-donut') => "none",
            ),
            "type" => "dropdown"
        ),
        array(
            "type" => "toggle",
            "heading" => __("Show Permalink?", 'jupiter-donut'),
            "param_name" => "permalink_icon",
            "value" => "true",
            "description" => __("If do not need portfolio single post you can remove permalink from image hover icon and title.", 'jupiter-donut')
        ),
        array(
            "type" => "icon_selector",
            "heading" => __("Permalink Icon", 'jupiter-donut'),
            "param_name" => "permalink_icon_name",
            "value" => "mk-jupiter-icon-arrow-circle",
            "dependency" => array(
                'element' => "permalink_icon",
                'value' => array(
                    'true'
                )
            )
        ),
        array(
            "type" => "toggle",
            "heading" => __("Show Zoom Link?", 'jupiter-donut'),
            "param_name" => "zoom_icon",
            "value" => "true",
            "description" => __("If do not need portfolio single post you can remove zoom link from image hover icon and title.", 'jupiter-donut')
        ),
        array(
            "type" => "icon_selector",
            "heading" => __("Zoom Icon", 'jupiter-donut'),
            "param_name" => "zoom_icon_name",
            "value" => "mk-jupiter-icon-plus-circle",
            "dependency" => array(
                'element' => "zoom_icon",
                'value' => array(
                    'true',
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("How many Posts?", 'jupiter-donut'),
            "param_name" => "count",
            "value" => "10",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("How many Posts would you like to show? (-1 means unlimited)", 'jupiter-donut')
        ),
        array(
            "type" => "toggle",
            "heading" => __("Sortable?", 'jupiter-donut'),
            "description" => __("", 'jupiter-donut'),
            "param_name" => "sortable",
            "value" => 'true',
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Sortable Align?", 'jupiter-donut'),
            "param_name" => "sortable_align",
            "value" => array(
                __("Left", 'jupiter-donut') => "left",
                __("Center", 'jupiter-donut') => "center",
                __("Right", 'jupiter-donut') => "right"
            ),
            "dependency" => array(
                'element' => "sortable",
                'value' => array(
                    'true',
                )
            )

        ),
        array(
            "heading" => __("Sortable Style", 'jupiter-donut'),
            "description" => __("The look of sortable section of portfolio loop", 'jupiter-donut'),
            "param_name" => "sortable_style",
            "value" => array(
                __("Classic", 'jupiter-donut') => "classic",
                __("Outline", 'jupiter-donut') => "outline"
            ),
            "type" => "dropdown",
            "dependency" => array(
                'element' => "sortable",
                'value' => array(
                    'true',
                )
            )
        ),
        array(
            "heading" => __("Sortable Mode", 'jupiter-donut'),
            "description" => __("Ajax Mode retrieves the result by searching through the whole portfolio posts. On the other hand, Static Mode searches to find results only in the same page.", 'jupiter-donut'),
            "param_name" => "sortable_mode",
            "value" => array(
                __("Ajax", 'jupiter-donut') => "ajax",
                __("Static", 'jupiter-donut') => "static"
            ),
            "type" => "dropdown",
            "dependency" => array(
                'element' => "sortable",
                'value' => array(
                    'true',
                )
            )
        ),
        array(
            "type" => "textfield",
            "heading" => __("Sortable [All] Link Title", 'jupiter-donut'),
            "param_name" => "sortable_all_text",
            "value" => "All",
            "description" => __("", 'jupiter-donut')
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Sortable Background Custom Color (Outline Style)", 'jupiter-donut'),
            "param_name" => "sortable_bg_color",
            "value" => "#1a1a1a",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "sortable_style",
                'value' => array(
                    'outline'
                )
            )
        ),
        array(
            "type" => "alpha_colorpicker",
            "heading" => __("Sortable Text Custom Color (Outline Style)", 'jupiter-donut'),
            "param_name" => "sortable_txt_color",
            "value" => "#cccccc",
            "description" => __("", 'jupiter-donut'),
            "dependency" => array(
                'element' => "sortable_style",
                'value' => array(
                    'outline'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Offset", 'jupiter-donut'),
            "param_name" => "offset",
            "value" => "0",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("Number of post to displace or pass over, it means based on your order of the loop, this number will define how many posts to pass over and start from the nth number of the offset.", 'jupiter-donut')
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Categories', 'jupiter-donut' ),
            'param_name'  => 'categories',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for category name to get autocomplete suggestions', 'jupiter-donut' ),
        ),
         array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Posts', 'jupiter-donut' ),
            'param_name'  => 'posts',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for post ID or post title to get autocomplete suggestions', 'jupiter-donut' ),
        ),
        array(
            'type'        => 'autocomplete',
            'heading'     => __( 'Select specific Authors', 'jupiter-donut' ),
            'param_name'  => 'author',
            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'unique_values' => true,
                            ),
            'description' => __( 'Search for user ID, Username, Email Address to get autocomplete suggestions', 'jupiter-donut' ),
        ),

        array(
            "type" => "toggle",
            "heading" => __("Show Pagination?", 'jupiter-donut'),
            "param_name" => "pagination",
            "value" => "true",
            "description" => __("Disable this option if you do not want pagination for this loop.", 'jupiter-donut')
        ),
        array(
            "heading" => __("Pagination Style", 'jupiter-donut'),
            "description" => __("Select which pagination style you would like to use on this loop.", 'jupiter-donut'),
            "param_name" => "pagination_style",
            "value" => array(
                __("Classic Pagination Navigation", 'jupiter-donut') => "1",
                __("Load More button", 'jupiter-donut') => "2",
                __("Load More on page scroll", 'jupiter-donut') => "3"
            ),
            "type" => "dropdown",
            "dependency" => array(
                'element' => "pagination",
                'value' => array(
                    'true'
                )
            )
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
            "description" => __("Sort retrieved Blog items by parameter.", 'jupiter-donut'),
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Target", 'jupiter-donut'),
            "param_name" => "target",
            "value" => $target_arr,
            "description" => __("Target for title permalink and image hover permalink icon.", 'jupiter-donut'),
            "dependency" => array(
                'element' => "permalink_icon",
                'value' => array(
                    'true'
                )
            )
        ),
        array(
            "type" => "toggle",
            "heading" => __("Lazyload", 'jupiter-donut'),
            "param_name" => "lazyload",
            "value" => "false",
        ),
        array(
            "type" => "toggle",
            "heading" => __("Disable Lazyload", 'jupiter-donut'),
            "param_name" => "disable_lazyload",
            "value" => "false",
            "description" => __("Disable Lazyload is only available when 'Global Lazyload' is enabled in the Theme Options.", 'jupiter-donut'),
        ),
        $add_device_visibility,
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'jupiter-donut'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", 'jupiter-donut')
        ),
    )
));
