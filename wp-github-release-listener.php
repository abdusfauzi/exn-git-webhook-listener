<?php
/**
 * Plugin Name: EXN Git Webhook Listener
 * Description: Listens to a GitLab webhook and run shell script everytime called.
 * Version: 1.1.0
 * Author: Abdus Fauzi
 * Author URI: https://exnano.io
 * License: GPLv2
 * Text Domain: gitlab-webhook-listener
 */

defined('ABSPATH') or die('No!');

add_action('wp_ajax_egwl_release_post', 'egwl_new_release_handler');
add_action('wp_ajax_nopriv_egwl_release_post', 'egwl_new_release_handler');
function egwl_new_release_handler() {
    ignore_user_abort(true);
    set_time_limit(0);

    // We will send a response on every request
    header( 'Content-Type: application/json' );

    $token  = get_option( 'egwl-webhook-token' );
    $script = get_option( 'egwl-webhook-shell-script' );
    $scripts = [];
    $status = '';
    $output = '';

    // error_log( "curl called\n" . print_r( $_SERVER, true ) . "\n\n", 3, WP_CONTENT_DIR . '/githook.log' );

    if ( $_SERVER['HTTP_X_GITLAB_TOKEN'] != $token ) {
        $status = json_encode( [ 'success' => false, 'error' => 'Failed to validate the secret token' ] );

        ob_start();
        echo $status;
        header( 'Connection: close' );
        header( 'Content-Length: ' . ob_get_length() );
        ob_end_flush();
        flush();

        fastcgi_finish_request();
    } else {
        $status = json_encode( [ 'success' => true ] );

        ob_start();
        echo $status;
        header( 'Connection: close' );
        header( 'Content-Length: ' . ob_get_length() );
        ob_end_flush();
        flush();

        fastcgi_finish_request();



        $scripts = preg_split( '/\r\n|[\r\n]/', $script );

        chdir( ABSPATH );
        $output .= "==============================================================\n";
        $output .= date( 'Y m d h:i:s' ) . "\n";
        $output .= "--------------------------------------------------------------\n";

        foreach ( $scripts as $key => $script ) {
            $output .= shell_exec( $script . ' 2>&1' );
            $output .= "\n";
        }
    }

    error_log( $output . "\n\n", 3, WP_CONTENT_DIR . '/githook.log' );

    die();
}

add_action('admin_menu', 'egwl_menu');
function egwl_menu() {
    add_options_page(
        'GitLab Webhook Listener Settings',
        'GitLab Webhook',
        'manage_options',
        'egwl-options',
        'egwl_options_page'
    );
}

add_action('admin_init', 'egwl_register_settings');
function egwl_register_settings() {
    register_setting( 'egwl-options', 'egwl-webhook-token' );
    register_setting( 'egwl-options', 'egwl-webhook-shell-script' );
}

function egwl_options_page() {
    include plugin_dir_path(__FILE__) . '/options.php';
}
