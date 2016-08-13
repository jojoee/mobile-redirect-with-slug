<?php

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
 * Version:           1.0.0
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

/*================================================================
*/

class MRWS_Settings_Page {
  private $text_domain; // unused
  private $options;

  private $is_debug;

  private $menu_page;

  private $option_group_name;
  private $option_field_name;

  /*================================================================ Debug
  */

  private function dd( $var = null, $is_die = true ) {
    echo '<pre>';
    print_r( $var );
    echo '</pre>';

    if ( $is_die ) die();
  }

  private function da( $var = null ) {
    $this->dd( $var, false );
  }

  private function dhead( $head, $var, $is_die = false ) {
    echo '<div class="debug-box">';
    echo '================';
    echo ' ' . $head . ' ';
    echo '================';
    echo '<br>';
    $this->dd( $var, $is_die );
    echo '</div>';
  }

  private function dump( $is_die = false ) {
    $this->da( $this->options, $is_die );
  }

  private function reset() {
   update_option( $this->option_field_name, [] );
  }

  /*================================================================ Utils
  */
 
  /**
   * [is_https description]
   *
   * @see    http://stackoverflow.com/questions/1175096/how-to-find-out-if-youre-using-https-without-serverhttps
   * @see    http://stackoverflow.com/questions/4503135/php-get-site-url-protocol-http-vs-https
   * 
   * @return boolean [description]
   */
  private function is_https() {
    return ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' );
  }

  /**
   * [get_current_url description]
   *
   * @see    http://stackoverflow.com/questions/6768793/get-the-full-url-in-php
   * 
   * @return [type] [description]
   */
  private function get_current_url() {
    $http_protocol = $this->get_http_protocol();
    $current_url = "$http_protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    return $current_url;
  }

  private function get_current_request_url() {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * [is_null_or_empty_string description]
   *
   * @see    http://stackoverflow.com/questions/381265/better-way-to-check-variable-for-null-or-empty-string
   * @see    http://stackoverflow.com/questions/8236354/php-is-null-or-empty
   * 
   * @param  [type]  $str [description]
   * @return boolean      [description]
   */
  private function is_null_or_empty_string( $str ) {
    return ( ! isset( $str ) || trim( $str ) === '' );
  }

  private function get_http_protocol() {
    $http_protocol = 'http';

    if ( $this->is_https() ) {
      $http_protocol = 'https';
    }

    return $http_protocol . '://';
  }

  /**
   * [is_valid_url description]
   *
   * @see    http://stackoverflow.com/questions/2058578/best-way-to-check-if-a-url-is-valid
   * 
   * @param  [type]  $url [description]
   * @return boolean      [description]
   */
  private function is_valid_url( $url ) {
    // It's not validate url's protocol of a url.
    // For example, ssh://, ftp:// etc will also pass.
    
    return ( ! ( filter_var( $url, FILTER_VALIDATE_URL ) === false ) );
  }

  /*================================================================ Public
  */

  public function __construct() {
    $this->is_debug = false;
    // $this->is_debug = true;
    $this->text_domain = 'mrws'; // unused
    $this->menu_page = 'mobile-redirect-with-slug';
    $this->option_group_name = 'mrws_option_group';
    $this->option_field_name = 'mrws_option_field';

    // set class property
    $this->options = get_option( $this->option_field_name );

    // set default prop
    // for only
    // - first time or
    // - no summiting form
    $this->mrws_set_default_prop();

    add_action( 'admin_menu', array( $this, 'mrws_add_menu' ) );
    add_action( 'admin_init', array( $this, 'mrws_page_init' ) );
    add_action( 'template_redirect', array( $this, 'mrws_template_redirect' ) );
  }

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

  public function mrws_set_default_prop() {
    // default
    // 
    // [
    //   'mrws_field_is_enabled'         => 1
    //   'mrws_field_is_including_slug'  => 0
    //   'mrws_field_redirect_url'       => ''
    //   'mrws_field_redirect_mode'      => 301
    // ]

    $options = $this->options;

    if ( ! isset( $options['mrws_field_is_enabled'] ) )         $options['mrws_field_is_enabled']         = 0;
    if ( ! isset( $options['mrws_field_is_including_slug'] ) )  $options['mrws_field_is_including_slug']  = 0;
    if ( ! isset( $options['mrws_field_redirect_url'] ) )       $options['mrws_field_redirect_url']       = '';
    if ( ! isset( $options['mrws_field_redirect_mode'] ) )      $options['mrws_field_redirect_mode']      = 301;

    $this->options = $options;
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

    // add plugin link
    add_filter( 'plugin_action_links_'. plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ), 10, 4 );
  }

  public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
    if ( is_plugin_active( $plugin_file ) ) {
      $actions[] = '<a href="' . admin_url( 'options-general.php?page=mobile-redirect-with-slug' ) . '">Settings</a>';
    }

    return $actions;
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

$mrws_settings_page = new MRWS_Settings_Page();
