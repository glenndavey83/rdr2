<?php
vc_map(
	array(
		'name' => __( 'Photo Album', 'jupiter-donut' ),
		'base' => 'mk_photo_album',
		'html_template' => dirname( __FILE__ ) . '/mk_photo_album.php',
		'icon' => 'icon-mk-gallery vc_mk_element-icon',
		'category' => __( 'Loops', 'jupiter-donut' ),
		'description' => __( 'Photo Albums with loads of styles.', 'jupiter-donut' ),
		'params' => array(
			array(
				'type' => 'range',
				'heading' => __( 'Space Between Grids', 'jupiter-donut' ),
				'param_name' => 'space',
				'value' => '5',
				'min' => '0',
				'max' => '30',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
			),

			array(
				'type' => 'range',
				'heading' => __( 'How many column?', 'jupiter-donut' ),
				'param_name' => 'column',
				'value' => '3',
				'min' => '1',
				'max' => '4',
				'step' => '1',
				'unit' => 'column',
				'description' => __( '', 'jupiter-donut' ),
			),

			array(
				'heading' => __( 'Image Size', 'jupiter-donut' ),
				'description' => __( 'Please note that this option will not work for Masonry option.', 'jupiter-donut' ),
				'param_name' => 'image_size',
				'value' => mk_get_image_sizes(),
				'type' => 'dropdown',
			),


			array(
				'type' => 'range',
				'heading' => __( 'Album height?', 'jupiter-donut' ),
				'param_name' => 'album_height',
				'value' => '300',
				'min' => '100',
				'max' => '500',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'heading' => __( 'Show Description?', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'description_preview',
				'default' => 'false',
				'value' => array(
					__( 'Yes', 'jupiter-donut' ) => 'true',
					__( 'No', 'jupiter-donut' ) => 'false',
				),
				'type' => 'dropdown',
			),

			array(
				'heading' => __( 'Show Preview thumbnails?', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'thumbnail_preview',
				'default' => 'false',
				'value' => array(
					__( 'Yes', 'jupiter-donut' ) => 'true',
					__( 'No', 'jupiter-donut' ) => 'false',
				),
				'type' => 'dropdown',
			),

			array(
				'heading' => __( 'Show Overlay?', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'overlay_preview',
				'default' => 'false',
				'value' => array(
					__( 'Yes', 'jupiter-donut' ) => 'true',
					__( 'No', 'jupiter-donut' ) => 'false',
				),
				'type' => 'dropdown',
			),

			array(
				'type' => 'range',
				'heading' => __( 'Offset', 'jupiter-donut' ),
				'param_name' => 'offset',
				'value' => '0',
				'min' => '0',
				'max' => '50',
				'step' => '1',
				'unit' => 'posts',
				'description' => __( 'Number of post to displace or pass over, it means based on your order of the loop, this number will define how many posts to pass over and start from the nth number of the offset.', 'jupiter-donut' ),
			),

			array(
				'type' => 'range',
				'heading' => __( 'How many Posts?', 'jupiter-donut' ),
				'param_name' => 'count',
				'value' => '10',
				'min' => '-1',
				'max' => '50',
				'step' => '1',
				'unit' => 'posts',
				'description' => __( 'How many Posts would you like to show? (-1 means unlimited)', 'jupiter-donut' ),
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
				'type' => 'toggle',
				'heading' => __( 'Show Pagination?', 'jupiter-donut' ),
				'param_name' => 'pagination',
				'value' => 'true',
				'description' => __( 'Disable this option if you do not want pagination for this loop.', 'jupiter-donut' ),
			),
			array(
				'heading' => __( 'Pagination Style', 'jupiter-donut' ),
				'description' => __( 'Select which pagination style you would like to use on this loop.', 'jupiter-donut' ),
				'param_name' => 'pagination_style',
				'value' => array(
					__( 'Classic Pagination Navigation', 'jupiter-donut' ) => '1',
					__( 'Load More button', 'jupiter-donut' ) => '2',
					__( 'Load More on page scroll', 'jupiter-donut' ) => '3',
				),
				'type' => 'dropdown',
				'dependency' => array(
					'element' => 'pagination',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'heading' => __( 'Orderby', 'jupiter-donut' ),
				'description' => __( 'Sort retrieved Blog items by parameter.', 'jupiter-donut' ),
				'param_name' => 'orderby',
				'value' => $mk_orderby,
				'type' => 'dropdown',
			),
			array(
				'heading' => __( 'Order', 'jupiter-donut' ),
				'description' => __( "Designates the ascending or descending order of the 'orderby' parameter.", 'jupiter-donut' ),
				'param_name' => 'order',
				'value' => array(
					__( 'DESC (descending order)', 'jupiter-donut' ) => 'DESC',
					__( 'ASC (ascending order)', 'jupiter-donut' ) => 'ASC',
				),
				'type' => 'dropdown',
			),
			$add_device_visibility,
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'value' => '',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.', 'jupiter-donut' ),
			),

			array(
				'type' => 'group_heading',
				'title' => __( 'Title/Description Styles?', 'jupiter-donut' ),
				'param_name' => 'title_description_style_title',
				'style' => 'border: 0; font-size: 18px; padding:0;',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
			),

			array(
				'heading' => __( 'Show Title/Description by default (on mouse out)?', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'show_title_desc_without_hover',
				'value' => array(
					__( 'No', 'jupiter-donut' ) => 'false',
					__( 'Yes', 'jupiter-donut' ) => 'true',
				),
				'type' => 'dropdown',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
			),

			array(
				'heading' => __( 'Title/Description container style', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'title_preview_style',
				'value' => array(
					__( 'None', 'jupiter-donut' ) => 'none',
					__( 'Bottom Bar', 'jupiter-donut' ) => 'bar',
					__( 'Bottom Gradient', 'jupiter-donut' ) => 'gradient',
				),
				'type' => 'dropdown',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
			),

			array(
				'type' => 'range',
				'heading' => __( 'Title Font size?', 'jupiter-donut' ),
				'param_name' => 'title_font_size',
				'value' => '25',
				'min' => '10',
				'max' => '80',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
			),

			array(
				'type' => 'range',
				'heading' => __( 'Description Font size?', 'jupiter-donut' ),
				'param_name' => 'description_font_size',
				'value' => '15',
				'min' => '10',
				'max' => '20',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'description_preview',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'type' => 'group_heading',
				'title' => __( 'Preview Thumbnail Styles?', 'jupiter-donut' ),
				'param_name' => 'preview_thumbnail_style_title',
				'style' => 'border: 0; font-size: 18px; padding:0;',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'thumbnail_preview',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'type' => 'toggle',
				'heading' => __( 'Show Preview Thumbnail by default (on mouse out)?', 'jupiter-donut' ),
				'param_name' => 'show_thumbnail_without_hover',
				'value' => 'false',
				'description' => __( '', 'jupiter-donut' ),
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'thumbnail_preview',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'heading' => __( 'Thumbnail Shape', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'thumbnail_shape',
				'value' => array(
					__( 'Rectangle Frame ', 'jupiter-donut' ) => 'rectangle',
					__( 'Circle Frame', 'jupiter-donut' ) => 'circle',
					__( 'Diamond Frame', 'jupiter-donut' ) => 'diamond',
				),
				'type' => 'dropdown',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'thumbnail_preview',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'type' => 'group_heading',
				'title' => __( 'Overlay Styles?', 'jupiter-donut' ),
				'param_name' => 'overlay_style_title',
				'style' => 'border: 0; font-size: 18px; padding:0;',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'overlay_preview',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'heading' => __( 'Show Overlay by default (on mouse out)?', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'show_overlay_without_hover',
				'value' => array(
					__( 'No', 'jupiter-donut' ) => 'false',
					__( 'Yes', 'jupiter-donut' ) => 'true',
				),
				'type' => 'dropdown',
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'overlay_preview',
					'value' => array(
						'true',
					),
				),
			),

			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'Overlay Color', 'jupiter-donut' ),
				'param_name' => 'overlay_background',
				'value' => '',
				'description' => __( '', 'jupiter-donut' ),
				'group' => __( 'Styles & Colors', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'overlay_preview',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'heading' => __( 'Title / Description Animation (on mouse over)', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'title_animation',
				'value' => array(
					__( 'Fade in', 'jupiter-donut' ) => 'fade_in',
					__( 'Slide from bottom', 'jupiter-donut' ) => 'slide_from_bottom',
					__( 'Scale in', 'jupiter-donut' ) => 'scale_in',
				),
				'type' => 'dropdown',
				'group' => __( 'Hover Options', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'show_title_desc_without_hover',
					'value' => array(
						'false',
					),
				),
			),

			array(
				'heading' => __( 'Overlay Animation (on mouse over)', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'overlay_hover_animation',
				'value' => array(
					__( 'Fade in', 'jupiter-donut' ) => 'fade_in',
					__( 'Ripple', 'jupiter-donut' ) => 'ripple',
				),
				'type' => 'dropdown',
				'group' => __( 'Hover Options', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'show_overlay_without_hover',
					'value' => array(
						'false',
					),
				),
			),

			array(
				'heading' => __( 'Cover Image Animation (on mouse over)', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
				'param_name' => 'cover_image_hover_animation',
				'value' => array(
					__( 'None', 'jupiter-donut' ) => 'none',
					__( 'Slide', 'jupiter-donut' ) => 'slide',
					__( 'Blur', 'jupiter-donut' ) => 'blur',
				),
				'type' => 'dropdown',
				'group' => __( 'Hover Options', 'jupiter-donut' ),
			),
		),
	)
);
