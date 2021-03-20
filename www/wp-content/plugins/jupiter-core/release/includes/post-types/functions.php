<?php
/**
 * Handle custom post types.
 *
 * @package Jupiter_Core\Post_Types
 *
 * @since 1.0.0
 */

add_action( 'init', 'jupiter_core_post_types' );
/**
 * Register custom post types.
 *
 * @since 1.0.0
 */
function jupiter_core_post_types() {
	register_post_type(
		'mkhb_header',
		array(
			'labels'          => array(
				'name'          => __( 'Headers' ),
				'singular_name' => __( 'Header' ),
			),
			'public'          => false,
			'rewrite'         => array(
				'slug' => 'mkhb_header',
			),
			'capability_type' => 'post',
			'supports'        => array( 'title', 'author' ),
		)
	);

	register_post_type(
		'mkhb_revision',
		array(
			'labels'           => array(
				'name'          => __( 'Header Revisions' ),
				'singular_name' => __( 'Header Revision' ),
			),
			'public'           => false,
			'capability_type'  => 'post',
			'map_meta_cap'     => true,
			'hierarchical'     => false,
			'rewrite'          => false,
			'query_var'        => false,
			'can_export'       => false,
			'delete_with_user' => true,
			'supports'         => array( 'title', 'author' ),
		)
	);
}
