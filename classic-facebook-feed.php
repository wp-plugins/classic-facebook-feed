<?php
/*
Plugin Name: Classic Facebook Feed
Plugin URI: http://www.anshullabs.com
Description: A WordPress Shortcode Plugin for show Latest facebook Feeds from Facebook Fan Page.
Version: 2.5
Author: Anshul Gangrade
Author URI: http://www.anshullabs.com
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/*
Copyright 2012  Anshul Labs  (email : me@anshullabs.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('CFF_VERSION', '2.5');
define('CFF_FILE', basename(__FILE__));
define('CFF_NAME', str_replace('.php', '', CFF_FILE));
define('CFF_PATH', plugin_dir_path(__FILE__));
define('CFF_URL', plugin_dir_url(__FILE__));
define('CFF_HOMEPAGE', 'https://wordpress.org/plugins/classic-facebook-feed');


if(!class_exists('classic_facebook_feed'))
{
	class classic_facebook_feed
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$classic_facebook_feed_settings = new classic_facebook_feed_settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=classic_facebook_feed">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
	} // END class classic_facebook_feed
} // END if(!class_exists('classic_facebook_feed'))

if(class_exists('classic_facebook_feed'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('classic_facebook_feed', 'activate'));
	register_deactivation_hook(__FILE__, array('classic_facebook_feed', 'deactivate'));

	// instantiate the plugin class
	$classic_facebook_feed = new classic_facebook_feed();
}
