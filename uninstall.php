<?php
/**
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Exit if accessed directly or not through uninstall process
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Only proceed if user opted to remove data
if ( get_option( 'cts_delete_on_uninstall' ) ) {

    require_once plugin_dir_path( __FILE__ ) . 'includes/core/class-uninstall.php';

    $uninstaller = new \CTS\Core\Uninstall();
    $uninstaller->run(); // method that deletes data, options, tables, etc.
}

