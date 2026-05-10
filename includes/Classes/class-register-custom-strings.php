<?php
namespace CTS\Classes; 
/**
 * 
 * @package WordPress
 * @subpackage Custom Translation Strings
 * @since 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Register_Custom_Strings {

    public function register_custom_strings_for_translation() {
    global $wpdb;

        if ( ! function_exists( 'pll_register_string' ) ) {
            return; 
        }

    $table_name = $wpdb->prefix . 'cts_custom_strings'; 
    $data = $wpdb->get_results("SELECT * FROM $table_name");


    if ($data) {
        foreach ($data as $row) {
            $name = $row->string_identifier;
            $string = $row->string_value;
            $group = $row->text_domain;

            pll_register_string($name, $string, $group, false);
        }
    }
}

}

