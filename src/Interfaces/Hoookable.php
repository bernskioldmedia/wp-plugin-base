<?php

namespace BernskioldMedia\WP\PluginBase\Interfaces;

/**
 * Interface Hoookable
 *
 * @package BernskioldMedia\WP\PluginBase\Interfaces
 */
interface Hoookable {

	/**
	 * Hookable classes must implement a standardized hooks function
	 * that can be called when booted.
	 */
	public static function hooks(): void;

}
