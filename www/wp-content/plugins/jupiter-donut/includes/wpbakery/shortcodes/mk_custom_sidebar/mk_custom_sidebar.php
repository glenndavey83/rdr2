<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$class = $el_class;
$class .= ' jupiter-donut-' . $visibility;

?>

<aside id="mk-sidebar" class="<?php echo $class; ?>">
	<div class="sidebar-wrapper" style="padding:0;">
		<?php

			if ( jupiter_donut_is_jupiterx() ) {
				echo jupiterx_widget_area( $sidebar ); // XSS ok.
				return;
			}

			dynamic_sidebar( $sidebar );
		?>
	</div>
</aside>

