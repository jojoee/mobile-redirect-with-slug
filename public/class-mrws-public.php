<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/jojoee/mobile-redirect-with-slug
 * @since      1.0.0
 *
 * @package    MRWS
 * @subpackage MRWS/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MRWS
 * @subpackage MRWS/public
 * @author     Nathachai Thongniran <inid3a@gmail.com>
 */
class MRWS_Public extends MRWS_Common {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param    string    $plugin_name  The name of the plugin.
   * @param    string    $version      The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    parent::__construct();

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action( 'template_redirect', array( $this, 'mrws_template_redirect' ) );
  }

  /*================================================================ Enqueue
  */
 
  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in MRWS_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The MRWS_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    wp_enqueue_style(
      $this->plugin_name,
      plugin_dir_url( __FILE__ ) . 'css/mrws-public.css',
      array(),
      $this->version,
      'all'
    );
  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in MRWS_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The MRWS_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    wp_enqueue_script(
      $this->plugin_name,
      plugin_dir_url( __FILE__ ) . 'js/mrws-public.js',
      array( 'jquery' ),
      $this->version,
      false
    );
  }
 
  /*================================================================ Public
  */

  public function mrws_template_redirect() {
    // move all variables to the top
    // cause debugging purpose
    
    $is_enabled         = $this->options['mrws_field_is_enabled'] == 1;
    $is_including_slug  = $this->options['mrws_field_is_including_slug'] == 1;
    $redirect_url       = $this->options['mrws_field_redirect_url'];
    $redirect_mode      = $this->options['mrws_field_redirect_mode'];

    $current_url  = esc_url( $this->get_current_url() );
    $redirect_url = esc_url( $redirect_url );
    
    // update slug
    $redirect_url = ( $is_including_slug ) ?
      rtrim( $redirect_url, '/' ) . $this->get_current_request_url() :
      $redirect_url;

    if ( $this->is_debug ) {
      $tmp = [
        'is_enabled'        => $is_enabled,
        'is_including_slug' => $is_including_slug,
        'current_url'       => $current_url,
        'redirect_url'      => $redirect_url,
        'redirect_mode'     => $redirect_mode
      ];
      $this->da( $tmp );
    }

    // check - not mobile (or tablet)
    if ( ! wp_is_mobile() ) return;

    // check - not enabled
    if ( ! $is_enabled ) return;

    // check - empty $redirect_url
    if ( $this->is_null_or_empty_string( $redirect_url ) ) return;

    // check - not valid url
    if ( ! $this->is_valid_url( $redirect_url ) ) return;

    // check - redirect itself
    if ( $redirect_url == $current_url ) return;

    wp_redirect( $redirect_url, $redirect_mode );
    exit;
  }
}
