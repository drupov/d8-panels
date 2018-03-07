# Drupal 8 Panels Suite Status-Quo

## Start lando

Run `lando start` in the root of the repository.

## Installation Instructions

Run `lando composer update` inside the root folder to get files needed for your Drupal installation.

In order to use the configuration through the config_installer profile add this to you settings.php

`$settings['install_profile'] = 'config_installer';`

Run `lando drush si config_installer --db-url=mysql://root:@localhost/d8_panels --account-pass=admin --yes` inside your `/web` folder (change the appropriate credentials and database names).
