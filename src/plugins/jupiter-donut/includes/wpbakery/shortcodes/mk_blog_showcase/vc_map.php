<?php
	vc_map(
		array(
			'name' => __( 'Blog Showcase', 'jupiter-donut' ),
			'base' => 'mk_blog_showcase',
			'html_template' => dirname( __FILE__ ) . '/mk_blog_showcase.php',
			'icon' => 'icon-mk-blog-portfolio-showcase vc_mk_element-icon',
			'category' => __( 'Loops', 'jupiter-donut' ),
			'description' => __( 'Showcase your blog posts.', 'jupiter-donut' ),
			'params' => array(
				array(
					'type'        => 'autocomplete',
					'heading'     => __( 'Select specific Categories', 'jupiter-donut' ),
					'param_name'  => 'cat',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'unique_values' => true,
										// In UI show results except selected. NB! You should manually check values in backend
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
										// In UI show results except selected. NB! You should manually check values in backend
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
										// In UI show results except selected. NB! You should manually check values in backend
					),
					'description' => __( 'Search for user ID, Username, Email Address to get autocomplete suggestions', 'jupiter-donut' ),
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
					'heading' => __( 'Post Excerpt Length', 'jupiter-donut' ),
					'description' => __( 'Define the length of the excerpt by number of characters. Zero will disable excerpt.', 'jupiter-donut' ),
					'param_name' => 'excerpt_length',
					'value' => '200',
					'min' => '0',
					'max' => '2000',
					'step' => '1',
					'unit' => 'characters',
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
				array(
					'heading' => __( 'Orderby', 'jupiter-donut' ),
					'description' => __( 'Sort retrieved Blog items by parameter.', 'jupiter-donut' ),
					'param_name' => 'orderby',
					'value' => $mk_orderby,
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
			),
		)
	);
