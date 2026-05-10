<?php
namespace CTS\Core;
/**
 * Fired during plugin activation
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Custom Translation Strings
 * @subpackage Custom Translation Strings\Core
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activator {

    public static function activate() {
        self::plugin_table();
    }

    public static function plugin_table() {
        global $wpdb;
        $table = $wpdb->prefix . 'cts_custom_strings';

        $table_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $table
            )
        );

        if ($table_exists !== $table) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                string_identifier varchar(255) NOT NULL,
                string_value text NOT NULL,
                text_domain varchar(255) DEFAULT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }
    }
}

