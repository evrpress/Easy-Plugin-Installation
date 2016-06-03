# Easy Plugin Installation #

## What is this? ##

This plugin allows users to directly install the download package from CodeCanyon (or any other similar marketplace) which contains the actual plugin. It's something to prevent the "missing stylesheet" problem but only for plugins.

**This only works for plugins!**

## Installation ##

Simple place the `easy-plugin-installation.php` file in the root of your zip file you upload to Envato. Make sure your plugin is also in the package and zipped.
Update the Plugin information in the `easy-plugin-installation.php` file.

## You can ##

* rename the file `easy-plugin-installation.php` to whatever you like (keep the `php` file extension)
* update the plugin information to whatever you like. This will show up in the plugin overview of WordPress

```
Plugin Name: My Super Awesome Plugin
Plugin URI: https://example.com
Description: Why don't you just upload the plugin like others do?
Version: Edge
Author: Justin Case
Author URI: https://example.com
```
will look lile

![Plugin Preview](https://i.imgur.com/ioULic0.png)

## Attention! ##

**The plugin will delete itself after plugin activation!**

This happens only if it has activated your plugin but if you do some testing you should disable
```
delete_plugins( array($plugin) );
```
around line 90.
