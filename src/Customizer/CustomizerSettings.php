<?php

namespace BernskioldMedia\WP\PluginBase\Customizer;

use \WP_Customize_Control;
use \WP_Customize_Manager;

/**
 * Class Customizer Settings
 */
abstract class CustomizerSettings {

	/**
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * The prefix that we prefix all settings and sections with.
	 *
	 * @var string
	 */
	protected static $settings_prefix = '';

	/**
	 * Default Settings Arguments
	 *
	 * @var array
	 */
	protected static $default_setting_args = [
		'default'   => '',
		'type'      => 'theme_mod',
		'transport' => 'refresh',
	];

	/**
	 * Default Control Settings
	 *
	 * @var array
	 */
	protected static $default_control_args = [
		'label'    => '',
		'settings' => '',
		'context'  => '',
	];

	/**
	 * Customizer constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'register' ] );
	}

	/**
	 * Extend this method where you'll add the
	 * sections, settings and controls.
	 */
	abstract protected function setup(): void;

	/**
	 * Register the customizer extension.
	 *
	 * @param  WP_Customize_Manager  $wp_customize  WP Customize Instance.
	 */
	public function register( WP_Customize_Manager $wp_customize ): void {
		$this->wp_customize = $wp_customize;
		$this->setup();
	}

	protected function add_setting( string $id, array $args = [] ): void {
		$this->wp_customize->add_setting( static::$settings_prefix . '_' . $id, $args );
	}

	protected function add_section( string $id, array $args = [] ): void {
		$this->wp_customize->add_section( static::$settings_prefix . '_' . $id, $args );
	}

	protected function add_control( WP_Customize_Control $control ): void {
		$this->wp_customize->add_control( $control );
	}

	/**
	 * Adds the customizer setting and control.
	 *
	 * @param  string       $index            Array key index to use for the customizer setting.
	 * @param  string       $control_type     Which control to create.
	 * @param  array        $control_args     Customizer control object arguments.
	 *                                        Only those different from the default need to be passed.
	 * @param  string|null  $id               Optional. Customizer control object ID.
	 *                                        Will default to 'pliant-' . $index.
	 * @param  array        $custom_settings  Optional. Customizer setting arguments.
	 *                                        Only those different from the default need to be passed.
	 */
	protected function add_setting_and_control( string $index, string $control_type, array $control_args, ?string $id = null, array $custom_settings = [] ): void {

		$setting                  = sprintf( static::$settings_prefix . '_%s', $index );
		$control_args             = array_merge( static::$default_control_args, $control_args );
		$control_args['settings'] = $setting;

		if ( WP_Customize_Control::class === $control_type ) {
			$settings_args = array_merge( [
				'type' => 'text',
			], static::$default_setting_args );
		} else {
			$settings_args = static::$default_setting_args;
		}

		if ( ! empty( $custom_settings ) ) {
			$settings_args = array_merge( $settings_args, $custom_settings );
		}

		if ( ! isset( $id ) ) {
			$id = static::$settings_prefix . $index;
		}

		$this->add_setting( $index, $settings_args );

		$control = new $control_type( $this->wp_customize, $id, $control_args );

		$this->add_control( $control );
	}

}
