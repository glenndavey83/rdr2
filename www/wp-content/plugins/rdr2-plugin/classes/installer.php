<?php

/**
 * CollectablesTracker installer class
 *
 * @author weDevs
 */
class CollectablesTracker_Installer {


    function do_install() {

        // installs
        $this->user_roles();
		
	}
	
	
	
    /**
     * Init rdr2 user roles
     *
     * @since CollectablesTracker 1.0
     *
     * @global WP_Roles $wp_roles
     */
    function user_roles() {
        global $wp_roles;

        if ( class_exists( 'WP_Roles' ) && !isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }

        add_role( 'merchant', __( 'Merchant', 'rdr2' ), array(
            'read'                      => true,
            'publish_posts'             => true,
            'edit_posts'                => true,
            'delete_published_posts'    => true,
            'edit_published_posts'      => true,
            'delete_posts'              => true,
            'manage_categories'         => true,
            'moderate_comments'         => true,
            'unfiltered_html'           => true,
            'upload_files'              => true,
            'edit_shop_orders'          => true,
            'edit_product'              => true,
            'read_product'              => true,
            'delete_product'            => true,
            'edit_products'             => true,
            'publish_products'          => true,
            'read_private_products'     => true,
            'delete_products'           => true,
            'delete_products'           => true,
            'delete_private_products'   => true,
            'delete_published_products' => true,
            'delete_published_products' => true,
            'edit_private_products'     => true,
            'edit_published_products'   => true,
            'manage_product_terms'      => true,
            'delete_product_terms'      => true,
            'assign_product_terms'      => true,
            'rdr2dar'                  => true
        ) );
		
        add_role( 'player', __( 'Player', 'rdr2' ), array(
            'read'                      => true,
        ) );

        $capabilities = array();
        $all_cap      = rdr2_get_all_caps();

        foreach( $all_cap as $key => $cap ) {
            $capabilities = array_merge( $capabilities, $cap );
        }

        $wp_roles->add_cap( 'shop_manager', 'rdr2dar' );
        $wp_roles->add_cap( 'administrator', 'rdr2dar' );

        foreach ( $capabilities as $key => $capability ) {
            $wp_roles->add_cap( 'merchant', $capability );
            $wp_roles->add_cap( 'administrator', $capability );
            $wp_roles->add_cap( 'shop_manager', $capability );
        }
    }

}

