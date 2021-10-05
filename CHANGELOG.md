# Changelog

All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Integrated our block plugin support package into the plugin base for easier use and maintenance.
- New `forge make:block` command to easily scaffold a new block.
- New `forge setup:block-build` command to easily scaffold the block build process.

### Fixed

- AssetManager was not hooking the static implementation but the abstract.

## [2.3.1] - 2021-09-05

### Fixed

- Issue where wakeup and clone methods would throw error on PHP8.

## [2.3.0] - 2021-07-26

### Added

- New `AssetManager` class to simplify loading of assets.

### Changed

- `BasePlugin` is now a standard class that can be called, not just abstract.

## [2.2.0] - 2021-07-24

### Added

- A new plugin slug variable, used to create automatic action hooks.
- New lifecycle action hooks.

### Changed

- Updated base plugin to support loading of blocks via the updated Block API.

### Fixed

- Rest endpoint abstract would force a type that WordPress doesn't, causing an error.

## [2.1.0] - 2021-07-17

### Added

- New bootable list to hook things on init faster.
- New integration with our Block Plugin Support trait that will autoload the blocks if the trait is present.

### Changed

- Make logger abstract and with configurable paths.
- Moved console dependency to dev.
- Moved to minimum PHP 7.4 with typehinted code.

### Fixed

- Customizer stub has correct namespace and class name.
- Loading of admin columns works properly.
- Relaxed Symfony console requirements so that it plays nice with php scoper.

## [2.0.1] - 2021-05-30

### Added

- Support for PHP 8.

## [2.0.0] - 2021-03-14

### Added

- New `forge` CLI for easy scaffolding.
- Scaffolding for separating field groups into own classes for better organization. Add them to a data store by adding the class to an array.
- Scaffolding for customizer sections. Just add them to an array in the base plugin class to load.
- Scaffolding for adding FacetWP facets. Just add them to an array in the base plugin class to load.

### Changed

- Renamed classes and folders to consistently use CamelCase.
- Simplified loading of data stores and rest endpoints, now just add them to the respective arrays in the base plugin class. No trait or boot method needed.

### Deprecated

- The `fields()` method on data stores should no longer be used, instead replaced by loading field group classes via the `$field_groups` property.

### Removed

- `Has_Data_Stores` trait, which is no longer needed.
- `Has_Rest_Endpoints` trait, which is no longer needed.
- `Queries_Interface` interface, which was never used.

## [1.0.4] - 2020-09-26

## Added

- Helper methods for getting ACF data for boolean, term ID and dates.

## Changed

- We now honor our dependency checker method in the constructor call.

## Removed

- In `Data_Store_WP` an action was hooked to `bm_events_init`. This has been removed.

## Fixed

- Spelling mistake where property `$database_version` was misspelt.
- Textdomains were not loading properly, this is now fixed.
- The datastore exception class was not named properly, causing it to fail.

## [1.0.3] - 2020-09-16

### Fixed

- Adding routes with REST was forced to return an array, should not need to return anything.
- REST endpoints calls a load function, but the load function wasn't present.

## [1.0.2] - 2020-07-06

### Fixed

- An issue where abstract classes where not resolving the static values.

## [1.0.1] - 2020-07-05

### Fixed

- An issue where the base plugin classes loaded from "self" and not "static".

## [1.0.0] - 2020-07-05

First Version
