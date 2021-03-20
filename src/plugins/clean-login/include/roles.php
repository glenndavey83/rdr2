<?php

class CleanLogin_Roles{
    function load(){
        add_action( 'admin_init', array( $this, 'add_roles' ) );
    }

    function add_roles() {
        $create_standby_role = get_option( 'cl_standby' );
        $role = get_role( 'standby' );
    
        if ( $create_standby_role ) {
            // create if neccesary
            if ( !$role )
                $role = add_role('standby', 'StandBy');
            // and remove capabilities
            $role->remove_cap( 'read' );
        } else {
            // remove if exists
            if ( $role )
                remove_role( 'standby' );
        }
    }
}