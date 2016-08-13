<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/jojoee/mobile-redirect-with-slug
 * @since      1.0.0
 *
 * @package    MRWS
 * @subpackage MRWS/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    MRWS
 * @subpackage MRWS/includes
 * @author     Nathachai Thongniran <inid3a@gmail.com>
 */
class MRWS_i18n {

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {
    load_plugin_textdomain(
      'mobile-redirect-with-slug',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );
  }
}
