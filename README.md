# Drupal 8 Panels Suite Status-Quo

## Installation Instructions

Run `composer update` inside the root folder to get files needed for your Drupal installation.

In order to use the configuration through the config_installer profile add this to you settings.php

`$settings['install_profile'] = 'config_installer';`

Run `drush si config_installer --db-url=mysql://root:@localhost/d8_panels --account-pass=admin --yes` inside your `/web` folder (change the appropriate credentials and database names).