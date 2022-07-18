# Changelog

## v2.3.0

+ Add license and update readme

## v2.2.1

+ Add GitHub workflow actions CI

## v2.2.0

+ Add RepositoryManagerService to enable easier access to autoload repositories
  + Model name
  + Model FQDN
  + Repository name
  + Repository FQDN
+ Add UsesRepositoryManager trait to allow quick access to RM

## v2.1.0

+ PHP Supported Versions ^8.0|^8.1
+ Add support for Laravel 9

## v2.0.0

+ PHP supported versions are now ^7.4|^8.0|^8.1
+ Remove support for Laravel v5.x

## v1.2.0 (23/10/2020)

## Changed

- Changed createMany method parameters from Collection to Array

## v1.1.0 (22/10/2020)

## Changed

- Added new default repository method Find Many `findMany(array $ids)`
- Altered HasRepository method to contain a default location for repositories

## v1.0.1 (06/10/2020)

## Changed

- Updated illuminate package support to include versions 7 and 8

## v1.0.0 (06/10/2020)

- Initial release