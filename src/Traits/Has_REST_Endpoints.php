<?php

namespace BernskioldMedia\WP\PluginBase\Traits;

defined( 'ABSPATH' ) || exit;

trait Has_REST_Endpoints {

	/**
	 * Load the data stores.
	 */
	public function boot_rest_endpoints() {
		foreach ( self::$rest_endpoints as $endpoint ) {
			( new $endpoint() )->load();
		}
	}

}
