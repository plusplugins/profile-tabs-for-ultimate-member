<?php
/**
 * Plugin Name: Ultimate Member - Profile Tabs
 * Plugin URI: https://www.plusplugins.com
 * Description: Add custom profile tabs to your Ultimate Member site with content area and privacy settings.
 * Author: PlusPlugins
 * Version: 2.1.1
 * Author URI: https://www.plusplugins.com
 * Text Domain: profile-tabs-for-ultimate-member
 * Domain Path: /languages
 */

define( 'PP_TABS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PP_TABS_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'PP_TABS_REQUIRES', '2.0.5' );

require_once PP_TABS_PLUGIN_DIR . 'init.php';
