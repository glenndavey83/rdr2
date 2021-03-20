<?php
if(shortcode_exists('mk_blog')) {
	echo do_shortcode( '[mk_blog style="modern"]' );
} else {
	mk_the_default_loop();
}
