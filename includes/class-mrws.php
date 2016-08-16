<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/jojoee/mobile-redirect-with-slug
 * @since      1.0.0
 *
 * @package    MRWS
 * @subpackage MRWS/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MRWS
 * @subpackage MRWS/includes
 * @author     Nathachai Thongniran <inid3a@gmail.com>
 */
class MRWS {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      MRWS_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->plugin_name = 'mobile-redirect-with-slug';
    $this->version = ''; // unused

    $this->load_dependencies();
    // $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - MRWS_Loader. Orchestrates the hooks of the plugin.
   * - MRWS_i18n. Defines internationalization functionality.
   * - MRWS_Admin. Defines all hooks for the admin area.
   * - MRWS_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {
    // load
    // - common
    // - actions & filters
    // - i18n
    // - admin
    // - public
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mrws-common.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mrws-loader.php';
    // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mrws-i18n.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mrws-admin.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mrws-public.php';

    $this->loader = new MRWS_Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the MRWS_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {
    $plugin_i18n = new MRWS_i18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {
    $plugin_admin = new MRWS_Admin( $this->get_plugin_name(), $this->get_version() );

    // unused
    // $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    // $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {
    $plugin_public = new MRWS_Public( $this->get_plugin_name(), $this->get_version() );

    // unused
    // $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    // $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    MRWS_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }
}
