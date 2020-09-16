# Changelog

All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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