<?php
/**
 * Logger
 *
 * Adds a logger based on Monolog easily accessible as static functions
 * in the plugin.
 *
 * See link below for an overview of the log levels.
 *
 * @link    https://github.com/Seldaek/monolog/blob/HEAD/doc/01-usage.md#log-levels
 *
 * @package BernskioldMedia\WP\PluginBase
 */

namespace BernskioldMedia\WP\PluginBase;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Class Log
 *
 * @package BernskioldMedia\WP\PluginBase
 */
abstract class Log {

	/**
	 * Class Instance
	 *
	 * @var $instance
	 */
	protected static $instance;

	protected static $log_name;

	protected static $log_path;

	/**
	 * Method to return the Monolog instance
	 *
	 * @return \Monolog\Logger
	 */
	public static function get() {
		if ( ! static::$instance ) {
			static::configure();
		}

		return static::$instance;
	}

	/**
	 * Configure Monolog to use a rotating files system.
	 */
	protected static function configure() {
		// Create the logger.
		$logger = new Logger( static::$log_name );

		// Define the log level depending on environment.

		if ( defined( 'LOGGING_LEVEL' ) ) {
			$log_level = LOGGING_LEVEL;
		} elseif ( defined( 'ENABLE_LOGGING' ) && true === ENABLE_LOGGING ) {
			$log_level = Logger::DEBUG;
		} else {
			$log_level = Logger::ERROR;
		}

		// Set up the local saving.
		$logger->pushHandler( new StreamHandler( static::$log_path, $log_level ) );

		static::$instance = $logger;
	}

	/**
	 * Debug
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function debug( $message, array $context = [] ) {
		static::get()->addDebug( $message, $context );
	}

	/**
	 * Info
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function info( $message, array $context = [] ) {
		static::get()->addInfo( $message, $context );
	}

	/**
	 * Notice
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function notice( $message, array $context = [] ) {
		static::get()->addNotice( $message, $context );
	}

	/**
	 * Warning
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function warning( $message, array $context = [] ) {
		static::get()->addWarning( $message, $context );
	}

	/**
	 * Error
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function error( $message, array $context = [] ) {
		static::get()->addError( $message, $context );
	}

	/**
	 * Critical
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function critical( $message, array $context = [] ) {
		static::get()->addCritical( $message, $context );
	}

	/**
	 * Alert
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function alert( $message, array $context = [] ) {
		static::get()->addAlert( $message, $context );
	}

	/**
	 * Emergency
	 *
	 * @param  string  $message  Message.
	 * @param  array  $context  Data.
	 */
	public static function emergency( $message, array $context = [] ) {
		static::get()->addEmergency( $message, $context );
	}

}
