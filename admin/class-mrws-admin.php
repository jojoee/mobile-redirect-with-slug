<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/jojoee/mobile-redirect-with-slug
 * @since      1.0.0
 *
 * @package    MRWS
 * @subpackage MRWS/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MRWS
 * @subpackage MRWS/admin
 * @author     Nathachai Thongniran <inid3a@gmail.com>
 */
class MRWS_Admin extends MRWS_Common {

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
   * @param    string    $plugin_name    The name of this plugin.
   * @param    string    $version        The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    parent::__construct();

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action( 'admin_menu', array( $this, 'mrws_add_menu' ) );
    add_action( 'admin_init', array( $this, 'mrws_page_init' ) );

    // add plugin link
    add_filter( 'plugin_action_links', array( $this, 'mrws_plugin_action_links' ), 10, 4 );
  }

  /*================================================================ Enqueue
  */

  /**
   * Register the stylesheets for the admin area.
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
      plugin_dir_url( __FILE__ ) . 'css/mrws-admin.css',
      array(),
      $this->version,
      'all'
    );
  }

  /**
   * Register the JavaScript for the admin area.
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
      plugin_dir_url( __FILE__ ) . 'js/mrws-admin.js',
      array( 'jquery' ),
      $this->version,
      false
    );
  }

  /*================================================================ Public
  */

  /**
   * Add options page
   *
   * @see https://codex.wordpress.org/Function_Reference/add_options_page
   */
  public function mrws_add_menu() {
    // args
    // - page title
    // - menu title
    // - capability
    // - menu slug (menu page)
    // - function
    add_options_page(
      'Mobile Redirect With Slug',
      'Mobile Redirect',
      'manage_options',
      $this->menu_page,
      array( $this, 'mrws_admin_page' )
    );
  }

  /**
   * Options page callback
   * 
   * TODO: move style to /admin/css/mrws-admin.css
   */
  public function mrws_admin_page() {

    // debug
    if ( $this->is_debug ) {
      $this->dhead( 'Before setting default value', get_option( $this->option_field_name ) );
      $this->dhead( 'After setting default value', $this->options );
    }
    
    ?>
    <div class="wrap">
      <h1>Mobile Redirect With Slug</h1>

      <form method="post" action="options.php">
        <?php
          settings_fields( $this->option_group_name );
          do_settings_sections( $this->menu_page );
          submit_button();
        ?>
      </form>
    </div>

    <style>
    .debug-box {
      padding: 12px 0;
    }
    .form-table th,
    .form-table td {
      padding: 0;
      line-height: 30px;
      height: 30px;
    }
    </style>
    <?php
  }

  /**
   * Register and add settings
   */
  public function mrws_page_init() {
    $section_id = 'mrws_setting_section_id';

    register_setting(
      $this->option_group_name,
      $this->option_field_name,
      array( $this, 'sanitize' )
    );

    // section
    add_settings_section(
      $section_id,
      'Settings',
      array( $this, 'print_section_info' ),
      $this->menu_page
    );  

    // 4 option fields
    // - is_enabled
    // - is_including_slug
    // - redirect_url
    // - redirect_mode
    add_settings_field(
      'mrws_field_is_enabled',
      'Enable Redirect',
      array( $this, 'mrws_field_is_enabled_callback' ),
      $this->menu_page,
      $section_id
    );

    add_settings_field(
      'mrws_field_is_including_slug',
      'Including Slug',
      array( $this, 'mrws_field_is_including_slug_callback' ),
      $this->menu_page,
      $section_id
    );

    add_settings_field(
      'mrws_field_redirect_url',
      'Redirect URL',
      array( $this, 'mrws_field_redirect_url_callback' ),
      $this->menu_page,
      $section_id
    );

    add_settings_field(
      'mrws_field_redirect_mode',
      'Redirect Mode',
      array( $this, 'mrws_field_redirect_mode_callback' ),
      $this->menu_page,
      $section_id
    );
  }

  public function mrws_plugin_action_links( $links, $plugin_file ) {
    $plugin_link = [];

    if ( $plugin_file == MRWS_BASE_FILE ) {
      $plugin_link[] = '<a href="' . admin_url( 'options-general.php?page=mobile-redirect-with-slug' ) . '">Settings</a>';
    }

    return array_merge( $links, $plugin_link );
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitize( $input ) {
    $result = array();

    // text
    $text_input_ids = [
      'mrws_field_redirect_url'
    ];
    foreach ( $text_input_ids as $text_input_id ) {
      $result[ $text_input_id ] = isset( $input[ $text_input_id ] ) ?
        sanitize_text_field( $input[ $text_input_id ] ) :
        '';
    }

    // number
    $number_input_ids = [
      'mrws_field_is_enabled',
      'mrws_field_is_including_slug',
      'mrws_field_redirect_mode'
    ];
    foreach ( $number_input_ids as $number_input_id ) {
      $result[ $number_input_id ] = isset( $input[ $number_input_id ] ) ?
        sanitize_text_field( $input[ $number_input_id ] ) :
        0;
    }

    return $result;
  }

  /** 
   * Print the Section text
   */
  public function print_section_info() {
    print 'Enter your settings below:';
  }

  /*================================================================ Public - Form callback
  */
 
  public function mrws_field_is_enabled_callback() {
    $field_id = 'mrws_field_is_enabled';
    $field_name = $this->option_field_name . "[$field_id]";
    $field_value = 1;
    $check_attr = checked( 1, $this->options[ $field_id ], false );

    printf(
      '<input type="checkbox" id="%s" name="%s" value="%s" %s />',
      $field_id,
      $field_name,
      $field_value,
      $check_attr
    );
  }

  public function mrws_field_is_including_slug_callback() {
    $field_id = 'mrws_field_is_including_slug';
    $field_name = $this->option_field_name . "[$field_id]";
    $field_value = 1;
    $check_attr = checked( 1, $this->options[ $field_id ], false );

    printf(
      '<input type="checkbox" id="%s" name="%s" value="%s" %s />',
      $field_id,
      $field_name,
      $field_value,
      $check_attr
    );
  }

  public function mrws_field_redirect_url_callback() {
    $field_id = 'mrws_field_redirect_url';
    $field_name = $this->option_field_name . "[$field_id]";
    $field_value = isset( $this->options[ $field_id ] ) ? esc_attr( $this->options[ $field_id ] ) : '';

    printf(
      '<input type="text" id="%s" name="%s" value="%s" />',
      $field_id,
      $field_name,
      $field_value
    );
  }

  public function mrws_field_redirect_mode_callback() {
    $field_id = 'mrws_field_redirect_mode';
    $field_name = $this->option_field_name . "[$field_id]";
    
    $html = sprintf( '<select id="%s" name="%s">', $field_id, $field_name );
    $html .= sprintf( '<option value="301" %s>301</option>', selected( $this->options[ $field_id ], '301', false ) );
    $html .= sprintf( '<option value="302" %s>302</option>', selected( $this->options[ $field_id ], '302', false ) );
    $html .= '</select>';

    echo $html;
  }
}
