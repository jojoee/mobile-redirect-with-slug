<?php

define( 'MRWS_BASE_FILE', plugin_basename( __FILE__ ) );

/**
 * mobile-redirect-with-slug
 *
 * @link              https://github.com/jojoee/mobile-redirect-with-slug
 * @since             1.0.0
 * @package           MRWS
 *
 * @wordpress-plugin
 * Plugin Name:       Mobile Redirect With Slug
 * Plugin URI:        https://github.com/jojoee/mobile-redirect-with-slug
 * Description:       Redriect to mobile site with slug
 * Version:           1.0.1
 * Author:            Nathachai Thongniran
 * Author URI:        http://jojoee.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mrws
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mrws-activator.php
 */
function activate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-mrws-activator.php';
  MRWS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mrws-deactivator.php
 */
function deactivate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-mrws-deactivator.php';
  MRWS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mrws.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mobile_redirect_with_slug() {
  $plugin = new MRWS();
  $plugin->run();
}

run_mobile_redirect_with_slug();
