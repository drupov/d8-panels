# Drupal 8 Panels Suite Status-Quo

## Start lando

Run `lando start` in the root of the repository.

## Installation Instructions

Run `lando composer install` inside the root folder to get files needed for your Drupal installation.

In order to use the configuration through the config_installer profile add this to you settings.php

Run `lando drush si config_installer --db-url=mysql://drupal8:drupal8@database/drupal8 --account-pass=admin --yes` inside your `/web` folder.
