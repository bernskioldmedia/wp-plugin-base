<?php

namespace BernskioldMedia\WP\PluginBase\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @package BernskioldMedia\WP\PluginBase
 */
abstract class Base_Plugin {

	/**
	 * Version
	 *
	 * @var string
	 */
	protected static $version = '1.0.0';

	/**
	 * Database Version
	 *
	 * @var string
	 */
	protected static $datbase_version = '1000';

	/**
	 * Plugin Textdomain
	 *
	 * @var string
	 */
	protected static $textdomain = 'wp-plugin-base';

	/**
	 * Main plugin file path.
	 *
	 * @var string
	 */
	protected static $plugin_file_path = '';

	/**
	 * Plugin Class Instance Variable
	 *
	 * @var static
	 */
	protected static $_instance = null;

	/**
	 * Plugin Instantiator
	 *
	 * @return static
	 */
	public static function instance() {

		if ( is_null( static::$_instance ) ) {
			static::$_instance = new static();
		}

		return static::$_instance;

	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.2
	 */
	private function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.2
	 */
	private function __wakeup() {
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hooks that are run on the time of init.
	 */
	protected function init_hooks(): void {
		add_action( 'init', [ static::class, 'load_languages' ] );
	}

	/**
	 * Load plugin translations.
	 */
	public static function load_languages(): void {

		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, static::get_textdomain() );

		unload_textdomain( static::get_textdomain() );

		// Start checking in the main language dir.
		load_textdomain( static::get_textdomain(), WP_LANG_DIR . '/' . static::get_textdomain() . '/' . static::get_textdomain() . '-' . $locale . '.mo' );

		// Otherwise, load from the plugin.
		load_plugin_textdomain( static::get_textdomain(), false, static::get_path( 'languages/' ) );

	}

	/**
	 * Get the path to the plugin folder, or the specified
	 * file relative to the plugin folder home.
	 *
	 * @param  string  $file
	 *
	 * @return string
	 */
	public static function get_path( $file = '' ): string {
		return untrailingslashit( plugin_dir_path( static::$plugin_file_path ) ) . '/' . $file;
	}

	/**
	 * Get the URL to the plugin folder, or the specified
	 * file relative to the plugin folder home.
	 *
	 * @param  string  $file
	 *
	 * @return string
	 */
	public static function get_url( $file = '' ): string {
		return untrailingslashit( plugin_dir_url( static::$plugin_file_path ) ) . '/' . $file;
	}

	/**
	 * Get the URL to the assets folder, or the specified
	 * file relative to the assets folder home.
	 *
	 * @param  string  $file
	 *
	 * @return string
	 */
	public static function get_assets_url( $file = '' ): string {
		return static::get_url( 'assets/' . $file );
	}

	/**
	 * Get AJAX URL
	 *
	 * @return string
	 */
	public static function get_ajax_url(): string {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Get the Plugin's Version
	 *
	 * @return string
	 */
	public static function get_version(): string {
		return static::$version;
	}

	/**
	 * Get the database version number.
	 *
	 * @return string
	 */
	public static function get_database_version(): string {
		return static::$datbase_version;
	}

	/**
	 * Get the plugin textdomain.
	 *
	 * @return string
	 */
	public static function get_textdomain(): string {
		return static::$textdomain;
	}

}
