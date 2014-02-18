Global Climate Dashboard Drupal Module
======================================

This module defines a block called "Global Climate Dashboard"
(machine name global_climate_dashboard) which embeds the dashboard
application in a Drupal site.

Installation
------------

* install and enable the module
* install the node_export module
* load the mugl & csv data by running the "dev/import_data" script
* place the block, and the preview block, on the pages in the site where you want them
* re-configure the block's config paths to point to the mugl coming from the module's mugl service:
  * gcd/dashboard/html5-dashboard
  * gcd/dashboard/flash-dashboard
* edit the gcd_mugl and gcd_data content types to enable workbench moderation for them
