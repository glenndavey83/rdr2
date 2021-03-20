<?php

if ( ! function_exists( 'jupiter_core_decode' ) ) {
	/**
	 * Decode the data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data Data.
	 */
	function jupiter_core_decode( $data ) {
		return base64_decode( $data );
	}
}

if ( ! function_exists( 'jupiter_core_encode' ) ) {
	/**
	 * Encode the data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data Data.
	 */
	function jupiter_core_encode( $data ) {
		return base64_encode( $data );
	}
}
