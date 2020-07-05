<?php

namespace BernskioldMedia\WP\PluginBase\Traits;

defined( 'ABSPATH' ) || exit;

trait Has_Data_Stores {

	/**
	 * Load the data stores.
	 */
	public function boot_data_stores() {
		foreach ( self::$data_stores as $data_store ) {
			new $data_store();
		}
	}

}
