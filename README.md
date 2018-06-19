# Drupal 8 Panels Suite Status-Quo

## Start lando

Run `lando start` in the root of the repository.

## Installation Instructions

Run `lando composer install` inside the root folder to get files needed for your Drupal installation.

Run `lando drush si config_installer --db-url=mysql://drupal8:drupal8@database/drupal8 --account-pass=admin --yes`
inside your `/web` folder.

## Style plugin

Edit the configuration of the pane in
`/admin/structure/page_manager/manage/demo_style_plugin/general` to see the style option appearing in the pane
configuration or see the page at `/demo-style-plugin`.
