<?php

extract(
	shortcode_atts(
		array(
			'title' => '',
			'twitter_name' => 'twitter',
			'tweets_count' => 5,
			'text_color' => '',
			'link_color' => '',
			'item_id' => '',
			'visibility' => '',
			'el_class' => '',
		) , $atts
	)
);

$item_id = ( ! empty( $item_id )) ? $item_id : global_get_post_id();

$consumer_key        = jupiter_donut_get_option( 'twitter_consumer_key' );
$consumer_secret     = jupiter_donut_get_option( 'twitter_consumer_secret' );
$access_token        = jupiter_donut_get_option( 'twitter_access_token' );
$access_token_secret = jupiter_donut_get_option( 'twitter_access_token_secret' );

Mk_Static_Files::addAssets( 'vc_twitter' );
