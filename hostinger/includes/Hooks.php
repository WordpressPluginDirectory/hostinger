<?php

namespace Hostinger;

defined( 'ABSPATH' ) || exit;

class Hooks {

	public function __construct() {
		add_action( 'init', array( $this, 'check_url_and_flush_rules' ) );
	}

	public function check_url_and_flush_rules() {
		$https       = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
		$host        = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
		$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		$current_url    = $https . "://" . $host . $request_uri;
		$url_components = parse_url( $current_url );

		if ( isset( $url_components['query'] ) ) {
			parse_str( $url_components['query'], $params );

			if ( isset( $params['app_name'] ) ) {
				$app_name = sanitize_text_field( urldecode( $params['app_name'] ) );
				$app_name = str_replace( '+', ' ', $app_name );

				if ( $app_name === 'Omnisend App' ) {
					if ( function_exists( 'flush_rewrite_rules' ) ) {
						flush_rewrite_rules();
					}

					if ( has_action( 'litespeed_purge_all' ) ) {
						do_action( 'litespeed_purge_all' );
					}
				}
			}
		}
	}

}
