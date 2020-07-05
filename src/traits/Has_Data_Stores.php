<?php

namespace BernskioldMedia\WP\PluginBase\Traits;

defined( 'ABSPATH' ) || exit;

trait Has_Data_Stores {

	/**
	 * A list of all data store classes to register.
	 *
	 * @var array
	 */
	public static $data_stores = [];

	/**
	 * Load the data stores.
	 */
	public function boot_data_stores() {
		foreach ( self::$data_stores as $data_store ) {
			new $data_store();
		}
	}

}
