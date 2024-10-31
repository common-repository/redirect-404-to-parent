<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
 * 	Contributors: mooveagency
 *  Plugin Name: Redirect 404 to parent
 *  Plugin URI: http://www.mooveagency.com
 *  Description: This plugin helps you define redirect rules that will redirect any 404 request under a defined URL base to the parent URL base.
 *  Version: 1.4.1
 *  Author: Moove Agency
 *  Author URI: http://www.mooveagency.com
 *  License: GPLv2
 *  Text Domain: moove
 */
define( 'MOOVE_REDIRECT404_VERSION', '1.4.1' );

register_activation_hook( __FILE__ , 'moove_redirect_activate' );
register_deactivation_hook( __FILE__ , 'moove_redirect_deactivate' );

/**
 * Functions on plugin activation, create relevant pages and defaults for settings page.
 */
function moove_redirect_activate() {
  $activate = get_option( 'moove_404_redirect_activate' );
  if ( ! $activate ) :
    update_option( 'moove_404_redirect_activate', false );
  endif;
}

/**
 * Function on plugin deactivation. It removes the pages created before.
 */
function moove_redirect_deactivate() {
  $activate = get_option( 'moove_404_redirect_activate' );
  if ( ! $activate ) :
    update_option( 'moove_404_redirect_options', null );
    // update_option( 'moove_404_redirect_statistics', array() );
  endif;
}

/**
 * Star rating on the plugin listing page
 */
if ( ! function_exists('redirect404_add_plugin_meta_links') ) {
function redirect404_add_plugin_meta_links($meta_fields, $file) {
  if ( plugin_basename(__FILE__) == $file ) :
    $plugin_url = "https://wordpress.org/support/plugin/redirect-404-to-parent/reviews/?rate=5#new-post";
    $meta_fields[] = "<a href='" . esc_url($plugin_url) ."' target='_blank' title='" . esc_html__('Rate', 'moove') . "'>
          <i class='redirect404-star-rating'>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "</i></a>";      
     
  endif;
  return $meta_fields;
  }
}
add_filter('plugin_row_meta' , 'redirect404_add_plugin_meta_links', 10, 2);

include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-view.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-options.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-controller.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-actions.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-functions.php' );

