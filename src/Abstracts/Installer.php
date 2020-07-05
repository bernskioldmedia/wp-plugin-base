<?php

namespace BernskioldMedia\WP\PluginBase\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginBase
 */
abstract class Installer {

	public static function install(): void {

		if ( method_exists( static::class, 'scheduled_tasks' ) ) {
			static::scheduled_tasks();
		}

		flush_rewrite_rules();
	}

}
