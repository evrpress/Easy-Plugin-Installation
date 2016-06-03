<?php
/*

Easy Plugin Installation.

This plugin allows users to directly install the download package from CodeCanyon (or any other similar marketplace) which contains the actual plugin. It's something to prevent the "missing stylesheet" problem but only form plugins.

Installation:
Simple place this file in the root of your zip file you upload to Envato. Make sure your plugin is also in the package and zipped.
Update the Plugin information down below with your plugins info.

Readme: https://github.com/revaxarts/Easy-Plugin-Installation

*/



/*

!! UPDATE THIS INFO WITH THE DETAILS OF YOUR PLUGIN !!

Plugin Name: Easy Plugin Installation
Plugin URI: https://github.com/revaxarts/Easy-Plugin-Installation
Description: This plugin allows users to directly install the download package from CodeCanyon (or any other similar marketplace)
Author: revaxarts.com
Author URI: https://revaxarts.com
*/


class easy_plugin_installation {

	public function __construct(){

		//make it nice and inline
		add_action('admin_notices', array( $this, 'plugin_activation' ) );
		add_filter('upgrader_package_options', array( $this, 'upgrader_package_options' ) );

	}

	public function upgrader_package_options($options){

		$options['clear_destination'] = true;
		$options['abort_if_destination_exists'] = false;
		return $options;
	}

	public function plugin_activation(){

		//the slug of this plugin
		$plugin = basename(dirname(__FILE__)).'/'.basename(__FILE__);

		//include some function
		if(!function_exists('list_files'))
			include( ABSPATH . 'wp-admin/includes/file.php' );

		if(!function_exists('show_message'))
			include( ABSPATH . 'wp-admin/includes/misc.php' );

		if(!function_exists('get_plugin_data'))
			include( ABSPATH . 'wp-admin/includes/plugin.php' );

		if(!class_exists('Plugin_Upgrader'))
			include( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		//create a new upgrader
		$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin( ));

		//get all zips in the plugins directory
		$files = list_files(dirname( __FILE__ ));
		$files = preg_grep('#\.zip$#', $files);

		foreach($files as $file){

			//try to install the plugin
			if($upgrader->install( $file )){

				//get all php files of the installed plugin
				$source_files = preg_grep('#\.php$#', $upgrader->result['source_files']);

				foreach($source_files as $source_file){

					//try to get the plugin data of the file
					$plugin_data = get_plugin_data($upgrader->result['destination'].$source_file);

					//this is the plugin file
					if(!empty($plugin_data['Name'])){

						//the slug of the new plugin
						$plugin_slug = basename($upgrader->result['destination']).'/'.$source_file;

						//activate it
						if(!is_wp_error(activate_plugin( $plugin_slug ))){

							//deactivate and remove this plugin
							deactivate_plugins( __FILE__ );

							//comment this line out if you do some testings
							delete_plugins( array($plugin) );

							//echo some javascript to reload the page
							echo '<script>try{location.reload();}catch(e){}</script>';
							exit;

						}

					}

				}

			}

		}

		show_message('<div class="error"><p>No valid plugin has been found!</p></div>' );

		deactivate_plugins( __FILE__ );
	}


}

new easy_plugin_installation();

