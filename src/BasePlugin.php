<?php

namespace BernskioldMedia\WP\PluginBase;

defined( 'ABSPATH' ) || exit;

/**
 * Class BasePlugin
 *
 * @package BernskioldMedia\WP\PluginBase
 */
abstract class BasePlugin {

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
	protected static $database_version = '1000';

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
	 * Add a list of Facet classes here that will be
	 * loaded alongside this plugin.
	 *
	 * @var string[]
	 */
	protected static $facets = [];

	/**
	 * The data stores (class names) that will be loaded
	 * alongside this plugin.
	 *
	 * @var string[]
	 */
	protected static $data_stores = [];

	/**
	 * The REST endpoints (class names) that will be loaded
	 * alongside this plugin.
	 *
	 * @var string[]
	 */
	protected static $rest_endpoints = [];

	/**
	 * Include a list of customizer section classes to
	 * load them with the theme.
	 *
	 * @var array
	 */
	protected static $customizer_sections = [];

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

		if ( method_exists( static::class, 'has_dependencies' ) ) {
			if ( static::has_dependencies() ) {
				$this->init_hooks();
			}
		} else {
			$this->init_hooks();
		}

	}

	/**
	 * Hooks that are run on the time of init.
	 */
	protected function init_hooks(): void {
		add_action( 'init', [ static::class, 'load_languages' ] );

		if ( method_exists( static::class, 'setup_admin_columns_storage_repository' ) ) {
			add_action( 'acp/storage/repositories', [ static::class, 'setup_admin_columns_storage_repository' ] );
		}

		if ( ! empty( static::$data_stores ) ) {
			foreach ( static::$data_stores as $data_store ) {
				new $data_store();
			}
		}

		if ( ! empty( static::$rest_endpoints ) ) {
			foreach ( static::$rest_endpoints as $endpoint ) {
				( new $endpoint() )->load();
			}
		}

		if ( ! empty( static::$rest_endpoints ) ) {
			foreach ( static::$rest_endpoints as $endpoint ) {
				( new $endpoint() )->load();
			}
		}

		if ( ! empty( static::$facets ) ) {
			foreach ( static::$facets as $facet ) {
				add_filter( 'facetwp_facets', [ $facet, 'make' ] );
			}
		}

		if ( ! empty( static::$customizer_sections ) ) {
			foreach ( static::$customizer_sections as $class ) {
				new $class();
			}
		}
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
		load_plugin_textdomain( static::get_textdomain(), false, dirname( plugin_basename( static::$plugin_file_path ) ) . '/languages' );

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
		return static::$database_version;
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