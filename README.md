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

## Style plugin

Edit the configuration of the pane in `/admin/structure/page_manager/manage/demo_style_plugin/general` to see the style
option appearing in the pane configuration or see the page at `/demo-style-plugin`.
