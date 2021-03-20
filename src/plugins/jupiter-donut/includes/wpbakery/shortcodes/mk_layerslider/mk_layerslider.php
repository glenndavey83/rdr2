<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

if ( ! empty( $visibility ) ) {
	echo '<div class="jupiter-donut-' . $visibility . '">';
}

if ( !empty( $id ) ) {
	echo do_shortcode( '[layerslider id="'.$id.'"]' );
}

if ( ! empty( $visibility ) ) {
	echo '</div>';
}
