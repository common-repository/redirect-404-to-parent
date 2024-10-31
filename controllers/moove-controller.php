<?php
/**
 * Moove_Redirect_Controller File Doc Comment
 *
 * @category Moove_Redirect_Controller
 * @package   moove-404-redirect
 * @author    Gaspar Nemes
 */

/**
 * Moove_Redirect_Controller Class Doc Comment
 *
 * @category Class
 * @package  Moove_Redirect_Controller
 * @author   Gaspar Nemes
 */
class Moove_Redirect_Controller {
	/**
	 * Construct function
	 */
	public function __construct() {
        add_action( 'wp', array( &$this, 'moove_redirect_determine_if_404' ) );
	}
    /**
     * Checks if the multidimensional array has empty arrays, and removes them
     * @return void
     */
    public static function moove_remove_empty_array() {
        $options = get_option( 'moove_404_redirect_options' );
        if ( ! empty( $options ) && is_array( $options ) ) :
            foreach ( $options as $opkey => $redirects ) {
               foreach ( $redirects as $rdkey => $redirect) {
                    if ( empty( $redirect ) ) {
                        unset( $options[ $opkey ][ $rdkey ] );
                    }
                }
                if ( empty( $redirects ) ) {
                    unset( $options[ $opkey ]);
                }
            }
            $filtered_options = array_filter($options);
            if ( empty( $filtered_options ) ) {
                $options = null;
            }
            update_option( 'moove_404_redirect_options', $options );
        endif;
    }
    public function get_plugin_details( $plugin_slug = '' ) {
        $plugin_return = false;
        $wp_repo_plugins    = '';
        $wp_response        = '';
        $wp_version         = get_bloginfo('version');
        if ( $plugin_slug && $wp_version > 3.8 ) :
            $args = array(
                'author' => 'MooveAgency',
                'fields' => array(
                    'downloaded'        => true,
                    'active_installs'   => true,
                    'ratings'           => true
                )
            );
            $wp_response = wp_remote_post(
                'http://api.wordpress.org/plugins/info/1.0/', 
                array(
                    'body' => array(
                        'action'    => 'query_plugins',
                        'request'   => serialize( (object) $args )
                    )
                )
            );
            if ( ! is_wp_error( $wp_response ) ) :
                $wp_repo_response       = unserialize( wp_remote_retrieve_body( $wp_response ) );
                $wp_repo_plugins        = $wp_repo_response->plugins;
            endif;
            if ( $wp_repo_plugins ) :
                foreach ( $wp_repo_plugins as $plugin_details ) :
                    if ( $plugin_slug == $plugin_details->slug ) :
                        $plugin_return = $plugin_details;
                    endif;
                endforeach;
            endif;
        endif;
        return $plugin_return;
    }
    /**
     * Searching for $base term on options page, and if is found, than remove from the options
     * field, and remove from statistics as well.
     * After that, remove all empty arrays from the redirect options.
     * @param  string $base Base url, this should be removed.
     * @return void
     */
    public static function moove_redirect_remove_item( $base ) {
        if ( $base !== '' ) :
            $options = get_option( 'moove_404_redirect_options' );
            $base_url = $_SERVER['SERVER_NAME'];
            if ( ! empty( $options ) && is_array( $options ) ) :
                foreach ( $options as $opkey => $redirects ) :
                   foreach ( $redirects as $rdkey => $redirect ) :
                        if ( $redirect['base'] === $base ) :
                            // Remove base url from options.

                            unset( $options[ $opkey ][ $rdkey ] );
                            update_option( 'moove_404_redirect_options', $options );
                            // Remove base url from statistics.
                            $statistics = json_decode( get_option( 'moove_404_redirect_statistics' ), true);
                            if ( isset( $statistics[ $base ] ) ) :
                                unset( $statistics[ $base ] );
                                update_option( 'moove_404_redirect_statistics', json_encode( $statistics ) );
                            endif;
                            // Remove empty arrays from options.
                            Moove_Redirect_Controller::moove_remove_empty_array();
                        endif;
                    endforeach;
                endforeach;
            endif;
        endif;
    }
    /**
     * Validate an URL via wp_remote_get
     * @param  string $base URL that should be validated.
     * @return array       Validation response
     */
    public static function moove_validate_url( $base ) {
            $url        = home_url( '/' ) . $base;
            $response   = wp_remote_get( $url );
            $http_code  = wp_remote_retrieve_response_code( $response );
            if ( $http_code == 404 || $http_code == 301 ) {
                /* Handle 404 here. */
                $message = '<h4 class="moove-error">'.__('URL does not exists or is redirected! Please type a valid URL!','moove').'</h4>';
                return array( 'success' => false, 'error' => $message );
            }
        return array( 'success' => true, 'error' => '' );
        
    }
    /**
     * User IP address
     *
     * @return string IP Address
     */
    public static function moove_get_the_user_ip() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) :
            // Check ip from share internet.
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) :
            // To check ip is pass from proxy.
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else :
            $ip = $_SERVER['REMOTE_ADDR'];
        endif;
        return $ip;
    }
    /**
     * Create a new entry to statistics
     * @param  string $base The base url, save under this rule.
     * @param  string $path Target url which return 404.
     * @return void
     */
    public static function moove_set_redirect_stat( $base, $path ) {
        $stats_hook             = apply_filters( 'moove_404_redirect_stats_enabled', false );
        $general_options        = get_option( 'moove_404_general_options' );
        $general_options        = $general_options && is_array( $general_options ) ? $general_options : array();
        $statistics_status      = isset( $general_options['redirect_statistics'] ) && intval( $general_options['redirect_statistics'] ) >= 0 ? intval( $general_options['redirect_statistics'] ) : ( $stats_hook ? 1 : 0 );

        if ( $statistics_status > 0 ) :
            $statistics = json_decode( get_option( 'moove_404_redirect_statistics' ), true );
            if ( ! is_array( $statistics ) ) :
                $statistics = array();
            endif;
        
            $ip = Moove_Redirect_Controller::moove_get_the_user_ip();
            $details = json_decode( file_get_contents( "http://ipinfo.io/{$ip}/json" ) );
            $statistics[ $base ][] = array(
                'date'          =>  current_time( 'mysql' ),
                'uip'           =>  esc_attr( $ip ),
                'city'          =>  isset( $details->city ) ? esc_attr( $details->city ) : '',
                'target_url'    =>  esc_attr( $path ),
                'referer'       =>  isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '',
            );
            update_option( 'moove_404_redirect_statistics', json_encode( $statistics ) );
        endif;
    }
    /**
     * Check if is 404 page, if true, search the targeted url base on options.
     * If true than the page will be redirected to base url, and create a new statistic entry.
     * @return void
     */
    public static function moove_redirect_determine_if_404() {
        global $wp_query;
        if ( $wp_query->is_404 ) :
            $path       = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $options    = get_option( 'moove_404_redirect_options' );
            $site_url   = get_site_url();
            $site_url   = parse_url( $site_url );
            $base_url   = isset( $site_url['host'] ) && isset( $site_url['path'] ) ? $site_url['host'] . $site_url['path'] : $_SERVER['SERVER_NAME'];
            if ( ! empty( $options ) && is_array( $options ) ) :
                foreach ( $options as $redirects ) :
                   foreach ( $redirects as $redirect ) :
                        $url = $base_url . '/' . $redirect['base'];
                        if ( false !== strpos( $path, $url ) ) :
                            Moove_Redirect_Controller::moove_set_redirect_stat( $redirect['base'], $path );
                            wp_redirect( home_url('/') . $redirect['base'], intval( $redirect['status'] ) );
                            exit;
                        endif;
                    endforeach;
                endforeach;
            endif;
        endif;
    }
}
$moove_redirect_controller_provider = new Moove_Redirect_Controller();
