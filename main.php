<?php
/**
Plugin Name: Custom translation strings
Plugin URI: http://trifectamarketing.pl
Description: Adds translation strings to polylang
Version: 3.0
Author: Sebastian Szydlowski
Author URI: http://trifectamarketing.pl
Text Domain:       cts
Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define('CTS_PLUGIN_PATH', plugin_dir_path(__FILE__));


require_once plugin_dir_path( __FILE__ ) . 'autoload.php';


register_activation_hook( __FILE__, [ \CTS\Core\Activator::class, 'activate' ] );


function deactivate() {
  \CTS\Core\Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate' );




add_action( 'plugins_loaded', function() {
    
    if ( class_exists( 'PLL' ) || function_exists( 'pll_register_string' ) ) {
        new \CTS\Run();
    } else {
        add_action( 'admin_notices', 'cts_polylang_missing_notice' );
    }
}, 10 ); 


function cts_polylang_missing_notice() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p>
            <strong><?php _e( 'CTS Plugin:', 'cts' ); ?></strong> 
            <?php _e( 'The required Polylang plugin is not active.', 'cts' ); ?>
            <br>
            <?php _e( 'Our features have been suspended.', 'cts' ); ?> 
            <a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>">
                <?php _e( 'Manage plugins', 'cts' ); ?>
            </a>.
        </p>
    </div>
    <?php
}