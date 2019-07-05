# Drupal 8 Panels Suite Status-Quo

## Start lando

Copy lando example file: `cp .lando.yml.example .lando.yml`

Run `lando start` in the root of the repository.

## Installation Instructions

Run `lando composer install` to get files needed for your Drupal installation.

Run `lando drush si config_installer --db-url=mysql://drupal8:drupal8@database/drupal8 --account-pass=admin --yes`.

## Development environment

As this is a development environment, follow the steps here to enable development mode, see "Enable local development
settings" in https://www.drupal.org/node/2598914
