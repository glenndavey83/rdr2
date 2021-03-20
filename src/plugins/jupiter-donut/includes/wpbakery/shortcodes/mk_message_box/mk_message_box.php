<?php
$phpinfo = pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );
$id = Mk_Static_Files::shortcode_id();
Mk_Static_Files::addCSS( '#box-' . $id . '{background-image:url(' . JUPITER_DONUT_ASSETS_URL . '/img/box-' . $type . '-icon.png)}', $id );
?>

<div id="box-<?php echo $id; ?>" class="mk-message-box <?php echo $el_class . ' jupiter-donut-' . $visibility; ?> mk-<?php echo $type; ?>-box">
	<a class="box-close-btn" href="#"><i class="mk-icon-remove"></i></a>
	<span>
		<?php echo strip_tags( do_shortcode( $content ) ); ?>
	</span>
	<div class="clearboth"></div>
</div>
