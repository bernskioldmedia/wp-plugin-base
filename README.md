# WP Plugin Base

<a href="https://packagist.org/packages/bernskioldmedia/wp-plugin-base"><img src="https://img.shields.io/packagist/dt/bernskioldmedia/wp-plugin-base" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/bernskioldmedia/wp-plugin-base"><img src="https://img.shields.io/packagist/v/bernskioldmedia/wp-plugin-base" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/bernskioldmedia/wp-plugin-base"><img src="https://img.shields.io/packagist/l/bernskioldmedia/wp-plugin-base" alt="License"></a>

The WP Plugin Base is a feature-rich composer project to be included in WordPress plugins. It provides a good base scaffolding, as well as many common base features to speed up
development.

**Note:** We recommend scoping your end plugin using [PHP Scoper](https://github.com/humbug/php-scoper) to prevent version conflicts across plugins.

## Installation

The plugin base is used by the Bernskiold Media [WP Plugin Scaffold](https://github.com/bernskioldmedia/wp-plugin-scaffold). We generally use this to start off new plugin
development.

To use the Plugin Base, load it via composer in your plugin:

`composer require bernskioldmedia/wp-plugin-base`

## Overview

In this plugin base you'll find classes to help you create:

- Base Plugin (Main Plugin Class)
- Installer
- Data Stores (Custom Post Types & Taxonomies)
- Data (Getters/Setters for a data store)
- FacetWP Facet
- ACF Field Group
- Custom REST Endpoint
- Customizer Settings
- Admin Columns Pro Sets
- Asset Loading
- Custom Blocks
- Jobs Class

## Forge CLI

Included in the plugin base is our `forge` CLI that helps you scaffold new classes that extend the base classes provided by the plugin base. The CLI is automatically installed to
your vendor's bin folder for easy access.

For even simpler access, you may define a composer script in your project, such as our scaffold has:

```
"scripts": {
    "forge": "./vendor/bin/forge",
}
```

You can then access the CLI by running: `composer forge COMMAND`.

The following commands are available:

### `forge make:customizer {name}` - Customizer

Generate a class that can be used to add a customizer section and settings.

Options:

- `--namespace="MyPlugin\Namespace"` The root plugin namespace.
- `--prefix="my-plugin"` An optional prefix for your settings.

### `forge make:data {name}` - Data Class

Generate a data class where getters and setters are used to interact with a data store.

Options:

- `--namespace="MyPlugin\Namespace"` The root plugin namespace.
- `--type="taxonomy"` Create a taxonomy data class instead of the default custom post type.

`forge make:cpt {name}` - **Custom Post Type Data Store Class**
Generate a custom post type data store.

Options:
`--namespace="MyPlugin\Namespace"` The root plugin namespace.
`--textdomain="my-plugin"` The plugin textdomain.

### `forge make:taxonomy {name}` - Taxonomy Data Store Class

Generate a taxonomy data store.

Options:

- `--namespace="MyPlugin\Namespace"` The root plugin namespace.

- `--textdomain="my-plugin"` The plugin textdomain.

### `forge make:facet {name}` - Facet WP Facet Class

Generate a class to register a FacetWP Facet based on a FacetWP facet export.

Options:

- `--namespace="MyPlugin\Namespace"` The root plugin namespace.

### `forge make:fieldgroup {name}` - ACF Field Group Class

Generate a class to register an ACF field group based on the ACF field group export.

Options:

- `--namespace="MyPlugin\Namespace"` The root plugin namespace.

### `forge make:rest {name}` - REST Endpoint Class

Generate a class to create a custom REST API endpoint.

Options:

- `--namespace="MyPlugin\Namespace"` The root plugin namespace.

### `forge make:block {prefix} {name}` - Creating a custom block

Generate all the files needed for a custom block.

Options:

- `--dynamic` or `-d` Creates a dynamic block which renders using PHP.
- `--namespace="MyPlugin\Namespace"` The root plugin namespace. Only needed if dynamic is set.

Example:

```bash
./bin/forge make:block bm my-block
```

### `forge setup:block-build` - Sets up build assets for blocks

Generates the webpack config file as well as requires all the various NPM dependencies for building blocks.

## Booting Classes

We typically need to boot (run) a series of classes and hooks/init functions when the plugin loads. This to boot further features.

Normally we do this by running the function (usually containing action calls) within the `init_hooks()` function.

To make this simpler, you can add your class to the `$boot = []` array on the base plugin class. It will then be run on the init hooks function automatically. This way you don't
have to extend the init hooks method for simple bootable operations.

**Note:** A function that you load via the boot property must implement the `Hookable` interface.

```
protected static $boot = [
  Assets::class,
];
```

## Loading Assets

The abstract class `AssetManager` can be used to load styles and script both public or admin intelligently.

It gives you two options. Either defining all values yourself, or if you are using `@wordpress/scripts` (which we almost always are), rely on the `name.asset.php` file that it
generates for dependencies and version.

The process is the same for public/admin scripts and styles.

When extending the class, you can define four arrays and methods. The array properties auto-registers the scripts, while the four methods are responsible for enqueuing.

Because you likely want to enqueue the scripts conditionally, the helper library doesn't do this automatically.

### Registering scripts

Registering scripts by using the asset meta file, requires just the simple config:

```
protected static array $public_scripts = [
  'my-script' => 'assets/scripts/dist'
];
```

This configuration assumes the following structure:

1. Script is called `my-script.js`.
2. Script is placed in the `assets/scripts/dist` folder.
3. There is a `my-script.asset.php` file next to the script file in the same folder.

Alternatively, you can define the full settings (with their defaults):

```
protected static array $public_scripts = [
  'my-script' => [
    'subfolder' => 'assets/scripts/dist', // Required.
    'dependencies' => [], // Defaults to empty array if not set.
    'version' => '1.0', // Defaults to plugin version if not set.
    'in_footer' => true, // Defaults to true if not set.
  ],
];
```

Scripts are registered with the array key name as handle.

### Registering styles

Registering styles by using the asset meta file, requires just the simple config:

```
protected static array $public_styles = [
  'my-style' => 'assets/styles/dist'
];
```

This configuration assumes the following structure:

1. Stylesheet is called `my-style.css`.
2. Stylesheet is placed in the `assets/styles/dist` folder.
3. There is a `my-style.asset.php` file next to the stylesheet file in the same folder.

Alternatively, you can define the full settings (with their defaults):

```
protected static array $public_styles = [
  'my-style' => [
    'subfolder' => 'assets/styles/dist', // Required.
    'dependencies' => [], // Defaults to empty array if not set.
    'version' => '1.0', // Defaults to plugin version if not set.
    'media' => 'screen', // Defaults to 'all' if not set.
  ],
];
```

Styles are registered with the array key name as handle.

### Enqueue scripts and styles

We don't automatically enqueue scripts or styles. Instead we have four magic methods that hook in correctly when defined. This allows you to conditionally enqueue scripts and
styles easily as needed.

When they exist in the file, they are hooked appropriately:

```
public static function enqueue_public_scripts(): void
public static function enqueue_admin_scripts(): void
public static function enqueue_public_styles(): void
public static function enqueue_admin_styles(): void
```

They are run at priority 100 by default. To override, you can set the `protected static int $enqueue_priority`.

### Customizing Register Priority

By default we run the registration at priority 10. To customize, set the `protected static int $register_priority` to a custom value.

## Adding Admin Columns Support

To easily share Admin Columns Pro sets between environments, it's often a good idea to commit them. To export and save them easily, the plugin base hooks into Admin Columns for
custom data stores.

First, include the `HasAdminColumns` trait on the main plugin class.

```
import BernskioldMedia\WP\PluginBase\BasePlugin;
import BernskioldMedia\WP\PluginBase\Admin\HasAdminColumns;

class Plugin extends BasePlugin {

    use HasAdminColumns;

    // ...
}
```

Then, on the custom data stores that you want to store admin column sets for, set the `$store_admin_columns` property to true:

```
/**
 * When set to true the admin columns in Admin Columns Pro
 * will be stored in a directory in this plugin as opposed to in the database.
 *
 * @var bool
 */
protected static $store_admin_columns = false;
```

## Data Stores

Adding a new data store is best done via the Forge CLI `forge make:cpt MyDataStore`.

To register the data store, add it to the `$data_stores` property in the main plugin class:

```
/**
 * The data stores (class names) that will be loaded
 * alongside this plugin.
 *
 * @var string[]
 */
protected static $data_stores = [
    MyDataStore::class,
];
```

**Note:** When creating a data store, you should also create a matching Data class with getters and setters.

Special data store features:

- Extend the `$metadata` array on the data store with names of the data class get/set method to automatically add the data to the REST API endpoint for the data store.
- Automated permissions handling on the data store level. You may adjust default permissions by adjusting the permissions array.
- Simple hook for adjusting data store queries on `pre_get_posts`. Just add the `protected static query_modifications( \WP_Query $query ): \WP_Query;` method to the data store
  class. It will be automatically loaded.
- Create Field Groups and add their class names to the `$field_groups` array to automatically load them.
- Disable the block editor by setting the class property: `protected static $block_editor = false;`
- CRUD methods for the data store.

## Data Class

By creating a data class, we have a simple method for interacting with data from the data store. It's best created with the Forge CLI `forge make:data {name}`. It's name should
match the data store.

It's used as: `$data = new DataClass( $id );`. Within the class `$this->get_id()` will always get you the current object ID.

**Note:** When creating a data class for a taxonomy, the `TaxonomyData` class will ensure ACF taxonomy compatibility automatically.

Add your getters and setters to the data class. It has built-in support for ACF fields especially through the following methods:

- `get_prop( $field_key )`
- `set_prop( $field_key, $value )`
- `get_date_prop( $field_key, $format )`
- `get_bool_prop( $field_key ): bool`
- `get_term_id_prop( $field_key ): ?int`
- `get_taxonomy_prop( $field_key, bool $multiple )`
- `get_taxonomy_string( $field_key, $separator, $key ): string`

It also has some easy querying features that return the data class.

- `::find( $name )` looks up an object based on the name.
- `::find_or_create( $name )` looks up an object based on the name, and creates it if it doesn't exist.

You may convert all the data for an object into an array with the `to_array()` method.

## Custom Blocks

We find ourselves increasingly creating a bunch of plugins that add one or more blocks to the block editor. This requires a few functions to load and register.

All you need to do is include the `Has_Blocks` trait in the base plugin class.

Blocks are assumed to be placed in a `blocks` subfolder, and they are being built to `dist/blocks`.

```
use BernskioldMedia\WP\PluginBase\BasePlugin;
use BernskioldMedia\WP\PluginBase\Blocks\Has_Blocks;

class My_Plugin extends BasePlugin {
    use Has_Blocks;

}
```

### Required Folder Structure for Blocks

The trait assumes the following folder structure:

`blocks/` is the location of all blocks with one folder per block. The `index.js` file in each block folder is the entrypoint for the build script.

`dist/blocks` is the location of built JavaScript blocks with the same filename as the main folder name.

`languages/` is the location of the translation files. The handle and domain are both set to `{$block_prefix}-{$block_name}`.

### Defining a custom block prefix

The trait will default to using the 'bm' prefix for blocks. Blocks are named with a prefix to scope them to the project they are a part of. This also influences how the script and
style handles are registered ($prefix-block-$block_name).

Your project probably requires its custom prefix. You can set it on the main plugin class like so:

```
use BernskioldMedia\WP\PluginBase\BasePlugin;
use BernskioldMedia\WP\PluginBase\Blocks\Has_Blocks;

class My_Plugin extends BasePlugin {

    use Has_Blocks;
    
    // Set my custom block prefix.
    protected static string $block_prefix = 'my-prefix';

}
```

### Defining a custom job

When you have a long-running process that you need to run in the background, a job is what you want to run.

```php
use BernskioldMedia\WP\PluginBase\Jobs\Job;

class My_Job extends Job {

    protected $action = 'clone_media_job';

	/**
	 * Process the Queue
	 *
	 * @param  array  $data
	 *
	 * @return bool
	 */
	protected function task( $data ) {
	    // Do things here...

		return false;
	}

	protected function complete() {
		parent::complete();
        // This code will run once the entire job is complete.
	}

}
```

You can then dispatch your job like this:

```php
$job = new MyJob();

foreach( $items as $item ) {
    $item_data = [];
    $job->push_to_queue( $item_data );
}

$job->save()->dispatch();
```

### Adding a bulk action

The framework contains an abstract class that you can extend to add a bulk action to one of the post type table views.

```php
use BernskioldMedia\WP\PluginBase\Admin\Bulk_Action;

class My_Bulk_Action extends Bulk_Action {

	protected static $scope = 'edit-post';
	protected static $slug  = 'my_bulk_action';

	public static function process( int $object_id ): void {
        // Do something with each post here.
	}

	protected static function get_name(): string {
		return __( 'My Bulk Action', 'TEXTDOMAIN' );
	}
}
```

_Don't forget to load the bulk action by adding it to the `$boot` array in the main plugin file._

### Adding a multisite tab

Extend this abstract class to add a new tab to the network admin website edit screen.

```php
use BernskioldMedia\WP\PluginBase\Admin\Multisite_Tab;

class My_Tab extends Multisite_Tab {

	protected static string $nonce = 'my-tab-nonce';
	protected static string $slug = 'my-tab';
	protected static string $capability = 'manage_sites';

	protected static function get_title(): string {
		return __( 'My Tab', 'TEXTDOMAIN' );
	}

	public static function notice(): void {

		if ( ! isset( $_GET['updated'], $_GET['page'] ) || self::$slug !== $_GET['page'] ) {
			return;
		}

		?>
		<div class="notice is-dismissible updated">
			<p><?php esc_html_e( 'Success message.', 'TEXTDOMAIN' ); ?></p>
		</div>
		<?php

	}

	public static function save( WP_Site $site, $request_data ): void {
        // Handle saving.
	}

	public static function render(): void {
		$site = self::get_site_from_request();

		if ( ! $site ) {
			return;
		}

		?>
		<div class="wrap">
		    <p>Tab Content</p>
		</div>
		<?php

	}

}
```

_Don't forget to load the tab by adding it to the `$boot` array in the main plugin file._
