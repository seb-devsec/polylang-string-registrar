<?php
namespace CTS\Core; 
/**
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}


class Uninstall {

    public function run() {
      
        delete_option( 'cts_delete_on_uninstall' );
        delete_option( 'cts_per_page' );
        delete_option( '' );
        
        global $wpdb;
        $table = $wpdb->prefix . 'cts_custom_strings';
        $wpdb->query( "DROP TABLE IF EXISTS $table" );
    }
}

