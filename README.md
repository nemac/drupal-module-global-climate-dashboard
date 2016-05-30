Global Climate Dashboard Drupal Module
======================================

This Drupal module does several things:

1. Defines a block called **Global Climate Dashboard** (machine name **global_climate_dashboard**) which displays the Global Climate
   Dashboard (GCD) application.  That block can be placed anywhere on the site, just like any other Drupal block.
   The GCD application has several parameters that determine where it reads its configuration file from, and where it loads its
   required image assets from; these parameters are set on the block's configuration screen.  They are initialized to reasonable
   default values but can be modified through that screen after the module is installed/enabled.
   
2. Defines an input filter called **multigraph** that allows markup like the following to be placed in page content on the site:
       [multigraph width="600" height="300" mugl="/gcd/multigraph-mugl/html5-graph-temperature"]
   The filter replaces this markup with an instance of Multigraph; this facilitates including interactive graphs in pages
   on the site.
   
3. Defines two content types which are used to store and manage the XML configuration files and data used by the GCD application,
   and by Multigraph:

      * **Dashboard/Multigraph MUGL** (machine name **gcd_mugl**)
      
        This content type stores XML documents that define the graphs, tabs, and overall structure of the contents of the dashboard.

      * **Dashboard/Multigraph Data** (machine name **gcd_data**)
      
        This content type store the numerical data, in CSV format, displayed in the dashboard graphs.

4. Defines the following REST services for serving out the above XML configuration documents

      * **gcd/dashboard-config/MUGL-ID**
      
        Responds with the XML contents of the gcd_mugl node having the gcd_mugl_id field value of MUGL-ID, with
        all internal references to other gcd_mugl nodes replaced with their contents.
        
      * **gcd/multigraph-mugl/MUGL-ID**
      
        Like the above, but assumes that the targeted gcd_mugl node represents a <graph> element, and
        responds with the contents of the <mugl> element contained within it; this is for use by the **multigraph**
        input filter mentioned above.
        
      * **gcd/dashboard-config-preview**
      
        The same as gcd/dashboard-config/MUGL-ID, but returns the latest (possibly unpublished) draft copy, rather
        than the published version.

      * **gcd/multigraph-mugl-preview/MUGL-ID**
      
        The same as gcd/multigraph-mugl/MUGL-ID, but returns the latest (possibly unpublished) draft copy, rather
        than the published version.
        
5. Defines a page, at the path **gcd/preview**, which displays an instance of the dashboard that uses the most recent
   draft version of all MUGLs and data sets, rather than the published versions.  This page is not visible to anonymous
   users.  It can be used to preview what the public version of the dashboard will look like once any draft MUGLs or data
   sets are published.


Installation
------------

1. Install and enable this module

2. Optional Step - only needed if this is the first time this module has been installed on your site,
   or if you have not previously populated your site with the gcd_mugl and gcd_data node instances
   needed by the dashboard:
   
     * install the node_export module
     * load the mugl & csv data by running the "dev/import_data" script
     * (optional) uninstall node_export
     
3. Place the global_climate_dashboard block on the page(s) where you want it to appear

4. Reconfigure the block's config paths to point to the mugl coming from the module's mugl service:

   * Set *Dashboard Config Path* to `/gcd/dashboard/html5-dashboard`
   * Set *Dashboard config path for flash fallback version* to `/gcd/dashboard/flash-dashboard`
  
5. Edit the **gcd_mugl** and **gcd_data** content types to enable workbench moderation for them


Rebuilding
----------

You do not need to rebuild the Global Climate Dashboard application in order
to use this module; the module comes with a
compiled and production-ready copy of the dashboard
app in the "html5-app" subdirectory.

You can, however, use the included [Ant](ant.apache.org) `build.xml`
file to rebuild the app from source (located in the
submodules/global-climate-dashboard directory) and replace the copy in
the html5-app directory with the newly built copy.

Before doing that, you'll need to run the command

```
git submodule update --init --recursive
```

to download all the project dependencies. Then run `ant build` to perform the rebuild.
