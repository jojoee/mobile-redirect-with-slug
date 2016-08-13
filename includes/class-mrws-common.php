<?php

/**
 * Common
 *
 * @link       https://github.com/jojoee/mobile-redirect-with-slug
 * @since      1.0.0
 *
 * @package    MRWS
 * @subpackage MRWS/includes
 */

/**
 * @package    MRWS
 * @subpackage MRWS/includes
 * @author     Nathachai Thongniran <inid3a@gmail.com>
 */
class MRWS_Common {
  protected $text_domain; // unused
  protected $options;

  protected $is_debug;

  protected $menu_page;

  protected $option_group_name;
  protected $option_field_name;

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
  }

  /*================================================================ Debug
  */

  protected function dd( $var = null, $is_die = true ) {
    echo '<pre>';
    print_r( $var );
    echo '</pre>';

    if ( $is_die ) die();
  }

  protected function da( $var = null ) {
    $this->dd( $var, false );
  }

  protected function dhead( $head, $var, $is_die = false ) {
    echo '<div class="debug-box">';
    echo '================';
    echo ' ' . $head . ' ';
    echo '================';
    echo '<br>';
    $this->dd( $var, $is_die );
    echo '</div>';
  }

  protected function dump( $is_die = false ) {
    $this->da( $this->options, $is_die );
  }

  protected function reset() {
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
  protected function is_https() {
    return ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' );
  }

  /**
   * [get_current_url description]
   *
   * @see    http://stackoverflow.com/questions/6768793/get-the-full-url-in-php
   * 
   * @return [type] [description]
   */
  protected function get_current_url() {
    $http_protocol = $this->get_http_protocol();
    $current_url = "$http_protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    return $current_url;
  }

  protected function get_current_request_url() {
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
  protected function is_null_or_empty_string( $str ) {
    return ( ! isset( $str ) || trim( $str ) === '' );
  }

  protected function get_http_protocol() {
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
  protected function is_valid_url( $url ) {
    // It's not validate url's protocol of a url.
    // For example, ssh://, ftp:// etc will also pass.
    
    return ( ! ( filter_var( $url, FILTER_VALIDATE_URL ) === false ) );
  }

  /*================================================================ Private
  */
 
  private function mrws_set_default_prop() {
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

  /*================================================================ Public
  */
}
